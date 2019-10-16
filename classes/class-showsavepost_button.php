<?php

class RecentPosts_ShowSavePost_Button {

    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the shortcode
     */
    public function init() {
        add_shortcode('savepost-button', array( $this, 'show_savepost_button' ) );
        add_action( 'wp_ajax_savepost_ajax', array( $this, 'savepost_ajax' ) );
        add_action( 'wp_ajax_nopriv_savepost_ajax', array( $this, 'savepost_ajax' ) );
        add_action( 'after_savepost_button', array( $this, 'show_savepost_modal' ) );
        require_once RECENTPOSTS_PLUGIN_DIR . 'classes/class-logsaved.php';
    }

    /**
     * Show Modal when not signed in
     * @return string html
     */
    public function show_savepost_modal() {
        $current_url = get_permalink();
        $redirect_url = home_url('/login');
        $redirect_url = add_query_arg( 'redirect_to', urlencode($current_url), $redirect_url );
        
        ob_start(); ?>
            <div class="savepost-modal-wrapper">
                <div class="savepost-modal">
                    <div class="icon">
                        <span class="fa-stack">
                            <i class="fal fa-circle fa-stack-2x"></i>
                            <i class="far fa-heart fa-stack-1x"></i>
                        </span>
                    </div>
                    <div class="heading">Save this activity to your list and easily access it in your dashboard.</div>
                    <div class="button-wrap"><a href="<?php echo $redirect_url; ?>" class="button">Login / Register</a></div>
                </div>
            </div>
        <?php echo ob_get_clean();
    }

    /** 
     * Register Ajax Method for saving post to database
     * @param object $data
     */
    public function savepost_ajax(){
        $user_id = $_POST['userID'];
        $post_id = $_POST['postID'];
        $post_type = $_POST['postType'];

        $logsaved = new RecentPosts_LogSaved();
        $response = $logsaved->log_saved($user_id, $post_id, $post_type);
        echo $response;
        wp_die();
    }

    /**
     * Create shortcode to display the save post button
     * 
     * @param string $atts not used
     * @return string shortcode html
     */
    public function show_savepost_button($atts) {
        ob_start();
        ?>
        <span class="savepost-button">
            <span class="fa-stack savepost-icon">
                <i class="fal fa-circle fa-stack-2x"></i>
                <i class="far fa-heart fa-stack-1x"></i>
            </span>
            <a href="#">Save to your list</a>
            <?php do_action('after_savepost_button');?>
            <?php if (!is_user_logged_in()) {
                // show modal window
                ?> <script type="text/javascript">
                    jQuery('span.savepost-button a').click(function(e){
                        e.preventDefault();
                        jQuery('.savepost-modal-wrapper').show();
                    });
                </script>
                <?php
            } else {
                // do ajax call to save post to db
                // define variables
                $user_id = get_current_user_id();
                $post_id = get_the_ID();
                $post_type = get_post_type();
                ?> <script type="text/javascript">
                    jQuery('span.savepost-button a').click(function(e){
                        e.preventDefault();
                        var data = {
                            action: 'savepost_ajax',
                            postID: '<?php echo $post_id;?>',
                            userID: '<?php echo $user_id;?>',
                            postType: '<?php echo $post_type;?>'
                        };
                        jQuery.post(recentpostsAjax.url, data, function(response) {
                            jQuery('span.savepost-button a').text(response);
                        });
                    });
                </script>
                <?php
            } ?>
        </span>
        <?php return ob_get_clean();
    }
}

new RecentPosts_ShowSavePost_Button();