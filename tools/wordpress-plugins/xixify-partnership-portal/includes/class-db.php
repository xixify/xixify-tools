<?php
/**
 * Database Handler for Xixify Partnership Portal Plugin (Senior Upgrade)
 */

if (!defined('ABSPATH')) {
    exit;
}

class Xixify_Partnership_DB {

    public static function get_projects_table() {
        global $wpdb;
        return $wpdb->prefix . 'xixify_partnership_projects';
    }

    public static function get_transactions_table() {
        global $wpdb;
        return $wpdb->prefix . 'xixify_payment_transactions';
    }

    public static function get_requirements_table() {
        global $wpdb;
        return $wpdb->prefix . 'xixify_client_requirements';
    }

    public static function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // 1. Projects Table
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

        // 2. Transactions Table
        $table_trans = self::get_transactions_table();
        $sql_trans = "CREATE TABLE $table_trans (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            project_id bigint(20) NOT NULL,
            amount decimal(10,2) NOT NULL DEFAULT 0.00,
            payment_date date DEFAULT NULL,
            payment_method varchar(100) DEFAULT 'Bank Transfer',
            note text DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        dbDelta($sql_trans);

        // 3. Client Requirements Table
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
