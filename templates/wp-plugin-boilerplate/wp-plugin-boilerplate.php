<?php
/**
 * Plugin Name:       Xixify Tool Boilerplate
 * Plugin URI:        https://xixify.com/tools/boilerplate
 * Description:       Standard starter plugin boilerplate for Xixify WordPress tools.
 * Version:           1.0.0
 * Author:            Xixify
 * Author URI:        https://xixify.com
 * License:           GPL-2.0+
 * Text Domain:       xixify-tool-boilerplate
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class Xixify_Tool_Boilerplate {

    public function __construct() {
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function register_admin_menu() {
        add_menu_page(
            __('Xixify Tool', 'xixify-tool-boilerplate'),
            __('Xixify Tool', 'xixify-tool-boilerplate'),
            'manage_options',
            'xixify-tool-boilerplate',
            array($this, 'render_admin_page'),
            'dashicons-admin-tools',
            90
        );
    }

    public function enqueue_admin_assets($hook) {
        if ($hook !== 'toplevel_page_xixify-tool-boilerplate') {
            return;
        }
        // Enqueue styles and scripts here if needed
    }

    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Xixify Tool Boilerplate', 'xixify-tool-boilerplate'); ?></h1>
            <p><?php esc_html_e('Welcome to your new Xixify WordPress Tool plugin!', 'xixify-tool-boilerplate'); ?></p>
        </div>
        <?php
    }
}

new Xixify_Tool_Boilerplate();
