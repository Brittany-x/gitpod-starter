<?php

declare(strict_types=1);

namespace Drupal\audit\Form;

use Drupal\audit\Event\IncidentReport;
use Drupal\audit\Event\IncidentReportEvents;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an Audit form for reporting an issue regarding the Deletion Records.
 */
final class IncidentReportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'audit_incident_report';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#placeholder' => $this->t('Enter your name'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#placeholder' => $this->t('Enter your email address'),
      '#required' => TRUE,
    ];

    $form['entity'] = [
      '#type' => 'select',
      '#title' => $this->t('Select the entity that was incorrectly deleted'),
      '#options' => $this->getDeletedRecordOptions(),
      '#required' => TRUE,
    ];

    $form['report'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Detailed report'),
      '#placeholder' => $this->t('Describe the issue in more detail.'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Submit report'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if (mb_strlen($form_state->getValue('report')) < 15) {
      $form_state->setErrorByName(
        'report',
        $this->t('Detailed report must be at least 15 characters.'),
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $reporter_name = $form_state->getValue('name');
    $reporter_email = $form_state->getValue('email');
    $entity = $form_state->getValue('entity');
    $report = $form_state->getValue('report');

    // Create instance of the IncidentReport event object.
    $event = new IncidentReport($reporter_name, $reporter_email, $entity, $report);

    // Trigger the event.
    \Drupal::service('event_dispatcher')->dispatch($event, IncidentReportEvents::NEW_INCIDENT);
    
    $this->messenger()->addStatus($this->t('Your incident report has been submitted.'));
    $form_state->setRedirect('<front>');
  }

  public function getDeletedRecordOptions() {
    $storage = \Drupal::entityTypeManager()->getStorage('deletion_record');
    $query = $storage->getQuery();
    $query->accessCheck();
    $query->sort('deleted', 'DESC');
    $ids = $query->execute();

    $records = $storage->loadMultiple($ids);

    $options = [];
    foreach ($records as $item) {
      $options[$item->id()] = $item->label();
    }

    return $options;
  }

}
