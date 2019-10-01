<?php

class RecentPosts_LogRecent {
    // Class properties
    private $action;
    private $max_results;

    public function __construct() {
        // Define class properties
        $this->action = 'visited';
        $this->max_results = '20';

        $this->init();
    }

    /**
     * Kick off the logging of recent posts
     */
    public function init() {
        add_action('wp', array($this, 'get_recent'));
    }

    /**
     * Log recent post id on page load to database
     * 
     * @param string $user_id
     * @param string $post_id
     * @param string $post_type
     */
    public function log_recent($user_id, $post_id, $post_type) {
        if (!is_user_logged_in() || !is_single()) {
            return;
        }

        // Write to database
        global $wpdb;
        $table_name = $wpdb->prefix . 'recentposts';
        $wpdb->insert(
            $table_name,
            array(
                'time' => current_time('mysql'),
                'user_id' => $user_id,
                'post_id' => $post_id,
                'post_type' => $post_type,
                'action' => $this->action,
            )
        );
    }

    /**
     * Query database for existing entries
     */
    public function get_recent() {
        if (!is_user_logged_in() || !is_single()) {
            return;
        }

        // Define current post variables
        $user_id = get_current_user_id();
        $post_id = get_the_ID();
        $post_type = get_post_type();

        // Query the database for recent views
        global $wpdb;
        $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'recentposts WHERE user_id = "' . $user_id . '" AND action = "' . $this->action . '" ORDER BY id');

        // If query equals max results, delete the first row in the database and 
        // prune array to match
        if (count($results) >= $this->max_results){
            $wpdb->query('DELETE FROM '. $wpdb->prefix .'recentposts ORDER BY id LIMIT 1');
            array_shift($results);
        }

        // Check to see if post id is already in the array
        foreach ($results as $result) {
            if ($result->post_id == $post_id) {
                // If the post id is already in the database,
                // we don't want to add it again, so we return
                return;
            }
        }

        // Finally log the post to the database
        $this->log_recent($user_id, $post_id, $post_type);   
    }
}

new RecentPosts_LogRecent();