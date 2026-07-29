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

// -----------------------------------------------------------------------------
// 1. DATABASE SCHEMA & SEEDER CLASS
// -----------------------------------------------------------------------------
class Xixify_Partnership_DB {

    public static function get_projects_table() {
        global $wpdb;
        return $wpdb->prefix . 'xixify_partnership_projects';
    }

    public static function get_requirements_table() {
        global $wpdb;
        return $wpdb->prefix . 'xixify_client_requirements';
    }

    public static function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Projects Table
        $table_projects = self::get_projects_table();
        $sql_projects = "CREATE TABLE $table_projects (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            project_name varchar(255) NOT NULL,
            client varchar(255) NOT NULL,
            client_email varchar(255) DEFAULT '',
            access_key varchar(100) DEFAULT '',
            source varchar(255) DEFAULT '',
            lead_owner varchar(100) DEFAULT 'Sumayah',
            amount decimal(10,2) NOT NULL DEFAULT 0.00,
            expenses decimal(10,2) NOT NULL DEFAULT 0.00,
            paid decimal(10,2) NOT NULL DEFAULT 0.00,
            due decimal(10,2) NOT NULL DEFAULT 0.00,
            month varchar(50) DEFAULT 'January',
            status varchar(50) DEFAULT 'Pending',
            progress_percent int(3) NOT NULL DEFAULT 0,
            milestone_stage varchar(100) DEFAULT 'Requirement Gathering',
            description text DEFAULT '',
            distributed varchar(20) DEFAULT 'No',
            client_visible tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        dbDelta($sql_projects);

        // Requirements Table
        $table_req = self::get_requirements_table();
        $sql_req = "CREATE TABLE $table_req (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            project_id bigint(20) DEFAULT 0,
            client_name varchar(255) NOT NULL,
            client_email varchar(255) NOT NULL,
            requirement_title varchar(255) NOT NULL,
            requirement_details text NOT NULL,
            status varchar(50) DEFAULT 'New',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        dbDelta($sql_req);

        self::seed_initial_data();
    }

    public static function seed_initial_data() {
        global $wpdb;
        $table_name = self::get_projects_table();

        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        if ($count > 0) {
            return;
        }

        $initial_projects = array(
            array('project_name' => 'Canopy (Jan Salary)', 'client' => 'Canopy', 'source' => 'Salary', 'lead_owner' => 'Sumayah', 'amount' => 248000, 'expenses' => 0, 'paid' => 248000, 'due' => 0, 'month' => 'January', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Twills Original', 'client' => 'Twills', 'source' => 'Rifat', 'lead_owner' => 'Sumayah', 'amount' => 40000, 'expenses' => 0, 'paid' => 30000, 'due' => 10000, 'month' => 'January', 'status' => 'Partial', 'progress_percent' => 85, 'milestone_stage' => 'Final QA', 'distributed' => 'Yes'),
            array('project_name' => 'ZUQO', 'client' => 'ZUQO', 'source' => 'Tarikul Islam', 'lead_owner' => 'Sumayah', 'amount' => 6000, 'expenses' => 0, 'paid' => 6000, 'due' => 0, 'month' => 'January', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Car4mates', 'client' => 'Car4mates', 'source' => 'Fayaz Bhai', 'lead_owner' => 'Sumayah', 'amount' => 9000, 'expenses' => 0, 'paid' => 9000, 'due' => 0, 'month' => 'January', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'DoelCell', 'client' => 'DoelCell', 'source' => 'Fayaz Bhai', 'lead_owner' => 'Sumayah', 'amount' => 15000, 'expenses' => 0, 'paid' => 15000, 'due' => 0, 'month' => 'January', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'MarajMedia', 'client' => 'MarajMedia', 'source' => 'Fayaz Bhai', 'lead_owner' => 'Sumayah', 'amount' => 15000, 'expenses' => 0, 'paid' => 15000, 'due' => 0, 'month' => 'January', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Property', 'client' => 'Property Client', 'source' => 'Fayaz Bhai', 'lead_owner' => 'Sumayah', 'amount' => 0, 'expenses' => 0, 'paid' => 0, 'due' => 0, 'month' => 'January', 'status' => 'Pending', 'progress_percent' => 10, 'milestone_stage' => 'Requirement Gathering', 'distributed' => 'No'),
            array('project_name' => 'Thank You Page', 'client' => 'Thank You Page', 'source' => 'Fayaz Bhai', 'lead_owner' => 'Sumayah', 'amount' => 3500, 'expenses' => 0, 'paid' => 0, 'due' => 3500, 'month' => 'January', 'status' => 'Pending', 'progress_percent' => 40, 'milestone_stage' => 'Design', 'distributed' => 'No'),
            array('project_name' => 'Head Warrior', 'client' => 'Head Warrior', 'source' => 'Sumayah', 'lead_owner' => 'Sumayah', 'amount' => 12400, 'expenses' => 0, 'paid' => 0, 'due' => 12400, 'month' => 'February', 'status' => 'Pending', 'progress_percent' => 20, 'milestone_stage' => 'Prototyping', 'distributed' => 'No'),
            array('project_name' => 'Aion Studio', 'client' => 'Aion Studio', 'source' => 'Fayaz Bhai', 'lead_owner' => 'Sumayah', 'amount' => 15000, 'expenses' => 0, 'paid' => 7500, 'due' => 7500, 'month' => 'February', 'status' => 'Partial', 'progress_percent' => 70, 'milestone_stage' => 'Development', 'distributed' => 'Yes'),
            array('project_name' => 'Canopy (Feb Salary)', 'client' => 'Canopy', 'source' => 'Salary', 'lead_owner' => 'Sumayah', 'amount' => 248000, 'expenses' => 0, 'paid' => 248000, 'due' => 0, 'month' => 'February', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Lab Creation', 'client' => 'Lab Creation', 'source' => 'Fayaz', 'lead_owner' => 'Sumayah', 'amount' => 35000, 'expenses' => 0, 'paid' => 35000, 'due' => 0, 'month' => 'March', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'SAMAF', 'client' => 'SAMAF', 'source' => 'Fayaz', 'lead_owner' => 'Sumayah', 'amount' => 3650, 'expenses' => 0, 'paid' => 3650, 'due' => 0, 'month' => 'February', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Gemglow Landing Page', 'client' => 'Gemglow', 'source' => 'Fayaz', 'lead_owner' => 'Sumayah', 'amount' => 5000, 'expenses' => 0, 'paid' => 5000, 'due' => 0, 'month' => 'February', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'thepush.agency', 'client' => 'thepush.agency', 'source' => 'Walid', 'lead_owner' => 'Sumayah', 'amount' => 17892, 'expenses' => 0, 'paid' => 0, 'due' => 17892, 'month' => 'March', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'No'),
            array('project_name' => 'Voltas\' Spa', 'client' => 'Voltas\' Spa', 'source' => 'Fayaz', 'lead_owner' => 'Sumayah', 'amount' => 30000, 'expenses' => 0, 'paid' => 15000, 'due' => 15000, 'month' => 'March', 'status' => 'Partial', 'progress_percent' => 60, 'milestone_stage' => 'Development', 'distributed' => 'No'),
            array('project_name' => 'Claud Tools (Expense)', 'client' => 'Internal Operations', 'source' => 'Firoz', 'lead_owner' => 'Firoz', 'amount' => 0, 'expenses' => 2454, 'paid' => 0, 'due' => 0, 'month' => 'March', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'No'),
            array('project_name' => 'Tax Support', 'client' => 'Tax Client', 'source' => 'Firoz', 'lead_owner' => 'Firoz', 'amount' => 49000, 'expenses' => 0, 'paid' => 49000, 'due' => 0, 'month' => 'March', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Apu Bhiya CM 20%', 'client' => 'Apu Bhiya', 'source' => 'Apu', 'lead_owner' => 'Sumayah', 'amount' => 12260, 'expenses' => 0, 'paid' => 12260, 'due' => 0, 'month' => 'March', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Simpli Basic Monthly', 'client' => 'Simpli Basic', 'source' => 'Fayaz Bhai', 'lead_owner' => 'Sumayah', 'amount' => 19000, 'expenses' => 0, 'paid' => 19000, 'due' => 0, 'month' => 'March', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Canopy (March Salary)', 'client' => 'Canopy', 'source' => 'Salary', 'lead_owner' => 'Sumayah', 'amount' => 248000, 'expenses' => 0, 'paid' => 248000, 'due' => 0, 'month' => 'March', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Canopy (April Salary)', 'client' => 'Canopy', 'source' => 'Salary', 'lead_owner' => 'Sumayah', 'amount' => 248000, 'expenses' => 0, 'paid' => 248000, 'due' => 0, 'month' => 'April', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'Yes'),
            array('project_name' => 'Hosting for 4 Years (Expense)', 'client' => 'Internal Infrastructure', 'source' => 'Infrastructure', 'lead_owner' => 'Sumayah', 'amount' => 0, 'expenses' => 14266, 'paid' => 0, 'due' => 0, 'month' => 'May', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'No'),
            array('project_name' => 'MKPmc', 'client' => 'MKPmc', 'source' => 'Fayaz', 'lead_owner' => 'Sumayah', 'amount' => 35000, 'expenses' => 0, 'paid' => 35000, 'due' => 0, 'month' => 'May', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'No'),
            array('project_name' => 'QII', 'client' => 'QII', 'source' => 'Fayaz', 'lead_owner' => 'Sumayah', 'amount' => 30000, 'expenses' => 0, 'paid' => 30000, 'due' => 0, 'month' => 'July', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'No'),
            array('project_name' => 'TSM', 'client' => 'TSM UK', 'source' => 'Anzar UK', 'lead_owner' => 'Sumayah', 'amount' => 42000, 'expenses' => 0, 'paid' => 42000, 'due' => 0, 'month' => 'July', 'status' => 'Paid', 'progress_percent' => 100, 'milestone_stage' => 'Completed', 'distributed' => 'No'),
            array('project_name' => 'Arman Website Transfer', 'client' => 'Arman', 'source' => 'Abrar', 'lead_owner' => 'Sumayah', 'amount' => 30000, 'expenses' => 10000, 'paid' => 0, 'due' => 30000, 'month' => 'July', 'status' => 'Pending', 'progress_percent' => 50, 'milestone_stage' => 'Migration', 'distributed' => 'No'),
            array('project_name' => 'Painting Site', 'client' => 'Painting Client', 'source' => 'Fayaz', 'lead_owner' => 'Sumayah', 'amount' => 7000, 'expenses' => 0, 'paid' => 0, 'due' => 7000, 'month' => 'July', 'status' => 'Pending', 'progress_percent' => 35, 'milestone_stage' => 'Design', 'distributed' => 'No'),
            array('project_name' => 'Comete', 'client' => 'Comete', 'source' => 'Zahid', 'lead_owner' => 'Sumayah', 'amount' => 24000, 'expenses' => 0, 'paid' => 0, 'due' => 24000, 'month' => 'July', 'status' => 'Pending', 'progress_percent' => 45, 'milestone_stage' => 'Development', 'distributed' => 'No')
        );

        foreach ($initial_projects as $proj) {
            $wpdb->insert($table_name, $proj);
        }
    }
}

// -----------------------------------------------------------------------------
// 2. ADMIN CONTROLLER CLASS
// -----------------------------------------------------------------------------
class Xixify_Partnership_Admin {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_menu'));
        add_action('admin_post_xixify_save_project', array(__CLASS__, 'handle_save_project'));
        add_action('admin_post_xixify_delete_project', array(__CLASS__, 'handle_delete_project'));
        add_action('admin_post_xixify_update_requirement_status', array(__CLASS__, 'handle_requirement_status'));
        add_action('admin_head', array(__CLASS__, 'render_admin_styles_and_scripts'));
    }

    public static function register_menu() {
        add_menu_page(
            __('Xixify Accounts', 'xixify-partnership-portal'),
            __('Xixify Accounts', 'xixify-partnership-portal'),
            'manage_options',
            'xixify-partnership-portal',
            array(__CLASS__, 'render_admin_page'),
            'dashicons-chart-area',
            30
        );
    }

    public static function render_admin_styles_and_scripts() {
        if (isset($_GET['page']) && $_GET['page'] === 'xixify-partnership-portal') {
            ?>
            <style>
            .xixify-admin-wrap { margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
            .xixify-metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin: 20px 0 28px 0; }
            .xixify-metric-card { background: #fff; border: 1px solid #c3c4c7; border-radius: 8px; padding: 16px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
            .xixify-metric-card.highlight { border-left: 4px solid #2271b1; background: #f0f6fc; }
            .metric-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #646970; margin-bottom: 6px; }
            .metric-num { font-size: 20px; font-weight: 700; color: #1d2327; }
            .text-danger { color: #d9534f; } .text-success { color: #5cb85c; } .text-warning { color: #f0ad4e; }
            .xixify-form-box { background: #fff; border: 1px solid #c3c4c7; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
            .xixify-form-box h2 { margin-top: 0; font-size: 16px; font-weight: 700; border-bottom: 1px solid #f0f0f1; padding-bottom: 10px; }
            .xixify-form-row { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 12px; }
            .form-field { flex: 1; min-width: 180px; }
            .form-field label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: #2c3338; }
            .form-field input, .form-field select, .form-field textarea { width: 100%; padding: 6px 10px; }
            .xixify-live-summary { background: #f0f6fc; border: 1px solid #c5d9ed; border-radius: 6px; padding: 10px 16px; font-size: 13px; margin-top: 12px; color: #1d2327; }
            .xixify-progress-bar-wrap { background: #dcdcde; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 4px; }
            .xixify-progress-bar { background: #2271b1; height: 100%; }
            .xixify-badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; }
            .badge-paid { background: #e6f4ea; color: #137333; }
            .badge-partial { background: #fef7e0; color: #b06000; }
            .badge-pending { background: #fce8e6; color: #c5221f; }
            </style>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const statusSelect = document.getElementById('xixify-status-select');
                const amountInput = document.getElementById('xixify-amount-input');
                const expensesInput = document.getElementById('xixify-expenses-input');
                const paidWrapper = document.getElementById('xixify-paid-wrapper');
                const paidInput = document.getElementById('xixify-paid-input');
                const duePreview = document.getElementById('xixify-due-preview');
                const netPreview = document.getElementById('xixify-net-preview');
                const partnerPreview = document.getElementById('xixify-partner-preview');

                function calculateMath() {
                    if (!amountInput) return;
                    const amount = parseFloat(amountInput.value) || 0;
                    const expenses = parseFloat(expensesInput ? expensesInput.value : 0) || 0;
                    const status = statusSelect ? statusSelect.value : 'Pending';
                    let paid = parseFloat(paidInput ? paidInput.value : 0) || 0;

                    if (status === 'Paid') {
                        paid = amount;
                        if (paidInput) paidInput.value = amount;
                        if (paidWrapper) paidWrapper.style.display = 'none';
                    } else if (status === 'Pending') {
                        paid = 0;
                        if (paidInput) paidInput.value = 0;
                        if (paidWrapper) paidWrapper.style.display = 'none';
                    } else if (status === 'Partial') {
                        if (paidWrapper) paidWrapper.style.display = 'block';
                    }

                    const due = Math.max(0, amount - paid);
                    const net = amount - expenses;
                    const partnerEach = Math.round(net / 2);

                    if (duePreview) duePreview.textContent = '৳ ' + due.toLocaleString();
                    if (netPreview) netPreview.textContent = '৳ ' + net.toLocaleString();
                    if (partnerPreview) partnerPreview.textContent = '৳ ' + partnerEach.toLocaleString() + ' / partner';
                }

                if (statusSelect) statusSelect.addEventListener('change', calculateMath);
                if (amountInput) amountInput.addEventListener('input', calculateMath);
                if (expensesInput) expensesInput.addEventListener('input', calculateMath);
                if (paidInput) paidInput.addEventListener('input', calculateMath);
                calculateMath();
            });
            </script>
            <?php
        }
    }

    public static function handle_save_project() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'xixify-partnership-portal'));
        }

        check_admin_referer('xixify_save_project_action', 'xixify_nonce');

        global $wpdb;
        $table_name = Xixify_Partnership_DB::get_projects_table();

        $project_id       = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
        $project_name     = sanitize_text_field($_POST['project_name']);
        $client           = sanitize_text_field($_POST['client']);
        $client_email     = sanitize_email($_POST['client_email']);
        $source           = sanitize_text_field($_POST['source']);
        $lead_owner       = sanitize_text_field($_POST['lead_owner']);
        $amount           = floatval($_POST['amount']);
        $expenses         = floatval($_POST['expenses']);
        $status           = sanitize_text_field($_POST['status']);
        $progress_percent = intval($_POST['progress_percent']);
        $milestone_stage  = sanitize_text_field($_POST['milestone_stage']);
        $month            = sanitize_text_field($_POST['month']);
        $description      = sanitize_textarea_field($_POST['description']);

        $paid = 0;
        if ($status === 'Paid') {
            $paid = $amount;
        } elseif ($status === 'Pending') {
            $paid = 0;
        } elseif ($status === 'Partial') {
            $paid = floatval($_POST['paid']);
        }

        $due = max(0, $amount - $paid);

        $data = array(
            'project_name'     => $project_name,
            'client'           => $client,
            'client_email'     => $client_email,
            'source'           => $source,
            'lead_owner'       => $lead_owner,
            'amount'           => $amount,
            'expenses'         => $expenses,
            'paid'             => $paid,
            'due'              => $due,
            'month'            => $month,
            'status'           => $status,
            'progress_percent' => $progress_percent,
            'milestone_stage'  => $milestone_stage,
            'description'      => $description,
        );

        if ($project_id > 0) {
            $wpdb->update($table_name, $data, array('id' => $project_id));
        } else {
            $wpdb->insert($table_name, $data);
        }

        wp_redirect(admin_url('admin.php?page=xixify-partnership-portal&msg=saved'));
        exit;
    }

    public static function handle_delete_project() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'xixify-partnership-portal'));
        }

        $project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
        check_admin_referer('xixify_delete_project_' . $project_id, 'xixify_nonce');

        if ($project_id > 0) {
            global $wpdb;
            $table_name = Xixify_Partnership_DB::get_projects_table();
            $wpdb->delete($table_name, array('id' => $project_id));
        }

        wp_redirect(admin_url('admin.php?page=xixify-partnership-portal&msg=deleted'));
        exit;
    }

    public static function handle_requirement_status() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'xixify-partnership-portal'));
        }

        $req_id = isset($_GET['req_id']) ? intval($_GET['req_id']) : 0;
        $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'Reviewed';

        if ($req_id > 0) {
            global $wpdb;
            $table_req = Xixify_Partnership_DB::get_requirements_table();
            $wpdb->update($table_req, array('status' => $status), array('id' => $req_id));
        }

        wp_redirect(admin_url('admin.php?page=xixify-partnership-portal&tab=requirements&msg=req_updated'));
        exit;
    }

    public static function render_admin_page() {
        global $wpdb;
        $table_projects = Xixify_Partnership_DB::get_projects_table();
        $table_req = Xixify_Partnership_DB::get_requirements_table();

        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'projects';
        $projects = $wpdb->get_results("SELECT * FROM $table_projects ORDER BY id DESC");
        $requirements = $wpdb->get_results("SELECT * FROM $table_req ORDER BY id DESC");

        $gross = 0; $expenses = 0; $paid = 0; $dues = 0;
        foreach ($projects as $p) {
            $gross += floatval($p->amount);
            $expenses += floatval($p->expenses);
            $paid += floatval($p->paid);
            $dues += floatval($p->due);
        }

        $net = $gross - $expenses;
        $partner_share = round($net / 2, 2);

        $editing_id = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : 0;
        $edit_proj = null;
        if ($editing_id > 0) {
            $edit_proj = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_projects WHERE id = %d", $editing_id));
        }
        ?>
        <div class="wrap xixify-admin-wrap">
            <h1 class="wp-heading-inline">Xixify × DevPify Agency Management</h1>
            <p class="description">Senior Accounts, Dynamic Payments, Project Milestones & Client Requirements Center.</p>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'saved'): ?>
                <div class="notice notice-success is-dismissible"><p>Project & Payment details saved successfully!</p></div>
            <?php endif; ?>

            <!-- Navigation Tabs -->
            <h2 class="nav-tab-wrapper" style="margin-top: 15px;">
                <a href="<?php echo esc_url(admin_url('admin.php?page=xixify-partnership-portal&tab=projects')); ?>" class="nav-tab <?php echo $active_tab === 'projects' ? 'nav-tab-active' : ''; ?>">Projects & Financials</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=xixify-partnership-portal&tab=requirements')); ?>" class="nav-tab <?php echo $active_tab === 'requirements' ? 'nav-tab-active' : ''; ?>">Client Requirement Requests (<?php echo count($requirements); ?>)</a>
            </h2>

            <?php if ($active_tab === 'projects'): ?>
                <!-- Metrics Grid -->
                <div class="xixify-metrics-grid">
                    <div class="xixify-metric-card">
                        <span class="metric-label">Gross Revenue</span>
                        <span class="metric-num">৳ <?php echo number_format($gross); ?></span>
                    </div>
                    <div class="xixify-metric-card">
                        <span class="metric-label">Expenses</span>
                        <span class="metric-num text-danger">৳ <?php echo number_format($expenses); ?></span>
                    </div>
                    <div class="xixify-metric-card">
                        <span class="metric-label">Net Profit</span>
                        <span class="metric-num text-success">৳ <?php echo number_format($net); ?></span>
                    </div>
                    <div class="xixify-metric-card highlight">
                        <span class="metric-label">Sumayah Share (50%)</span>
                        <span class="metric-num">৳ <?php echo number_format($partner_share); ?></span>
                    </div>
                    <div class="xixify-metric-card highlight">
                        <span class="metric-label">Firoz Share (50%)</span>
                        <span class="metric-num">৳ <?php echo number_format($partner_share); ?></span>
                    </div>
                    <div class="xixify-metric-card">
                        <span class="metric-label">Outstanding Dues</span>
                        <span class="metric-num text-warning">৳ <?php echo number_format($dues); ?></span>
                    </div>
                </div>

                <!-- Project Form Box -->
                <div class="xixify-form-box">
                    <h2><?php echo $edit_proj ? 'Edit Project & Payment Details' : 'Add New Project'; ?></h2>
                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <input type="hidden" name="action" value="xixify_save_project">
                        <input type="hidden" name="project_id" value="<?php echo $edit_proj ? esc_attr($edit_proj->id) : 0; ?>">
                        <?php wp_nonce_field('xixify_save_project_action', 'xixify_nonce'); ?>

                        <div class="xixify-form-row">
                            <div class="form-field">
                                <label>Project Name</label>
                                <input type="text" name="project_name" required value="<?php echo $edit_proj ? esc_attr($edit_proj->project_name) : ''; ?>" placeholder="e.g. Canopy, Twills">
                            </div>
                            <div class="form-field">
                                <label>Client Name</label>
                                <input type="text" name="client" required value="<?php echo $edit_proj ? esc_attr($edit_proj->client) : ''; ?>" placeholder="e.g. Canopy, Fayaz Bhai">
                            </div>
                            <div class="form-field">
                                <label>Client Email (for portal access)</label>
                                <input type="email" name="client_email" value="<?php echo $edit_proj ? esc_attr($edit_proj->client_email) : ''; ?>" placeholder="client@example.com">
                            </div>
                            <div class="form-field">
                                <label>Lead Owner</label>
                                <select name="lead_owner">
                                    <option value="Sumayah" <?php selected($edit_proj ? $edit_proj->lead_owner : '', 'Sumayah'); ?>>Sumayah</option>
                                    <option value="Firoz" <?php selected($edit_proj ? $edit_proj->lead_owner : '', 'Firoz'); ?>>Firoz</option>
                                </select>
                            </div>
                        </div>

                        <div class="xixify-form-row">
                            <div class="form-field">
                                <label>Total Billed Amount (BDT)</label>
                                <input type="number" step="0.01" id="xixify-amount-input" name="amount" required value="<?php echo $edit_proj ? esc_attr($edit_proj->amount) : '0'; ?>">
                            </div>
                            <div class="form-field">
                                <label>Expenses (BDT)</label>
                                <input type="number" step="0.01" id="xixify-expenses-input" name="expenses" value="<?php echo $edit_proj ? esc_attr($edit_proj->expenses) : '0'; ?>">
                            </div>
                            <div class="form-field">
                                <label>Payment Status</label>
                                <select id="xixify-status-select" name="status">
                                    <option value="Pending" <?php selected($edit_proj ? $edit_proj->status : '', 'Pending'); ?>>Pending (Zero Paid)</option>
                                    <option value="Partial" <?php selected($edit_proj ? $edit_proj->status : '', 'Partial'); ?>>Partial (Advance Paid)</option>
                                    <option value="Paid" <?php selected($edit_proj ? $edit_proj->status : '', 'Paid'); ?>>Paid (Fully Completed)</option>
                                </select>
                            </div>
                            <div class="form-field" id="xixify-paid-wrapper" style="<?php echo ($edit_proj && $edit_proj->status === 'Partial') ? 'display:block;' : 'display:none;'; ?>">
                                <label>Advance / Partial Paid Amount (BDT)</label>
                                <input type="number" step="0.01" id="xixify-paid-input" name="paid" value="<?php echo $edit_proj ? esc_attr($edit_proj->paid) : '0'; ?>">
                            </div>
                        </div>

                        <div class="xixify-form-row">
                            <div class="form-field">
                                <label>Milestone Stage</label>
                                <select name="milestone_stage">
                                    <option value="Requirement Gathering" <?php selected($edit_proj ? $edit_proj->milestone_stage : '', 'Requirement Gathering'); ?>>Requirement Gathering</option>
                                    <option value="Design & Wireframing" <?php selected($edit_proj ? $edit_proj->milestone_stage : '', 'Design & Wireframing'); ?>>Design & Wireframing</option>
                                    <option value="Development" <?php selected($edit_proj ? $edit_proj->milestone_stage : '', 'Development'); ?>>Development</option>
                                    <option value="QA & Testing" <?php selected($edit_proj ? $edit_proj->milestone_stage : '', 'QA & Testing'); ?>>QA & Testing</option>
                                    <option value="Completed" <?php selected($edit_proj ? $edit_proj->milestone_stage : '', 'Completed'); ?>>Completed & Live</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Progress Percentage (0% - 100%)</label>
                                <input type="number" min="0" max="100" name="progress_percent" value="<?php echo $edit_proj ? esc_attr($edit_proj->progress_percent) : '0'; ?>">
                            </div>
                            <div class="form-field">
                                <label>Billing Month</label>
                                <select name="month">
                                    <?php 
                                    $months = array('January','February','March','April','May','June','July','August','September','October','November','December');
                                    foreach ($months as $m) {
                                        $sel = ($edit_proj && $edit_proj->month === $m) ? 'selected' : '';
                                        echo "<option value='$m' $sel>$m</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Referrer / Source</label>
                                <input type="text" name="source" value="<?php echo $edit_proj ? esc_attr($edit_proj->source) : ''; ?>" placeholder="e.g. Fayaz Bhai">
                            </div>
                        </div>

                        <!-- Live Math Summary Box -->
                        <div class="xixify-live-summary">
                            <span>Computed Due: <strong id="xixify-due-preview">৳ 0</strong></span> | 
                            <span>Net Profit: <strong id="xixify-net-preview">৳ 0</strong></span> | 
                            <span>Partner Split: <strong id="xixify-partner-preview">৳ 0 / partner</strong></span>
                        </div>

                        <p class="submit" style="margin-top:15px;">
                            <input type="submit" class="button button-primary" value="<?php echo $edit_proj ? 'Update Project' : 'Save Project'; ?>">
                            <?php if ($edit_proj): ?>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=xixify-partnership-portal')); ?>" class="button">Cancel Edit</a>
                            <?php endif; ?>
                        </p>
                    </form>
                </div>

                <!-- Projects Datatable -->
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Client</th>
                            <th>Progress</th>
                            <th>Billed Amount</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $p): ?>
                            <?php 
                            $delete_url = wp_nonce_url(admin_url('admin-post.php?action=xixify_delete_project&project_id=' . $p->id), 'xixify_delete_project_' . $p->id, 'xixify_nonce');
                            $edit_url = admin_url('admin.php?page=xixify-partnership-portal&edit_id=' . $p->id);
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html($p->project_name); ?></strong><br>
                                    <small style="color:#646970;"><?php echo esc_html($p->milestone_stage); ?></small>
                                </td>
                                <td><?php echo esc_html($p->client); ?></td>
                                <td>
                                    <div class="xixify-progress-bar-wrap">
                                        <div class="xixify-progress-bar" style="width:<?php echo esc_attr($p->progress_percent); ?>%;"></div>
                                    </div>
                                    <small><?php echo esc_html($p->progress_percent); ?>%</small>
                                </td>
                                <td>৳ <?php echo number_format($p->amount); ?></td>
                                <td>৳ <?php echo number_format($p->paid); ?></td>
                                <td><span style="color:<?php echo $p->due > 0 ? '#d9534f' : '#5cb85c'; ?>;">৳ <?php echo number_format($p->due); ?></span></td>
                                <td><span class="xixify-badge badge-<?php echo strtolower($p->status); ?>"><?php echo esc_html($p->status); ?></span></td>
                                <td>
                                    <a href="<?php echo esc_url($edit_url); ?>" class="button button-small">Edit</a>
                                    <a href="<?php echo esc_url($delete_url); ?>" class="button button-small button-link-delete" onclick="return confirm('Delete this project?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php elseif ($active_tab === 'requirements'): ?>
                <!-- Client Requirements List -->
                <div class="xixify-form-box">
                    <h2>Client Requirements & Feedback Submissions</h2>
                    <p>Requirements submitted by clients via the front-end portal shortcode.</p>

                    <table class="wp-list-table widefat fixed striped" style="margin-top:15px;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Client Name</th>
                                <th>Client Email</th>
                                <th>Title</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($requirements)): ?>
                                <tr><td colspan="7">No client requirements submitted yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($requirements as $r): ?>
                                    <tr>
                                        <td><?php echo esc_html(date('M d, Y', strtotime($r->created_at))); ?></td>
                                        <td><strong><?php echo esc_html($r->client_name); ?></strong></td>
                                        <td><?php echo esc_html($r->client_email); ?></td>
                                        <td><strong><?php echo esc_html($r->requirement_title); ?></strong></td>
                                        <td><?php echo esc_html($r->requirement_details); ?></td>
                                        <td><span class="xixify-badge badge-<?php echo strtolower($r->status); ?>"><?php echo esc_html($r->status); ?></span></td>
                                        <td>
                                            <?php if ($r->status !== 'Completed'): ?>
                                                <a href="<?php echo esc_url(admin_url('admin-post.php?action=xixify_update_requirement_status&req_id=' . $r->id . '&status=Completed')); ?>" class="button button-small button-primary">Mark Completed</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

// -----------------------------------------------------------------------------
// 3. FRONT-END SHORTCODE PORTAL CLASS
// -----------------------------------------------------------------------------
class Xixify_Partnership_Shortcode {

    public static function init() {
        add_shortcode('xixify_partnership_portal', array(__CLASS__, 'render_shortcode'));
        add_action('admin_post_nopriv_xixify_submit_client_requirement', array(__CLASS__, 'handle_requirement_submission'));
        add_action('admin_post_xixify_submit_client_requirement', array(__CLASS__, 'handle_requirement_submission'));
    }

    public static function handle_requirement_submission() {
        if (isset($_POST['xixify_req_nonce']) && wp_verify_nonce($_POST['xixify_req_nonce'], 'xixify_submit_req_action')) {
            global $wpdb;
            $table_req = Xixify_Partnership_DB::get_requirements_table();

            $client_name  = sanitize_text_field($_POST['client_name']);
            $client_email = sanitize_email($_POST['client_email']);
            $title        = sanitize_text_field($_POST['requirement_title']);
            $details      = sanitize_textarea_field($_POST['requirement_details']);

            $wpdb->insert($table_req, array(
                'client_name'        => $client_name,
                'client_email'       => $client_email,
                'requirement_title'  => $title,
                'requirement_details'=> $details,
                'status'             => 'New'
            ));

            $redirect = wp_get_referer() ? wp_get_referer() : home_url();
            wp_redirect(add_query_arg('xixify_req_msg', 'submitted', $redirect));
            exit;
        }
    }

    public static function render_shortcode($atts) {
        global $wpdb;
        $table_projects = Xixify_Partnership_DB::get_projects_table();

        $active_projects = $wpdb->get_results("SELECT * FROM $table_projects WHERE status != 'Paid' AND client_visible = 1 ORDER BY id DESC");
        $completed_projects = $wpdb->get_results("SELECT * FROM $table_projects WHERE status = 'Paid' AND client_visible = 1 ORDER BY id DESC");

        ob_start();
        ?>
        <style>
        .xixify-portal-wrapper { font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #090A0F; color: #F3F4F6; border: 1px solid #232738; border-radius: 12px; padding: 28px; margin: 24px 0; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5); }
        .xixify-portal-header { margin-bottom: 24px; }
        .portal-brand-title { font-size: 22px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.02em; }
        .xixify-portal-header p { color: #9CA3AF; font-size: 14px; margin-top: 4px; }
        .xixify-portal-alert.alert-success { background: rgba(16, 185, 129, 0.15); border: 1px solid #10B981; color: #34D399; padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; }
        .xixify-portal-tabs { display: flex; gap: 8px; border-bottom: 1px solid #232738; padding-bottom: 12px; margin-bottom: 24px; }
        .portal-tab-btn { background: #12141D; border: 1px solid #232738; color: #9CA3AF; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
        .portal-tab-btn:hover, .portal-tab-btn.active { background: #6366F1; color: #FFFFFF; border-color: #6366F1; }
        .xixify-tab-content { display: none; }
        .xixify-tab-content.active { display: block; }
        .xixify-empty-msg { color: #9CA3AF; text-align: center; padding: 30px; }
        .xixify-project-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .xixify-card { background: #12141D; border: 1px solid #232738; border-radius: 10px; padding: 20px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .card-header h4 { margin: 0; font-size: 16px; color: #FFFFFF; }
        .card-client, .card-milestone { font-size: 13px; color: #9CA3AF; margin-bottom: 6px; }
        .progress-container { margin: 16px 0; }
        .progress-label { font-size: 12px; color: #9CA3AF; margin-bottom: 6px; font-weight: 500; }
        .progress-bar-bg { background: #232738; height: 8px; border-radius: 4px; overflow: hidden; }
        .progress-bar-fill { background: linear-gradient(90deg, #6366F1, #A855F7); height: 100%; border-radius: 4px; }
        .invoice-box { background: #0D0E15; border: 1px solid #232738; border-radius: 8px; padding: 12px; display: flex; justify-content: space-between; font-size: 12px; margin-top: 14px; }
        .invoice-item { color: #9CA3AF; } .invoice-item span { color: #F3F4F6; font-weight: 600; } .invoice-item.due strong { color: #F87171; }
        .xixify-form-card { background: #12141D; border: 1px solid #232738; border-radius: 10px; padding: 24px; max-width: 600px; margin: 0 auto; }
        .xixify-form-card h4 { margin-top: 0; font-size: 18px; color: #FFF; }
        .xixify-form-card p { color: #9CA3AF; font-size: 13px; margin-bottom: 20px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #9CA3AF; margin-bottom: 6px; }
        .form-group input, .form-group textarea { width: 100%; box-sizing: border-box; background: #090A0F; border: 1px solid #232738; border-radius: 6px; padding: 10px 14px; color: #F3F4F6; font-size: 14px; outline: none; }
        .form-group input:focus, .form-group textarea:focus { border-color: #6366F1; }
        .btn-submit { background: #6366F1; color: #FFF; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .xixify-badge { padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge-paid { background: rgba(16, 185, 129, 0.15); color: #34D399; }
        .badge-partial { background: rgba(245, 158, 11, 0.15); color: #FBBF24; }
        .badge-pending { background: rgba(239, 68, 68, 0.15); color: #F87171; }
        </style>

        <div class="xixify-portal-wrapper">
            <div class="xixify-portal-header">
                <div class="portal-brand-title">Xixify Project Portal</div>
                <p>Track project progress, milestone completion, invoice balances, or submit new project requirements.</p>
            </div>

            <?php if (isset($_GET['xixify_req_msg']) && $_GET['xixify_req_msg'] === 'submitted'): ?>
                <div class="xixify-portal-alert alert-success">
                    ✓ Your requirement request has been submitted to the engineering team! We will review it shortly.
                </div>
            <?php endif; ?>

            <!-- Portal Tabs -->
            <div class="xixify-portal-tabs">
                <button class="portal-tab-btn active" onclick="switchXixifyTab(event, 'active-projects')">Active Projects (<?php echo count($active_projects); ?>)</button>
                <button class="portal-tab-btn" onclick="switchXixifyTab(event, 'completed-projects')">Past Completed Projects (<?php echo count($completed_projects); ?>)</button>
                <button class="portal-tab-btn" onclick="switchXixifyTab(event, 'submit-requirement')">+ Submit New Requirement</button>
            </div>

            <!-- Tab 1: Active Projects -->
            <div id="active-projects" class="xixify-tab-content active">
                <?php if (empty($active_projects)): ?>
                    <p class="xixify-empty-msg">No active ongoing projects at the moment.</p>
                <?php else: ?>
                    <div class="xixify-project-cards">
                        <?php foreach ($active_projects as $p): ?>
                            <div class="xixify-card">
                                <div class="card-header">
                                    <h4><?php echo esc_html($p->project_name); ?></h4>
                                    <span class="xixify-badge badge-<?php echo strtolower($p->status); ?>"><?php echo esc_html($p->status); ?></span>
                                </div>
                                <div class="card-body">
                                    <p class="card-client">Client: <strong><?php echo esc_html($p->client); ?></strong></p>
                                    <p class="card-milestone">Current Milestone: <strong><?php echo esc_html($p->milestone_stage); ?></strong></p>

                                    <!-- Progress Bar -->
                                    <div class="progress-container">
                                        <div class="progress-label">Overall Progress: <?php echo esc_html($p->progress_percent); ?>%</div>
                                        <div class="progress-bar-bg">
                                            <div class="progress-bar-fill" style="width: <?php echo esc_attr($p->progress_percent); ?>%;"></div>
                                        </div>
                                    </div>

                                    <!-- Financial Invoice Box -->
                                    <div class="invoice-box">
                                        <div class="invoice-item">Billed: <span>৳ <?php echo number_format($p->amount); ?></span></div>
                                        <div class="invoice-item">Paid: <span>৳ <?php echo number_format($p->paid); ?></span></div>
                                        <div class="invoice-item due">Due: <strong>৳ <?php echo number_format($p->due); ?></strong></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab 2: Past Completed Projects -->
            <div id="completed-projects" class="xixify-tab-content">
                <?php if (empty($completed_projects)): ?>
                    <p class="xixify-empty-msg">No past completed projects recorded.</p>
                <?php else: ?>
                    <div class="xixify-project-cards">
                        <?php foreach ($completed_projects as $p): ?>
                            <div class="xixify-card">
                                <div class="card-header">
                                    <h4><?php echo esc_html($p->project_name); ?></h4>
                                    <span class="xixify-badge badge-paid">Fully Paid</span>
                                </div>
                                <div class="card-body">
                                    <p class="card-client">Client: <strong><?php echo esc_html($p->client); ?></strong></p>
                                    <p class="card-milestone">Billing Month: <strong><?php echo esc_html($p->month); ?></strong></p>
                                    <div class="invoice-box">
                                        <div class="invoice-item">Total Billed: <span>৳ <?php echo number_format($p->amount); ?></span></div>
                                        <div class="invoice-item due">Due: <strong>৳ 0</strong></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab 3: Submit New Requirement -->
            <div id="submit-requirement" class="xixify-tab-content">
                <div class="xixify-form-card">
                    <h4>Submit New Requirement or Feature Request</h4>
                    <p>Have a new request, scope change, or bug update? Submit details below directly to our engineering team.</p>

                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <input type="hidden" name="action" value="xixify_submit_client_requirement">
                        <?php wp_nonce_field('xixify_submit_req_action', 'xixify_req_nonce'); ?>

                        <div class="form-group">
                            <label>Your Name</label>
                            <input type="text" name="client_name" required placeholder="e.g. Tarikul Islam">
                        </div>

                        <div class="form-group">
                            <label>Your Email Address</label>
                            <input type="email" name="client_email" required placeholder="client@example.com">
                        </div>

                        <div class="form-group">
                            <label>Requirement Title</label>
                            <input type="text" name="requirement_title" required placeholder="e.g. Add payment gateway integration">
                        </div>

                        <div class="form-group">
                            <label>Requirement Details & Scope Description</label>
                            <textarea name="requirement_details" rows="5" required placeholder="Describe the feature or updates requested..."></textarea>
                        </div>

                        <button type="submit" class="btn-submit">Submit Requirement</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
        function switchXixifyTab(evt, tabId) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("xixify-tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            tablinks = document.getElementsByClassName("portal-tab-btn");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(tabId).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
        </script>
        <?php
        return ob_get_clean();
    }
}

// -----------------------------------------------------------------------------
// 4. AUTOMATED GITHUB UPDATER CLASS
// -----------------------------------------------------------------------------
class Xixify_Partnership_Updater {

    private $file;
    private $plugin;
    private $basename;
    private $username;
    private $repository;
    private $github_response;

    public function __construct($file, $username, $repository) {
        $this->file = $file;
        $this->plugin = plugin_basename($file);
        $this->basename = current(explode('/', $this->plugin));
        $this->username = $username;
        $this->repository = $repository;

        add_action('admin_init', array($this, 'set_plugin_properties'));
    }

    public function set_plugin_properties() {
        add_filter('site_transient_update_plugins', array($this, 'modify_transient'), 10, 1);
        add_filter('plugins_api', array($this, 'plugin_popup'), 10, 3);
    }

    private function get_repository_info() {
        if (!empty($this->github_response)) {
            return;
        }

        $url = "https://api.github.com/repos/{$this->username}/{$this->repository}/releases/latest";
        $response = wp_remote_get($url, array(
            'headers' => array(
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url')
            )
        ));

        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $this->github_response = json_decode(wp_remote_retrieve_body($response), true);
        }
    }

    public function modify_transient($transient) {
        if (property_exists($transient, 'checked') && $transient->checked) {
            $this->get_repository_info();

            if (!empty($this->github_response)) {
                $plugin_data = get_plugin_data($this->file);
                $version = str_replace('v', '', $this->github_response['tag_name']);

                if (version_compare($plugin_data['Version'], $version, '<')) {
                    $package = '';

                    if (!empty($this->github_response['assets'])) {
                        foreach ($this->github_response['assets'] as $asset) {
                            if (substr($asset['name'], -4) === '.zip') {
                                $package = $asset['browser_download_url'];
                                break;
                            }
                        }
                    }

                    if (empty($package)) {
                        $package = $this->github_response['zipball_url'];
                    }

                    $obj = new stdClass();
                    $obj->slug = $this->basename;
                    $obj->new_version = $version;
                    $obj->url = $plugin_data['PluginURI'];
                    $obj->package = $package;
                    $obj->plugin = $this->plugin;

                    $transient->response[$this->plugin] = $obj;
                }
            }
        }
        return $transient;
    }

    public function plugin_popup($result, $action, $args) {
        if ($action !== 'plugin_information') {
            return $result;
        }

        if (!empty($args->slug) && $args->slug === $this->basename) {
            $this->get_repository_info();

            if (!empty($this->github_response)) {
                $plugin_data = get_plugin_data($this->file);

                $plugin = new stdClass();
                $plugin->name = $plugin_data['Name'];
                $plugin->slug = $this->basename;
                $plugin->version = str_replace('v', '', $this->github_response['tag_name']);
                $plugin->author = $plugin_data['AuthorName'];
                $plugin->homepage = $plugin_data['PluginURI'];
                $plugin->requires = $plugin_data['RequiresWP'];
                $plugin->tested = $plugin_data['TestedUpTo'];
                $plugin->downloaded = 0;
                $plugin->last_updated = $this->github_response['published_at'];
                $plugin->sections = array(
                    'description' => $plugin_data['Description'],
                    'changelog' => $this->github_response['body']
                );

                $download_link = '';
                if (!empty($this->github_response['assets'])) {
                    foreach ($this->github_response['assets'] as $asset) {
                        if (substr($asset['name'], -4) === '.zip') {
                            $download_link = $asset['browser_download_url'];
                            break;
                        }
                    }
                }
                $plugin->download_link = !empty($download_link) ? $download_link : $this->github_response['zipball_url'];

                return $plugin;
            }
        }
        return $result;
    }
}

// -----------------------------------------------------------------------------
// 5. HOOK REGISTRATIONS & INITIALIZATION
// -----------------------------------------------------------------------------
register_activation_hook(__FILE__, array('Xixify_Partnership_DB', 'create_table'));

Xixify_Partnership_Admin::init();
Xixify_Partnership_Shortcode::init();

if (is_admin()) {
    new Xixify_Partnership_Updater(__FILE__, 'xixify', 'xixify-tools');
}
