<?php
   /*
   Plugin Name: Bluemail (wordpress-bluemail)
   description: Route WordPress wp_mail() function to IBM Bluemail service
   Version: 1.2
   Author: Terry Snyder
   Author URI: http://ibm.biz/tsnyder
   License: 
   */

require_once('sendrest.php');

add_action( 'init', '\plugin_init' );

if (!function_exists('wp_mail'))
{
    function wp_mail($to, $subject, $message, $headers = '', $attachments = array())
    {
        sendrestmail($to, $subject, $message, $headers);
    }
}

function plugin_init() {}

?>
