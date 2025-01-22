<?php

declare(strict_types=1);

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Returns responses for Hello World routes.
 */
final class HelloController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function hello($person = NULL, $nid = NULL): array {
    $output = $this->t('Hello World!');

    if ($nid !== NULL) {
      $node = Node::load($nid);
      
      // Alternatives to printing values using functions and properties.
      // ksm($node->getTitle());
      // ksm($node->title->value);

      // ksm($node->get('body')->getValue());
      // ksm($node->body->value);

      // ksm($node->get('field_specialty'));
      // ksm($node->field_specialty->entity->name->value);
    }

    if ($person !== NULL) {
      if ($node) {
        $url = Url::fromRoute('entity.node.canonical', ['node' => $nid]);
        $link = Link::fromTextAndUrl('here', $url);

        $output = $this->t('Hello @person_name! The node with ID @nid is @title', [
          '@person_name' => $person,
          '@nid' => $nid,
          '@title' => $link->toString(),
        ]);
      } else {
        $output = $this->t('Hello @person_name!', [
          '@person_name' => $person,
        ]);
      }
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output,
    ];

    return $build;
  }

}
