<?php
namespace Drupal\oauth_login_oauth2;
use Drupal\oauth_login_oauth2\Controller\oauth_login_oauth2Controller;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Component\Utility;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class handler{
    static function generateRandom($length=30) {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';

        for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
    }

    static function oauth_login_oauth2_validate_code($code, $request_code,$request_time)
    {
      $current_time = time();
      if($current_time - $request_time >=400){
        echo "Your authentication code has expired. Please try again.";exit;
      }
      if($code == $request_code){
            \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_code','')->save();
        }
        else{
            print_r("Incorrect Code");exit;
        }
    }
    /**
     * Sending feedback email to drupalsupport
     */
    public static function sendFeedbackEmail()
    {
        $_SESSION['mo_other']="False";
        global $base_url;
      $reason=$_POST['deactivate_plugin'];
      $q_feedback=$_POST['query_feedback'];
      $message='Reason: '.$reason.'<br>Feedback: '.$q_feedback;
      $url = 'https://login.xecurify.com/moas/api/notify/send';
      $ch = curl_init($url);
      $email =\Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_customer_admin_email');
        if(empty($email))
            $email = $_POST['miniorange_feedback_email'];
        $phone = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_customer_admin_phone');
      $customerKey= \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_customer_id');
      $apikey = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_customer_api_key');
      if($customerKey==''){
        $customerKey="16555";
        $apikey="fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
      }

      $controller = new oauth_login_oauth2Controller();
      $currentTimeInMillis = $controller->get_oauth_timestamp();
      $stringToHash 		= $customerKey .  $currentTimeInMillis . $apikey;
      $hashValue 			= hash("sha512", $stringToHash);
      $customerKeyHeader 	= "Customer-Key: " . $customerKey;
      $timestampHeader 	= "Timestamp: " .  $currentTimeInMillis;
      $authorizationHeader= "Authorization: " . $hashValue;
      $fromEmail 			= $email;
      $subject            = "Drupal 8 OAuth Client Login Module Feedback";
      $query        = '[Drupal 8 OAuth Client Login]: ' . $message;
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
        return;
    }
    static function ValidateAccessToken($accessToken, $request_time)
    {
      $current_time = time();

      if($current_time - $request_time >=900)
      {
        echo "Your access token has expired. Please try again.";exit;
      }

    }
    static function oauth_login_oauth2_validate_clientSecret($client_secret)
    {
      $secret_stored = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_client_secret');
      if($secret_stored != ''){
        if($client_secret != $secret_stored){
          print_r('Client Secret mismatch');exit;
        }
      }
      else{
        print_r('Client Secret is not configured');exit;
      }
    }
    static function oauth_login_oauth2_validate_grant($grant_type)
    {
        if($grant_type != "authorization_code"){
            print_r("Only Authorization Code grant type supported");exit;
        }
    }
    static function oauth_login_oauth2_validate_clientId($client_id)
    {
      $id_stored = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_client_id');
      if($id_stored != ''){
        if($client_id != $id_stored){
          print_r('Client ID mismatch');exit;
        }
      }
      else{
        print_r('Client ID is not configured');exit;
      }
    }
    static function oauth_login_oauth2_validate_redirectUrl($redirect_uri)
    {
      $redirect_url_stored = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_redirect_url');
      if($redirect_url_stored != ''){
        if($redirect_uri != $redirect_url_stored){
          print_r('Redirect URL mismatch');exit;
        }
      }
      else{
        print_r('Redirect URL is not configured');exit;
      }
    }


    /**
     * Reset Saved Configuration
     */
    public static function reset_mo_config()
    {
      global $base_url;
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('oauth_login_oauth2_app')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('oauth_login_oauth2_appval')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_client_id')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_app_name')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_display_name')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_client_secret')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_scope')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_authorize_endpoint')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_access_token_ep')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('oauth_login_oauth2_email_attr_val')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('oauth_login_oauth2_name_attr_val')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_user_info_ep')->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_auth_client_stat')->save();
      \Drupal::messenger()->addMessage("Your Configurations have been deleted successfully");

      if(!empty(\Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url')))
          $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url');
      else
          $baseUrlValue = $base_url;
      $response = new RedirectResponse($baseUrlValue."/admin/config/people/oauth_login_oauth2/config_clc");
      $response->send();
      exit;
    }
}
?>
