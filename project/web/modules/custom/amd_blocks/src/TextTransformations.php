<?php

declare(strict_types=1);

namespace Drupal\amd_blocks;

/**
 * @todo Add class description.
 */
final class TextTransformations {

  public function reverse($text) {
    return strrev($text);
  }

  public function uppercase($text) {
    return strtoupper($text);
  }

  public function titleCase($text) {
    return ucfirst($text);
  }

}
