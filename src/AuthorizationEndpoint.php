<?php
namespace Drupal\oauth_login_oauth2;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Component\Utility;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Extension;
use Drupal\Component\Utility\Html;
use Drupal\oauth_login_oauth2\handler;
    class AuthorizationEndpoint{
        public static function mo_oauth_client_initiateLogin() {
            global $base_url;
            $app_name = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_app_name');
            $client_id = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_id');
            $client_secret = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_client_secret');
            $scope = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_scope');
            $authorizationUrl =\Drupal::config('oauth_login_oauth2.settings')->get('miniorange_auth_client_authorize_endpoint');
            if(!empty(\Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url')))
                $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_base_url');
            else
                $baseUrlValue = $base_url;
            $callback_uri = $baseUrlValue."/mo_login";
            $state = base64_encode($app_name);
            if (strpos($authorizationUrl,'?') !== false)
                $authorizationUrl =$authorizationUrl. "&client_id=".$client_id."&scope=".$scope."&redirect_uri=".$callback_uri."&response_type=code&state=".$state;
            else 
                $authorizationUrl =$authorizationUrl. "?client_id=".$client_id."&scope=".$scope."&redirect_uri=".$callback_uri."&response_type=code&state=".$state;
            if (session_status() == PHP_SESSION_NONE)
                session_start();
            $_SESSION['oauth2state'] = $state;
            $_SESSION['appname'] = $app_name;
            $response = new RedirectResponse($authorizationUrl);
            $response->send();
            return new Response();
        }
    }
?>