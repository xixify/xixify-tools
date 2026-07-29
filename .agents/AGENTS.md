# Xixify Tools - AI Agent Rules & Instructions

Welcome to the **Xixify Tools** repository! This project serves as a centralized hub for designing, developing, testing, and packaging micro-tools for Xixify. These tools are published to **GitHub** and integrated into the **Xixify WordPress tools site**.

---

## 1. Core Objectives & Workflow Principles

1. **Modular Tool Architecture**: Every tool must reside in its own self-contained subdirectory under `tools/<category>/<tool-name>`.
2. **Dual-Publishing Compatibility**:
   - Tools must be ready for standalone distribution via **GitHub** (with proper `README.md`, license, setup instructions, and release tags).
   - Tools built for WordPress (plugins or embeddable JS web apps) must be optimized for easy embedding, lightweight performance, and compatibility with standard WordPress environments.
3. **No Unnecessary Dependencies**: Keep micro-tools fast and lean. Prefer Vanilla JavaScript / CSS for web tools unless a framework is explicitly required. For WordPress plugins, follow modern WordPress API standards (PHP 7.4+ / PHP 8.0+).
4. **Self-Documenting**: Every tool created MUST include a comprehensive `README.md` explaining:
   - What the tool does
   - How to install / use it locally or on GitHub
   - How to embed or publish it on WordPress

---

## 2. Directory Layout Standard

```
xixify tools/
├── .agents/
│   ├── AGENTS.md                  # (This File) Repository rules for AI Agents
│   ├── master-agent.md            # Xixify Founder Master Prompt & Deliverables Spec
│   └── product-manager.md         # Xixify Product Manager Agent spec
├── docs/                          # Developer & Publishing workflows
│   ├── DEVELOPMENT_GUIDE.md       # Step-by-step tool creation guide
│   ├── GITHUB_PUBLISHING.md       # GitHub release & tagging standards
│   └── WORDPRESS_PUBLISHING.md    # WordPress embedding & plugin packaging
├── tools/                         # Active tools sub-directories
│   ├── wordpress-plugins/         # Standalone WP Plugins (e.g. Image Compressor)
│   ├── web-apps/                  # Embeddable HTML/CSS/JS tools for WP pages
│   └── cli-utilities/             # Node/Python/PHP CLI & automation utilities
├── templates/                     # Starters for creating new tools
│   ├── wp-plugin-boilerplate/     # WP plugin boilerplate
│   └── embeddable-web-tool/       # Embeddable web app boilerplate
└── README.md                      # Main repo overview & tools catalog
```

---

## 3. Tool Creation Guidelines for AI Agents

When asked to create a new tool in this repository, follow this workflow:

1. **Identify Tool Category**:
   - WordPress Plugin -> `tools/wordpress-plugins/<tool-name>`
   - Embeddable Web Tool (JS/HTML) -> `tools/web-apps/<tool-name>`
   - CLI / Script Utility -> `tools/cli-utilities/<tool-name>`

2. **Initialize from Template**:
   - Copy the appropriate template from `templates/` to the target tool folder.

3. **Code Standards**:
   - **HTML/CSS/JS (Web Apps)**:
     - Use CSS custom properties (variables) for easy thematic matching with WordPress sites.
     - Ensure responsive design (mobile-first).
     - Keep JS modular and isolated without global namespace pollution.
   - **PHP (WordPress Plugins)**:
     - Always prefix functions, classes, constants, and hooks with `xixify_<tool_slug>_` to prevent collisions.
     - Include security checks: `if (!defined('ABSPATH')) exit;`
     - Sanitize inputs (`sanitize_text_field`, `intval`, etc.) and escape outputs (`esc_html`, `esc_attr`, `esc_url`).

4. **Update Repo Catalog**:
   - Add the newly created tool to the table in the main root `README.md`.

---

## 4. Quality & Safety Requirements

- Never leave inline placeholder text or unhandled exceptions in production scripts.
- Validate file uploads securely (mime types, file size limits) especially for image processing/compressor tools.
- Provide clean user feedback (loading states, success toasts, error messages) in all UI tools.
