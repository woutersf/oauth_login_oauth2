<?php
/**
 * @file
 * Contains Attribute for miniOrange OAuth Client Module.
 */

 /**
 * Showing Settings form.
 */
namespace Drupal\oauth_login_oauth2\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Render;
 class MiniorangeFAQ extends FormBase {

  public function getFormId() {
    return 'oauth_login_oauth2_faq';
  }


 public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
 {
  $html = "<div><object type='text/html' data='https://faq.miniorange.com/kb/oauth-openid-connect/' width='100%' height='600px' ></object></div>";
  $form['text']['#markup'] = t($html);
  return $form;
}
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
  {

  }

}