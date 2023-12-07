<?php
/**
 * Plugin Name: Version Wordpress 
 * Description: Une extension qui permet d'afficher la version de wordpress avec information sur l'état de version. 
 * Version: 1.0.0
 * Author: Saddek Touati
 */

defined('ABSPATH') || exit;
define('API_URL', 'https://endoflife.date/api/wordpress.json');

// Inclusion de la classe principale
require_once plugin_dir_path(__FILE__) . 'includes/wp_v_handle_api.php';
require_once plugin_dir_path(__FILE__) . 'includes/wp_v_main.php';
require_once plugin_dir_path(__FILE__) . 'includes/wp_v_handle_version.php';
require_once plugin_dir_path(__FILE__) . 'public/wp_v_enqueue.php';

// Instantiate the classes
$wp_v_handle_api_instance = new wp_v_handle_api();

$wp_v_handle_version_instance = new wp_v_handle_version();

$wp_v_main_instance = new wp_v_main($wp_v_handle_api_instance, $wp_v_handle_version_instance);

$wp_v_enqueue_instance = new wp_v_enqueue($wp_v_main_instance);

// // Initialize the plugin
// add_action('plugins_loaded', array('Your_Plugin', 'get_instance'));

// // Activation and Deactivation hooks
// register_activation_hook(__FILE__, array('Your_Plugin', 'activate'));
// register_deactivation_hook(__FILE__, array('Your_Plugin', 'deactivate'));