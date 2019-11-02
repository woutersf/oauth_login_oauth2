<?php
 /**
 * @file
 * Contains \Drupal\oauth_login_oauth2\Controller\DefaultController.
 */
namespace Drupal\oauth_login_oauth2\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Component\Utility;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Extension;
use Drupal\Component\Utility\Html;
use Drupal\oauth_login_oauth2\handler;
use Drupal\oauth_login_oauth2\AuthorizationEndpoint;
use Drupal\oauth_login_oauth2\AccessToken;
use Drupal\oauth_login_oauth2\UserResource;

class oauth_login_oauth2Controller extends ControllerBase {
    //handles the feedback flow of the module
    public function oauth_login_oauth2_feedback_func(){
        global $base_url;
        handler::sendFeedbackEmail();
        /**
         * Uninstalling the OAuth client login module after sending the feedback email
         */
        \Drupal::service('module_installer')->uninstall(['oauth_login_oauth2']);
        if(!empty(\Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url')))
            $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url');
        else
            $baseUrlValue = $base_url;
        $uninstall_redirect = $baseUrlValue.'/admin/modules';
        $response = new RedirectResponse($uninstall_redirect);
        $response->send();
        return new Response();
    }

    /**
     * This function is used to get the timestamp value
     */
    public static function get_oauth_timestamp() {
        $url = 'https://login.xecurify.com/moas/rest/mobile/get-timestamp';
        $ch  = curl_init( $url );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false ); // required for https urls
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt( $ch, CURLOPT_POST, true );
        $content = curl_exec( $ch );
        if ( curl_errno( $ch ) ) {
            echo 'Error in sending curl Request';
            exit ();
        }
        curl_close( $ch );
        if(empty( $content )){
            $currentTimeInMillis = round( microtime( true ) * 1000 );
            $currentTimeInMillis = number_format( $currentTimeInMillis, 0, '', '' );
        }
        return empty( $content ) ? $currentTimeInMillis : $content;
    }

    public function oauth_login_oauth2_mo_login()
    {
        $code = isset($_GET['code']) ? $_GET['code'] : '';
        $code = Html::escape($code);
        $state = isset($_GET['state']) ? $_GET['state'] : '';
        $state = Html::escape($state);
        if( isset( $code) && isset($state ) ){
            if(session_id() == '' || !isset($_SESSION))
				session_start();
            if (!isset($code)){
                if(isset($_GET['error_description']))
                    exit($_GET['error_description']);
			    else if(isset($_GET['error']))
			        exit($_GET['error']);
			    exit('Invalid response');
            }
            else {
                $currentappname = "";
                if (isset($_SESSION['appname']) && !empty($_SESSION['appname']))
                    $currentappname = $_SESSION['appname'];
                else if (isset($state) && !empty($state)) {
                    $currentappname = base64_decode($state);
                }
                if (empty($currentappname)) {
                    exit('No request found for this application.');
                }
            }
        }
        // Getting Access Token
        $app = array();
        $app = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_appval');
        $name_attr = "";
        $email_attr = "";
        $name = "";
        $email ="";
		if(isset($app['oauth_login_oauth2_email_attr'])){
		    $email_attr = trim($app['oauth_login_oauth2_email_attr']);
        }
		if(isset($app['oauth_login_oauth2_name_attr'])){
            $name_attr = trim($app['oauth_login_oauth2_name_attr']);
        }
        $accessToken = AccessToken::getAccessToken($app['access_token_ep'], 'authorization_code',
        $app['client_id'], $app['client_secret'], $code, $app['callback_uri']);
        if(!$accessToken){
            print_r('Invalid token received.');
            exit;
        }
        $resourceownerdetailsurl = $app['user_info_ep'];
        if (substr($resourceownerdetailsurl, -1) == "=") {
            $resourceownerdetailsurl .= $accessToken;
        }
        $resourceOwner = UserResource::getResourceOwner($resourceownerdetailsurl, $accessToken);
        /*
        *   Test Configuration
        */
        if (isset($_COOKIE['Drupal_visitor_mo_oauth_test']) && ($_COOKIE['Drupal_visitor_mo_oauth_test'] == true)){
            $_COOKIE['Drupal_visitor_mo_oauth_test'] = 0;
            $module_path = drupal_get_path('module', 'oauth_login_oauth2');
            $username = isset($resourceOwner['email']) ? $resourceOwner['email']:'User';
            \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_attr_list_from_server',$resourceOwner)->save();
            echo '<div style="font-family:Calibri;padding:0 3%;">';
            echo '<div style="color: #3c763d;background-color: #dff0d8; padding:2%;margin-bottom:20px;text-align:center; border:1px solid #AEDB9A; 
                        font-size:15pt;">
                        TEST SUCCESSFUL
                      </div>
                      <div style="display:block;text-align:center;margin-bottom:4%;">
                        <img style="width:15%;"src="'. $module_path . '/includes/images/green_check.png">
                      </div>';
            echo '<span style="font-size:13pt;"><b>Hello</b>, '.$username.'</span><br/>
                      <p style="font-weight:bold;font-size:13pt;margin-left:1%;">ATTRIBUTES RECEIVED:</p>
                      <table style="border-collapse:collapse;border-spacing:0; display:table;width:100%; font-size:13pt;background-color:#EDEDED;">
                          <tr style="text-align:center;">
                              <td style="font-weight:bold;border:2px solid #949090;padding:2%;width: fit-content;">ATTRIBUTE NAME</td>
                              <td style="font-weight:bold;padding:2%;border:2px solid #949090; word-wrap:break-word;">ATTRIBUTE VALUE</td>
                          </tr>';
            self::testattrmappingconfig("",$resourceOwner);
            echo '</table></div>';
            echo '<div style="margin:3%;display:block;text-align:center;">
                        <input style="padding:1%;width:37%;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;
                            border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;
                            box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Configure Attribute/Role Mapping" 
                        onClick="close_and_redirect();">
                        <input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;
                            border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;
                            box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();">      
                    </div>
                    <script>
                        function close_and_redirect(){
                            window.opener.redirect_to_attribute_mapping();
                            self.close();
                        }
                        function redirect_to_attribute_mapping(){
                            var baseurl = window.location.href.replace("config_clc","mapping");
                            window.location.href= baseurl;
                          }
                    </script>';
                    return new Response();
                    exit();
        }
        if(!empty($email_attr))
            $email = self::getnestedattribute($resourceOwner, $email_attr);          //$resourceOwner[$email_attr];
        if(!empty($name_attr))
            $name = self::getnestedattribute($resourceOwner, $name_attr);          //$resourceOwner[$name_attr];

        /*************==============Attributes not mapped check===============************/
        if(empty($email)){
            echo "Email address not received. Check your <b>Attribute Mapping<b> configuration.";exit;
        }
        //Validates the email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format of the received value"; exit;
        }
        if(empty($name)){
            $name = $email;
        }
        $account ='';
        if(!empty($email))
            $account = user_load_by_mail($email);
        if($account == null){
            if(!empty($name) && isset($name))
                $account = user_load_by_name($name);
        }
        global $base_url;
	    global $user;
        $mo_count = "";
        $mo_count = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_free_users');
        /**
         * Creating a new user in case the user does not exists in the Drupal database
         */
        if (!isset($account->uid)) {
            if ($mo_count <= 10) {
                $mo_count = $mo_count + 1;
                \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_free_users', $mo_count)->save();
                $random_password = user_password(8);
                $new_user = [
                    'name' => $name,
                    'mail' => $email,
                    'pass' => $random_password,
                    'status' => 1,
                ];
                $account = User::create($new_user);
                $account->save();
            } else {
                echo '<br><br><br><br><br><div style="color: #111010;background-color: #fadbdb; padding:2%;margin-bottom:20px;text-align:center;
                        border:1px solid #fadbdb;font-size:15pt;">
                        You can create only 10 new users in this version of the plugin/module.
                        <br>Please upgrade to the enterprise version of the plugin in order to create unlimited new users.</div>';

                return new Response();
            }
        }
        $user = \Drupal\user\Entity\User::load($account->id());
        $edit = array();
        if(!empty(\Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url')))
            $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url');
        else
            $baseUrlValue = $base_url;
        $edit['redirect'] = $baseUrlValue;
		user_login_finalize($account);
        $response = new RedirectResponse($edit['redirect']);
        $response->send();
        return new Response();
    }
    function testattrmappingconfig($nestedprefix, $resourceOwnerDetails){
        foreach($resourceOwnerDetails as $key => $resource){
            if(is_array($resource) || is_object($resource)){
                if(!empty($nestedprefix))
                    $nestedprefix .= ".";
                self::testattrmappingconfig($nestedprefix.$key,$resource);
            } else {
                echo "<tr style='text-align:center;'><td style='font-weight:bold;border:2px solid #949090;padding:2%;'>";
                if(!empty($nestedprefix))
                    echo $nestedprefix.".";
                echo $key."</td><td style='font-weight:bold;padding:2%;border:2px solid #949090; word-wrap:break-word;'>".$resource."</td></tr>";
            }
        }
    }
    /**
     * This function is used to get some specific values from the resource
     */
    function getnestedattribute($resource, $key){
        if(empty($key))
            return "";
        $keys = explode(".",$key);
        if(sizeof($keys)>1){
            $current_key = $keys[0];
            if(isset($resource[$current_key]))
                return self::getnestedattribute($resource[$current_key], str_replace($current_key.".","",$key));
        } else {
            $current_key = $keys[0];
            if(isset($resource[$current_key]))
                return $resource[$current_key];
        }
    }
    /**
     * Handling Test Configuration Flow
     */
    public function test_mo_config(){
         user_cookie_save(array("mo_oauth_test" => true));
         AuthorizationEndpoint::mo_oauth_client_initiateLogin();
         return new Response();
    }
    public function reset_mo_config(){
        handler::reset_mo_config();
    }

    /**
     * Initiating OAuth SSO flow
     */
    public function oauth_login_oauth2_mologin()
    {
        user_cookie_save(array("mo_oauth_test" => false));
        AuthorizationEndpoint::mo_oauth_client_initiateLogin();
        return new Response();
    }
}