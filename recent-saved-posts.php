<?php
/**
 * Plugin Name: Recent and Saved Post History
 * Description: A plugin that logs user's recent post views and allows users to save their favorite posts.
 * Version: 1.0.0
 * Author: Eric Mathison
 * License: GPL-2.0+
 * Test Domain: mmedia-recentposts
 * GitHub Plugin URI: 
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Plugin version
if (!defined('RECENTPOSTS_VERSION')) {
    define('RECENTPOSTS_VERSION', '1.0.0');
}

// Plugin folder path
if (!defined('RECENTPOSTS_PLUGIN_DIR')) {
    define('RECENTPOSTS_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

// Plugin folder url
if (!defined('RECENTPOSTS_PLUGIN_URL')) {
    define('RECENTPOSTS_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Plugin root file
if (!defined('RECENTPOSTS_PLUGIN_FILE')) {
    define('RECENTPOSTS_PLUGIN_FILE', __FILE__);
}

require_once dirname(__FILE__) . '/classes/class-install.php';

// Initialize the plugin
$mmedia_recentposts_plugin = new RecentPosts_Install();

register_activation_hook(RECENTPOSTS_PLUGIN_FILE, array('RecentPosts_Install', 'init'));