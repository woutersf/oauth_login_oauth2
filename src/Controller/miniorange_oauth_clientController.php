<?php
 /**
 * @file
 * Contains \Drupal\miniorange_oauth_client\Controller\DefaultController.
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
class miniorange_oauth_clientController extends ControllerBase {

public function miniorange_oauth_client_feedback_func()
{
    $_SESSION['mo_other']="False";
    global $base_url;
			$reason=$_POST['deactivate_plugin'];
			$q_feedback=$_POST['query_feedback'];
			$message='Reason: '.$reason.'<br>Feedback: '.$q_feedback;
			$url = 'https://login.xecurify.com/moas/api/notify/send';
			$ch = curl_init($url);
			$email =\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_email');
            if(empty($email))
                $email = $_POST['miniorange_feedback_email'];
            $phone = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_phone');
			$customerKey= \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_id');
			$apikey = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_api_key');
			if($customerKey==''){
			$customerKey="16555";
			$apikey="fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
			}
			$currentTimeInMillis = self::get_oauth_timestamp();
			$stringToHash 		= $customerKey .  $currentTimeInMillis . $apikey;
			$hashValue 			= hash("sha512", $stringToHash);
			$customerKeyHeader 	= "Customer-Key: " . $customerKey;
			$timestampHeader 	= "Timestamp: " .  $currentTimeInMillis;
			$authorizationHeader= "Authorization: " . $hashValue;
			$fromEmail 			= $email;
			$subject            = "Drupal 8 OAuth Client Module Feedback";
			$query        = '[Drupal 8 OAuth Client]: ' . $message;
			$content='<div >Hello, <br><br>Company :<a href="'.$_SERVER['SERVER_NAME'].'" target="_blank" >'.$_SERVER['SERVER_NAME'].'</a><br><br>Phone Number :'.$phone.'<br><br>Email :<a href="mailto:'.$fromEmail.'" target="_blank">'.$fromEmail.'</a><br><br>Query :'.$query.'</div>';
			$fields = array(
				'customerKey'	=> $customerKey,
				'sendEmail' 	=> true,
				'email' 		=> array(
				'customerKey' 	=> $customerKey,
				'fromEmail' 	=> $fromEmail,
				'fromName' 		=> 'miniOrange',
				'toEmail' 		=> 'drupalsupport@xecurify.com',
				'toName' 		=> 'drupalsupport@xecurify.com',
				'subject' 		=> $subject,
				'content' 		=> $content
				),
			);
			$field_string = json_encode($fields);
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $ch, CURLOPT_ENCODING, "" );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
			curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $customerKeyHeader,
				$timestampHeader, $authorizationHeader));
			curl_setopt( $ch, CURLOPT_POST, true);
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $field_string);
			$content = curl_exec($ch);
			if(curl_errno($ch)){
				return json_encode(array("status"=>'ERROR','statusMessage'=>curl_error($ch)));
			}
            curl_close($ch);
            \Drupal::service('module_installer')->uninstall(['oauth_login_oauth2']);
            if(!empty(\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url')))
                $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url');
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
	public function get_oauth_timestamp() {
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
    public function miniorange_oauth_client_mo_login()
    {
        $code = $_GET['code'];
        $code = Html::escape($code);
        $state = $_GET['state'];
        $state = Html::escape($state);
        if( isset( $code) && isset($state ) )
        {
            if(session_id() == '' || !isset($_SESSION))
				session_start();
            if (!isset($code))
            {
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
        $app = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_appval');
        $name_attr = "";
        $email_attr = "";
        $name = "";
        $email ="";
		if(isset($app['miniorange_oauth_client_email_attr'])){
		      $email_attr = trim($app['miniorange_oauth_client_email_attr']);
        }
		if(isset($app['miniorange_oauth_client_name_attr']))
        {
            $name_attr = trim($app['miniorange_oauth_client_name_attr']);
        }

        $accessToken = self::getAccessToken($app['access_token_ep'], 'authorization_code',
        $app['client_id'], $app['client_secret'], $code, $app['callback_uri']);

        if(!$accessToken)
        {
            print_r('Invalid token received.');
            exit;

        }

        $resourceownerdetailsurl = $app['user_info_ep'];
        if (substr($resourceownerdetailsurl, -1) == "=") {
            $resourceownerdetailsurl .= $accessToken;
        }
        $resourceOwner = self::getResourceOwner($resourceownerdetailsurl, $accessToken);

        /*
        *   Test Configuration
        */
        if (isset($_COOKIE['Drupal_visitor_mo_oauth_test']) && ($_COOKIE['Drupal_visitor_mo_oauth_test'] == true))
        {
            $_COOKIE['Drupal_visitor_mo_oauth_test'] = 0;
            $module_path = drupal_get_path('module', 'miniorange_oauth_client');
            $username = isset($resourceOwner['email']) ? $resourceOwner['email']:'User';
            \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_attr_list_from_server',$resourceOwner)->save();

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

        if(empty($email))
        {
            echo "Email address not received. Check your <b>Attribute Mapping<b> configuration.";exit;
        }

        //Validates the email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format of the received value"; exit;
        }

        if(empty($name))
        {
            $name = $email;
        }
        $account ='';
        if(!empty($email))
            $account = user_load_by_mail($email);
        if($account == null)
        {
            if(!empty($name) && isset($name))
                $account = user_load_by_name($name);
        }

        global $base_url;
	    global $user;

        $mo_count = "";
        $mo_count = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_free_users');

        /*************================================== Create user if not already present. ======================================*************/
        if (!isset($account->uid)) {
            if ($mo_count <= 10) {
                $mo_count = $mo_count + 1;
                \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_free_users', $mo_count)->save();
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
        if(!empty(\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url')))
            $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url');
        else
            $baseUrlValue = $base_url;
        $edit['redirect'] = $baseUrlValue;
		user_login_finalize($account);
        $response = new RedirectResponse($edit['redirect']);
        $response->send();
        return new Response();
    }

    /**
     * This function gets the access token from the server
     */
    public function getAccessToken($tokenendpoint, $grant_type, $clientid, $clientsecret, $code, $redirect_url) {
        $ch = curl_init($tokenendpoint);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Authorization: Basic '.base64_encode($clientid.":".$clientsecret),
            'Accept: application/json'
        ));

        curl_setopt( $ch, CURLOPT_POSTFIELDS, 'redirect_uri='.urlencode($redirect_url).'&grant_type='.$grant_type.'&client_id='.$clientid.'&client_secret='.$clientsecret.'&code='.$code);
        $content = curl_exec($ch);

        if(curl_error($ch)){
            echo "<b>Response : </b><br>";print_r($content);echo "<br><br>";
            exit( curl_error($ch) );
        }

        if(!is_array(json_decode($content, true))){
            echo "<b>Response : </b><br>";print_r($content);echo "<br><br>";
            exit("Invalid response received.");
        }

        $content = json_decode($content,true);

        if(isset($content["error_description"])){
            exit($content["error_description"]);
        } else if(isset($content["error"])){
            exit($content["error"]);
        } else if(isset($content["access_token"])) {
            $access_token = $content["access_token"];
        } else {
            exit('Invalid response received from OAuth Provider. Contact your administrator for more details.');
        }

        return $access_token;
    }

    public function getResourceOwner($resourceownerdetailsurl, $access_token){

        $ch = curl_init($resourceownerdetailsurl);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt( $ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$access_token
        ));

        $content = curl_exec($ch);
        if(curl_error($ch)){
            exit( curl_error($ch) );
        }

        if(!is_array(json_decode($content, true))) {
            exit("Invalid response received.");
        }

        $content = json_decode($content,true);
        if(isset($content["error_description"])){
            if(is_array($content["error_description"]))
                print_r($content["error_description"]);
            else
                echo $content["error_description"];
            exit;
        } else if(isset($content["error"])){
            if(is_array($content["error"]))
                print_r($content["error"]);
            else
                echo $content["error"];
            exit;
        }

        return $content;

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

    public function mo_oauth_client_initiateLogin() {
        global $base_url;
        $app_name = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_app_name');
        $client_id = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_id');
        $client_secret = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_secret');
        $scope = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_scope');
        $authorizationUrl =\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_authorize_endpoint');
        $callback_uri = $base_url."/mo_login";
        $state = base64_encode($app_name);
        if (strpos($authorizationUrl,'?') !== false) {
            $authorizationUrl =$authorizationUrl. "&client_id=".$client_id."&scope=".$scope."&redirect_uri=".$callback_uri."&response_type=code&state=".$state;
        } else {
            $authorizationUrl =$authorizationUrl. "?client_id=".$client_id."&scope=".$scope."&redirect_uri=".$callback_uri."&response_type=code&state=".$state;
        }
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['oauth2state'] = $state;
        $_SESSION['appname'] = $app_name;
        //header('Location: ' . $authorizationUrl);
        $response = new RedirectResponse($authorizationUrl);
        $response->send();
        return new Response();
    }

     public function test_mo_config()
     {
         user_cookie_save(array("mo_oauth_test" => true));
         self::mo_oauth_client_initiateLogin();
         return new Response();
     }

    public function reset_mo_config()
    {
        global $base_url;
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_oauth_client_app')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_oauth_client_appval')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_client_id')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_app_name')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_display_name')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_client_secret')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_scope')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_authorize_endpoint')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_access_token_ep')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_oauth_client_email_attr_val')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_oauth_client_name_attr_val')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_user_info_ep')->save();
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_stat')->save();
        drupal_set_message("Your Configurations have been deleted successfully");

        if(!empty(\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url')))
            $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url');
        else
            $baseUrlValue = $base_url;
        $response = new RedirectResponse($baseUrlValue."/admin/config/people/oauth_login_oauth2/config_clc");
        $response->send();
    }

    public function miniorange_oauth_client_mologin()
    {
        user_cookie_save(array("mo_oauth_test" => false));
        self::mo_oauth_client_initiateLogin();
        return new Response();
    }
}