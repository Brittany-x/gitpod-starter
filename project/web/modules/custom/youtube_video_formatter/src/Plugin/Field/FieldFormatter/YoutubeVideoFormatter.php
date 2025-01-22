<?php

declare(strict_types=1);

namespace Drupal\youtube_video_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Attribute\FieldFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Plugin implementation of the 'Youtube Video' formatter.
 */
#[FieldFormatter(
  id: 'youtube_video_formatter',
  label: new TranslatableMarkup('Youtube Video'),
  field_types: ['string'],
)]
final class YoutubeVideoFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings(): array {
    $setting = [
      'width' => 560,
      'height' => 315,
    ];
    return $setting + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state): array {
    $elements['width'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Width'),
      '#default_value' => $this->getSetting('width'),
    ];

    $elements['height'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Height'),
      '#default_value' => $this->getSetting('height'),
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(): array {
    return [
      $this->t('Width: @width', ['@width' => $this->getSetting('width')]),
      $this->t('Height: @height', ['@height' => $this->getSetting('height')]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $element = [];
    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#theme' => 'youtube_video',
        '#youtube_id' => $item->value,
        '#width' => $this->getSetting('width'),
        '#height' => $this->getSetting('height'),
      ];
    }
    return $element;
  }

}
