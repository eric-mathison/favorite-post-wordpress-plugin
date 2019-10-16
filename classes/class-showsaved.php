<?php

class RecentPosts_ShowSaved {
    // Class Properties
    private $action;

    public function __construct() {
        // Define class properties
        $this->action = 'saved';
        $this->init();
    }
    
    /**
     * Initialize the shortcode
     */
    public function init() {
        add_shortcode('savedposts', array($this, 'show_savedposts'));
    }

    /**
     * Create shortcode to display recently viewed posts
     * 
     * @param string $atts not used
     * @return string shortcode html
     */
    public function show_savedposts($atts) {
        $posts = $this->get_savedposts();
        ob_start(); ?>
        <div class="savedposts">
            <div class="wrap">
                <?php foreach ($posts as $post) {
                    $post_id = $post->post_id;
                    $post_title = get_the_title($post_id);
                    $post_url = get_the_permalink($post_id);
                    $post_thumbnail = get_the_post_thumbnail_url($post_id);
                    ?>
                    <div class="saved-post" data-id="<?php echo $post_id; ?>">
                        <span class="delete"><i class="fas fa-trash"></i></span>
                        <a href="<?php echo $post_url; ?>">
                            <img class="nopin cp-img-lazy" src="<?php echo $post_thumbnail; ?>" alt="<?php echo $post_title; ?>">
                            <h4><?php echo $post_title; ?></h4>
                        </a>
                    </div>
                <?php    
                } ?>
            </div>
        </div>
        <?php return ob_get_clean();
    }

    /**
     * Query the database for saved posts
     * 
     * @return array 
     */
    private function get_savedposts() {
        global $wpdb;
        $user_id = get_current_user_id();

        return $wpdb->get_results('SELECT * FROM '. $wpdb->prefix .'recentposts WHERE user_id = "'. $user_id .'" AND action = "'. $this->action .'" ORDER BY id DESC');
    }

}

new RecentPosts_ShowSaved();