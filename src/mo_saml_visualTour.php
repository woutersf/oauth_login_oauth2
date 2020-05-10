<?php
namespace Drupal\oauth2_login;

class mo_saml_visualTour {

    public static function genArray($overAllTour = 'tabTour'){
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $exploded = explode('/', $link);
        $getPageName = end($exploded);
        $Tour_Token = \Drupal::config('oauth2_login.settings')->get('mo_saml_tourTaken_' . $getPageName);
        if($overAllTour == 'overAllTour'){
            $getPageName = 'overAllTour';
        }
        //\Drupal::configFactory()->getEditable('oauth2_login.settings')->set('mo_saml_tourTaken_' . $getPageName, TRUE)->save();
        $moTourArr = array (
            'pageID' => $getPageName,
            'tourData' => mo_saml_visualTour::getTourData($getPageName),
            'tourTaken' => $Tour_Token,
            'addID' => mo_saml_visualTour::addID(),
            'pageURL' => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        );

        \Drupal::configFactory()->getEditable('oauth2_login.settings')->set('mo_saml_tourTaken_' . $getPageName, TRUE)->save();
        $moTour = json_encode($moTourArr);
        return $moTour;
    }

    public static function addID()
    {
        $idArray = array(
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(1)',
                'newID'     =>'mo_vt_oauth_client_config',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(2)',
                'newID'     =>'mo_vt_oauth_client_mapping',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(3)',
                'newID'     =>'mo_vt_oauth_client_signin',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(4)',
                'newID'     =>'mo_vt_oauth_client_upgrade',
            ),
        );
        return $idArray;
    }
    public static function getTourData($pageID)
    {

        $tourData = array();
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $exploded = explode('/', $link);
        $getPageName = end($exploded);
        $Tour_Token = \Drupal::config('oauth2_login.settings')->get('mo_saml_tourTaken_' . $getPageName);

        if($Tour_Token == 0 || $Tour_Token == FALSE)
            $tab_index = 'idp_setup';
        else $tab_index = 'idp_tab';

        if($Tour_Token == FALSE) {
            $tourData['config_clc'] = array(
                0 => array(
                    'targetE'       => 'mo_vt_oauth_client_config',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Configure OAuth</h1>',
                    'contentHTML'   => 'Configure your OAuth Server with OAuth Client here to perform SSO.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                1 => array(
                    'targetE'       => 'mo_vt_oauth_client_mapping',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Attribute Mapping</h1>',
                    'contentHTML'   => 'In this tab, you can perform Attribute and Role Mapping configurations.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                2 => array(
                    'targetE'       => 'mo_vt_oauth_client_signin',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Signin Settings</h1>',
                    'contentHTML'   => 'Here you can select between various sign in options.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                3 => array(
                    'targetE'       => 'mo_vt_oauth_client_upgrade',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Upgrade here</h1>',
                    'contentHTML'   => 'You can see the complete list of features that we provide in our various plans and can also upgrade to any of them.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'big',
                ),
                4 => array(
                    'targetE'       => 'miniorange_oauth_client_app',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Select Application</h1>',
                    'contentHTML'   => 'Please select your OAuth server to configure. Select Custom OAuth if your server not listed.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                5 => array(
                    'targetE'       => 'mo_vt_callback_url',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Callback/Redirect URL</h1>',
                    'contentHTML'   => 'Provide this <b>Callback/Redirect URL</b> to your OAuth Server to configure your OAuth Client.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                6 => array(
                    'targetE'       => 'mo_vt_add_data',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Configure OAuth Server</h1>',
                    'contentHTML'   => 'Enter details to configure your OAuth Server.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'medium',
                ),
                7 => array(
                    'targetE'       => 'mo_vt_add_data2',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Scope</h1>',
                    'contentHTML'   => 'Scope decides the range of data that comes from your OAuth Server.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                8 => array(
                    'targetE'       => 'mo_vt_add_data3',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Endpoints</h1>',
                    'contentHTML'   => 'The endpoints from your OAuth Server will be used during OAuth SSO login.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                9 => array(
                    'targetE'       => 'mo_vt_add_data4',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Enable login with OAuth</h1>',
                    'contentHTML'   => 'Enable the checkbox if you want to enable SSO login with OAuth.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                10 => array(
                    'targetE'       => 'button_config',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Save Settings</h1>',
                    'contentHTML'   => 'You can save your configurations by clicking on this button.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'medium',
                ),
                11 => array(
                    'targetE'       => 'mo_oauth_guide_vt',
                    'pointToSide'   => 'right',
                    'titleHTML'     => '<h1>Documentaion</h1>',
                    'contentHTML'   => 'To see step by step guides of how to configure Drupal OAuth Client with any OAuth Server.',
                    'ifNext'        => true,
                    'buttonText'    => 'End Tour',
                    'cardSize'      => 'largemedium',
                ),
            );
        }
        else{
            $tourData['config_clc'] = array(
                0 => array(
                    'targetE'       => 'miniorange_oauth_client_app',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Select Application</h1>',
                    'contentHTML'   => 'Please select your OAuth server to configure. Select Custom OAuth if your server not listed.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                1 => array(
                    'targetE'       => 'mo_vt_callback_url',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Callback/Redirect URL</h1>',
                    'contentHTML'   => 'Provide this <b>Callback/Redirect URL</b> to your OAuth Server to configure your OAuth Client.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                2 => array(
                    'targetE'       => 'mo_vt_add_data',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Configure OAuth Server</h1>',
                    'contentHTML'   => 'Enter details to configure your OAuth Server.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'medium',
                ),
                3 => array(
                    'targetE'       => 'mo_vt_add_data2',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Scope</h1>',
                    'contentHTML'   => 'Scope decides the range of data that comes from your OAuth Server.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'medium',
                ),
                4 => array(
                    'targetE'       => 'mo_vt_add_data3',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Endpoints</h1>',
                    'contentHTML'   => 'The endpoints from your OAuth Server will be used during OAuth SSO login.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                5 => array(
                    'targetE'       => 'mo_vt_add_data4',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Enable login with OAuth</h1>',
                    'contentHTML'   => 'Enable the checkbox if you want to enable SSO login with OAuth.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                6 => array(
                    'targetE'       => 'button_config',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Save Settings</h1>',
                    'contentHTML'   => 'You can save your configurations by clicking on this button.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'medium',
                ),
                7 => array(
                    'targetE'       => 'mo_oauth_guide_vt',
                    'pointToSide'   => 'right',
                    'titleHTML'     => '<h1>Documentaion</h1>',
                    'contentHTML'   => 'To see step by step guides of how to configure Drupal OAuth Client with any OAuth Server.',
                    'ifNext'        => true,
                    'buttonText'    => 'End Tour',
                    'cardSize'      => 'largemedium',
                    'ifskip'        =>  'hidden',
                ),
            );
        }

        $tourData['mapping'] = array(
            0 =>    array(
                'targetE'       =>  'mo_oauth_vt_attrn',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  '<h1>Email Attribute</h1>',
                'contentHTML'   =>  'Please enter attribute name which holds email address here. You can find this in test configuration',
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'img'           =>  array(),
                'cardSize'      =>  'largemedium',
                'action'        =>  '',
            ),
            1 =>    array(
                'targetE'       =>  'mo_oauth_vt_attre',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  '<h1>Username Attribute</h1>',
                'contentHTML'   =>  'Enter the Username Attribute which holds name. You can find this in test configuration.',
                'ifNext'        =>  true,
                'buttonText'    =>  'End Tour',
                'img'           =>  array(),
                'cardSize'      =>  'largemedium',
                'action'        =>  '',
                'ifskip'        =>  'hidden',
            ),
        );
        $tourData['licensing'] = array(
            0 =>    array(
                'targetE'       =>  'edit-miniorange-saml-idp-support-side-button',
                'pointToSide'   =>  'right',
                'titleHTML'     =>  '<h1>Want a demo?</h1>',
                'contentHTML'   =>  'Want to test any paid modules before purchasing? Just send us a request.',
                'ifNext'        =>  true,
                'buttonText'    =>  'End Tour',
                'img'           =>  array(),
                'cardSize'      =>  'largemedium',
                'action'        =>  '',
                'ifskip'        =>  'hidden',
            ),
        );
        return $tourData[$pageID] ;
    }
}
