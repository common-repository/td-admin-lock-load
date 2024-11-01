<?php
/**
 * Plugin Name: TD Admin Lock & Load
 * Plugin URI: http://www.transcendevelopment.com/td-admin-lock/
 * Description: A simple way to prevent unauthorized access to the admin login
 * Version: 1.0.1
 * Author: TranscenDevelopment
 * Author URI: http://www.transcendevelopment.com
 * License: GPL2
 

 /*  Copyright 2014  Mike Ramirez  (email : transcendev@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 
global $wpdb;

define('TDALLDIRURL', plugin_dir_url( __FILE__ ));

$installed_ver = get_option( "td_al_db_version" );
register_activation_hook( __FILE__, 'td_al_install');

add_action('registered_taxonomy', 'td_al_check_access');
add_action('admin_menu', 'td_al_regMenuPage');

//---------------------------------------------------------//
function td_al_check_access() {
//---------------------------------------------------------//
$thisPage = $_SERVER['REQUEST_URI'];
$isLogin = preg_match('/wp\-login\.php/', $thisPage);
if (isset($_GET['redirect_to'])) {$redirect = filter_var($_GET['redirect_to'], FILTER_SANITIZE_STRING);} else {$redirect='';}
preg_match("/a\=(.*)/", $redirect, $matches);

$accesscode = get_option('td_al_access_code');
$activated = get_option('td_al_lock_active');
$divertto = get_option('td_al_divertto');

    if ($activated == 1) {
        if ( ( is_admin() ) || (($isLogin) && (empty($_POST['log']))) ) {
            if (!is_user_logged_in()) {
                if (isset($_GET['a'])) {$a = filter_var($_GET['a'], FILTER_SANITIZE_STRING);} else {$a='';}
                $accessCheck = $a;
                if ( ! isset($matches[1]) ) {$matches[1] = null;}
                if( ($accesscode != $accessCheck)
                   && ( $accesscode != $matches[1] ) ) {
                    if ($divertto) {
                        header( 'Location:' . $divertto ) ;
                    } else {die();}
                }
            }        
        }
    }
}
//---------------------------------------------------------//
function td_al_page() {
//---------------------------------------------------------//
global $wpdb;

$adminURL = admin_url();
$accesscode = get_option('td_al_access_code');
$activated = get_option('td_al_lock_active');
$divertto = get_option('td_al_divertto');

if (isset($_POST['tdaccess'])) {$tdaccess = filter_var($_POST['tdaccess'], FILTER_SANITIZE_STRING);} else {$tdaccess='';}
if ($tdaccess) {
    if (isset($_POST['activate'])) {$activate = filter_var($_POST['activate'], FILTER_SANITIZE_STRING);} else {$activate='';}
    if (isset($_POST['divertto'])) {$diverturl = filter_var($_POST['divertto'], FILTER_SANITIZE_STRING);} else {$diverturl='';}

    if ($activate==1) {
        delete_option('td_al_lock_active');
        delete_option('td_al_access_code');
        delete_option('td_al_divertto');
        add_option('td_al_lock_active', '1', '', 'yes');
        add_option('td_al_access_code', $tdaccess, '', 'yes');
        add_option('td_al_divertto', $diverturl, '', 'yes');
        $accesscode = $tdaccess;
        $activated = 1;
        $divertto = $diverturl;
    } else {
        delete_option('td_al_lock_active');
         $activated = 0;
    }
}

$lockedMess=''; $checkActive=''; $oopsmess=''; $codeNotSet='';
if ($activated == 1 ) {
    $checkActive = 'checked';
    $lockedMess = '<div style="color:green;padding:15px 0 0 0;">This baby\'s locked down!</div>';
    $oopsmess = '<div style="color:red;padding:15px 0 0 0;">Note: If you find yourself forgetful and get yourself locked out of your admin panel down the road, simply delete this plugin from your Wordpress plugins folder.</div>';
}

if (empty($accesscode)) {
    $accesscode = 'CODE';
    $codeNotSet = 'Where "CODE" is the access code you enter below.';
}


echo <<<HTML
<form method="post">
<div style="float:left;width:400px;">
    <div style="width:400px;padding:25px 0 25px 15px;">
    <h1>TD Admin Lock & Load</h1>
    <div style="font-style:italic">Don't leave your barn door open!</div>
    <h2>How to Use</h2>
        1. Create your access code below.<br />
        2. Click the Activate Admin Lock checkbox.<br />
        3. Optionally divert traffic to another URL.<br />
        4. Future admin login should use the following URL:<br />
        <span style="color:blue;">$adminURL?a=$accesscode</span><br />
        $codeNotSet
        $lockedMess
        $oopsmess
    </div>
    <div style="width:400px;padding:0 0 25px 15px;line-height:30px;">
        Access Code: <input type="text" id="tdaccess" name="tdaccess" value="$accesscode" /><br />
        Send Traffic to: <input type="text" id="divertto" name="divertto" value="$divertto" /><br />
        <input type="checkbox" name="activate" id="activate" $checkActive value="1" /> Activate Admin Lock<br />
        <input type="submit" value="Save" onclick="
            if(document.getElementById('access').value=='' && document.getElementById('activate').checked ){alert('Are you trying to lock yourself out? You need to set an access code before activating the lock buddy!');return false;}
        " />
    </div>
</div>
<div style="float:left;width:400px;padding:15px 0 0 25px;text-align:center">
<h2>Wanna Support This Project?</h2>
If you like this plugin and would like to see continued development, please consider
a donation. Thank you!<br /><br />
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="PBM7V2TGX9AM6">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br /><br />

    <strong>Or, help spread the word:</strong><br /><br />
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=168164770005656";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <div class="fb-share-button" data-href="http://www.transcendevelopment.com/" data-type="button_count"></div>
    
    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.transcendevelopment.com/" data-text="Check out the TD Admin Lock & Load Wordpress Plugin!" data-via="TranscenDev" data-hashtags="wordpressplugins">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    
</div>
<div style="clear:both"></div>
</form>
HTML;
}
//---------------------------------------------------------//
function td_al_regMenuPage() {
//---------------------------------------------------------//    
   add_options_page('TD Admin Lock', 'TD Admin Lock & Load', 'manage_options', 'td-admin-lock.php', 'td_al_page');
}
//---------------------------------------------------------//
function td_al_install() {
//---------------------------------------------------------//    
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    delete_option('td_al_lock_active');
    delete_option('td_al_access_code');
    delete_option('td_al_divertto');
    add_option( "td_al_db_version", "1.0.1" );    
}


?>