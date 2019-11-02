<?php

/**
 * @file
 * Contains \Drupal\oauth_login_oauth2\Form\MiniorangeGeneralSettings.
 */

namespace Drupal\oauth_login_oauth2\Form;
use Drupal\oauth_login_oauth2\mo_saml_visualTour;
use Drupal\oauth_login_oauth2\Utilities;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_login_oauth2\MiniorangeOAuthClientSupport;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MiniorangeMapping extends FormBase{
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'miniorange_mapping';
  }
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state){
    global $base_url;
    $moTour = mo_saml_visualTour::genArray();
    $form['tourArray'] = array(
        '#type' => 'hidden',
        '#value' => $moTour,
    );
    $form['markup_library'] = array(
      '#attached' => array(
          'library' => array(
              "oauth_login_oauth2/oauth_login_oauth2.admin",
              "oauth_login_oauth2/oauth_login_oauth2.style_settings",
              "oauth_login_oauth2/oauth_login_oauth2.Vtour",
              "oauth_login_oauth2/mo-card",
          )
      ),
    );
    $form['header_top_style_1'] = array('#markup' => '<div class="mo_oauth_table_layout_1">');
    $form['markup_top'] = array(
        '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
    );

    $form['markup_top_vt_start'] = array(
        '#markup' => '<h3>ATTRIBUTE MAPPING&nbsp;&nbsp; <a id="Restart_moTour" class="mo_oauth_btn mo_oauth_btn-primary-color mo_oauth_btn-large">Take a Tour</a></h3><hr><br>',
    );
    $email_attr = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_email_attr_val');
    $name_attr =\Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_name_attr_val');
    $form['oauth_login_oauth2_email_attr'] = array(
      '#type' => 'textfield',
      '#title' => t('Email Attribute: '),
      '#default_value' => $email_attr,
      '#description' => 'This field is mandatory for login',
      '#attributes' => array('id'=>'mo_oauth_vt_attrn','style' => 'width:73%;','placeholder' => 'Enter Email Attribute'),
    );
    $form['oauth_login_oauth2_name_attr'] = array(
      '#type' => 'textfield',
      '#title' => t('Username Attribute: '),
      '#description' => 'This field is mandatory for login',
      '#default_value' => $name_attr,
      '#attributes' => array('id'=>'mo_oauth_vt_attre','style' => 'width:73%;','placeholder' => 'Enter Name Attribute'),
    );
    $form['oauth_login_oauth2_attr_setup_button_2'] = array(
      '#type' => 'submit',
      '#id' => 'button_config_left',
      '#value' => t('Save Configuration'),
      '#submit' => array('::oauth_login_oauth2_attr_setup_submit'),
    );
    $form['markup_cam'] = array(
      '#markup' => '<br><h3>CUSTOM ATTRIBUTE MAPPING <b><a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/Licensing"> [PREMIUM]</a></b></h3><hr><br>
                    <div class="mo_saml_highlight_background_note_1">Add the Drupal field attributes in the Attribute Name textfield and add the OAuth Server attributes that you need to map with the drupal attributes in the OAuth Server Attribute Name textfield. 
                      Drupal Field Attributes will be of type text. Add the machine name of the attribute in the Drupal Attribute textfield. 
                    <b>For example: </b>If the attribute name in the drupal is name then its machine name will be field_name.</div><br>',
    );
    $form['miniorange_oauth_attr5_name'] = array(
      '#type' => 'textfield',
      '#id' => 'text_field',
      '#title' => t('Attribute Name 1'),
      '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter Attribute Name'),
      '#required' => FALSE,
      '#disabled' => TRUE,
    );

    $form['miniorange_oauth_server_attr5_name'] = array(
      '#type' => 'textfield',
      '#id' => 'text_field',
      '#title' => t('OAuth Server Attribute Name 1'),
      '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter OAuth Server Attribute Name'),
      '#required' => FALSE,
      '#disabled' => TRUE,
    );
    $form['miniorange_oauth_attr2_name'] = array(
      '#type' => 'textfield',
      '#id' => 'text_field',
      '#title' => t('Attribute Name 2'),
      '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter Attribute Name'),
      '#required' => FALSE,
      '#disabled' => TRUE,
    );
    $form['miniorange_oauth_server_attr2_name'] = array(
      '#type' => 'textfield',
      '#id' => 'text_field',
      '#title' => t('OAuth Server Attribute Name 2'),
      '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter OAuth Server Attribute Name'),
      '#required' => FALSE,
      '#disabled' => TRUE,
    );
    $form['miniorange_oauth_attr3_name'] = array(
      '#type' => 'textfield',
      '#id' => 'text_field',
      '#title' => t('Attribute Name 3'),
      '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter Attribute Name'),
      '#required' => FALSE,
      '#disabled' => TRUE,
    );
    $form['miniorange_oauth_attr3_name'] = array(
      '#type' => 'textfield',
      '#id' => 'text_field',
      '#title' => t('OAuth Server Attribute Name 3'),
      '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter OAuth Server Attribute Name'),
      '#required' => FALSE,
      '#disabled' => TRUE,
    );
    $form['miniorange_oauth_attr4_name'] = array(
      '#type' => 'textfield',
      '#id' => 'text_field',
      '#title' => t('Attribute Name 4'),
      '#required' => FALSE,
      '#disabled' => TRUE,
      '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter Attribute Name'),
    );
    $form['miniorange_oauth_server_attr4_name'] = array(
      '#type' => 'textfield',
      '#id' => 'text_field',
      '#title' => t('OAuth Server Attribute Name 4'),
      '#required' => FALSE,
      '#disabled' => TRUE,
      '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter OAuth Server Attribute Name'),
    );
    $form['markup_role'] = array(
      '#markup' => '<br><h3>Custom Role Mapping</h3><hr><br>',
    );
    $form['miniorange_disable_attribute'] = array(
      '#type' => 'checkbox',
      '#title' => t('Do not update existing user&#39;s role. <b><a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/Licensing"> [PREMIUM]</a></b>'),
    '#disabled' => TRUE,
    );
    $form['miniorange_oauth_disable_role_update'] = array(
      '#type' => 'checkbox',
      '#title' => t('Check this option if you do not want to update user role if roles not mapped. <b><a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/Licensing"> [PREMIUM]</a></b>'),
    '#disabled' => TRUE,
    );
    $form['miniorange_oauth_disable_autocreate_users'] = array(
      '#type' => 'checkbox',
      '#title' => t('Check this option if you want to disable <b>auto creation</b> of users if user does not exist. <b><a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/Licensing"> [PREMIUM]</a></b>'),
      '#disabled' => TRUE,
    );
    $form['markup_role_signin'] = array(
      '#markup' => '<h3>Custom Login/Logout (Optional). <b><a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/Licensing"> [PREMIUM]</a></b></h3>'
    );
    $form['oauth_login_oauth2_login_url'] = array(
        '#type' => 'textfield',
        '#id' => 'text_field',
        '#required' => FALSE,
        '#disabled' => TRUE,
        '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter Login URL'),
    );
    $form['oauth_login_oauth2_logout_url'] = array(
        '#type' => 'textfield',
        '#id' => 'text_field',
        '#required' => FALSE,
        '#disabled' => TRUE,
        '#attributes' => array('style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter Logout URL'),
    );
    $form['markup_role_break'] = array(
        '#markup' => '<br>',
    );
    $form['oauth_login_oauth2_attr_setup_button'] = array(
      '#type' => 'submit',
      '#id' => 'button_config_center',
      '#value' => t('Save Configuration'),
      '#submit' => array('::oauth_login_oauth2_attr_setup_submit'),
    );
    $form['mo_header_style_end'] = array('#markup' => '</div>');
    Utilities::show_attr_list_from_idp($form, $form_state);
    $form['miniorange_idp_guide_link_end'] = array(
        '#markup' => '</div>',
    );
    Utilities::AddSupportButton($form, $form_state);
    return $form;
  }

  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
  }

  function oauth_login_oauth2_attr_setup_submit($form, $form_state){
      $email_attr = trim($form['oauth_login_oauth2_email_attr']['#value']);
      $name_attr = trim($form['oauth_login_oauth2_name_attr']['#value']);
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_email_attr_val', $email_attr)->save();
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_name_attr_val', $name_attr)->save();
      $app_values = \Drupal::config('oauth_login_oauth2.settings')->get('oauth_login_oauth2_appval');
      $app_values['oauth_login_oauth2_email_attr'] = $email_attr;
      $app_values['oauth_login_oauth2_name_attr'] = $name_attr;
      \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('oauth_login_oauth2_appval',$app_values)->save();
      \Drupal::messenger()->addMessage(t('Attribute Mapping saved successfully.'));
  }

    /**
     * Send support query.
     */
    public function saved_support(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
        $email = trim($form['oauth_login_oauth2_email_address']['#value']);
        $phone = trim($form['oauth_login_oauth2_phone_number']['#value']);
        $query = trim($form['oauth_login_oauth2_support_query']['#value']);
        Utilities::send_support_query($email, $phone, $query);
    }

    function clear_attr_list(&$form,$form_state){
        \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('oauth_login_oauth2_attr_list_from_server')->save();
        Utilities::show_attr_list_from_idp($form, $form_state);
    }

    public function rfd(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        global $base_url;
        $response = new RedirectResponse($base_url."/admin/config/people/oauth_login_oauth2/request_for_demo");
        $response->send();
    }
}
