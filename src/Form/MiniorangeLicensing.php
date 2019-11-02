<?php
/**
 * @file
 * Contains Licensing information for miniOrange OAuth Client Login Module.
 */

 /**
 * Showing Licensing form info.
 */
namespace Drupal\oauth_login_oauth2\Form;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_login_oauth2\Utilities;
use Drupal\oauth_login_oauth2\MiniorangeOAuthClientSupport;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MiniorangeLicensing extends FormBase {

public function getFormId() {
    return 'oauth_login_oauth2_licensing';
  }

public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
{

  $form['markup_library'] = array(
    '#attached' => array(
        'library' => array(
          "oauth_login_oauth2/oauth_login_oauth2.admin",
          "oauth_login_oauth2/oauth_login_oauth2.style_settings",
          "oauth_login_oauth2/oauth_login_oauth2.Vtour",
        )
    ),
  );

    $form['header_top_style_2'] = array(
        '#markup' => '<div class="mo_saml_table_layout_1"><div class="mo_saml_table_layout">'
    );

    $form['markup_1'] = array(
        '#markup' =>'<br><h3>&nbsp; UPGRADE PLANS</h3><hr>'
    );

    $form['markup_free'] = array(
        '#markup' => '<html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- Main Style -->
        </head>
        <body>
        <!-- Pricing Table Section -->
        <section id="mo_oauth_pricing-table">
            <div class="mo_oauth_container_1">
                <div class="mo_oauth_row">
                    <div class="mo_oauth_pricing">
                        <div>
                            <div class="mo_oauth_pricing-table mo_oauth_class_inline_1">
                                <div class="mo_oauth_pricing-header">
                                    <h2 class="mo_oauth_pricing-title">Features / Plans</h2>
                                </div>
                                <div class="mo_oauth_pricing-list">
                                    <ul>
                                    <li>OAuth Provider Support</li>
                                        <li>Auto fill OAuth servers configuration</li>
                                        <li>Basic Attribute Mapping (Email, Username)</li>
                                        <li>Login using the link</li>
                                        <li>Auto Create Users</li>
                                        <li>Advanced Attribute Mapping (Username, Display Name, Email, Group Name)	</li>
                                        <li>Custom Redirect URL after login and logout</li>
                                        <li>Basic Role Mapping (Support for default role for new users)</li>
                                        <li>Advanced Role Mapping</li>
                                        <li>Force authentication / Protect complete site</li>
                                        <li>OpenId Connect Support (Login using OpenId Connect Server)</li>
                                        <li>Domain specific registration</li>
                                        <li>Dynamic Callback URL</li>
                                        <li>Page Restriction</li>
                                        <li>Login Reports / Analytics</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mo_oauth_pricing-table mo_oauth_class_inline">
                                <div class="mo_oauth_pricing-header">
                                <p class="mo_oauth_pricing-title">Free</p>
                                <p class="mo_oauth_pricing-rate"><sup>$</sup> 0</p>
                                <div class="filler-class"></div>
                                    <a class="mo_oauth_btn mo_oauth_btn-primary">You are on this plan</a>
                                </div>
                            <div class="mo_oauth_pricing-list">
                                <ul>
                                <li>1</li>
                                <li>&#x2714;</li>
                                <li>&#x2714;</li>
                                <li>&#x2714;</li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li></li>
                                <li></li>
                                <li> </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mo_oauth_pricing-table mo_oauth_class_inline">
                            <div class="mo_oauth_pricing-header">
                                <p class="mo_oauth_pricing-title">Standard<br><span>(Role & Attribute Mapping)</span></p>
                                <p class="mo_oauth_pricing-rate"><sup>$</sup> 99<sup>*</sup></p>
                                <div class="filler-class"></div>
                                 <a href="https://login.xecurify.com/moas/login?redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_oauth_client_standard_plan" target="_blank" class="mo_oauth_btn mo_oauth_btn-primary">Click to Upgrade</a>
                        </div>

                        <div class="mo_oauth_pricing-list">
                            <ul>
                            <li>1</li>
                            <li>&#x2714;</li>
                            <li>&#x2714;</li>
                            <li>&#x2714;</li>
                            <li>&#x2714;</li>
                            <li>&#x2714;</li>
                            <li>&#x2714;</li>
                            <li>&#x2714;</li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            </ul>
                        </div>
                        </div>

                        <div class="mo_oauth_pricing-table mo_oauth_class_inline">
                            <div class="mo_oauth_pricing-header">
                                <p class="mo_oauth_pricing-title">Premium<br><span>(OpenID support)</span></p>
                                <p class="mo_oauth_pricing-rate"><sup>$</sup> 199<sup>*</sup></p>
                                 <a href="https://login.xecurify.com/moas/login?redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_oauth_client_premium_plan" target="_blank" class="mo_oauth_btn mo_oauth_btn-primary">Click to Upgrade</a>
                            </div>

                            <div class="mo_oauth_pricing-list">
                                <ul>
                                <li>1</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>

                                </ul>
                            </div>
                        </div>
                        <div class="mo_oauth_pricing-table mo_oauth_class_inline">
                            <div class="mo_oauth_pricing-header">
                                <p class="mo_oauth_pricing-title">Enterprise<br><span>(Domain Page Restriction)</span></p>
                                <p class="mo_oauth_pricing-rate"><sup>$</sup> 249<sup>*</sup></p>
                                 <a href="https://login.xecurify.com/moas/login?redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_oauth_client_enterprise_plan" target="_blank" class="mo_oauth_btn mo_oauth_btn-primary">Click to upgrade</a>
                            </div>

                            <div class="mo_oauth_pricing-list">
                                <ul>
                                    <li>1**</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pricing Table Section End --> 
     </body>
    </html>',
    );

    $form['markup_5'] = array(
        '#markup' => '<h3>Steps to Upgrade to Premium Module</h3>'
            . '<ol><li>You will be redirected to miniOrange Login Console. Enter your password with which you created an'
            . ' account with us. After that you will be redirected to payment page.</li>'
            . '<li>Enter you card details and complete the payment. On successful payment completion, you will see the '
            . 'link to download the premium module.</li>'
            . '<li>Once you download the premium module, just unzip it and replace the folder with existing module. Clear Drupal Cache.</li></ol>'
    );

    $form['markup_6'] = array(
        '#markup' => '<br><h4>* One Time Payment</h4>'
    );

    $form['markup_7'] = array(
        '#markup' => '<p>** Multiple OAuth providers are supported using Xecurify Broker service. For more information contact us at using the <b>Support </b> tab or by dropping us an email at <a href="mailto:info@xecurify.com">info@xecurify.com</a></p>'
    );

    Utilities::AddSupportButton($form, $form_state);
    return $form;

 }

    public function saved_support(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        $email = $form['oauth_login_oauth2_email_address']['#value'];
        $phone = $form['oauth_login_oauth2_phone_number']['#value'];
        $query = $form['oauth_login_oauth2_support_query']['#value'];
        Utilities::send_support_query($email, $phone, $query);
    }

    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    }

    public function rfd(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        global $base_url;
        $response = new RedirectResponse($base_url."/admin/config/people/oauth_login_oauth2/request_for_demo");
        $response->send();
    }
 }

