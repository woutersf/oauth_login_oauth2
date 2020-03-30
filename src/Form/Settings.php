<?php

namespace Drupal\oauth_login_oauth2\Form;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_login_oauth2\Utilities;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Settings extends FormBase
{
    public function getFormId() {
        return 'miniorange_oauth_client_settings';
    }
/**
 * Showing Settings form.
 */
 public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    global $base_url;
    $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url');

    $attachments['#attached']['library'][] = 'oauth_login_oauth2/oauth_login_oauth2.admin';

    $form['markup_library'] = array(
        '#attached' => array(
            'library' => array(
                "oauth_login_oauth2/oauth_login_oauth2.admin",
                "oauth_login_oauth2/oauth_login_oauth2.style_settings",
                "oauth_login_oauth2/oauth_login_oauth2.slide_support_button",
            )
        ),
    );

    $form['header_top_style_1'] = array('#markup' => '<div class="mo_oauth_table_layout_1">');

    $form['markup_top'] = array(
         '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
    );

    $form['markup_top_vt_start'] = array(
         '#markup' => '<b><h3>SIGN IN SETTINGS</h3></b><hr><br/>'
    );

    $form['miniorange_oauth_client_base_url'] = array(
        '#type' => 'textfield',
        '#title' => t('Base URL: '),
        '#default_value' => $baseUrlValue,
        '#attributes' => array('id'=>'mo_oauth_vt_baseurl','style' => 'width:73%;','placeholder' => 'Enter Base URL'),
        '#description' => '<b>Note: </b>You can change your base/site URL from here. (For eg: https://www.xyz.com or http://localhost/abc)',
        '#suffix' => '<br>',
    );

    
    $form['miniorange_oauth_client_siginin1'] = array(
        '#type' => 'submit',
        '#id' => 'button_config_center',
        '#value' => t('Update'),
        '#suffix' => '<br><hr>',
    );

    $form['miniorange_oauth_force_auth'] = array(
        '#type' => 'checkbox',
        '#title' => t('Protect website against anonymous access <a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>[Premium, Enterprise]</b></a>'),
        '#disabled' => TRUE,
        '#description' => t('<b>Note: </b>Users will be redirected to your OAuth server for login in case user is not logged in and tries to access website.<br><br>'),
    );

    $form['miniorange_oauth_auto_redirect'] = array(
        '#type' => 'checkbox',
        '#title' => t('Check this option if you want to <b> Auto-redirect to OAuth Provider/Server </b><a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>[Premium, Enterprise]</b></a>'),
        '#disabled' => TRUE,
        '#description' => t('<b>Note: </b>Users will be redirected to your OAuth server for login when the login page is accessed.<br><br>'),
    );

    $form['miniorange_oauth_enable_backdoor'] = array(
        '#type' => 'checkbox',
        '#title' => t('Check this option if you want to enable <b>backdoor login </b><a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>[Premium, Enterprise]</b></a>'),
        '#disabled' => TRUE,
        '#description' => t('<b>Note: </b>Checking this option creates a backdoor to login to your Website using Drupal credentials<br> incase you get locked out of your OAuth server.
                <b>Note down this URL: </b>Available in <a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>Premium, Enterprise</b></a> versions of the module.<br><br><br><br>'),
    );

     $form['markup_bottom_vt_start'] = array(
         '#markup' => '<hr><b><h3>DOMAIN & PAGE RESTRICTION</h3></b><hr><br/>'
     );

     $form['miniorange_oauth_client_white_list_url'] = array(
         '#type' => 'textfield',
         '#title' => t('Allowed Domains <a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>[Enterprise]</b></a>'),
         '#attributes' => array('style' => 'width:73%','placeholder' => 'Enter semicolon(;) separated domains (Eg. xxxx.com; xxxx.com)'),
         '#description' => t('<b>Note: </b> Enter <b>semicolon(;) separated</b> domains to allow SSO. Other than these domains will not be allowed to do SSO.'),
         '#disabled' => TRUE,
     );

     $form['miniorange_oauth_client_black_list_url'] = array(
         '#type' => 'textfield',
         '#title' => t('Restricted Domains <a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>[Enterprise]</b></a>'),
         '#attributes' => array('style' => 'width:73%','placeholder' => 'Enter semicolon(;) separated domains (Eg. xxxx.com; xxxx.com)'),
         '#description' => t('<b>Note: </b> Enter <b>semicolon(;) separated</b> domains to restrict SSO. Other than these domains will be allowed to do SSO.'),
         '#disabled' => TRUE,
     );

     $form['miniorange_oauth_client_page_restrict_url'] = array(
         '#type' => 'textfield',
         '#title' => t('Page Restriction <a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>[Enterprise]</b></a>'),
         '#attributes' => array('style' => 'width:73%','placeholder' => 'Enter semicolon(;) separated page URLs (Eg. xxxx.com/yyy; xxxx.com/yyy)'),
         '#description' => t('<b>Note: </b> Enter <b>semicolon(;) separated</b> URLs to restrict unauthorized access.'),
         '#disabled' => TRUE,
     );

    $form['miniorange_oauth_client_siginin'] = array(
            '#type' => 'button',
            '#id' => 'button_config_center',
            '#disabled' => TRUE,
            '#value' => t('Save Configuration'),
    );

    $form['mo_header_style_end'] = array('#markup' => '</div>');

    Utilities::spConfigGuide($form, $form_state);

    $form['mo_markup_div_imp']=array('#markup'=>'</div>');
    Utilities::AddSupportButton($form, $form_state);
    return $form;
 }

 public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $baseUrlvalue = trim($form['miniorange_oauth_client_base_url']['#value']);
    if(!empty($baseUrlvalue) && filter_var($baseUrlvalue, FILTER_VALIDATE_URL) == FALSE) {
        \Drupal::messenger()->adderror(t('Please enter a valid URL'));
        return;
    }
    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_base_url', $baseUrlvalue)->save();
    \Drupal::messenger()->addMessage(t('Configurations saved successfully.'));
 }

/**
     * Send support query.
     */
    public function saved_support(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
        $email = trim($form['miniorange_oauth_client_email_address']['#value']);
        $phone = trim($form['miniorange_oauth_client_phone_number']['#value']);
        $query = trim($form['miniorange_oauth_client_support_query']['#value']);
        Utilities::send_support_query($email, $phone, $query);
    }

    public function rfd(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        global $base_url;
        $response = new RedirectResponse($base_url."/admin/config/people/oauth_login_oauth2/request_for_demo");
        $response->send();
    }
}
