<?php

class RecentPosts_Install {
    public function __construct() {
        $this->load_hooks();
        $this->load_classes();
    }

    /**
     * Plugin Activation Install
     */
    public static function init() {
        // Create database recent and saved tables if not present
        global $wpdb;

        $table_name = $wpdb->prefix . 'recentposts';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            user_id mediumint(9) NOT NULL,
            post_id mediumint(9) NOT NULL,
            post_type varchar(55) NOT NULL,
            action varchar(55) NOT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        add_option('recentposts_db_version', RECENTPOSTS_VERSION);
    }

    /**
     * Load Hook Related actions
     */
    public function load_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'add_plugin_scripts' ) );
    }

    /**
     * Load required classes
     */
    public function load_classes() {
        require_once RECENTPOSTS_PLUGIN_DIR . 'classes/class-logrecent.php';
        require_once RECENTPOSTS_PLUGIN_DIR . 'classes/class-showrecent.php';
        require_once RECENTPOSTS_PLUGIN_DIR . 'classes/class-showsaved.php';
        require_once RECENTPOSTS_PLUGIN_DIR . 'classes/class-showsavepost_button.php';
        require_once RECENTPOSTS_PLUGIN_DIR . 'classes/class-deletesaved.php';
    }

    /**
     * Load required styles
     */
    public function add_plugin_scripts() {
        wp_enqueue_script( 'recent-saved-posts-script', RECENTPOSTS_PLUGIN_URL . 'assets/recent-saved-posts.js', array( 'jquery' ), RECENTPOSTS_VERSION, true );
        wp_localize_script( 'recent-saved-posts-script', 'recentpostsAjax', array(
            'url' => admin_url( 'admin-ajax.php' )
            ) );
        wp_enqueue_style( 'recent-saved-posts-style', RECENTPOSTS_PLUGIN_URL . 'assets/recent-saved-posts.css', '', RECENTPOSTS_VERSION );
    }
    
}