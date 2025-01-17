<?php

declare(strict_types=1);

namespace Drupal\audit;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the deletion record entity type.
 */
final class DeletionRecordListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    $header['label'] = $this->t('Label');
    $header['created'] = $this->t('Created');
    $header['changed'] = $this->t('Updated');
    $header['deleted'] = $this->t('Deleted');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\audit\DeletionRecordInterface $entity */
    $row['id'] = $entity->id();
    $row['label'] = $entity->toLink();
    $row['created']['data'] = $entity->get('created')->view(['label' => 'hidden']);
    $row['changed']['data'] = $entity->get('changed')->view(['label' => 'hidden']);
    $row['deleted']['data'] = $entity->get('deleted')->view(['label' => 'hidden']);
    return $row + parent::buildRow($entity);
  }

}
