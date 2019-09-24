namespace Drupal\mmm_registration\Plugin\WebformHandler;
 
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
 
/**
 * Form submission handler.
 *
 * Redirects to the [...] after the submit.
 *
 * @WebformHandler(
 *   id = "my_module_redirect",
 *   label = @Translation("Redirect to the ..."),
 *   category = @Translation("Webform Handler"),
 *   description = @Translation("Redirect to the ..."),
 *   cardinality =
 *       \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results =
 *    \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class MMMRegistrationActions extends WebformHandlerBase {
 
  public function confirmForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
 
    $values = $webform_submission->getData();
 
    if (!empty($values['some_data'])) {
      $form_state->setRedirect('some_route.view', [
        'some_id' => $values['some_data'],
      ]);
    }
    print_r("test");
  }
}
