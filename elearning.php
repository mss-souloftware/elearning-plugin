<?php
/**
 * 
 * Plugin Name: Zoom Learning Platform
 * plugin URI: https://souloftware.com/
 * version:1.0.0
 * Text Domail: lic. M. Sufyan Shaikh 
 * Description: Custom online learning platform with Zoom class integration. Provides role-based access for students and instructors, Zoom class enrollment, payment handling, and commission tracking — all built within a custom plugin.
 * Version: 1.0.0
 * Author: Souloftware
 * Author URI: https://souloftware.com/contact
 */

if (!defined('ABSPATH')) {
  exit;
}


require_once plugin_dir_path(__FILE__) . './admin/activation/activate-plugin.php';

register_activation_hook(__FILE__, 'createAllTables');

register_uninstall_hook(__FILE__, 'removeAllTables');



// Include functions.php, use require_once to stop the script if functions.php is not found
require_once plugin_dir_path(__FILE__) . 'utils/functions.php';
