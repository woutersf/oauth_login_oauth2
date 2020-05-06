<?php

/**
 * @file
 * Contains \Drupal\miniorange_oauth_client\Form\MiniorangeGeneralSettings.
 */

namespace Drupal\oauth_login_oauth2\Form;

use Drupal\oauth_login_oauth2\mo_saml_visualTour;
use Drupal\oauth_login_oauth2\Utilities;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_login_oauth2\MiniorangeOAuthClientSupport;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MiniorangeMapping extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'miniorange_mapping';
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    global $base_url;

    $form['markup_library'] = [
      '#attached' => [
        'library' => [
          "oauth_login_oauth2/oauth_login_oauth2.admin",
          "oauth_login_oauth2/oauth_login_oauth2.style_settings",
          "oauth_login_oauth2/oauth_login_oauth2.Vtour",
        ],
      ],
    ];
    $form['header_top_style_1'] = ['#markup' => '<div class="mo_oauth_table_layout_1">'];
    $form['markup_top'] = [
      '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container">',
    ];

    $form['markup_top_vt_start'] = [
      '#markup' => '<h3>ATTRIBUTE MAPPING</h3><hr><br>',
    ];
    $email_attr = \Drupal::config('oauth_login_oauth2.settings')
      ->get('miniorange_oauth_client_email_attr_val');
    $name_attr = \Drupal::config('oauth_login_oauth2.settings')
      ->get('miniorange_oauth_client_name_attr_val');
    $form['miniorange_oauth_client_email_attr'] = [
      '#type' => 'textfield',
      '#title' => t('Email Attribute: '),
      '#default_value' => $email_attr,
      '#description' => 'This field is mandatory for login',
      '#required' => TRUE,
      '#attributes' => [
        'id' => 'mo_oauth_vt_attrn',
        'style' => 'width:73%;',
        'placeholder' => 'Enter Email Attribute',
      ],
    ];
    $form['miniorange_oauth_client_name_attr'] = [
      '#type' => 'textfield',
      '#title' => t('Username Attribute: '),
      '#description' => "<b>Note:</b> If this text field is empty, then by default email id will be the user's username",
      '#default_value' => $name_attr,
      '#attributes' => [
        'id' => 'mo_oauth_vt_attre',
        'style' => 'width:73%;',
        'placeholder' => 'Enter Username Attribute',
      ],
    ];
    $form['miniorange_oauth_client_attr_setup_button_2'] = [
      '#type' => 'submit',
      '#id' => 'button_config_center',
      '#value' => t('Save Configuration'),
      '#submit' => ['::miniorange_oauth_client_attr_setup_submit'],
    ];
    $form['markup_cam'] = [
      '#markup' => '<br><h3>CUSTOM ATTRIBUTE MAPPING</h3><hr><br>
                    <div class="mo_saml_highlight_background_note_1">Define your mapping in hook_oauth_login_oauth2_field_mapping(). See the example:<br>function MYMODULE_hook_oauth_login_oauth2_field_mapping(){
  <br>return [\'REMOTE_FIELD\', \'LOCAL FIELD\'];
<br>} </div><br>',
    ];
    $output = '';
    $hook1 = 'oauth_login_oauth2_field_mapping';
    $implementations = \Drupal::moduleHandler()->getImplementations($hook1);
    foreach ($implementations as $implementation) {
      $func = $implementation . '_' . $hook1;
      $output = '<li>' . $func . '</li>';
    }

    $form['markup_hook'] = [
      '#markup' => '<h3>Hooks found.</h3><ul>' . $output . '</ul>',
    ];

    $form['markup_role'] = [
      '#markup' => '<br><h3>Custom Role Mapping</h3><hr><br>',
    ];
    $form['miniorange_disable_attribute'] = [
      '#type' => 'checkbox',
      '#default_value' => \Drupal::config('oauth_login_oauth2.settings')
        ->get('miniorange_disable_attribute'),
      '#title' => t('Do not update existing user&#39;s role.'),
    ];
    $form['miniorange_oauth_disable_role_update'] = [
      '#type' => 'checkbox',
      '#default_value' => \Drupal::config('oauth_login_oauth2.settings')
        ->get('miniorange_oauth_disable_role_update'),
      '#title' => t('Check this option if you do not want to update user role if roles not mapped. '),
    ];
    $form['miniorange_oauth_disable_autocreate_users'] = [
      '#type' => 'checkbox',
      '#default_value' => \Drupal::config('oauth_login_oauth2.settings')
        ->get('miniorange_oauth_disable_autocreate_users'),
      '#title' => t('Check this option if you want to disable <b>auto creation</b> of users if user does not exist. '),
    ];

    $mrole = user_role_names($membersonly = TRUE);
    $drole = array_values($mrole);

    $form['miniorange_oauth_default_mapping'] = [
      '#type' => 'select',
      '#id' => 'miniorange_oauth_client_app',
      '#title' => t('Select default group for the new users'),
      '#options' => $mrole,
      '#default_value' => \Drupal::config('oauth_login_oauth2.settings')
        ->get('miniorange_oauth_default_mapping'),
      '#attributes' => ['style' => 'width:73%;'],
    ];

    foreach ($mrole as $roles) {
      $rolelabel = str_replace(' ', '', $roles);
      $form['miniorange_oauth_role_' . $rolelabel] = [
        '#type' => 'textfield',
        '#title' => t($roles),
        '#default_value' => \Drupal::config('oauth_login_oauth2.settings')
          ->get('miniorange_oauth_role_' . $rolelabel, ''),
        '#attributes' => [
          'style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;',
          'placeholder' => 'Semi-colon(;) separated Group/Role value for ' . $roles,
        ],
      ];
    }

    $form['markup_role_signin'] = [
      '#markup' => '<br><h3>Custom Login/Logout (Optional)</h3><hr>',
    ];
    $form['miniorange_oauth_client_login_url'] = [
      '#type' => 'textfield',
      '#id' => 'text_field2',
      '#default_value' => \Drupal::config('oauth_login_oauth2.settings')
        ->get('miniorange_oauth_client_login_url'),
      '#required' => FALSE,
      '#attributes' => [
        'style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;',
        'placeholder' => 'Enter Login URL',
      ],
    ];
    $form['miniorange_oauth_client_logout_url'] = [
      '#type' => 'textfield',
      '#id' => 'text_field3',
      '#required' => FALSE,
      '#default_value' => \Drupal::config('oauth_login_oauth2.settings')
        ->get('miniorange_oauth_client_logout_url'),
      '#attributes' => [
        'style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;',
        'placeholder' => 'Enter Logout URL',
      ],
    ];
    $form['markup_role_break'] = [
      '#markup' => '<br>',
    ];
    $form['miniorange_oauth_client_attr_setup_button'] = [
      '#type' => 'submit',
      '#id' => 'button_config_center',
      '#value' => t('Save Configuration'),
      '#submit' => ['::miniorange_oauth_client_attr_setup_submit2'],
    ];
    $form['mo_header_style_end'] = ['#markup' => '</div>'];
    Utilities::show_attr_list_from_idp($form, $form_state);
    $form['miniorange_idp_guide_link_end'] = [
      '#markup' => '</div>',
    ];
    Utilities::AddSupportButton($form, $form_state);
    return $form;
  }

  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

  }

  function miniorange_oauth_client_attr_setup_submit($form, $form_state) {
    $email_attr = trim($form['miniorange_oauth_client_email_attr']['#value']);
    $name_attr = trim($form['miniorange_oauth_client_name_attr']['#value']);

    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')
      ->set('miniorange_oauth_client_email_attr_val', $email_attr)->save();
    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')
      ->set('miniorange_oauth_client_name_attr_val', $name_attr)->save();
    $app_values = \Drupal::config('oauth_login_oauth2.settings')
      ->get('miniorange_oauth_client_appval');
    $app_values['miniorange_oauth_client_email_attr'] = $email_attr;
    $app_values['miniorange_oauth_client_name_attr'] = $name_attr;
    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')
      ->set('miniorange_oauth_client_appval', $app_values)->save();
    \Drupal::messenger()
      ->addMessage(t('Attribute Mapping saved successfully.'));
  }


  function miniorange_oauth_client_attr_setup_submit2($form, $form_state) {
    $settings = \Drupal::configFactory()
      ->getEditable('oauth_login_oauth2.settings');
    $settings->set('miniorange_oauth_client_logout_url', $form['miniorange_oauth_client_logout_url']['#value'])
      ->save();
    $settings->set('miniorange_oauth_client_login_url', $form['miniorange_oauth_client_login_url']['#value'])
      ->save();
    $settings->set('miniorange_oauth_default_mapping', $form['miniorange_oauth_default_mapping']['#value'])
      ->save();
    $settings->set('miniorange_disable_attribute', $form['miniorange_disable_attribute']['#value'])
      ->save();
    $settings->set('miniorange_oauth_disable_role_update', $form['miniorange_oauth_disable_role_update']['#value'])
      ->save();
    $settings->set('miniorange_oauth_disable_autocreate_users', $form['miniorange_oauth_disable_autocreate_users']['#value'])
      ->save();

    \Drupal::messenger()
      ->addMessage(t('Role Mapping saved successfully.'));
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

  function clear_attr_list(&$form, $form_state) {
    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')
      ->clear('miniorange_oauth_client_attr_list_from_server')->save();
    Utilities::show_attr_list_from_idp($form, $form_state);
  }

  public function rfd(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    global $base_url;
    $response = new RedirectResponse($base_url . "/admin/config/people/oauth_login_oauth2/request_for_demo");
    $response->send();
  }
}
