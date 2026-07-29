# Xixify Tools 🛠️

Welcome to **Xixify Tools** — a curated workspace for building, testing, and packaging micro-tools, WordPress plugins, SEO helpers, and interactive web utilities for Xixify.

All tools developed in this workspace are designed to be published open-source on **GitHub** as well as deployed directly to the **Xixify WordPress Tools Website**.

---

## 📁 Repository Structure

```
xixify tools/
├── .agents/                    # Agent instructions & workflow rules
├── docs/                       # Development & Publishing documentation
│   ├── DEVELOPMENT_GUIDE.md    # How to build a new tool
│   ├── GITHUB_PUBLISHING.md    # Packaging & releasing to GitHub
│   └── WORDPRESS_PUBLISHING.md # Integrating & embedding tools on WordPress
├── tools/                      # Tool source directories
│   ├── wordpress-plugins/      # WordPress Plugins (e.g. Image Compressor)
│   ├── web-apps/               # Interactive embeddable web tools
│   └── cli-utilities/          # Automation scripts & CLI tools
├── templates/                  # Boilerplate templates for new tools
│   ├── wp-plugin-boilerplate/  # Starter template for WP Plugins
│   └── embeddable-web-tool/    # Starter template for embeddable web apps
└── README.md                   # Main repository overview
```

---

## 🧰 Tools Catalog

| Tool Name | Category | Description | Status | Links |
| :--- | :--- | :--- | :--- | :--- |
| **Xixify Partnership Tracker Plugin** | WordPress Plugin | Editable WP Admin Accounts & 50/50 Profit Split Tracker | ✅ Completed | [Plugin](tools/wordpress-plugins/xixify-partnership-portal/) \| [Doc](tools/wordpress-plugins/xixify-partnership-portal/README.md) |
| **Xixify Portal** | Web App (`dev.xixify.com`) | Standalone Partnership Financial & Client Portal | ✅ Completed | [App](tools/web-apps/xixify-portal/) \| [Doc](tools/web-apps/xixify-portal/README.md) |
| *(Example Tool)* | WordPress Plugin | Image Compression & Optimization tool for WP | 🚧 Planned | [Doc](docs/DEVELOPMENT_GUIDE.md) |
| *(Example Tool)* | Web App | SEO Meta & Schema Generator Helper | 🚧 Planned | [Doc](docs/DEVELOPMENT_GUIDE.md) |

---

## 🚀 Quick Start Guide

### Creating a New Tool
1. Check the [Development Guide](docs/DEVELOPMENT_GUIDE.md) for naming conventions and setup.
2. Duplicate a starter template from `templates/` into `tools/<category>/<tool-name>`.
3. Build and test your tool locally.
4. Follow [GitHub Publishing Guide](docs/GITHUB_PUBLISHING.md) & [WordPress Publishing Guide](docs/WORDPRESS_PUBLISHING.md) to deploy.

---

## 📜 License

This repository and all sub-tools are licensed under the [MIT License](LICENSE).
