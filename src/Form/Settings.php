<?php

namespace Drupal\oauth_login_oauth2\Form;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_login_oauth2\Utilities;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Settings extends FormBase
{
    public function getFormId() {
        return 'oauth_login_oauth2_settings';
    }
/**
 * Showing Settings form.
 */
 public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    global $base_url;
    $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url');

    $attachments['#attached']['library'][] = 'oauth_login_oauth2/oauth_login_oauth2.admin';

    $form['markup_library'] = array(
        '#attached' => array(
            'library' => array(
                "oauth_login_oauth2/oauth_login_oauth2.admin",
                "oauth_login_oauth2/oauth_login_oauth2.style_settings",
                "oauth_login_oauth2/oauth_login_oauth2.Vtour",
            )
        ),
    );

    $form['header_top_style_1'] = array('#markup' => '<div class="mo_oauth_table_layout_1">');

    $form['markup_top'] = array(
         '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
    );

    $form['markup_top_vt_start'] = array(
         '#markup' => '<b><h3>SIGN IN SETTINGS</h3></b><br><hr><br/>'
    );

    $form['oauth_login_oauth2_base_url'] = array(
        '#type' => 'textfield',
        '#title' => t('Base URl: '),
        '#default_value' => $baseUrlValue,
        '#attributes' => array('id'=>'mo_oauth_vt_baseurl','style' => 'width:73%;','placeholder' => 'Enter Base URI'),
    );

    $form['miniorange_oauth_force_auth'] = array(
        '#type' => 'checkbox',
        '#title' => t('Protect website against anonymous access <a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>[Premium, Enterprise]</b></a>'),
        '#disabled' => TRUE,
        '#description' => t('<b>Note: </b>Users will be redirected to your IdP for login in case user is not logged in and tries to access website.<br><br>'),
    );

    $form['miniorange_oauth_auto_redirect'] = array(
        '#type' => 'checkbox',
        '#title' => t('Check this option if you want to <b> Auto-redirect to OAuth Provider/Server </b><a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>[Premium, Enterprise]</b></a>'),
        '#disabled' => TRUE,
        '#description' => t('<b>Note: </b>Users will be redirected to your IdP for login when the login page is accessed.<br><br>'),
    );

    $form['miniorange_oauth_enable_backdoor'] = array(
        '#type' => 'checkbox',
        '#title' => t('Check this option if you want to enable <b>backdoor login </b><a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>[Premium, Enterprise]</b></a>'),
        '#disabled' => TRUE,
        '#description' => t('Note: Checking this option creates a backdoor to login to your Website using Drupal credentials<br> incase you get locked out of your IdP.
                <b>Note down this URL: </b>Available in <a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>Premium, Enterprise</b></a> versions of the module.<br><br><br><br>'),
    );

    $form['oauth_login_oauth2_siginin'] = array(
            '#type' => 'submit',
            '#id' => 'button_config_center',
            '#value' => t('Save Configuration'),
    );

    $form['mo_header_style_end'] = array('#markup' => '</div>');

    Utilities::spConfigGuide($form, $form_state);

    $form['mo_markup_div_imp']=array('#markup'=>'</div>');
    Utilities::AddSupportButton($form, $form_state);
    return $form;
 }

 public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $baseUrlvalue = $form['oauth_login_oauth2_base_url']['#value'];
    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_base_url', $baseUrlvalue)->save();
    \Drupal::messenger()->addMessage(t('Configurations saved successfully.'));
 }

/**
     * Send support query.
     */
    public function saved_support(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
        $email = trim($form['oauth_login_oauth2_email_address']['#value']);
        $phone = trim($form['oauth_login_oauth2_phone_number']['#value']);
        $query = trim($form['oauth_login_oauth2_support_query']['#value']);
        Utilities::send_support_query($email, $phone, $query);
    }

    public function rfd(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        global $base_url;
        $response = new RedirectResponse($base_url."/admin/config/people/oauth_login_oauth2/request_for_demo");
        $response->send();
    }
}
