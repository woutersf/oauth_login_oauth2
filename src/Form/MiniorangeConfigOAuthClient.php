<?php

/**
 * @file
 * Contains \Drupal\miniorange_oauth_client\Form\MiniorangeConfigOAuthClient.
 */

namespace Drupal\oauth_login_oauth2\Form;
use Drupal\oauth_login_oauth2\mo_saml_visualTour;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_login_oauth2\Utilities;

class MiniorangeConfigOAuthClient extends FormBase
{
    public function getFormId() {
       return 'miniorange_oauth_client_settings';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
    {
        global $base_url;
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_disabled', FALSE)->save();
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
                'library' => 'miniorange_oauth_client/miniorange_oauth_client_idp.Vtour',
            ),
            '#markup' => '<h3>CONFIGURE OAUTH APPLICATION &nbsp;&nbsp; <a id="Restart_moTour" class="btn btn-primary-color btn-large" onclick="Restart_moTour()">Take a Tour</a></h3><hr><br>',
        );

        $form['miniorange_oauth_client_app_options'] = array(
            '#type' => 'value',
            '#id' => 'miniorange_oauth_client_app_options',
            '#value' => array(
                        'Select' => t('Select'),
                       'Google' => t('Google'),
                       'Facebook' => t('Facebook'),
                       'Windows Account' => t('Windows Account'),
                       'Strava' => t('Strava'),
                       'FitBit' => t('FitBit'),
                       'Custom' => t('Custom OAuth 2.0 Provider')),
        );

        $form['miniorange_oauth_client_app'] = array(
            '#title' => t('Select Application: '),
            '#id' => 'miniorange_oauth_client_app',
            '#type' => 'select',
            '#disabled' => $disabled,
            '#description' => "Select an OAuth Server",
            '#options' => $form['miniorange_oauth_client_app_options']['#value'],
            '#attributes' => $attributes_arr,
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_app'),
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
            '#id'  => 'miniorange_oauth_client_app_name',
            '#title' => t('Custom App Name: '),
            '#disabled' => $disabled,
            '#attributes' => $attributes_arr,
        );

        $form['miniorange_oauth_client_display_name'] = array(
            '#type' => 'textfield',
            '#id'  => 'miniorange_oauth_client_display_name',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_display_name'),
            '#title' => t('Display Name: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['miniorange_oauth_client_id'] = array(
            '#type' => 'textfield',
            '#id'  => 'miniorange_oauth_client_client_id',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_id'),
            '#title' => t('Client Id: '),
            '#description' => "You will get this value from your OAuth Server",
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['miniorange_oauth_client_secret'] = array(
            '#type' => 'textfield',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_secret'),
            '#description' => "You will get this value from your OAuth Server",
            '#id'  => 'miniorange_oauth_client_client_secret',
            '#title' => t('Client Secret: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['miniorange_oauth_client_scope'] = array(
            '#type' => 'textfield',
            '#id'  => 'miniorange_oauth_client_scope',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_scope'),
            '#description' => "You can edit the value of this field. Scope decides the range of data that you will be getting from your OAuth Provider",
            '#title' => t('Scope: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['miniorange_oauth_client_authorize_endpoint'] = array(
            '#type' => 'textfield',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_authorize_endpoint'),
            '#id'  => 'miniorange_oauth_client_auth_ep',
            '#title' => t('Authorize Endpoint: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['miniorange_oauth_client_access_token_endpoint'] = array(
            '#type' => 'textfield',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_access_token_ep'),
            '#id'  => 'miniorange_oauth_client_access_token_ep',
            '#title' => t('Access Token Endpoint: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['miniorange_oauth_client_userinfo_endpoint'] = array(
            '#type' => 'textfield',
            '#default_value' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_user_info_ep'),
            '#id'  => 'miniorange_oauth_client_user_info_ep',
            '#title' => t('Get User Info Endpoint: '),
            '#attributes' => array('style' => 'width:73%'),
        );

        $form['mo_btn_breaks'] = array(
            '#markup' => "</div><br><br>",
        );

        $form['miniorange_oauth_client_config_submit'] = array(
            '#type' => 'submit',
            '#value' => t('Save Configuration'),
            '#id' => 'button_config',
        );


        $baseUrlValue = Utilities::getOAuthBaseURL($base_url);

        $form['miniorange_oauth_client_test_config_button'] = array(
            '#value' => t('Test'),
            '#markup' => '<span id="base_Url" name="base_Url" data="'. $baseUrlValue.'"></span>
                                <a id="testConfigButton" class="btn btn-primary-color btn-large mo_oauth_btn_fix">Test Configuration</a>',
        );

        $form['mo_reset'] = array(
            '#markup' => "<a class='btn btn-primary btn-large' id ='vt_reset_config' href='".$baseUrlValue."/resetConfig'>Reset Configuration</a>",
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

        if(isset($form['miniorange_oauth_client_app']))
            $client_app =  $form['miniorange_oauth_client_app']['#value'];
        if(isset($form['miniorange_oauth_app_name']['#value']))
            $app_name = $form['miniorange_oauth_app_name']['#value'];
        if(isset($form['miniorange_oauth_client_display_name']['#value']))
            $display_name = $form['miniorange_oauth_client_display_name'] ['#value'];
        if(isset($form['miniorange_oauth_client_id']))
            $client_id = trim($form['miniorange_oauth_client_id']['#value']);
        if(isset($form['miniorange_oauth_client_secret']['#value']))
            $client_secret = trim($form['miniorange_oauth_client_secret'] ['#value']);
        if(isset($form['miniorange_oauth_client_scope']['#value']))
            $scope = trim($form['miniorange_oauth_client_scope']['#value']);
        if(isset($form['miniorange_oauth_client_authorize_endpoint']['#value']))
            $authorize_endpoint = trim($form['miniorange_oauth_client_authorize_endpoint'] ['#value']);
        if(isset($form['miniorange_oauth_client_access_token_endpoint']['#value']))
            $access_token_ep = trim($form['miniorange_oauth_client_access_token_endpoint']['#value']);
        if(isset($form['miniorange_oauth_client_userinfo_endpoint']['#value']))
            $user_info_ep = trim($form['miniorange_oauth_client_userinfo_endpoint']['#value']);

        if(($client_app=='Select') || empty($client_app) || empty($app_name) || empty($client_id) || empty($client_secret) || empty($authorize_endpoint) || empty($access_token_ep)
            || empty($user_info_ep))
        {
            if(empty($client_app)|| $client_app == 'Select'){
                drupal_set_message(t('The <b>Select Application</b> dropdown is required. Please Select your application.'), 'error');
                return;
            }
            drupal_set_message(t('The <b>Custom App name</b>, <b>Client ID</b>, <b>Client Secret</b>, <b>Authorize Endpoint</b>, <b>Access Token Endpoint</b>
                , <b>Get User Info Endpoint</b> fields are required.'), 'error');
        }

        if(empty($client_app))
        {
            $client_app =\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_app');
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
            $user_info_ep = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_userinfo_endpoint');
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

        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_app',$client_app)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_appval',$app_values)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_app_name',$app_name)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_display_name',$display_name)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_client_id',$client_id)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_client_secret',$client_secret)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_scope',$scope)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_authorize_endpoint',$authorize_endpoint)->save();         \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_access_token_ep',$access_token_ep)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_user_info_ep',$user_info_ep)->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_stat',"Review Config")->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_callback_uri',$callback_uri)->save();
        drupal_set_message(t('Configurations saved successfully.'));
    }

    /**
     * Send support query.
     */
    public function saved_support(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        $email = $form['miniorange_oauth_client_email_address']['#value'];
        $phone = $form['miniorange_oauth_client_phone_number']['#value'];
        $query = $form['miniorange_oauth_client_support_query']['#value'];
        Utilities::send_support_query($email, $phone, $query);
    }

}