/**
 * @file JS file to perform authentication and registration for miniOrange
 *       Authentication service.
 */
(function($) {
                        jQuery(document).ready(function() {
                           // jQuery('#miniorange_oauth_login_link').parent().hide();
                            var v=document.getElementById('miniorange_oauth_client_app');
                            v.options[16].disabled=true;
                            v.options[17].disabled=true;
                            v.options[18].disabled=true;
                            v.options[19].disabled=true;
                            v.options[20].disabled=true;
                        jQuery('#miniorange_oauth_client_app').parent().show();
                        jQuery('#miniorange_oauth_client_app').click(function()
                        {
                            var base_url = window.location.origin;
                            //var pathArray = window.location.pathname.split( '/' );
                            //var baseUrl = base_url+'/'+pathArray[1];
                            var baseUrl = base_url;
                            var appname = document.getElementById('miniorange_oauth_client_app').value;
                            if(appname=='Facebook' || appname=='Google' || appname=='Zendesk' || appname=='Box' || appname=='GitHub' || appname=='Wild Apricot' || appname=='Salesforce' || appname=='LinkedIn' || appname=='Azure AD' || appname=='Keycloak' || appname=='Custom' || appname=='Strava' || appname=='FitBit' || appname=='Discord' || appname=='Line'){

                                jQuery('#miniorange_oauth_client_app_name').parent().show();
                                jQuery('#miniorange_oauth_client_display_name').parent().show();
                                jQuery('#miniorange_oauth_client_client_id').parent().show();
                                jQuery('#miniorange_oauth_client_client_secret').parent().show();
                                jQuery('#miniorange_oauth_client_scope').parent().show();
                                jQuery('#miniorange_oauth_login_link').parent().show();
                                jQuery('#test_config_button').show();

                                jQuery('#callbackurl').parent().show();
                                jQuery('#mo_oauth_authorizeurl').attr('required','true');
                                jQuery('#mo_oauth_accesstokenurl').attr('required','true');
                                jQuery('#mo_oauth_resourceownerdetailsurl').attr('required','true');
                                jQuery('#miniorange_oauth_client_auth_ep').parent().show();
                                jQuery('#miniorange_oauth_client_access_token_ep').parent().show();
                                jQuery('#miniorange_oauth_client_user_info_ep').parent().show();

                                if(appname=='Facebook'){
                                    document.getElementById('miniorange_oauth_client_scope').value='email';
                                    document.getElementById('miniorange_oauth_client_auth_ep').value='https://www.facebook.com/dialog/oauth';
                                    document.getElementById('miniorange_oauth_client_access_token_ep').value='https://graph.facebook.com/v2.8/oauth/access_token';
                                    document.getElementById('miniorange_oauth_client_user_info_ep').value='https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=';
                                }else if(appname=='Google'){
                                    document.getElementById('miniorange_oauth_client_scope').value='email+profile';
                                    document.getElementById('miniorange_oauth_client_auth_ep').value='https://accounts.google.com/o/oauth2/auth';
                                    document.getElementById('miniorange_oauth_client_access_token_ep').value='https://www.googleapis.com/oauth2/v4/token';
                                    document.getElementById('miniorange_oauth_client_user_info_ep').value='https://www.googleapis.com/oauth2/v1/userinfo';
                                }else if(appname=='LinkedIn'){
                                  document.getElementById('miniorange_oauth_client_scope').value='r_basicprofile';
                                  document.getElementById('miniorange_oauth_client_auth_ep').value='https://www.linkedin.com/oauth/v2/authorization';
                                  document.getElementById('miniorange_oauth_client_access_token_ep').value='https://www.linkedin.com/oauth/v2/accessToken';
                                  document.getElementById('miniorange_oauth_client_user_info_ep').value='https://api.linkedin.com/v2/me';
                                }else if(appname=='Salesforce'){
                                  document.getElementById('miniorange_oauth_client_scope').value='id';
                                  document.getElementById('miniorange_oauth_client_auth_ep').value='https://login.salesforce.com/services/oauth2/authorize';
                                  document.getElementById('miniorange_oauth_client_access_token_ep').value='https://login.salesforce.com/services/oauth2/token';
                                  document.getElementById('miniorange_oauth_client_user_info_ep').value='https://login.salesforce.com/services/oauth2/userinfo';
                                }else if(appname=='Wild Apricot'){
                                  document.getElementById('miniorange_oauth_client_scope').value='auto';
                                  document.getElementById('miniorange_oauth_client_auth_ep').value='https://{your_account_url}/sys/login/OAuthLogin';
                                  document.getElementById('miniorange_oauth_client_access_token_ep').value='https://oauth.wildapricot.org/auth/token';
                                  document.getElementById('miniorange_oauth_client_user_info_ep').value='https://api.wildapricot.org/v2.1/accounts/{account_id}/contacts/me';
                                }else if(appname=='Azure AD'){
                                    document.getElementById('miniorange_oauth_client_scope').value='openid';
                                    document.getElementById('miniorange_oauth_client_auth_ep').value='https://login.microsoftonline.com/[tenant-id]/oauth2/authorize';
                                    document.getElementById('miniorange_oauth_client_access_token_ep').value='https://login.microsoftonline.com/[tenant-id]/oauth2/token';
                                    document.getElementById('miniorange_oauth_client_user_info_ep').value='https://login.windows.net/common/openid/userinfo';
                                }else if(appname=='Keycloak'){
                                    document.getElementById('miniorange_oauth_client_scope').value='email profile';
                                    document.getElementById('miniorange_oauth_client_auth_ep').value='{Keycloak_base_URL}/realms/{realm-name}/protocol/openid-connect/auth';
                                    document.getElementById('miniorange_oauth_client_access_token_ep').value='{Keycloak_base_URL}/realms/{realm-name}/protocol/openid-connect/token';
                                    document.getElementById('miniorange_oauth_client_user_info_ep').value='{Keycloak_base_URL}/realms/{realm-name}/protocol/openid-connect/userinfo';
                                }else if(appname=='Custom'){
                                    document.getElementById('miniorange_oauth_client_scope').value='';
                                    document.getElementById('miniorange_oauth_client_auth_ep').value='';
                                    document.getElementById('miniorange_oauth_client_access_token_ep').value='';
                                    document.getElementById('miniorange_oauth_client_user_info_ep').value='';
                                } else if(appname=='Strava'){
                                    document.getElementById('miniorange_oauth_client_scope').value='public';
                                    document.getElementById('miniorange_oauth_client_auth_ep').value='https://www.strava.com/oauth/authorize';
                                    document.getElementById('miniorange_oauth_client_access_token_ep').value='https://www.strava.com/oauth/token';
                                    document.getElementById('miniorange_oauth_client_user_info_ep').value='https://www.strava.com/api/v3/athlete';
                                }else if(appname=='FitBit'){
                                    document.getElementById('miniorange_oauth_client_scope').value='profile';
                                    document.getElementById('miniorange_oauth_client_auth_ep').value='https://www.fitbit.com/oauth2/authorize';
                                    document.getElementById('miniorange_oauth_client_access_token_ep').value='https://api.fitbit.com/oauth2/token';
                                    document.getElementById('miniorange_oauth_client_user_info_ep').value='https://api.fitbit.com/1/user/-/profile.json';
                                }else if(appname=='Discord'){
                                  document.getElementById('miniorange_oauth_client_scope').value='identify email';
                                  document.getElementById('miniorange_oauth_client_auth_ep').value='https://discordapp.com/api/oauth2/authorize';
                                  document.getElementById('miniorange_oauth_client_access_token_ep').value='https://discordapp.com/api/oauth2/token';
                                  document.getElementById('miniorange_oauth_client_user_info_ep').value='https://discordapp.com/api/users/@me';
                                }else if(appname=='Line'){
                                  document.getElementById('miniorange_oauth_client_scope').value='Profile openid email';
                                  document.getElementById('miniorange_oauth_client_auth_ep').value='https://access.line.me/oauth2/v2.1/authorize';
                                  document.getElementById('miniorange_oauth_client_access_token_ep').value='https://api.line.me/oauth2/v2.1/token';
                                  document.getElementById('miniorange_oauth_client_user_info_ep').value='https://api.line.me/ v2/profile';
                                }else if(appname=='GitHub'){
                                  document.getElementById('miniorange_oauth_client_scope').value='user repo';
                                  document.getElementById('miniorange_oauth_client_auth_ep').value='https://github.com/login/oauth/authorize';
                                  document.getElementById('miniorange_oauth_client_access_token_ep').value='https://github.com/login/oauth/access_token';
                                  document.getElementById('miniorange_oauth_client_user_info_ep').value='https://api.github.com/user';
                                }else if(appname=='Box'){
                                  document.getElementById('miniorange_oauth_client_scope').value='root_readwrite';
                                  document.getElementById('miniorange_oauth_client_auth_ep').value='https://account.box.com/api/oauth2/authorize';
                                  document.getElementById('miniorange_oauth_client_access_token_ep').value='https://api.box.com/oauth2/token';
                                  document.getElementById('miniorange_oauth_client_user_info_ep').value='https://api.box.com/2.0/users/me';
                                }else if(appname=='Zendesk'){
                                    document.getElementById('miniorange_oauth_client_scope').value='read write';
                                    document.getElementById('miniorange_oauth_client_auth_ep').value='https://{subdomain}.zendesk.com/oauth/authorizations/new';
                                    document.getElementById('miniorange_oauth_client_access_token_ep').value='https://{subdomain}.zendesk.com/oauth/tokens';
                                    document.getElementById('miniorange_oauth_client_user_info_ep').value='https://{subdomain}.zendesk.com/api/v2/users';
                                }
                                else if(appname == 'Eve Online')
                                {
                                    document.getElementById('miniorange_oauth_client_scope').value='characterContactsRead';
                                }
                            }

                        })
                    }
                    );

}(jQuery));
