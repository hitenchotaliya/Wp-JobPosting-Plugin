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
require_once($dir . 'wp-job-settings.php');
require_once($dir . 'wp-job-fields.php');

function dwwp_admin_enqueue_scripts()
{
    global $pagenow, $typenow;
    // var_dump($pagenow);

    if ($typenow == 'job') {
        wp_enqueue_style('dwwp-admin-css', plugins_url('css/admin-jobs.css', __FILE__));
    }

    if (($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'job') {
        wp_enqueue_script('dwwp-job-js', plugins_url('js/admin-jobs.js', __FILE__), array('jquery', 'jquery-ui-datepicker'), '20240304', true);
        wp_enqueue_style('jquery-style', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css');
        wp_enqueue_script('dwwp-custom-quicktags', plugins_url('js/dwwp-quicktags.js', __FILE__), array('quicktags'), '20240304', true);
    }

    if ($pagenow == 'edit.php' && $typenow = 'job') {
        wp_enqueue_script('reorder-js', plugins_url('js/reorder.js', __FILE__), array('jquery', 'jquery-ui-sortable'), '20240304', true);
        wp_localize_script('reorder-js', 'WP_JOB_LISTING', array(
            'security' => wp_create_nonce('wp_job_order')
        ));
    }
}
add_action('admin_enqueue_scripts', 'dwwp_admin_enqueue_scripts');
