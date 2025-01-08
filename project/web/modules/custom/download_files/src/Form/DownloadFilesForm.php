<?php

declare(strict_types=1);

namespace Drupal\download_files\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\media\Entity\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Provides a Download files form.
 */
final class DownloadFilesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'download_files_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['media'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a file to download'),
      '#options' => $this->getMediaOptions(),
    ];
  
    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Download'),
      ],
    ];

    return $form;
  }

  public function getMediaOptions() {
    // Get media items using Database Abstraction Layer.
    // $results = \Drupal::database()
    //   ->select('media_field_data', 'm')
    //   ->fields('m', ['mid', 'name'])
    //   ->condition('m.status', 1);

    // This is useful to get the ID of the file referenced. This table works only for the document media type,
    // we would need to add more joins for other tables/media types.
    // $results->leftJoin('media__field_media_file', 'mf', 'm.mid = mf.entity_id and m.vid = mf.revision_id');
    // $results->fields('mf', ['field_media_file_target_id']);

    // Get the uri of the file referenced by the media type.
    // $results->leftJoin('file_managed', 'f', 'mf.field_media_file_target_id = f.fid');
    // $results->fields('f', ['uri']);

    // $results = $results->execute()->fetchAll();

    // select m.mid, m.vid, m.name, mf.field_media_file_target_id, f.uri 
    // from media_field_data as m 
    // left join media__field_media_file as mf on m.mid = mf.entity_id and m.vid = mf.revision_id
    // left join file_managed as f on mf.field_media_file_target_id = f.fid;

    // $options = [];
    // foreach ($results as $media) {
    //   $options[$media->mid] = $media->name;
    // }

    // Using the Entity Query --------------------------------------------------------------------------
    $ids = \Drupal::entityQuery('media')
      ->condition('status', 1)
      ->condition('bundle', ['media'], 'IN')
      ->accessCheck()
      ->execute();

    $results = Media::loadMultiple($ids);

    $options = [];
    foreach ($results as $media) {
      // $options[$media->field_media_image->entity->uri->value] = $media->label();
      $options[$media->id()] = $media->label();
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Get user selection.
    $mid = $form_state->getValue('media');
    // Load media item based on user selection.
    $media = Media::load($mid);

    // Change behavior depending on media type.
    switch ($media->bundle()) {
      case 'video':
        // Videos don't have a file field, only a text field so we can get the value directly from the field.
        // Print the URL to the video.
        $uri = $media->field_media_oembed_video->value;
        $this->messenger()->addStatus($this->t('The video can be found in @url.', ['@url' => $uri]));
        break;
      case 'media':
        // This is for images.
        // The Image media type has a field (field_media_image) which is a File reference, so we have to access the 
        // entity before we can access the uri.
        $uri = $media->field_media_image->entity->uri->value;
        $response = new BinaryFileResponse($uri);
        $response->setContentDisposition('attachment');
        $form_state->setResponse($response);
        break;
      default:
        // Document or files.
        // The Document media type has a field (field_media_file) which is a File reference, so we have to access the 
        // entity before we can access the uri.
        // We're using this field as the default because this field name would be useful in those cases, though we should still
        // add an if to check if the field actually exists.
        $uri = $media->field_media_file->entity->uri->value;
        $response = new BinaryFileResponse($uri);
        $response->setContentDisposition('attachment');
        $form_state->setResponse($response);
        break; 
    }
  }

}
