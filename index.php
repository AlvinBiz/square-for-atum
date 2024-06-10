<?php
/**
 * Plugin Name: Square for ATUM
 * Description: A plugin that updates square inventory using ATUM
 * Version: 1.1.1
 * Author: Rafael Green
 * Author URI: https://github.com/AlvinBiz
 * Text Domain: square-for-atum
 * Domain Path: /languages
 **/

/**
 * Base config constants and functions
 */

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once( ABSPATH . 'wp-load.php' );

function require_multi($files) {
   $files = func_get_args();
   foreach($files as $file) {
       require_once $file;
     }
}

define('S4A_PLUGIN_URL',realpath(__FILE__));

require_multi(
 "inc/activation.php",
 "admin/options.php",
 "admin/notices.php",
 "classes/db.php",
 "classes/curl.php",
 "classes/api.php",
 "classes/square-curl-request.php",
 "classes/square-curl-update.php",
 "classes/sync-inventory.php",
 "inc/functions.php"
);
