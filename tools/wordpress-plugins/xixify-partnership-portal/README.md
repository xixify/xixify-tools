# Xixify Partnership & Financial Tracker (WordPress Plugin)

A custom WordPress plugin for managing agency partnership accounts (Xixify × DevPify), tracking advance/partial payments and due amounts, calculating 50/50 profit splits between **Sumayah Islam** & **Firoz Mahamud**, and rendering client-facing project portals.

---

## 🌟 Key Features

1. **WordPress Admin Panel (`WP Admin -> Xixify Accounts`)**:
   - Live metrics: **Gross Revenue**, **Expenses**, **Net Profit**, **Sumayah Share (50%)**, **Firoz Share (50%)**, and **Outstanding Dues**.
   - Editable Add/Edit form for project amounts, advance payments, expenses, lead owners, and status.
   - Nonce-secured form submissions and data sanitization.

2. **Automatic Database Table & Pre-Seeded Data**:
   - Creates `wp_xixify_partnership_projects` on plugin activation.
   - Pre-loaded with all **29 historical project records** (`1,447,702 BDT` Gross / `1,389,982 BDT` Net).

3. **Shortcode for Client Portal**:
   - `[xixify_partnership_portal]` shortcode for displaying project updates and payment status on your WordPress website without revealing internal 50/50 profit splits.

---

## 🚀 Installation

1. Zip or copy the `xixify-partnership-portal` folder to your WordPress site's `/wp-content/plugins/` directory.
2. Go to **WP Admin -> Plugins** and click **Activate**.
3. Manage projects and payments under **Xixify Accounts** in the admin sidebar.
