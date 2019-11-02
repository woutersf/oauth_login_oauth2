/**
 * @file JS file to perform authentication and registration for miniOrange
 *       Authentication service.
 */
(function($) {
                        jQuery(document).ready(function() {
                           // jQuery('#miniorange_oauth_login_link').parent().hide();
                            var v=document.getElementById('oauth_login_oauth2_app');
                            v.options[13].disabled=true;
                            v.options[14].disabled=true;
                            v.options[15].disabled=true;
                        jQuery('#oauth_login_oauth2_app').parent().show();
                        jQuery('#oauth_login_oauth2_app').click(function()
                        {
                            var base_url = window.location.origin;
                            //var pathArray = window.location.pathname.split( '/' );
                            //var baseUrl = base_url+'/'+pathArray[1];
                            var baseUrl = base_url;
                            var appname = document.getElementById('oauth_login_oauth2_app').value;
                            if(appname=='Facebook' || appname=='Google' || appname=='Wild Apricot' || appname=='Salesforce' || appname=='LinkedIn' || appname=='Azure AD' || appname=='Keycloak' || appname=='Custom' || appname=='Strava' || appname=='FitBit' || appname=='Discord' || appname=='Line'){

                                jQuery('#oauth_login_oauth2_app_name').parent().show();
                                jQuery('#oauth_login_oauth2_display_name').parent().show();
                                jQuery('#oauth_login_oauth2_client_id').parent().show();
                                jQuery('#oauth_login_oauth2_client_secret').parent().show();
                                jQuery('#oauth_login_oauth2_scope').parent().show();
                                jQuery('#miniorange_oauth_login_link').parent().show();
                                jQuery('#test_config_button').show();

                                jQuery('#callbackurl').parent().show();
                                jQuery('#mo_oauth_authorizeurl').attr('required','true');
                                jQuery('#mo_oauth_accesstokenurl').attr('required','true');
                                jQuery('#mo_oauth_resourceownerdetailsurl').attr('required','true');
                                jQuery('#oauth_login_oauth2_auth_ep').parent().show();
                                jQuery('#oauth_login_oauth2_access_token_ep').parent().show();
                                jQuery('#oauth_login_oauth2_user_info_ep').parent().show();

                                if(appname=='Facebook'){
                                    document.getElementById('oauth_login_oauth2_scope').value='email';
                                    document.getElementById('oauth_login_oauth2_auth_ep').value='https://www.facebook.com/dialog/oauth';
                                    document.getElementById('oauth_login_oauth2_access_token_ep').value='https://graph.facebook.com/v2.8/oauth/access_token';
                                    document.getElementById('oauth_login_oauth2_user_info_ep').value='https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=';
                                }else if(appname=='Google'){
                                    document.getElementById('oauth_login_oauth2_scope').value='email+profile';
                                    document.getElementById('oauth_login_oauth2_auth_ep').value='https://accounts.google.com/o/oauth2/auth';
                                    document.getElementById('oauth_login_oauth2_access_token_ep').value='https://www.googleapis.com/oauth2/v4/token';
                                    document.getElementById('oauth_login_oauth2_user_info_ep').value='https://www.googleapis.com/oauth2/v1/userinfo';
                                }else if(appname=='LinkedIn'){
                                  document.getElementById('oauth_login_oauth2_scope').value='r_basicprofile';
                                  document.getElementById('oauth_login_oauth2_auth_ep').value='https://www.linkedin.com/oauth/v2/authorization';
                                  document.getElementById('oauth_login_oauth2_access_token_ep').value='https://www.linkedin.com/oauth/v2/accessToken';
                                  document.getElementById('oauth_login_oauth2_user_info_ep').value='https://api.linkedin.com/v2/me';
                                }else if(appname=='Salesforce'){
                                  document.getElementById('oauth_login_oauth2_scope').value='id';
                                  document.getElementById('oauth_login_oauth2_auth_ep').value='https://login.salesforce.com/services/oauth2/authorize';
                                  document.getElementById('oauth_login_oauth2_access_token_ep').value='https://login.salesforce.com/services/oauth2/token';
                                  document.getElementById('oauth_login_oauth2_user_info_ep').value='https://login.salesforce.com/services/oauth2/userinfo';
                                }else if(appname=='Wild Apricot'){
                                  document.getElementById('oauth_login_oauth2_scope').value='auto';
                                  document.getElementById('oauth_login_oauth2_auth_ep').value='https://{your_account_url}/sys/login/OAuthLogin';
                                  document.getElementById('oauth_login_oauth2_access_token_ep').value='https://oauth.wildapricot.org/auth/token';
                                  document.getElementById('oauth_login_oauth2_user_info_ep').value='https://api.wildapricot.org/v2.1/accounts/{account_id}/contacts/me';
                                }else if(appname=='Azure AD'){
                                    document.getElementById('oauth_login_oauth2_scope').value='openid';
                                    document.getElementById('oauth_login_oauth2_auth_ep').value='https://login.microsoftonline.com/[tenant-id]/oauth2/authorize';
                                    document.getElementById('oauth_login_oauth2_access_token_ep').value='https://login.microsoftonline.com/[tenant-id]/oauth2/token';
                                    document.getElementById('oauth_login_oauth2_user_info_ep').value='https://login.windows.net/common/openid/userinfo';
                                }else if(appname=='Keycloak'){
                                    document.getElementById('oauth_login_oauth2_scope').value='email profile';
                                    document.getElementById('oauth_login_oauth2_auth_ep').value='{Keycloak_base_URL}/realms/{realm-name}/protocol/openid-connect/auth';
                                    document.getElementById('oauth_login_oauth2_access_token_ep').value='{Keycloak_base_URL}/realms/{realm-name}/protocol/openid-connect/token';
                                    document.getElementById('oauth_login_oauth2_user_info_ep').value='{Keycloak_base_URL}/realms/{realm-name}/protocol/openid-connect/userinfo';
                                }else if(appname=='Custom'){
                                    document.getElementById('oauth_login_oauth2_auth_ep').value='';
                                    document.getElementById('oauth_login_oauth2_access_token_ep').value='';
                                    document.getElementById('oauth_login_oauth2_user_info_ep').value='';
                                } else if(appname=='Strava'){
                                    document.getElementById('oauth_login_oauth2_scope').value='public';
                                    document.getElementById('oauth_login_oauth2_auth_ep').value='https://www.strava.com/oauth/authorize';
                                    document.getElementById('oauth_login_oauth2_access_token_ep').value='https://www.strava.com/oauth/token';
                                    document.getElementById('oauth_login_oauth2_user_info_ep').value='https://www.strava.com/api/v3/athlete';
                                }else if(appname=='FitBit'){
                                    document.getElementById('oauth_login_oauth2_scope').value='profile';
                                    document.getElementById('oauth_login_oauth2_auth_ep').value='https://www.fitbit.com/oauth2/authorize';
                                    document.getElementById('oauth_login_oauth2_access_token_ep').value='https://api.fitbit.com/oauth2/token';
                                    document.getElementById('oauth_login_oauth2_user_info_ep').value='https://api.fitbit.com/1/user/-/profile.json';
                                }else if(appname=='Discord'){
                                  document.getElementById('oauth_login_oauth2_scope').value='identify email';
                                  document.getElementById('oauth_login_oauth2_auth_ep').value='https://discordapp.com/api/oauth2/authorize';
                                  document.getElementById('oauth_login_oauth2_access_token_ep').value='https://discordapp.com/api/oauth2/token';
                                  document.getElementById('oauth_login_oauth2_user_info_ep').value='https://discordapp.com/api/users/@me';
                                }else if(appname=='Line'){
                                  document.getElementById('oauth_login_oauth2_scope').value='Profile openid email';
                                  document.getElementById('oauth_login_oauth2_auth_ep').value='https://access.line.me/oauth2/v2.1/authorize';
                                  document.getElementById('oauth_login_oauth2_access_token_ep').value='https://api.line.me/oauth2/v2.1/token';
                                  document.getElementById('oauth_login_oauth2_user_info_ep').value='https://api.line.me/ v2/profile';
                                }
                                else if(appname == 'Eve Online')
                                {
                                    document.getElementById('oauth_login_oauth2_scope').value='characterContactsRead';
                                }
                            }

                        })
                    }
                    );

}(jQuery));
