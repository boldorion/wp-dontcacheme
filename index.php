<?php
/*
Plugin Name: Smart Edge Cache
Description: Present a no-cache & must-revalidate header, if the user is logged in or an admin page is accessed. Ask BoldOrion to set up and manage your Cloudflare for this to work smoothly.
Version: 0.0.9
Author: BoldOrion
Author URI: https://www.boldorion.com
Text Domain: boldorion
License: GNU
*/

/* Autoupdating made easy 
v 4.11 - https://github.com/YahnisElsts/plugin-update-checker
*/
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/boldorion/wp-dontcacheme',
    __FILE__,
    'dontcacheme'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

/* The actual plugin, in all its glory */
function cache_control()
{
    if ( is_user_logged_in() || is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' ) 
    {
    	header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  		header("Pragma: no-cache"); //HTTP 1.0
  		header("x-comment: Not Caching"); //HTTP 1.0
        
  		header("x-edgecache: Cache action not applied (U: ".is_user_logged_in().", A: ".is_admin().", L: ".$GLOBALS['pagenow'] === 'wp-login.php'.")"); //Logging
    }
    else
    {
        $seconds_to_cache = (3600*24);
        $days_to_cache = 1;
        $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache * $days_to_cache) . " GMT";
        header("Expires: $ts");
        header("Pragma: cache");
        header("Cache-Control: max-age=$seconds_to_cache");
        header("x-edgecache: Ready");
    }
}

add_action('init', 'cache_control');
//add_action( 'send_headers', 'cache_control', 999 ); //Is this a better way?