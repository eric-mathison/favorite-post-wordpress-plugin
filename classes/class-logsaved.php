<?php

class RecentPosts_LogSaved {
    // Class properties
    private $action;

    public function __construct() {
        // Define class properties
        $this->action = 'saved';
    }

    /**
     * Log saved post id to database
     * 
     * @param string $user_id
     * @param string $post_id
     * @param string $post_type
     */
    public function log_saved($user_id, $post_id, $post_type) {
        global $wpdb;
        // Check database for existing save
        $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'recentposts WHERE user_id = "' . $user_id . '" AND action = "' . $this->action . '" AND post_id = "' . $post_id . '"');
        if ($results[0] > 0) {
            return 'Already in your list.';
        } else {
            // Write to database
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
            return 'Saved';
        }
    }
}