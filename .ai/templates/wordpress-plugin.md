# WordPress Plugin Tool Blueprint

## Plugin Specification Checklist
- [ ] Unique Plugin Slug & Prefix: `xixify_<tool_slug>_`
- [ ] PHP Header Metadata
- [ ] Admin Page / Settings Page Registration
- [ ] Enqueued Scripts & Styles
- [ ] AJAX / REST API Endpoints with Nonce Verification
- [ ] `readme.txt` formatted for WordPress.org standards

## File Layout
```
tools/wordpress-plugins/<plugin-slug>/
├── <plugin-slug>.php
├── readme.txt
├── README.md
├── assets/
│   ├── css/admin.css
│   └── js/admin.js
└── includes/
    ├── class-core.php
    └── class-api.php
```
