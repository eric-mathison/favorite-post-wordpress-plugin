<?php

class RecentPosts_DeleteSaved {
    // Class properties
    private $action;

    public function __construct() {
        $this->action = 'saved';
        $this->init();
    }
    
    /**
     * Initialize the shortcode
     */
    public function init() {
        add_action( 'wp_ajax_deletesaved_ajax', array( $this, 'deletesaved_ajax' ) );
        add_action( 'wp_ajax_nopriv_deletesaved_ajax', array( $this, 'deletesaved_ajax' ) );
    }

    /**
     * Delete the post in the database
     * 
     * @return array 
     */
    public function deletesaved_ajax() {
        global $wpdb;
        $post_id = $_POST['postID'];
        $user_id = get_current_user_id();

        return $wpdb->get_results('DELETE FROM '. $wpdb->prefix .'recentposts WHERE user_id = "'. $user_id .'" AND action = "'. $this->action .'" AND post_id = "'. $post_id .'" ORDER BY id DESC');
    }

}

new RecentPosts_DeleteSaved();