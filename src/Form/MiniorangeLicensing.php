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
    return 'miniorange_oauth_client_licensing';
  }

public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
{
    global $base_url;

  $form['markup_library'] = array(
    '#attached' => array(
        'library' => array(
          "oauth_login_oauth2/oauth_login_oauth2.admin",
          "oauth_login_oauth2/oauth_login_oauth2.style_settings",
          "oauth_login_oauth2/oauth_login_oauth2.slide_support_button",
        )
    ),
  );

    if(!Utilities::isCustomerRegistered())
    {
        $URL_Redirect_std = "https://login.xecurify.com/moas/login?redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_oauth_client_standard_plan";
        $URL_Redirect_pre = "https://login.xecurify.com/moas/login?redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_oauth_client_premium_plan";
        $URL_Redirect_ent = "https://login.xecurify.com/moas/login?redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_oauth_client_enterprise_plan";
        $targetBlank = 'target="_blank"';
    } else {
        $URL_Redirect_std = $base_url.'/admin/config/people/oauth_login_oauth2/customer_setup';
        $URL_Redirect_pre = $base_url.'/admin/config/people/oauth_login_oauth2/customer_setup';
        $URL_Redirect_ent = $base_url.'/admin/config/people/oauth_login_oauth2/customer_setup';
        $targetBlank = '';
    }

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
                                        <li>Autofill OAuth servers configuration</li>
                                        <li>Basic Attribute Mapping (Email, Username)</li>
                                        <li>Auto Create Users</li>
                                        <li>Advanced Attribute Mapping (Username, Email, First Name, Custom Attributes, etc.)	</li>
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
                                <li>Upto 5 users </li>
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
                                <p class="mo_oauth_pricing-title">Standard<br><span>(Role & Attribute Mapping)<br>[One Time Payment]</span></p>
                                <p class="mo_oauth_pricing-rate"><sup>$</sup> 149</p>
                                <div class="filler-class"></div>
                                 <a href="'.$URL_Redirect_std.'" '.$targetBlank.' class="mo_oauth_btn mo_oauth_btn-primary">Click to Upgrade</a>
                        </div>

                        <div class="mo_oauth_pricing-list">
                            <ul>
                            <li>1</li>
                            <li>&#x2714;</li>
                            <li>&#x2714;</li>
                            <li>Unlimited</li>
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
                                <p class="mo_oauth_pricing-title">Premium<br><span>(OpenID support)<br>[One Time Payment]</span></p>
                                <p class="mo_oauth_pricing-rate"><sup>$</sup> 199</p>
                                 <a href="'.$URL_Redirect_pre.'" '.$targetBlank.' class="mo_oauth_btn mo_oauth_btn-primary">Click to Upgrade</a>
                            </div>

                            <div class="mo_oauth_pricing-list">
                                <ul>
                                    <li>1</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>Unlimited</li>
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
                                <p class="mo_oauth_pricing-title">Enterprise<br><span>(Domain & Page Restriction)<br>[One Time Payment]</span></p>
                                <p class="mo_oauth_pricing-rate"><sup>$</sup> 249<sup></sup></p>
                                 <a href="'.$URL_Redirect_ent.'" '.$targetBlank.' class="mo_oauth_btn mo_oauth_btn-primary">Click to upgrade</a>
                            </div>

                            <div class="mo_oauth_pricing-list">
                                <ul>
                                    <li>1*</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>Unlimited</li>
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
            . '<ol><li>After clicking on <b>Click to Upgrade</b> button, you will be redirected to miniOrange Login Console. Enter your password with which you created an'
            . ' account with us. After that you will be redirected to payment page.</li>'
            . '<li>Enter your card details and complete the payment. On successful payment completion, you will see the '
            . 'link to download the premium module.</li>'
            . '<li>Once you download the premium module, just unzip it and replace the folder with existing module. Clear Drupal Cache from <a href= "'.$base_url.'/admin/config/development/performance" target="_blank">here</a>.</li></ol>'
    );


    $form['markup_7'] = array(
        '#markup' => '<p>* Multiple OAuth providers are supported using Xecurify Broker service. For more information contact us at using the <b>Support </b> button or by dropping us an email at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a></p><br>
        <b>10 Days Return Policy - </b><br><br> 
        At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium module you purchased is not working as advertised and you have attempted to resolve any issues with our support team, which could not get resolved. We will refund the whole amount within 10 days of the purchase. Please email us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a> for any queries regarding the return policy.'
    );

    Utilities::AddSupportButton($form, $form_state);
    return $form;

 }

    public function saved_support(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

        $email = $form['miniorange_oauth_client_email_address']['#value'];
        $phone = $form['miniorange_oauth_client_phone_number']['#value'];
        $query = $form['miniorange_oauth_client_support_query']['#value'];
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

