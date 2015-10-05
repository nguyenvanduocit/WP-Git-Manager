<?php

/*
Plugin Name: WP Pull Manager
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: nguyenvanduocit
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

define('WPPM_VERSION', '1.0.1');
define('WPPM_FILE', __FILE__);
define('WPPM_DIR', __DIR__);
define('WPPM_DOMAIN', 'wppm');

require_once WPPM_DIR.'/vendor/autoload.php';

global $wppm;
$wppm = new WPPM\PullManager();
$wppm->run();