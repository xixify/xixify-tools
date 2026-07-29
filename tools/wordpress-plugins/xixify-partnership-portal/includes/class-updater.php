<?php
/**
 * Automated GitHub Release Updater for Xixify WordPress Plugins
 */

if (!defined('ABSPATH')) {
    exit;
}

class Xixify_Partnership_Updater {

    private $file;
    private $plugin;
    private $basename;
    private $active;
    private $username;
    private $repository;
    private $github_response;

    public function __construct($file, $username, $repository) {
        $this->file = $file;
        $this->plugin = plugin_basename($file);
        $this->basename = current(explode('/', $this->plugin));
        $this->username = $username;
        $this->repository = $repository;

        add_action('admin_init', array($this, 'set_plugin_properties'));
    }

    public function set_plugin_properties() {
        add_filter('site_transient_update_plugins', array($this, 'modify_transient'), 10, 1);
        add_filter('plugins_api', array($this, 'plugin_popup'), 10, 3);
        add_filter('upgrader_post_install', array($this, 'after_install'), 10, 3);
    }

    private function get_repository_info() {
        if (!empty($this->github_response)) {
            return;
        }

        $url = "https://api.github.com/repos/{$this->username}/{$this->repository}/releases/latest";
        $response = wp_remote_get($url, array(
            'headers' => array(
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url')
            )
        ));

        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $this->github_response = json_decode(wp_remote_retrieve_body($response), true);
        }
    }

    public function modify_transient($transient) {
        if (property_exists($transient, 'checked') && $transient->checked) {
            $this->get_repository_info();

            if (!empty($this->github_response)) {
                $plugin_data = get_plugin_data($this->file);
                $version = str_replace('v', '', $this->github_response['tag_name']);

                if (version_compare($plugin_data['Version'], $version, '<')) {
                    $package = $this->github_response['zipball_url'];

                    // Check if a compiled release zip asset is attached
                    if (!empty($this->github_response['assets'])) {
                        foreach ($this->github_response['assets'] as $asset) {
                            if (substr($asset['name'], -4) === '.zip') {
                                $package = $asset['browser_download_url'];
                                break;
                            }
                        }
                    }

                    $obj = new stdClass();
                    $obj->slug = $this->basename;
                    $obj->new_version = $version;
                    $obj->url = $plugin_data['PluginURI'];
                    $obj->package = $package;
                    $obj->plugin = $this->plugin;

                    $transient->response[$this->plugin] = $obj;
                }
            }
        }
        return $transient;
    }

    public function plugin_popup($result, $action, $args) {
        if ($action !== 'plugin_information') {
            return $result;
        }

        if (!empty($args->slug) && $args->slug === $this->basename) {
            $this->get_repository_info();

            if (!empty($this->github_response)) {
                $plugin_data = get_plugin_data($this->file);

                $plugin = new stdClass();
                $plugin->name = $plugin_data['Name'];
                $plugin->slug = $this->basename;
                $plugin->version = str_replace('v', '', $this->github_response['tag_name']);
                $plugin->author = $plugin_data['AuthorName'];
                $plugin->homepage = $plugin_data['PluginURI'];
                $plugin->requires = $plugin_data['RequiresWP'];
                $plugin->tested = $plugin_data['TestedUpTo'];
                $plugin->downloaded = 0;
                $plugin->last_updated = $this->github_response['published_at'];
                $plugin->sections = array(
                    'description' => $plugin_data['Description'],
                    'changelog' => $this->github_response['body']
                );
                $plugin->download_link = $this->github_response['zipball_url'];

                return $plugin;
            }
        }
        return $result;
    }

    public function after_install($response, $hook_extra, $result) {
        global $wp_filesystem;
        $install_directory = plugin_dir_path($this->file);
        $wp_filesystem->move($result['destination'], $install_directory);
        $result['destination'] = $install_directory;

        if ($this->active) {
            activate_plugin($this->plugin);
        }
        return $result;
    }
}
