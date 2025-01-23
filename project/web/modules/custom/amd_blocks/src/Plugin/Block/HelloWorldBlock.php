<?php

declare(strict_types=1);

namespace Drupal\amd_blocks\Plugin\Block;

use Drupal\amd_blocks\TextTransformations;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an amd hello block block.
 */
#[Block(
  id: 'amd_blocks_hello_world',
  admin_label: new TranslatableMarkup('AMD Hello World Block'),
  category: new TranslatableMarkup('Custom'),
)]
final class HelloWorldBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Stores the current_user service.
   * @var Drupal\Core\Session\AccountProxy
   */
  protected AccountProxy $currentUser;

  /**
   * The text transformations service.
   * @var Drupal\amd_blocks\TextTransformations
   */
  protected TextTransformations $textTransformer;

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition){
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('amd_blocks.text_transformations')
    );
  }

  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountProxy $currentUser, TextTransformations $textTransformer){
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $currentUser;
    $this->textTransformer = $textTransformer;
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('Hello @username!', ['@username' => $this->textTransformer->reverse($this->currentUser->getAccountName())]),
    ];
    return $build;
  }

}
