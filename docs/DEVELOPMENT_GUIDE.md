# Development Guide for Xixify Tools

This guide outlines the standard development process for creating new micro-tools in the **Xixify Tools** workspace.

---

## 1. Tool Categories

Select the appropriate category for your new tool:

1. **WordPress Plugins (`tools/wordpress-plugins/`)**:
   - Built for WordPress admin or frontend site functionality (e.g., WordPress Image Compressor Plugin).
   - Contains standard WP headers, asset enqueuing, and `readme.txt`.

2. **Embeddable Web Tools (`tools/web-apps/`)**:
   - Interactive, client-side HTML/CSS/JS applications (e.g., SEO Schema Generator, Character Counter, Image Converter).
   - Designed to run standalone OR be easily embedded on Xixify WordPress pages using iframe or shortcode.

3. **CLI Utilities & Automation Scripts (`tools/cli-utilities/`)**:
   - Command-line scripts (Node.js, Python, PHP CLI) for site maintenance, SEO auditing, or bulk data processing.

---

## 2. Step-by-Step Creation Process

### Step 1: Copy Starter Template
From the root of `xixify tools`:
```bash
# For a WordPress Plugin:
cp -r templates/wp-plugin-boilerplate tools/wordpress-plugins/my-new-plugin

# For an Embeddable Web Tool:
cp -r templates/embeddable-web-tool tools/web-apps/my-web-tool
```

### Step 2: Configure & Rename Files
- Rename main source files to match your tool slug.
- Update metadata headers (Title, Description, Author, Slug, Version).

### Step 3: Develop & Test
- Keep code clean, documented, and modular.
- Ensure UI tools are fully responsive, accessible, and fast-loading.

### Step 4: Add Tool README
Each tool directory **must** have its own `README.md` containing:
- Feature highlights
- Usage / Installation instructions
- Screenshots or live demo link

### Step 5: Update Main Catalog
Add your new tool entry to the table in `README.md` at the root of the repository.
