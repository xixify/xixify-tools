<?php
/**
 * Senior Front-End Shortcode Portal for Xixify Partnership Tracker Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

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
                    <table class="xixify-portal-table">
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Client</th>
                                <th>Month</th>
                                <th>Billed Amount</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($completed_projects as $p): ?>
                                <tr>
                                    <td><strong><?php echo esc_html($p->project_name); ?></strong></td>
                                    <td><?php echo esc_html($p->client); ?></td>
                                    <td><?php echo esc_html($p->month); ?></td>
                                    <td>৳ <?php echo number_format($p->amount); ?></td>
                                    <td><span class="xixify-badge badge-paid">Fully Paid</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
