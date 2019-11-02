<?php

/**
 * @file
 * Contains \Drupal\oauth_login_oauth2\Form\MiniorangeConfigOAuthClient.
 */

namespace Drupal\oauth_login_oauth2\Form;
use Drupal\oauth_login_oauth2\mo_saml_visualTour;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_login_oauth2\Utilities;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MiniorangeConfigOAuthClient extends FormBase
{
    public function getFormId() {
       return 'oauth_login_oauth2_settings';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
    {
        global $base_url;
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_disabled', FALSE)->save();
        $moTour = mo_saml_visualTour::genArray();
        $form['tourArray'] = array(
            '#type' => 'hidden',
            '#value' => $moTour,
        );

        $baseUrlValue = Utilities::getOAuthBaseURL($base_url);

        $login_path = '<a href='.$baseUrlValue.'/moLogin>Enter what you want to display on the link</a>';
        $module_path = drupal_get_path('module', 'oauth_login_oauth2');

        $miniorange_auth_client_callback_uri = $baseUrlValue."/mo_login";
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_callback_uri',$miniorange_auth_client_callback_uri)->save();

        $attachments['#attached']['library'][] = 'oauth_login_oauth2/oauth_login_oauth2.admin';
        $form['markup_library'] = array(
            '#attached' => array(
               'library' => array(
                    "oauth_login_oauth2/oauth_login_oauth2.oauth_config",
                    "oauth_login_oauth2/oauth_login_oauth2.admin",
                    "oauth_login_oauth2/oauth_login_oauth2.testconfig",
                    "oauth_login_oauth2/oauth_login_oauth2.returnAttribute",
                    "oauth_login_oauth2/oauth_login_oauth2.style_settings",
                    "oauth_login_oauth2/oauth_login_oauth2.Vtour",
                    "oauth_login_oauth2/mo-card",
               )
            ),
        );

        $app_name = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_app_name');
        $client_id = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_id');
        if(!empty($app_name) && !empty($client_id)){
            $disabled = TRUE;
        }

        $app_name_selected = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_app_name');
        $client_id = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_id');
        if(!empty($app_name_selected) && !empty($client_id)){
            $disabled = TRUE;
            $attributes_arr =  array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;');
        }
        else{
            $disabled = FALSE;
            $attributes_arr =  array('style' => 'width:73%;');
        }

        $form['mo_oauth_top_div'] = array('#markup' => '<div class="mo_oauth_table_layout_1">');

        $form['mo_oauth_inside_div'] = array(
          '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
        );

        $form['markup_top_vt_start'] = array(
            '#attached' => array(
                'library' => 'oauth_login_oauth2/oauth_login_oauth2_idp.Vtour',
            ),
            '#markup' => '<h3>CONFIGURE OAUTH APPLICATION &nbsp;&nbsp; <a id="Restart_moTour" class="mo_oauth_btn mo_oauth_btn-primary-color mo_oauth_btn-large" onclick="Restart_moTour()">Take a Tour</a></h3><hr><br>',
        );

        $form['oauth_login_oauth2_app_options'] = array(
            '#type' => 'value',
            '#id' => 'oauth_login_oauth2_app_options',
            '#value' => array(
              'Select' => t('Select'),
              'Azure AD' => t('Azure AD'),
              'Keycloak' => t('Keycloak'),
              'Salesforce' => t('Salesforce'),
              'Google' => t('Google'),
              'Facebook' => t('Facebook'),
              'Discord' => t('Discord'),
              'Line' => t('Line'),
              'Wild Apricot' => t('Wild Apricot'),
              'LinkedIn' => t('LinkedIn'),
              'Strava' => t('Strava'),
              'FitBit' => t('FitBit'),
              'Custom' => t('Custom OAuth 2.0 Provider'),
              'Azure AD B2C' => t('Azure AD B2C (Premium and Enterprise)'),
              'AWS Cognito' => t('AWS Cognito (Premium and Enterprise)'),
              'Custom_openid' => t('Custom OpenID Provider (We support OpenID protocol in Premium and Enterprise version)')),
        );

        $form['oauth_login_oauth2_app'] = array(
            '#title' => t('Select Application: '),
            '#id' => 'oauth_login_oauth2_app',
            '#type' => 'select',
            '#disabled' => $disabled,
            '#description' => "Select an OAuth Server",
            '#options' => $form['oauth_login_oauth2_app_options']['#value'],
            '#attributes' => $attributes_arr,
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_app'),
        );

        $form['mo_vt_id_start'] = array(
            '#markup' => '<div id = "mo_vt_callback_url">',
        );

        $form['miniorange_oauth_callback'] = array(
            '#type' => 'textfield',
            '#title' => t('Callback/Redirect URL: '),
            '#id'  => 'callbackurl',
            '#default_value' => $base_url."/mo_login",
            '#disabled' => true,
            '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;'),
        );

        $form['mo_vt_id_end'] = array(
            '#markup' => '</div>',
        );

        $form['mo_vt_id_data'] = array(
            '#markup' => '<div id = "mo_vt_add_data">',
        );

        $form['miniorange_oauth_app_name'] = array(
            '#type' => 'textfield',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_app_name'),
            '#id'  => 'oauth_login_oauth2_app_name',
            '#title' => t('Custom App Name: '),
            '#disabled' => $disabled,
            '#attributes' => $attributes_arr,
        );

        $form['oauth_login_oauth2_display_name'] = array(
            '#type' => 'textfield',
            '#id'  => 'oauth_login_oauth2_display_name',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_display_name'),
            '#title' => t('Display Name: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['oauth_login_oauth2_id'] = array(
            '#type' => 'textfield',
            '#id'  => 'oauth_login_oauth2_client_id',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_id'),
            '#title' => t('Client Id: '),
            '#description' => "You will get this value from your OAuth Server",
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['oauth_login_oauth2_secret'] = array(
            '#type' => 'textfield',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_secret'),
            '#description' => "You will get this value from your OAuth Server",
            '#id'  => 'oauth_login_oauth2_client_secret',
            '#title' => t('Client Secret: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['oauth_login_oauth2_scope'] = array(
            '#type' => 'textfield',
            '#id'  => 'oauth_login_oauth2_scope',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_scope'),
            '#description' => "You can edit the value of this field. Scope decides the range of data that you will be getting from your OAuth Provider",
            '#title' => t('Scope: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['oauth_login_oauth2_authorize_endpoint'] = array(
            '#type' => 'textfield',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_authorize_endpoint'),
            '#id'  => 'oauth_login_oauth2_auth_ep',
            '#title' => t('Authorize Endpoint: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['oauth_login_oauth2_access_token_endpoint'] = array(
            '#type' => 'textfield',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_access_token_ep'),
            '#id'  => 'oauth_login_oauth2_access_token_ep',
            '#title' => t('Access Token Endpoint: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['oauth_login_oauth2_userinfo_endpoint'] = array(
            '#type' => 'textfield',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_user_info_ep'),
            '#id'  => 'oauth_login_oauth2_user_info_ep',
            '#title' => t('Get User Info Endpoint: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['mo_btn_breaks'] = array(
            '#markup' => "</div><br><br>",
        );

        $form['oauth_login_oauth2_config_submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save Configuration'),
            '#id' => 'button_config',
        );


        $baseUrlValue = Utilities::getOAuthBaseURL($base_url);

        $form['oauth_login_oauth2_test_config_button'] = array(
            '#value' => t('Test'),
            '#markup' => '<span id="base_Url" name="base_Url" data="'. $baseUrlValue.'"></span>
                                <a id="testConfigButton" class="mo_oauth_btn mo_oauth_btn-primary-color mo_oauth_btn-large mo_oauth_btn_fix">Test Configuration</a>',
        );

        $form['mo_reset'] = array(
            '#markup' => "<a class='mo_oauth_btn mo_oauth_btn-primary mo_oauth_btn-large' id ='vt_reset_config' href='".$baseUrlValue."/resetConfig'>Reset Configuration</a>",
        );

        $form['miniorange_oauth_login_link'] = array(
            '#id'  => 'miniorange_oauth_login_link',
            '#markup' => "<br><br><br><br><div class='mo_oauth_instruction_style'>
                <br><strong><div class='mo_custom_font_size_1'>Instructions to add login link to different pages in your Drupal site: </div></strong><br>
                <div class='mo_custom_font_size_2'>After completing your configurations, by default you will see a login link on your drupal site's login page.
                However, if you want to add login link somewhere else, please follow the below given steps:</div>
                <div class='mo_custom_font_size_3'>
                <li>Go to <b>Structure</b> -> <b>Blocks</b></li>
                <li> Click on <b>Add block</b></li>
                <li>Enter <b>Block Title</b> and the <b>Block description</b></li>
                <li>Under the <b>Block body</b> add the following URL to add a login link:
                    <ol> <h3><b>&lt;a href= '".$baseUrlValue."/moLogin'> Click here to Login&lt;/a&gt;</b></h3></ol>
                </li>
                <li>From the text filtered dropdown select either <b>Filtered HTML</b> or <b>Full HTML</b></li>
                <li>From the division under <b>REGION SETTINGS</b> select where do you want to show the login link</li>
                <li>Click on the <b>SAVE block</b> button to save your settings</li><br>
                </div>
                </div>",
            '#attributes' => array(),
        );

        $form['mo_header_style_end'] = array('#markup' => '</div>');


        Utilities::spConfigGuide($form, $form_state);
        $form['mo_markup_div_2_imp']=array('#markup'=>'</div>');
        Utilities::AddSupportButton($form, $form_state);

        return $form;
    }

    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
        global $base_url;
        $baseUrlValue = Utilities::getOAuthBaseURL($base_url);

        if(isset($form['oauth_login_oauth2_app']))
            $client_app =  $form['oauth_login_oauth2_app']['#value'];
        if(isset($form['miniorange_oauth_app_name']['#value']))
            $app_name = $form['miniorange_oauth_app_name']['#value'];
        if(isset($form['oauth_login_oauth2_display_name']['#value']))
            $display_name = $form['oauth_login_oauth2_display_name'] ['#value'];
        if(isset($form['oauth_login_oauth2_id']))
            $client_id = trim($form['oauth_login_oauth2_id']['#value']);
        if(isset($form['oauth_login_oauth2_secret']['#value']))
            $client_secret = trim($form['oauth_login_oauth2_secret'] ['#value']);
        if(isset($form['oauth_login_oauth2_scope']['#value']))
            $scope = trim($form['oauth_login_oauth2_scope']['#value']);
        if(isset($form['oauth_login_oauth2_authorize_endpoint']['#value']))
            $authorize_endpoint = trim($form['oauth_login_oauth2_authorize_endpoint'] ['#value']);
        if(isset($form['oauth_login_oauth2_access_token_endpoint']['#value']))
            $access_token_ep = trim($form['oauth_login_oauth2_access_token_endpoint']['#value']);
        if(isset($form['oauth_login_oauth2_userinfo_endpoint']['#value']))
            $user_info_ep = trim($form['oauth_login_oauth2_userinfo_endpoint']['#value']);

        if(($client_app=='Select') || empty($client_app) || empty($app_name) || empty($client_id) || empty($client_secret) || empty($authorize_endpoint) || empty($access_token_ep)
            || empty($user_info_ep))
        {
            if(empty($client_app)|| $client_app == 'Select'){
                \Drupal::messenger()->addMessage(t('The <b>Select Application</b> dropdown is required. Please Select your application.'), 'error');
                return;
            }
            \Drupal::messenger()->addMessage(t('The <b>Custom App name</b>, <b>Client ID</b>, <b>Client Secret</b>, <b>Authorize Endpoint</b>, <b>Access Token Endpoint</b>
                , <b>Get User Info Endpoint</b> fields are required.'), 'error');
            return;
        }

        if(empty($client_app))
        {
            $client_app =\Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_app');
        }
        if(empty($app_name))
        {
            $client_app = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_app_name');
        }
        if(empty($client_id))
        {
            $client_id =\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_id');
        }
        if(empty($client_secret))
        {
            $client_secret = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_secret');
        }
        if(empty($scope))
        {
            $scope = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_scope');
        }
        if(empty($authorize_endpoint))
        {
            $authorize_endpoint = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_authorize_endpoint');
        }
        if(empty($access_token_ep))
        {
            $access_token_ep =\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_access_token_ep');
        }
        if(empty($user_info_ep))
        {
            $user_info_ep = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_userinfo_endpoint');
        }

        $callback_uri = $baseUrlValue."/mo_login";

        $app_values = array();
        $app_values['client_id'] = $client_id;
        $app_values['client_secret'] = $client_secret;
        $app_values['app_name'] = $app_name;
        $app_values['display_name'] = $display_name;
        $app_values['scope'] = $scope;
        $app_values['authorize_endpoint'] = $authorize_endpoint;
        $app_values['access_token_ep'] = $access_token_ep;
        $app_values['user_info_ep'] = $user_info_ep;
        $app_values['callback_uri'] = $callback_uri;
        $app_values['client_app'] = $client_app;

        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_app',$client_app)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_appval',$app_values)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_app_name',$app_name)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_display_name',$display_name)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_client_id',$client_id)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_client_secret',$client_secret)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_scope',$scope)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_authorize_endpoint',$authorize_endpoint)->save();         \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_access_token_ep',$access_token_ep)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_user_info_ep',$user_info_ep)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_stat',"Review Config")->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_callback_uri',$callback_uri)->save();
        \Drupal::messenger()->addMessage(t('Configurations saved successfully.'));
    }

    /**
     * Send support query.
     */
    public function saved_support(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        $email = $form['oauth_login_oauth2_email_address']['#value'];
        $phone = $form['oauth_login_oauth2_phone_number']['#value'];
        $query = $form['oauth_login_oauth2_support_query']['#value'];
        Utilities::send_support_query($email, $phone, $query);
    }

    public function rfd(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        global $base_url;
        $response = new RedirectResponse($base_url."/admin/config/people/oauth_login_oauth2/request_for_demo");
        $response->send();
    }
}
