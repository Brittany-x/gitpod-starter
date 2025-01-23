<?php

declare(strict_types=1);

namespace Drupal\audit\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a deleted entities list block block.
 */
#[Block(
  id: 'audit_deleted_entities_list_block',
  admin_label: new TranslatableMarkup('Deleted Entities List Block'),
  category: new TranslatableMarkup('Custom'),
)]
final class DeletedEntitiesListBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $storage = \Drupal::entityTypeManager()->getStorage('deletion_record');
    $query = $storage->getQuery();
    $query->accessCheck();
    $query->sort('deleted', 'DESC');
    $query->range(0, 3);
    $ids = $query->execute();

    $records = $storage->loadMultiple($ids);
    $output = '<h3>Recently deleted entities</h3><ol>';

    foreach ($records as $item) {
      $output = $output . '<li>' . $item->label->value . '</li>';
    }
    $output = $output . '</ol>';

    // Get the URL to the Incident Report form using the route name.
    $form_url = \Drupal\Core\Url::fromRoute('audit.incident_report');

    $output = $output . '<p>';

    // Add a hyperlink to the incident report form.
    $output = $output . $this->t('Spotted an issue? <a href="@url">Report an incident</a>.', [
      '@url' => $form_url->toString(),
    ]);

    $output = $output. '</p>';

    $build['content'] = [
      '#markup' => $output,
    ];
    return $build;
  }

  public function getCacheTags() {
    $tags = [
      'deletion_record_list',
    ];
    return Cache::mergeTags(parent::getCacheTags(), $tags);
  }

}
