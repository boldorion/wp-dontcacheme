<?php
/*
Plugin Name: Don't Cache Me
Description: Present a no-cache & must-revalidate header, if the user is logged in. Use this in conjunction with hard Cloudflare caching.
Version: 0.0.3
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
    if ( is_user_logged_in() ) 
    {
    	header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  		header("Pragma: no-cache"); //HTTP 1.0
  		header("x-comment: Not Caching"); //HTTP 1.0
    }
}

add_action('init', 'cache_control');