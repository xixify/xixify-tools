<?php
/**
 * Senior Admin Controller for Xixify Partnership Tracker Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Xixify_Partnership_Admin {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_menu'));
        add_action('admin_post_xixify_save_project', array(__CLASS__, 'handle_save_project'));
        add_action('admin_post_xixify_delete_project', array(__CLASS__, 'handle_delete_project'));
        add_action('admin_post_xixify_update_requirement_status', array(__CLASS__, 'handle_requirement_status'));
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
