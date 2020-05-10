<?php

namespace Drupal\oauth2_login;
use Drupal\oauth2_login\Controller\miniorange_oauth_clientController;
/**
 * @file
 * This class represents support information for customer.
 */
/**
 * @file
 * Contains miniOrange Support class.
 */
class MiniorangeOAuthClientSupport {
  public $email;
  public $phone;
  public $query;
  public $plan;

  public function __construct($email, $phone, $query, $plan = '') {
    $this->email = $email;
    $this->phone = $phone;
    $this->query = $query;
    $this->plan = $plan;

  }

  /**
	 * Send support query.
	 */
    public function sendSupportQuery()
    {
        if ($this->plan == 'demo') {
            $url = MiniorangeOAuthClientConstants::BASE_URL . '/moas/api/notify/send';
            $ch = \curl_init($url);

            $subject = "Demo request for Drupal-8 OAuth Client Module";
            $this->query = 'Demo required for - ' . $this->query;

            $customerKey = \Drupal::config('oauth2_login.settings')->get('miniorange_oauth_client_customer_id');
            $apikey = \Drupal::config('oauth2_login.settings')->get('miniorange_oauth_client_customer_api_key');
            if ($customerKey == '') {
                $customerKey = "16555";
                $apikey = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
            }

            $controller = new miniorange_oauth_clientController();
            $currentTimeInMillis = $controller->get_oauth_timestamp();
            $stringToHash = $customerKey . $currentTimeInMillis . $apikey;
            $hashValue = hash("sha512", $stringToHash);
            $customerKeyHeader = "Customer-Key: " . $customerKey;
            $timestampHeader = "Timestamp: " . $currentTimeInMillis;
            $authorizationHeader = "Authorization: " . $hashValue;

            $content = '<div >Hello, <br><br>Company :<a href="' . $_SERVER['SERVER_NAME'] . '" target="_blank" >' . $_SERVER['SERVER_NAME'] . '</a><br><br>Phone Number:' . $this->phone . '<br><br>Email:<a href="mailto:' . $this->email . '" target="_blank">' . $this->email . '</a><br><br>Query:[DRUPAL 8 OAuth Login Free] ' . $this->query . '</div>';

            $fields = array(
                'customerKey' => $customerKey,
                'sendEmail' => true,
                'email' => array(
                    'customerKey' => $customerKey,
                    'fromEmail' => $this->email,
                    'fromName' => 'miniOrange',
                    'toEmail' => 'drupalsupport@xecurify.com',
                    'toName' => 'drupalsupport@xecurify.com',
                    'subject' => $subject,
                    'content' => $content
                ),
            );
            \curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $customerKeyHeader,
                $timestampHeader, $authorizationHeader));
        } else {

            $this->query = '[Drupal 8 OAuth Login Free Module] ' . $this->query;
            $fields = array(
                'company' => $_SERVER['SERVER_NAME'],
                'email' => $this->email,
                'phone' => $this->phone,
                'ccEmail' => 'drupalsupport@xecurify.com',
                'query' => $this->query,
            );

            $url = MiniorangeOAuthClientConstants::BASE_URL . '/moas/rest/customer/contact-us';
            $ch = \curl_init($url);
            \curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'charset: UTF-8',
                'Authorization: Basic'
            ));
        }

        $field_string = json_encode($fields);
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        \curl_setopt($ch, CURLOPT_ENCODING, "");
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        \curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        \curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        \curl_setopt($ch, CURLOPT_POST, TRUE);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
        $content = \curl_exec($ch);
        if (\curl_errno($ch)) {
            $error = array(
                '%method' => 'sendSupportQuery',
                '%file' => 'miniorange_oauth_client_support.php',
                '%error' => curl_error($ch),
            );
            \Drupal::logger('oauth2_login')->notice($error);
            return FALSE;
        }
        \curl_close($ch);
        return TRUE;
    }
}