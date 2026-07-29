# WordPress Publishing & Embedding Guide

This guide covers how to integrate tools developed in this workspace into your **Xixify WordPress tools website**.

---

## 1. Integrating Embeddable Web Tools (`web-apps`)

Web tools (HTML/CSS/JS applications such as SEO generators, text manipulators, image converters) can be published to your WordPress site in 3 ways:

### Method A: Custom HTML / Block Embedding (Fastest)
1. Copy the CSS into a `<style>` block or custom CSS plugin.
2. Paste the HTML container in a **Custom HTML Block** in Gutenberg / Elementor.
3. Enqueue or include the `app.js` script.

### Method B: Iframe Embedding (Best for Isolation)
1. Host the tool directory on your server (e.g. `xixify.com/tools/app-name/`).
2. Embed into any WordPress page using an iframe:
   ```html
   <iframe src="https://xixify.com/tools/app-name/index.html" width="100%" height="600px" style="border:none;"></iframe>
   ```

### Method C: Custom Shortcode Plugin
Package the web tool inside a lightweight WordPress plugin that registers a shortcode (e.g. `[xixify_seo_helper]`):
```php
function xixify_seo_helper_shortcode() {
    wp_enqueue_style('xixify-seo-style', plugin_dir_url(__FILE__) . 'style.css');
    wp_enqueue_script('xixify-seo-js', plugin_dir_url(__FILE__) . 'app.js', array(), '1.0.0', true);
    
    ob_start();
    include plugin_dir_path(__FILE__) . 'template.php';
    return ob_get_clean();
}
add_shortcode('xixify_seo_helper', 'xixify_seo_helper_shortcode');
```

---

## 2. Publishing WordPress Plugins (`wordpress-plugins`)

For WordPress plugin tools (e.g., Image Compressor WP plugin):

1. Zip the plugin directory: `xixify-image-compressor.zip`.
2. Upload via WordPress Admin (`Plugins -> Add New -> Upload Plugin`).
3. Activate the plugin and test all admin settings and API integrations.
