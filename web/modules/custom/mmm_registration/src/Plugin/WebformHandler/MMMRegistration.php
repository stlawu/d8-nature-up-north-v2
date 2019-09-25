<?php

namespace Drupal\mmm_registration\Plugin\WebformHandler;
 
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\webformSubmissionInterface;
use Drupal\Core\Url;

/**
 * Form submission handler.
 *
 * @WebformHandler(
 *   id = "mmm_registration",
 *   label = @Translation("MMM Registration"),
 *   category = @Translation("MMM Regestration Form Handler"),
 *   description = @Translation("Add to the MMM Registration form to add 'approved_user' and 'maple' roles on successful submission."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */

class MMMRegistration extends WebformHandlerBase {
  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) { 
    drupal_set_message("Registration Approved. You have been granted rights to contribute to Monitor My Maple.", 'notice');
    $values = $webform_submission->getData();
    $submitter = $webform_submission->getOwner();
    $submitter->addRole('approved_user');
    $submitter->addRole('maple');
    $submitter->save();
    // ksm($submitter);
  }

  /**
   * {@inheritdoc}
   */
  public function confirmForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) { 
    // drupal_set_message("test1", 'error');
    $values = $webform_submission->getData();
    // ksm($values);
  }
}
