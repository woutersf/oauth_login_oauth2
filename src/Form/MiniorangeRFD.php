<?php
/**
 * @file
 * Contains Attribute for miniOrange OAuth Client Module.
 */

 /**
 * Showing Settings form.
 */
namespace Drupal\oauth2_login\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Render;
use Drupal\oauth2_login\Utilities;

class MiniorangeRFD extends FormBase {

  public function getFormId() {
    return 'miniorange_oauth_client_rfd';
  }


    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {

        $form['markup_library'] = array(
            '#attached' => array(
                'library' => array(
                    "oauth2_login/oauth2_login.admin",
                    "oauth2_login/oauth2_login.style_settings",
                    "oauth2_login/oauth2_login.module",
                )
            ),
        );

        $user_email = \Drupal::config('oauth2_login.settings')->get('miniorange_oauth_client_customer_admin_email');

        $form['markup_1'] = array(
            '#markup' =>'<div class="mo_oauth_table_layout_1"><div class="mo_oauth_table_layout mo_oauth_container">
                        <h2>Request for Demo</h2><hr><br>'
        );

        $form['markup_2'] = array(
            '#markup' => '<div class="mo_oauth_highlight_background_note_export"><p><strong>Want to test any of the Premium module before purchasing? </strong></p>
                          <p>Just send us a request, We will setup a demo site for you on our cloud with the premium module and provide you with the administrator credentials.
                          You can configure it with your OAuth Server and test all the premium features as per your requirement.</p>  
                          </div><br>',
        );

        $form['customer_email'] = array(
            '#type' => 'email',
            '#title' => t('Email'),
            '#required' => TRUE,
            '#default_value' => t(strval($user_email)),
            '#attributes' => array('style' => 'width:65%;', 'placeholder' => 'Enter your email'),
            '#description' => t('<b>Note:</b> Use valid Email ID. ( We discourage the use of disposable emails )'),
        );

        $form['demo_plan'] = array(
            '#type' => 'select',
            '#title' => t('Demo Plan'),
            '#attributes' => array('style' => 'width:65%;'),
            '#options' => [
                'Drupal 8 OAuth Standard Module' => t('Drupal 8 OAuth Standard Module'),
                'Drupal 8 OAuth Premium Module' => t('Drupal 8 OAuth Premium Module'),
                'Drupal 8 OAuth Enterprise Module' => t('Drupal 8 OAuth Enterprise Module'),
                'Not Sure' => t('Not Sure'),
            ],
        );

        $form['description_doubt'] = array(
            '#type' => 'textarea',
            '#title' => t('Description'),
            '#attributes' => array('style' => 'width:65%', 'placeholder' => 'Describe your requirement'),
            '#required' => TRUE,
        );

        $form['submit_button'] = array(
            '#type' => 'submit',
            '#value' => t('Submit'),
            '#prefix' => '<br>',
            '#suffix' => '<br><br></div>',
        );

        Utilities::spConfigGuide($form, $form_state);
        $form['mo_markup_div_end1']=array('#markup'=>'</div>');

        return $form;
    }

    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
        $email = trim($form['customer_email']['#value']);
        $demo_plan = $form['demo_plan']['#value'];
        $description_doubt = trim($form['description_doubt']['#value']);
        $query = $demo_plan.' -> '.$description_doubt;
        Utilities::send_demo_query($email, $query,$description_doubt);
    }
}