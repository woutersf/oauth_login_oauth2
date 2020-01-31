<?php

/**
 * @file
 * Contains \Drupal\miniorange_oauth_client\Form\MiniorangeOAuthClientCustomerSetup.
 */

namespace Drupal\oauth_login_oauth2\Form;

use Drupal\oauth_login_oauth2\MiniorangeOAuthClientCustomer;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_login_oauth2\MiniorangeOAuthClientSupport;
use Drupal\oauth_login_oauth2\Utilities;
use Symfony\Component\HttpFoundation\RedirectResponse;

    class MiniorangeOAuthClientCustomerSetup extends FormBase
    {
        public function getFormId() {
            return 'miniorange_oauth_client_customer_setup';
        }

        public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
        {
                global $base_url;

                $current_status = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_status');
                $form['markup_library'] = array(
                    '#attached' => array(
                        'library' => array(
                            "oauth_login_oauth2/oauth_login_oauth2.admin",
                            "oauth_login_oauth2/oauth_login_oauth2.style_settings",
                            "oauth_login_oauth2/oauth_login_oauth2.module",
                            "oauth_login_oauth2/oauth_login_oauth2.slide_support_button",
                        )
                    ),
                );

                if ($current_status == 'VALIDATE_OTP')
                {
                    $form['header_top_style_1'] = array('#markup' => '<div class="mo_oauth_table_layout_1">');

                    $form['markup_top'] = array(
                        '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
                    );

                    $form['miniorange_oauth_client_customer_otp_token'] = array(
                        '#type' => 'textfield',
                        '#title' => t('OTP'),
                        '#attributes' => array('style' => 'width:30%;'),
                    );

                    $form['mo_btn_brk'] = array('#markup' => '<br><br>');

                    $form['miniorange_oauth_client_customer_validate_otp_button'] = array(
                        '#type' => 'submit',
                        '#value' => t('Validate OTP'),
                        '#submit' => array('::miniorange_oauth_client_validate_otp_submit'),
                    );

                    $form['miniorange_oauth_client_customer_setup_resendotp'] = array(
                        '#type' => 'submit',
                        '#value' => t('Resend OTP'),
                        '#submit' => array('::miniorange_oauth_client_resend_otp'),
                    );

                    $form['miniorange_oauth_client_customer_setup_back'] = array(
                    '#type' => 'submit',
                    '#value' => t('Back'),
                    '#submit' => array('::miniorange_oauth_client_back'),
                    );

                    $form['header_top_div_end'] = array('#markup' => '</div></div>');

                    Utilities::AddSupportButton($form, $form_state);

                    return $form;
                }
                elseif ($current_status == 'PLUGIN_CONFIGURATION')
                {

                    $form['header_top_style_1'] = array('#markup' => '<div class="mo_oauth_table_layout_1">');

                    $form['markup_top'] = array(
                        '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
                    );

                    $form['mo_message_wlcm'] = array(
                        '#markup' => '<div class="mo_oauth_client_welcome_message">Thank you for registering with miniOrange',
                    );

                    $form['mo_user_profile'] = array(
                        '#markup' => '</div><br><br><h4>Your Profile: </h4>'
                    );

                    $header = array(
                        'email' => array(
                        'data' => t('Customer Email')
                        ),
                        'customerid' => array(
                            'data' => t('Customer ID')
                        ),
                        'token' => array(
                            'data' => t('Token Key')
                        ),
                        'apikey' => array(
                            'data' => t('API Key')
                        ),
                    );

                    $options = [];

                    $options[0] = array(
                        'email' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_email'),
                        'customerid' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_id'),
                        'token' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_token'),
                        'apikey' => \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_api_key'),
                    );

                    $form['fieldset']['customerinfo'] = array(
                        '#theme' => 'table',
                        '#header' => $header,
                        '#rows' => $options,
                    );

                    $form['miniorange_oauth_client_support_div_cust'] = array(
                        '#markup' => '<br><br><br><br></div>'
                    );

                    Utilities::spConfigGuide($form, $form_state);
                    $form['mo_markup_div_end2']=array('#markup'=>'</div>');

                    Utilities::AddSupportButton($form, $form_state);
                    return $form;
                }

                $form['header_top_style_1'] = array('#markup' => '<div class="mo_oauth_table_layout_1">');

                $form['markup_top'] = array(
                    '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
                );

                $form['markup_14'] = array('#markup' => '<h3>Register/Login with miniOrange</h3><hr><br>');

            $form['markup_2'] = array(
                '#markup' => '<div class="mo_oauth_highlight_background_note_export"><p><h3>Why Should I register?</h3></p>
            <b> You will be needing a miniOrange account to upgrade to the Standard, Premium or Enterprise versions of the modules.</b>
             If you face any problem during registration, you can create an account by clicking <a href="https://www.miniorange.com/businessfreetrial" target="_blank">here.</a><br>
             We do not store any information except the email that you will use to register with us.<br></div><br>',
            );

                $form['miniorange_oauth_client_customer_setup_username'] = array(
                    '#type' => 'textfield',
                    '#title' => t('Email'),
                    '#attributes' => array('style' => 'width:50%;', 'placeholder' => 'Enter your email'),
                    '#required' => TRUE,
                );

                $form['miniorange_oauth_client_customer_setup_phone'] = array(
                    '#type' => 'textfield',
                    '#title' => t('Phone'),
                    '#attributes' => array('style' => 'width:50%;'),
                    '#description' => '<b>NOTE:</b> We will only call if you need support.'
                );

                $form['miniorange_oauth_client_customer_setup_password'] = array(
                    '#type' => 'password_confirm',
                    '#required' => TRUE,
                );

                $form['miniorange_oauth_client_customer_setup_button'] = array(
                    '#type' => 'submit',
                    '#value' => t('Submit'),
                    '#id' => 'button_config'
                );

                $form['markup_divEnd'] = array(
                    '#markup' => '</div>'
                );

                Utilities::spConfigGuide($form, $form_state);
                $form['mo_markup_div_end']=array('#markup'=>'</div>');
                Utilities::AddSupportButton($form, $form_state);
                return $form;
        }

        public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

                $username = trim($form['miniorange_oauth_client_customer_setup_username']['#value']);
                $phone = $form['miniorange_oauth_client_customer_setup_phone']['#value'];
                $password = trim($form['miniorange_oauth_client_customer_setup_password']['#value']['pass1']);

                if(strlen($password)<6)
                {
                    \Drupal::messenger()->addMessage(t('Password is too short.'), 'error');
                    return;
                }
                if(empty($username)||empty($password)){
                    \Drupal::messenger()->addMessage(t('The <b><u>Email </u></b> and <b><u>Password</u></b> fields are mandatory.'), 'error');
                    return;
                }
                if (!valid_email_address($username)) {
                    \Drupal::messenger()->addMessage(t('The email address <i>' . $username . '</i> is not valid.'), 'error');
                    return;
                }
                $customer_config = new MiniorangeOAuthClientCustomer($username, $phone, $password, NULL);
            $check_customer_response = json_decode($customer_config->checkCustomer());
            if ($check_customer_response->status == 'CUSTOMER_NOT_FOUND') {
                    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_admin_email', $username)->save();
                    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_admin_phone', $phone)->save();
                    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_admin_password', $password)->save();
                    $send_otp_response = json_decode($customer_config->sendOtp());

                    if ($send_otp_response->status == 'SUCCESS') {
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_tx_id', $send_otp_response->txId)->save();
                        $current_status = 'VALIDATE_OTP';
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_status', $current_status)->save();
                        \Drupal::messenger()->addMessage(t('Verify email address by entering the passcode sent to @username', [
                            '@username' => $username
                        ]));
                    }
                }
                elseif ($check_customer_response->status == 'CURL_ERROR') {
                    \Drupal::messenger()->addMessage(t('cURL is not enabled. Please enable cURL'), 'error');
                }
                else {
                    $customer_keys_response = json_decode($customer_config->getCustomerKeys());
                    if (json_last_error() == JSON_ERROR_NONE) {
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_id', $customer_keys_response->id)->save();
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_admin_token', $customer_keys_response->token)->save();
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_admin_email', $username)->save();
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_admin_phone', $phone)->save();
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_api_key', $customer_keys_response->apiKey)->save();
                        $current_status = 'PLUGIN_CONFIGURATION';
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_status', $current_status)->save();
                        \Drupal::messenger()->addMessage(t('Successfully retrieved your account.'));
                    }
                    elseif($check_customer_response->status == 'TRANSACTION_LIMIT_EXCEEDED') {
                        \Drupal::messenger()->addMessage(t('An error has been occured. Please try after some time or contact us at <a href="mailto:drupalsupport@xecurify.com" target="_blank">drupalsupport@xecurify.com</a>.'), 'error');
                        return;
                    }
                    else{
                        \Drupal::messenger()->addMessage(t('Invalid credentials.'), 'error');
                        return;
                    }

                }
        }

        public function miniorange_oauth_client_back(&$form, $form_state) {
                $current_status = 'CUSTOMER_SETUP';
                \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_status', $current_status)->save();
                \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_miniorange_oauth_client_customer_admin_email')->save();
                \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_oauth_client_customer_admin_phone')->save();
                \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_oauth_client_tx_id')->save();
                \Drupal::messenger()->addMessage(t('Register/Login with your miniOrange Account'),'status');
        }

        public function miniorange_oauth_client_resend_otp(&$form, $form_state) {
                \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_oauth_client_tx_id')->save();
                $username = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_email');
                $phone = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_phone');
                $customer_config = new MiniorangeOAuthClientCustomer($username, $phone, NULL, NULL);
                $send_otp_response = json_decode($customer_config->sendOtp());
                if ($send_otp_response->status == 'SUCCESS') {
                    // Store txID.
                    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_tx_id', $send_otp_response->txId)->save();
                    $current_status = 'VALIDATE_OTP';
                    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_status', $current_status)->save();
                    \Drupal::messenger()->addMessage(t('Verify email address by entering the passcode sent to @username', array('@username' => $username)));
                }
        }

        public function miniorange_oauth_client_validate_otp_submit(&$form, $form_state) {
                $otp_token = trim($form['miniorange_oauth_client_customer_otp_token']['#value']);
                if ($otp_token == NULL)
                {
                    \Drupal::messenger()->addMessage(t('Please enter OTP first.'), 'error');
                    return;
                }
                $username = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_email');
                $phone = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_phone');
                $tx_id = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_tx_id');
                $customer_config = new MiniorangeOAuthClientCustomer($username, $phone, NULL, $otp_token);
                $validate_otp_response = json_decode($customer_config->validateOtp($tx_id));
                if ($validate_otp_response->status == 'SUCCESS')
                {
                    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_oauth_client_tx_id')->save();
                    $password = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_password');
                    $customer_config = new MiniorangeOAuthClientCustomer($username, $phone, $password, NULL);
                    $create_customer_response = json_decode($customer_config->createCustomer());
                    if ($create_customer_response->status == 'SUCCESS') {
                        $current_status = 'PLUGIN_CONFIGURATION';
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_status', $current_status)->save();
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_admin_email', $username)->save();
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_admin_phone', $phone)->save();
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_admin_token', $create_customer_response->token)->save();
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_id', $create_customer_response->id)->save();
                        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_customer_api_key', $create_customer_response->apiKey)->save();
                        \Drupal::messenger()->addMessage(t('Customer account created.'));
                    }
                    else if(trim($create_customer_response->message) == 'Email is not enterprise email.')
                    {
                        \Drupal::messenger()->addMessage(t('There was an error creating an account for you.<br> You may have entered an invalid Email-Id
                        <strong>(We discourage the use of disposable emails) </strong>
                        <br>Please try again with a valid email.'), 'error');
                    }
                    else {
                        \Drupal::messenger()->addMessage(t('Error creating customer'), 'error');
                        return;
                    }
                }
                else {
                    \Drupal::messenger()->addMessage(t('Error validating OTP'), 'error');
                    return;
                }
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
