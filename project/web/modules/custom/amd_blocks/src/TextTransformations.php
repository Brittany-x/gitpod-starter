<?php

declare(strict_types=1);

namespace Drupal\amd_blocks;

use \Drupal\Core\Logger\LoggerChannelFactory;

/**
 * @todo Add class description.
 */
final class TextTransformations {
  /**
   * Logger factory.
   * 
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $logger;

  public function __construct(LoggerChannelFactory $loggerFactory){
    $this->logger = $loggerFactory;
  }

  public function reverse($text) {
    //\Drupal::logger('amd_blocks')->warning('The text was reversed.');
    $this->logger->get('amd_blocks')->warning('The text was reversed.');
    return strrev($text);
  }

  public function uppercase($text) {
    //\Drupal::logger('amd_blocks')->warning('The text was transformed to be uppercase.');
    $this->logger->get('amd_blocks')->warning('The text was transformed to be uppercase.');
    return strtoupper($text);
  }

  public function titleCase($text) {
    //\Drupal::logger('amd_blocks')->warning('The text was transformed to be title case.');
    $this->logger->get('amd_blocks')->warning('The text was transformed to be title case.');
    return ucfirst($text);
  }

}
