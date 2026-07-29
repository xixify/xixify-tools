# Xixify × DevPify Financial & Client Portal (`dev.xixify.com`)

A modern, fast, and minimalist single-page financial & project management application designed for tracking joint partnership accounts between **Xixify and DevPify**, calculating 50/50 profit splits between **Sumayah Islam** & **Firoz Mahamud**, tracking due payments, and providing a clean client-facing project view.

---

## 🌟 Key Features

1. **Financial Overview Dashboard**:
   - **Gross Revenue**: `৳ 1,447,702 BDT`
   - **Total Expenses**: `৳ 26,720 BDT`
   - **Net Profit**: `৳ 1,389,982 BDT`
   - **Sumayah Islam Share (50%)**: `৳ 694,991 BDT`
   - **Firoz Mahamud Share (50%)**: `৳ 694,991 BDT`
   - **Outstanding Dues**: Real-time total of pending receivables.

2. **Interactive Payment Tracker**:
   - Search by project, client, or lead owner.
   - Filter by status (`All`, `Paid`, `Partial`, `Pending`).
   - One-click payment resolution (`✓ Mark Paid`).
   - Instant new project modal form with automated due calculations.

3. **Client View Mode**:
   - Toggle to `View as Client` mode.
   - Hides internal 50/50 profit calculations, internal overhead expenses, and partner distribution details while presenting a clean project status interface to clients.

---

## 🚀 How to Deploy to `dev.xixify.com`

### Method 1: Static Web Hosting (Nginx / Apache / Vercel / Netlify)
1. Upload the contents of `tools/web-apps/xixify-portal/` directly to your web server root for `dev.xixify.com`.
2. Open `https://dev.xixify.com` in your browser. All data persists securely in client browser storage (`localStorage`).

### Method 2: PHP Server Deployment with REST API
1. Point your subdomain `dev.xixify.com` to the `xixify-portal/` folder on a cPanel or Nginx server running PHP 7.4+.
2. The included `api/` endpoints (`api/projects.php`, `api/auth.php`) can handle server-side storage in `api/storage.json` or connect to MySQL.

---

## 📁 File Structure

```
xixify-portal/
├── index.html     # SPA Main Portal Structure
├── style.css      # Linear/Vercel/Apple dark mode stylesheet
├── app.js         # Core application state & calculation engine
├── data.js        # Historical Google Sheet dataset
├── api/           # PHP REST API backend
│   ├── config.php
│   ├── auth.php
│   ├── projects.php
│   └── tasks.php
└── README.md      # Deployment guide
```
