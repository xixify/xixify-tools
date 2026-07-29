<?php
/**
 * Plugin Name:       Xixify Partnership & Financial Tracker
 * Plugin URI:        https://xixify.com/tools/partnership-portal
 * Description:       Track partnership accounts, manage dynamic advance payments & dues, calculate 50/50 profit splits, handle client requirements, and display minimalist project status portals. Includes automatic GitHub updates!
 * Version:           1.2.0
 * Author:            Xixify
 * Author URI:        https://xixify.com
 * License:           GPL-2.0+
 * Text Domain:       xixify-partnership-portal
 */

if (!defined('ABSPATH')) {
    exit;
}

define('XIXIFY_PORTAL_PATH', plugin_dir_path(__FILE__));
define('XIXIFY_PORTAL_URL', plugin_dir_url(__FILE__));

require_once XIXIFY_PORTAL_PATH . 'includes/class-db.php';
require_once XIXIFY_PORTAL_PATH . 'includes/class-admin.php';
require_once XIXIFY_PORTAL_PATH . 'includes/class-shortcode.php';
require_once XIXIFY_PORTAL_PATH . 'includes/class-updater.php';

register_activation_hook(__FILE__, array('Xixify_Partnership_DB', 'create_table'));

function xixify_portal_enqueue_assets($hook) {
    if (strpos($hook, 'xixify-partnership-portal') !== false) {
        wp_enqueue_style('xixify-portal-admin-css', XIXIFY_PORTAL_URL . 'assets/css/admin.css', array(), '1.2.0');
        wp_enqueue_script('xixify-portal-admin-js', XIXIFY_PORTAL_URL . 'assets/js/admin.js', array(), '1.2.0', true);
    }
}
add_action('admin_enqueue_scripts', 'xixify_portal_enqueue_assets');

function xixify_portal_enqueue_frontend_assets() {
    wp_enqueue_style('xixify-portal-frontend-css', XIXIFY_PORTAL_URL . 'assets/css/frontend.css', array(), '1.2.0');
}
add_action('wp_enqueue_scripts', 'xixify_portal_enqueue_frontend_assets');

Xixify_Partnership_Admin::init();
Xixify_Partnership_Shortcode::init();

// Initialize Automated GitHub Updater (Configured for xixify/xixify-tools)
if (is_admin()) {
    new Xixify_Partnership_Updater(__FILE__, 'xixify', 'xixify-tools');
}
