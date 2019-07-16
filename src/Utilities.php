<?php
/**
 * @package    miniOrange
 * @subpackage Plugins
 * @license    GNU/GPLv3
 * @copyright  Copyright 2015 miniOrange. All Rights Reserved.
 *
 *
 * This file is part of miniOrange Drupal OAuth Client module.
 *
 * miniOrange Drupal OAuth Client modules is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * miniOrange Drupal OAuth Client module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with miniOrange SAML plugin.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Drupal\oauth_login_oauth2;
use DOMElement;
use DOMDocument;
use DOMNode;
use DOMXPath;
class Utilities {

    /**
     * Shows support block
     */
    public static function AddSupportButton(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
    {
        $form['markup_idp_attr_header_top_support_btn'] = array(
            '#markup' => '<div id="mosaml-feedback-form" class="mo_saml_table_layout_support_btn">',
        );

        $form['miniorange_saml_idp_support_side_button'] = array(
            '#type' => 'button',
            '#value' => t('Support'),
            '#attributes' => array('style' => 'font-size: 15px;cursor: pointer;text-align: center;width: 150px;height: 35px;
                background: rgba(43, 141, 65, 0.93);color: #ffffff;border-radius: 3px;transform: rotate(90deg);text-shadow: none;
                position: relative;margin-left: -92px;top: 107px;'),
        );

        $form['markup_idp_attr_header_top_support'] = array(
            '#markup' => '<div id="Support_Section" class="mo_saml_table_layout_support_1">',
        );

        $form['markup_support_1'] = array(
            '#markup' => '<h3><b>Feature Request/Contact Us:</b></h3><div>Need any help? We can help you with configuring your Service Provider. Just send us a query and we will get back to you soon.<br /></div><br>',
        );

        $form['miniorange_oauth_client_email_address'] = array(
            '#type' => 'textfield',
            '#attributes' => array('placeholder' => 'Enter your Email'),
        );

        $form['miniorange_oauth_client_phone_number'] = array(
            '#type' => 'textfield',
            '#attributes' => array('placeholder' => 'Enter your Phone Number'),
        );

        $form['miniorange_oauth_client_support_query'] = array(
            '#type' => 'textarea',
            '#clos' => '10',
            '#rows' => '5',
            '#attributes' => array('placeholder' => 'Write your query here'),
        );

        $form['miniorange_oauth_client_support_submit'] = array(
            '#type' => 'submit',
            '#value' => t('Submit Query'),
            '#submit' => array('::saved_support'),
            '#attributes' => array('style' => 'background: #337ab7;color: #ffffff;text-shadow: 0 -1px 1px #337ab7, 1px 0 1px #337ab7, 0 1px 1px #337ab7, -1px 0 1px #337ab7;box-shadow: 0 1px 0 #337ab7;border-color: #337ab7 #337ab7 #337ab7;display:block;margin-left:auto;margin-right:auto;'),
        );

        $form['miniorange_oauth_client_support_note'] = array(
            '#markup' => '<div><br/>If you want custom features in the plugin, just drop an email to <a href="mailto:info@xecurify.com">info@xecurify.com</a></div>'
        );

        $form['miniorange_oauth_client_div_end'] = array(
            '#markup' => '</div></div><div hidden id="mosaml-feedback-overlay"></div>'
        );

    }
    public static function send_support_query($email, $phone, $query)
    {
        if(empty($email)||empty($query)){
            drupal_set_message(t('The <b><u>Email</u></b> and <b><u>Query</u></b> fields are mandatory.'), 'error');
            return;
        } elseif(!valid_email_address($email)) {
            drupal_set_message(t('The email address <b><i>' . $email . '</i></b> is not valid.'), 'error');
            return;
        }
        $support = new MiniorangeOAuthClientSupport($email, $phone, $query);
        $support_response = $support->sendSupportQuery();
        if($support_response) {
            drupal_set_message(t('Support query successfully sent'));
        }
        else {
            drupal_set_message(t('Error sending support query'), 'error');
        }
    }

    public static function getOAuthBaseURL($base_url){
        if(!empty(\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url')))
            $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url');
        else
            $baseUrlValue = $base_url;

        return $baseUrlValue;
    }

	public static function isCurlInstalled() {
      if (in_array('curl', get_loaded_extensions())) {
        return 1;
      }
      else {
        return 0;
      }
    }

    public static function isCustomerRegistered()
    {
        if (
          empty(\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_email'))||
          empty(\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_id')) ||
          empty(\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_token')) ||
          empty(\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_api_key')))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Shows block to configure various oauth servers
     */
    public static function spConfigGuide(&$form, &$form_state)
    {
        $form['miniorange_idp_setup_guide_link'] = array(
            '#markup' => '<div class="mo_saml_table_layout mo_saml_container_2" id="mo_oauth_guide_vt">',
        );

        $form['miniorange_idp_guide_link1'] = array(
            '#markup' => '<div><h5>To see detailed documentation of how to configure Drupal OAuth Client with any OAuth Server.</h5></div></br>',
        );

        $form['miniorange_oauth_guide_table_list'] = array(
            '#markup' => '<div class="table-responsive mo_guide_text-center" style="font-family: sans-serif;font-size: 15px;">          
                <table class="mo_guide_table mo_guide_table-striped mo_guide_table-bordered" style="border: 1px solid #ddd;max-width: 100%;border-collapse: collapse;">
                    <thead>
                        <tr><th class="mo_guide_text-center" style="">Providers</th><th class="mo_guide_text-center">Links</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>AWS Cognito</td><td><strong><a href="https://plugins.miniorange.com/configure-aws-cognito-oauth-openid-connect-server-in-drupal-8" class="mo_guide_text-color" target="_blank">Click Here</a></strong></td></tr>
                        <tr><td>Reddit</td><td><strong><a class="mo_guide_text-color" href="https://plugins.miniorange.com/configure-reddit-oauthopenid-connect-server-drupal-8" target="_blank">Click Here</a></strong></td></tr>
                        <tr><td>Google</td><td><strong><a class="mo_guide_text-color" href="https://plugins.miniorange.com/configure-google-oauth-server-drupal-8" target="_blank">Click Here</a></strong></td></tr>
                        <tr><td>Facebook</td><td><strong><a href="https://plugins.miniorange.com/configure-facebook-oauth-server-for-drupal-8" class="mo_guide_text-color" target="_blank">Click Here</a></strong></td></tr>
                        <tr><td>FitBit</td><td><strong><a href="https://plugins.miniorange.com/configure-fitbit-oauth-server-for-drupal-8" class="mo_guide_text-color" target="_blank">Click Here</a></strong></td></tr>
                        <tr><td>Instagram</td><td><strong><a href="https://plugins.miniorange.com/configure-instagram-as-an-oauth-openid-connect-server-for-drupal-8-client" class="mo_guide_text-color" target="_blank">Click Here</a></strong></td></tr>
                        <tr><td>linkedin</td><td><strong><a href="https://plugins.miniorange.com/configure-linkedin-as-an-oauth-openid-connect-server-for-drupal-8-client" class="mo_guide_text-color" target="_blank">Click Here</a></strong></td></tr>
                    </tbody>
                </table>
                <div>In case you do not find your desired OAuth Provider listed here, please mail us on <a href="mailto:info@xecurify.com">info@xecurify.com</a>
                    and we will help you to set it up.</div>
            </div>',

        );
    }

    /**
     * Advertise OAuth Server Module
     */
    public static function advertiseServer(&$form, &$form_state){
        global $base_url;
        $module_path = drupal_get_path('module', 'oauth_login_oauth2');
        $form['miniorange_oauth_client_setup_guide_link'] = array(
            '#markup' => '<div class="mo_saml_table_layout mo_saml_container_2" id="mo_oauth_guide_vt">',
        );

        $form['miniorange_oauth_client_guide_link1'] = array(
            '#markup' => '<div style="font-size: 15px;"><i>Looking for a Drupal OAuth Server module? Now create your own Drupal site as an OAuth Server.</i></div></br>',
        );

        $form['miniorange_oauth_client_guide_table_list'] = array(
            '#markup' => '<div class="table-responsive mo_guide_text-center" style="font-family: sans-serif;font-size: 15px;">
                <table class="" style="border: none !important;max-width: 100%;border-collapse: collapse;">
                    <thead>
                        <tr><th class="mo_guide_text-center" style="border: none;"><img src="'.$base_url.'/'. $module_path . '/includes/images/miniorange.png" alt="Simply Easy Learning" height = 80px width = 80px ></th><th class="mo_guide_text-center" style = "border: none;"><b>Drupal OAuth Server( OAuth Provider) - Single Sign On (SSO)</b></th></tr>
                    </thead>
                </table>
                <div>
                    <p>OAuth Server allows Single Sign-On to your client apps with Drupal. It allows you to use Drupal as your OAuth Server and access OAuth API’s</p>
                    <br>
                </div>
                <table>
                    <tr>
                    <a class="btn btn-get-module btn-large" href="https://www.drupal.org/project/oauth_server_sso" target ="_blank">
                        Download module
                    </a>
                    <a class="btn btn-know-more btn-large" href="https://plugins.miniorange.com/drupal-oauth-server" target ="_blank">
                        Know more
                    </a>
                    </tr>
                </table>
            </div>',
        );
    }

    /*=======Show attribute list coming from server on Attribute Mapping tab =======*/

    public static function show_attr_list_from_idp(&$form, $form_state)
    {
        global $base_url;
        $server_attrs = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_attr_list_from_server');

        if(empty($server_attrs)){
            Utilities::spConfigGuide($form, $form_state);
            return;
        }

        $form['miniorange_idp_guide_link'] = array(
            '#markup' => '<div class="mo_saml_table_layout mo_saml_container_2" id="mo_oauth_guide_vt">',
        );

        $form['miniorange_saml_attr_header'] = array(
            '#markup' => '<div class="mo_attr_table">Attributes received from the OAuth Server:</div><br>'
        );

        $icnt =  count($server_attrs);
        if($icnt >= 8){
            $scrollkit = 'scrollit';
        }else{
            $scrollkit = '';
        }

        $form['mo_saml_attrs_list_idp'] = array(
            '#markup' => '<div class="table-responsive mo_guide_text-center" style="font-family: sans-serif;font-size: 12px;"><div class='.$scrollkit.'>
                <table class="mo_guide_table mo_guide_table-striped mo_guide_table-bordered" style="border: 1px solid #ddd;max-width: 100%;border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th class="mo_guide_text-center mo_td_values">ATTRIBUTE NAME</th>
                            <th class="mo_guide_text-center mo_td_values">ATTRIBUTE VALUE</th>                         
                        </tr>
                    </thead>',
        );

        $someattrs = '';
        $attrroles = '';

        if(isset($server_attrs) && !empty($server_attrs))
        {
            foreach ($server_attrs as $attr_name => $attr_values)
            {
                $someattrs .= '<tr><td>' . $attr_name . '</td><td>' ;
                if( $attr_name == 'roles' && is_array($server_attrs['roles']))
                {
                    foreach ($attr_values as $attr_roles => $role)
                    {
                        $attrroles .=  $role . ' | ';
                    }
                    $someattrs .=  $attrroles.'</td></tr>';
                }
                else
                {
                    $someattrs .= $attr_values . '</td></tr>';
                }
            }
        }

        $form['miniorange_saml_guide_table_list'] = array(
            '#markup' => '<tbody style="font-weight:bold;font-size: 12px;color:gray;">'.$someattrs.'</tbody></table></div>',
        );

        $form['miniorange_break'] = array(
            '#markup' => '<br>',
        );

        $form['miniorange_saml_clear_attr_list'] = array(
            '#type' => 'submit',
            '#value' => t('Clear Attribute List'),
            '#submit' => array('::clear_attr_list'),
            '#id' => 'button_config_center',
        );

        $form['miniorange_saml_guide_clear_list_note'] = array(
            '#markup' => '<br><div style="font-size: 13px;"><b>NOTE : </b>Please clear this list after configuring the plugin to hide your confidential attributes.<br>
                            Click on <b>Test configuration</b> in <b>CONFIGURE OAUTH</b> tab to populate the list again.</div>',
        );

        $form['miniorange_saml_guide_table_end'] = array(
            '#markup' => '</div>',
        );
    }
}
?>