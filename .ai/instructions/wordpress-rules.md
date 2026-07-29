# WordPress Security & Coding Rules

## Mandatory Security Standard
- **Direct Access Guard**: Place `if (!defined('ABSPATH')) exit;` at the top of every PHP file.
- **Nonces**: Use `wp_create_nonce()` and `check_ajax_referer()` or `wp_verify_nonce()` on all state-changing endpoints.
- **Sanitization**: Sanitize every input (`sanitize_text_field`, `sanitize_email`, `intval`, `absint`).
- **Escaping**: Escape all outputs (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`).
- **Prefixing**: Prefix all functions, classes, action hooks, filter hooks, and options with `xixify_<tool_slug>_`.
