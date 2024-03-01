<?php

/**
 * Plugin Name: Job Listing Plugin
 * Plugin URI: https://hitentsplugin.com
 * Description: This Plugin allow you to add simple Job listing section to your wordpress website
 * Author: Hiten
 * Author URI: https://hiten.com
 * Version: 1.0.0
 * License: GPLv2 
 */

use FluentForm\Framework\Support\Arr;

//Exist if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
$dir = plugin_dir_path(__FILE__);
require_once($dir . 'wp-job-cpt.php');
require_once($dir . 'wp-job-rander-admin.php');
require_once($dir . 'wp-job-fields.php');
