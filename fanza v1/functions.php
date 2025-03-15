<?php
if (! defined('FANZALIVE_VERSION')) {
    define('FANZALIVE_VERSION', '1.5');
}

// load required files
require_once(get_template_directory() . '/inc/admin-user-profile-teams.php' );
require_once(get_template_directory() . '/inc/login-registration-functions.php' );
require_once(get_template_directory() . '/inc/match-report-functions.php' );
require_once(get_template_directory() . '/inc/user-functions.php' );
require_once(get_template_directory() . '/inc/advertisement-functions.php' );
require_once(get_template_directory() . '/inc/header-cleanup.php');

# require_once('post_types/league.php');
require_once('post_types/advertisement.php');


/*
 * Add custom widgets
 */
add_action('widgets_init', 'fanzalive_widgets_init');
function fanzalive_widgets_init() {
    // Register sidebars
    register_sidebar(array(
        'name' => 'Home Column One',
        'id' => 'home-col-one',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
     register_sidebar(array(
        'name' => 'Home Column Two',
        'id' => 'home-col-two',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    register_sidebar(array(
        'name' => 'Home Column Three',
        'id' => 'home-col-three',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    register_sidebar(array(
        'name' => 'Homepage Top',
        'id' => 'hometop',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    register_sidebar(array(
        'name' => 'Footer',
        'id' => 'left-footer',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));

    // include widget classes
    require_once(get_template_directory() . '/widgets/class-widget-latest-news.php');
    require_once(get_template_directory() . '/widgets/class-widget-leagues.php');

    // register widgets
    register_widget("Fanzalive_Latest_News_Widget");
    register_widget("Fanzalive_Leagues_Widget");
}

// enqueue sortable class on admin widget manager page
add_action('admin_enqueue_scripts', function(){
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_style('admin-advertise', get_stylesheet_directory_uri().'/assets/css/admin-advertise.css');
    wp_enqueue_script('admin-advertise', get_stylesheet_directory_uri().'/assets/js/admin-advertise.js');

});


add_action('after_setup_theme', 'fanzalive_setup_theme');
function fanzalive_setup_theme() {
    // Register menus
    register_nav_menus(
        array(
            'primary' => esc_html__('Main (visitor)', 'fanzalive'),
            'secondary' => esc_html__('Main (logged in)', 'fanzalive'),
            'footer_nav' => esc_html__('Footer', 'fanzalive'),
        )
    );

    // Theme Support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('sportspress');
    add_theme_support('yoast-seo-breadcrumbs');

    add_image_size('square-large', 600, 600, true);
    add_image_size('adv_image', 400, 150, true);
    set_post_thumbnail_size(600, 400, true);

    add_editor_style();
}

/*
 * Hide admin bar
 */

add_filter('show_admin_bar', 'fanzalive_admin_bar_visibility');
function fanzalive_admin_bar_visibility($show) {
    if (! current_user_can('administrator') && ! is_admin()) {
        $show = false;
    }

    return $show;
}


/*
 * Handle frontend actions
 */

add_action('template_redirect', 'fanzalive_template_redirect');
function fanzalive_template_redirect() {
    if (is_user_logged_in() && isset($_POST['action'])) {
        $action = $_POST['action'];
        $data = stripslashes_deep($_POST);
        unset($data['action']);

        switch ($action) {
            case 'fanzalive_assign_reporter':
                $update = fanzalive_assign_reporter(get_the_ID(), $data);
                if (is_wp_error($update)) {
                    wp_die($update);
                } else {
                    wp_redirect(add_query_arg('assigned', '1'));
                    exit;
                }
                break;

            case 'fanzalive_update_score' :
                $update = fanzalive_update_scores(get_the_ID(), $data);
                if (is_wp_error($update)) {
                    wp_die($update);
                } else {
                    wp_redirect(add_query_arg('updated', '1'));
                    exit;
                }
                break;

            case 'fanzalive_insert_commentary' :
                $update = fanzalive_insert_commentary(get_the_ID(), $data);
                if (is_wp_error($update)) {
                    wp_die($update);
                } else {
                    //wp_redirect(add_query_arg('inserted', '1'));
                    echo 'ok';
                    exit;
                }
                break;
                
            case 'fanzalive_insert_user_commentary' :
                $update = fanzalive_insert_user_commentary(get_the_ID(), $data);
                if (is_wp_error($update)) {
                    wp_die($update);
                } else {
                    //wp_redirect(add_query_arg('inserted', '1'));
                    echo 'ok';
                    exit;
                }
                break;

            case 'fanzalive_insert_commentary_test_page' :
                $update = fanzalive_insert_commentary_test_page(get_the_ID(), $data);
                if (is_wp_error($update)) {
                    wp_die($update);
                } else {
                    //wp_redirect(add_query_arg('inserted', '1'));
                    echo 'ok';
                    exit;
                }
                break;

            default:
                break;
        }
    }
}


add_filter('post_type_link', 'fanzalive_sanitize_future_event_permalink', 10, 4);
function fanzalive_sanitize_future_event_permalink($post_link, $post, $leavename, $sample) {
    if ('sp_event' == $post->post_type && 'future' == $post->post_status) {
        global $wp_rewrite;
        $post_link = $wp_rewrite->get_extra_permastruct($post->post_type);
        $slug = $post->post_name;
        $post_type = get_post_type_object($post->post_type);

        if (!empty($post_link)) {
            if (!$leavename) {
                $post_link = str_replace("%$post->post_type%", $slug, $post_link);
            }
            $post_link = home_url(user_trailingslashit($post_link));
        } else {
            if ($post_type->query_var && ( isset($post->post_status) )) {
                $post_link = add_query_arg($post_type->query_var, $slug, '');
            } else {
                $post_link = add_query_arg(
                    array(
                        'post_type' => $post->post_type,
                        'p' => $post->ID,
                    ), ''
                );
            }
            $post_link = home_url($post_link);
        }
    }

    return $post_link;
}


// Shortcodes
add_action('init', 'fanzalive_shortcodes_init');
function fanzalive_shortcodes_init() {
    require_once(get_template_directory() . '/shortcodes/class-shortcode-matches.php' );
    require_once(get_template_directory() . '/shortcodes/class-shortcode-standing-table.php' );
    Fanzalive_Shortcode_Matches::init();
    Fanzalive_Shortcode_Standing_Table::init();
}


// Register scripts and styles
add_action('wp_enqueue_scripts', 'fanzalive_register_scripts', 2);
function fanzalive_register_scripts() {
    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.min' : '';

    wp_register_script('owl-carousel', get_template_directory_uri() . '/assets/owl-carousel/owl.carousel.min.js', array('jquery'));
    wp_register_script('jquery-blockui', get_template_directory_uri() . '/assets/js/jquery.blockui.js', array('jquery', 'jquery-ui-core'));
    wp_register_script('facebook-plugin', get_template_directory_uri() . '/assets/js/facebook-plugin.js', array('jquery'));
    wp_register_script('twitter-plugin', get_template_directory_uri() . '/assets/js/twitter-plugin.js', array('jquery'));
    wp_register_script('instagram-plugin', get_template_directory_uri() . '/assets/js/instagram-plugin.js', array('jquery'));
    wp_register_script('jquery-tinymce', 'https://cdn.tinymce.com/4/tinymce.min.js', array('jquery'));
    wp_register_script('jquery', 'https://code.jquery.com/jquery-2.2.4.min.js', array('jquery'));    
    wp_register_script('fanzalive-frontend', get_template_directory_uri() . '/assets/js/fanzalive-frontend.js', array('jquery'));

    // NOT NEEDED
    wp_register_style('font-tello-main-css', get_stylesheet_directory_uri() . '/assets/css/fontello.css', array(), time());
    wp_register_style('font-tello-item-css', get_stylesheet_directory_uri() . '/assets/css/fontello-codes.css', array(), time());
    wp_register_style('media-upload', get_stylesheet_directory_uri() . '/assets/css/media-upload.css', array(), time());

    wp_register_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), time());
    wp_register_style('owl-carousel', get_template_directory_uri() . '/assets/owl-carousel/owl.carousel.min.css', '', FANZALIVE_VERSION);
    wp_register_style('fanzalive-frontend', get_template_directory_uri() . '/assets/css/fanzalive-frontend.css', '', FANZALIVE_VERSION);
}


/*
 * Enqueue scripts
 */
add_action('wp_enqueue_scripts', 'fanzalive_enqueue_scripts');
function fanzalive_enqueue_scripts() {
    wp_deregister_script('wp-embed');

    wp_localize_script('fanzalive-frontend', 'fanzalive', apply_filters('fanzalive_frontend_script_vars', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]));

    wp_enqueue_script([
        'jquery-ui-datepicker',
        'jquery-effects-core',
        'owl-carousel',
        'jquery-blockui',
        'jquery-tinymce',
        'facebook-plugin',
        'twitter-plugin',
        'instagram-plugin',
        'media-uploader',
        'fanzalive-frontend',
        
    ]);

    wp_enqueue_style([
        #'font-tello-main-css',
        #'font-tello-item-css',
        'media-upload',
        'font-awesome',
        'owl-carousel',
        'fanzalive-frontend'
    ]);
}

/*
add_filter('wp_nav_menu_items', 'fanzalive_logout_menu_item', 10, 2);
function fanzalive_logout_menu_item($items, $args) {
    if ($args->theme_location == 'secondary') {
        if (is_user_logged_in()) {
            $items .= '<li class="right"><a href="' . wp_logout_url(get_permalink()) . '">' . __("Logout") . '</a></li>';
        }
    }
    return $items;
}*/



function fanzalive_edit_profile_page_url() {
    $pages = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-templates/edit-profile.php'
    ]);

    if (!empty($pages)) {
        return get_permalink($pages[0]);
    } else {
        return wp_login_url();
    }
}

function fanzalive_login_page_url() {
    $pages = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-templates/login.php'
    ]);

    if (!empty($pages)) {
        return get_permalink($pages[0]);
    } else {
        return wp_login_url();
    }
}

function fanzalive_registration_page_url() {
    $pages = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-templates/registration.php'
    ]);

    if (!empty($pages)) {
        return get_permalink($pages[0]);
    } else {
        return wp_registration_url();
    }
}

add_action('init', 'fanzalive_ajax_load_comments');
function fanzalive_ajax_load_comments() {
    if (isset($_GET['action']) && $_GET['action'] == 'load_comments') {
        global $match_report_team_side, $event_id;

        $team = $match_report_team_side;
        $comments = get_comments([
            'post_id' => $_GET['post_id'],
            'meta_key' => 'team',
            'meta_value' => $_GET['team']
        ]);
        //echo '<pre>';
        //print_r($comments);
        ?>
        <div class="event-commentaries single-event-commentaries"><?php
        //foreach ($comments as $comment) :
        include_once 'template-parts/event-commentary-post.php';
        //endforeach;
        ?></div>
        <?php
        exit;
    }

    if (isset($_GET['action']) && $_GET['action'] == 'load_comments_widget') {
        include_once 'template-parts/home-comments.php';
        exit;
    }
}

add_filter( 'map_meta_cap', 'fanzalive_contributors_can_moderate_comments', 10, 2 );
function fanzalive_contributors_can_moderate_comments( $caps, $cap ) {
   if ( $cap == 'edit_comment' )
     $caps = array( 'edit_posts' );
   return $caps;
}


add_filter( 'map_meta_cap', 'restrict_comment_editing', 10, 4 );
function restrict_comment_editing( $caps, $cap, $user_id, $args ) {
    if ( 'edit_comment' == $cap ) {
        $comment = get_comment( $args[0] );
        if ( $comment->user_id != $user_id ) {
            $caps[] = 'moderate_comments';
        }
    }

   return $caps;
}

if (! current_user_can('edit_others_posts')) {
    add_filter('comments_clauses', 'wps_get_comment_list_by_user');
}
function wps_get_comment_list_by_user($clauses) {
   if (is_admin()) {
           global $user_ID, $wpdb;
           $clauses['join'] = ", ".$wpdb->base_prefix."posts";
           $clauses['where'] .= " AND ".$wpdb->base_prefix."posts.post_author = ".$user_ID." AND ".$wpdb->base_prefix."comments.comment_post_ID = ".$wpdb->base_prefix."posts.ID";
   };
   return $clauses;
}




if ( ! function_exists('word_limiter'))
{
    function word_limiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) == '')
        {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

        if (strlen($str) == strlen($matches[0]))
        {
            $end_char = '';
        }

        return rtrim($matches[0]).$end_char;
    }
}


add_filter( 'wp_mail_from', 'fanzalive_sender_email' );
function fanzalive_sender_email( $original_email_address ) {
	return 'team@fanzalive.co.uk';
}

add_filter( 'wp_mail_from_name', 'fanzalive_sender_name' );
function fanzalive_sender_name( $original_email_from ) {
	return 'Fanzalive';
}

add_filter( 'sportspress_register_taxonomy_league', 'fanzalive_allow_leagues_in_nav_menu' );
function fanzalive_allow_leagues_in_nav_menu( $args ) {
    $args['show_in_nav_menus'] = true;
    return $args;
}


add_action('load-edit.php', function(){
    global $typenow;

    if ('sp_team' === $typenow && isset($_REQUEST['action']) && 'delete_orphan_teams' === $_REQUEST['action']) {
        $leagues = get_terms(['taxonomy' => 'sp_league', 'fields' => 'ids']);
        $query = new WP_Query(['posts_per_page' => '100', 'post_type' => 'sp_team', 'post_status' => 'any', 'tax_query'=> [['taxonomy' => 'sp_league', 'terms' => $leagues, 'operator' => 'NOT IN']]]);
        if ($query->get_posts()) {
            foreach ($query->get_posts() as $post) {
                wp_trash_post($post->ID);
            }
        }

        fanzalive_p($query->found_posts);
        fanzalive_p($query->get_posts());
        exit;
    }
});

/**
 * Debug object/array
 */

function fanzalive_p($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

add_action('init','fanzalive_load_comments_widget');
function fanzalive_load_comments_widget() {
    if(isset($_GET['action']) && $_GET['action'] == 'fanzalive_load_comments_widget'){
        include_once 'template-parts/home-comments.php';
        exit;
    }
}

add_action('wp_ajax_fanzalive_get_comments', 'fanzalive_get_comments');
add_action('wp_ajax_nopriv_fanzalive_get_comments', 'fanzalive_get_comments');
function fanzalive_get_comments() {
    $contentUrl = site_url().'?action=fanzalive_load_comments_widget';
    $contentData =  file_get_contents($contentUrl);
    $encodedContentData = json_encode($contentData);
    echo $encodedContentData;
    wp_die();
}

function new_contact_methods( $contactmethods ) {
    $contactmethods['blog'] = 'Blog Count';
    $contactmethods['follower'] = 'Followers Count';
    $contactmethods['views'] = 'Views Count';

    return $contactmethods;
}
add_filter( 'user_contactmethods', 'new_contact_methods', 10, 1 );


function new_modify_user_table( $column ) {
    $column['blog'] = 'Blogs';
    $column['follower'] = 'Followers';
    $column['views'] = 'Views';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) { ?>

<style>.red { border: 1px solid red;
                    background: red;
                    padding: 5px;
                    display: inline-block;
                    border-radius: 50%;
            }
        .green { border: 1px solid green;
                    background: green;
                    padding: 5px;
                    display: inline-block;
                    border-radius: 50%;
        }</style>
   <?php  switch ($column_name) {
        case 'blog' :   $today = getdate();
                       // $args = array(
                            $args = array(
                              'author'        =>  $user_id, 
                              'orderby'       =>  'post_date',
                              'order'         =>  'ASC',
                              'year' => $today['year'],
                              'monthnum' => $today['mon'], 
                        );
                        $the_query = new WP_Query( $args );
                        if($the_query->post_count>=2)
                        {  $html = '<span class="green"></span>'; }else{ $html = '<span class="red"></span>';}
                        return $html;
                       
            break;
        case 'follower' :
                $count = get_user_meta($user_id, 'user_count_follow', true);
            if($count) {
                return $count;
            }else {
                return '0';
            }
            break;
        case 'views' :
            $count = get_user_meta($user_id, 'count_viewer', true);
            if($count) {
                return $count;
            }else {
                return '0';    
            }   
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );


add_action("admin_menu", "cspd_imdb_options_submenu");
function cspd_imdb_options_submenu() {
    add_submenu_page(
        'options-general.php',
        'Post Settings',
        'Post Settings',
        'administrator',
        'hlp-post-options',
        'hlp_post_settings_page' );
    add_submenu_page(
        'options-general.php',
        'Author Posts Settings',
        'Author Posts Settings',
        'administrator',
        'hlp-author-options',
        'hlp_author_settings_page' );
   /* add_submenu_page(
        'options-general.php',
        'Live Reports Settings',
        'Live Reports Settings',
        'administrator',
        'hlp-live-report-options',
        'hlp_live_reports_page' );*/
}

function hlp_post_settings_page() { 
?>
    <div class="wrap">
        <h2>Post Settings</h2>
            <?php 
                if(isset($_POST['hlp_post_show'])) {
                    update_option('hlp_post_show', $_POST['hlp_post_show'] );
                }
                if(isset($_POST['hlp_ads_after_post'])) {
                    update_option('hlp_ads_after_post', $_POST['hlp_ads_after_post'] );
                }
            ?>
        <form method="post" action="">
            <?php wp_nonce_field('update-options') ?>
            <table class="form-table">
                <tr>
                    <th>Posts to show</th>
                    <td><input type="text" name="hlp_post_show" id="hlp_post_show" size="45" value="<?php echo get_option('hlp_post_show', true); ?>" /></td>
                </tr>
                <tr>
                    <th>Show ads after posts</th>
                    <td><input type="text" name="hlp_ads_after_post" size="45" value="<?php echo get_option('hlp_ads_after_post', true); ?>" /></td>
                </tr>
                <tr>
                    <td><input type="submit" name="Submit" class="button button-primary button-large" value="Save" /></td>
                </tr>
            </table>
            
            <input type="hidden" name="action" value="update" />
        </form>
    </div>
<?php
}

function hlp_author_settings_page() { 
?>
    <div class="wrap">
        <h2>Author Settings</h2>
            <?php 
                if(isset($_POST['hlp_author_post_show'])) {
                    update_option('hlp_author_post_show', $_POST['hlp_author_post_show'] );
                }
                if(isset($_POST['hlp_ads_after_author_post'])) {
                    update_option('hlp_ads_after_author_post', $_POST['hlp_ads_after_author_post'] );
                }
                if(isset($_POST['hlp_follower_show'])) {
                    update_option('hlp_follower_show', $_POST['hlp_follower_show'] );
                }
                if(isset($_POST['hlp_reporters_show'])) {
                    update_option('hlp_reporters_show', $_POST['hlp_reporters_show'] );
                }
            ?>
        <form method="post" action="">
            <?php wp_nonce_field('update-options') ?>
            <table class="form-table">
                <tr>
                    <th>Posts to show</th>
                    <td><input type="text" name="hlp_author_post_show" id="hlp_author_post_show" size="45" value="<?php echo get_option('hlp_author_post_show', true); ?>" /></td>
                </tr>
                <tr>
                    <th>Show ads after posts</th>
                    <td><input type="text" name="hlp_ads_after_author_post" size="45" value="<?php echo get_option('hlp_ads_after_author_post', true); ?>" /></td>
                </tr>
                <tr>
                    <th>Followers to show</th>
                    <td><input type="text" name="hlp_follower_show" size="45" value="<?php echo get_option('hlp_follower_show', true); ?>" /></td>
                </tr>
                <tr>
                    <th>Reporters to show</th>
                    <td><input type="text" name="hlp_reporters_show" size="45" value="<?php echo get_option('hlp_reporters_show', true); ?>" /></td>
                </tr>
                <tr>
                    <td><input type="submit" name="Submit" class="button button-primary button-large" value="Save" /></td>
                </tr>
            </table>
            
            <input type="hidden" name="action" value="update" />
        </form>
    </div>
<?php
}
/*
function hlp_live_reports_page() { 
?>
    <div class="wrap">
        <h2>Post Settings</h2>
            <?php 
                if(isset($_POST['hlp_ads_after_comment_box'])) {
                    update_option('hlp_ads_after_comment_box', $_POST['hlp_ads_after_comment_box'] );
                }
            ?>
        <form method="post" action="">
            <?php wp_nonce_field('update-options') ?>
            <table class="form-table">
                <tr>
                    <th>Show ads after comment box</th>
                    <td><input type="text" name="hlp_ads_after_comment_box" size="45" value="<?php echo get_option('hlp_ads_after_comment_box', true); ?>" /></td>
                </tr>
                <tr>
                    <td><input type="submit" name="Submit" class="button button-primary button-large" value="Save" /></td>
                </tr>
            </table>
            
            <input type="hidden" name="action" value="update" />
        </form>
    </div>
<?php
}*/



add_action( 'admin_menu', 'advertise_menu' );
function advertise_menu() {
    add_menu_page( 'Advertise', 'Advertise', 'manage_options', 'hlp_advertise', 'hlp_advertise_page', 'dashicons-universal-access-alt', 24 );
}
 

function hlp_advertise_page() {
    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    $hlp_tab = $_REQUEST['tab'];
    echo '<div class="wrap">
                <h2>Advertisement Settings</h2>
                <h2 class="nav-tab-wrapper">
                    <a href="'.admin_url('?page=hlp_advertise&tab=events').'" class="nav-tab '.(($hlp_tab == "events" || (!$hlp_tab)) ? "nav-tab-active" : "") .'">Events</a>
                    <a href="'.admin_url('?page=hlp_advertise&tab=team').'" class="nav-tab '.(($hlp_tab == "team") ? "nav-tab-active" : "") .'">Team</a>
                    <a href="'.admin_url('?page=hlp_advertise&tab=league').'" class="nav-tab '.(($hlp_tab == "league") ? "nav-tab-active" : "" ) .'">League</a>
                    <a href="'.admin_url('?page=hlp_advertise&tab=fixture').'" class="nav-tab '.(($hlp_tab == "fixture") ? "nav-tab-active" : "" ) .'">Fixtures/Tables</a>
                    <a href="'.admin_url('?page=hlp_advertise&tab=news').'" class="nav-tab '.(($hlp_tab == "news") ? "nav-tab-active" : "" ) .'">News</a>
                    <a href="'.admin_url('?page=hlp_advertise&tab=author').'" class="nav-tab '.(($hlp_tab == "author") ? "nav-tab-active" : "" ) .'">Author</a>
                </h2>
            </div>';

    if($hlp_tab == ''){
        require_once(get_stylesheet_directory() . '/template-parts/advertise-events.php');
    }else {
        require_once(get_stylesheet_directory() . '/template-parts/advertise-'.$hlp_tab.'.php');
    }
}




add_action( 'wp_ajax_his_delete_post', 'his_delete_post' );
add_action( 'wp_ajax_nopriv_his_delete_post', 'his_delete_post' );

function his_delete_post() {
    extract($_POST);

    if($id) {
        wp_delete_post( $id );
        echo 'success';
    }
    die;
}


add_action( 'wp_ajax_upload_image', 'upload_image' );    // only for logged in user
add_action( 'wp_ajax_nopriv_upload_image', 'upload_image' );

function upload_image() {
    $submitted_file = current($_FILES);
    $uploaded_image = wp_handle_upload( $submitted_file, array( 'test_form' => false ) );
    if ( isset( $uploaded_image['file'] ) ) {
        $file_name          =   basename( $submitted_file['name'] );
        $file_type          =   wp_check_filetype( $uploaded_image['file'] );
        // Prepare an array of post data for the attachment.
        $attachment_details = array(
            'guid'           => $uploaded_image['url'],
            'post_mime_type' => $file_type['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );
        $attach_id      =   wp_insert_attachment( $attachment_details, $uploaded_image['file'] );
        $attach_data    =   wp_generate_attachment_metadata( $attach_id, $uploaded_image['file'] );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        $thumbnail_url = wp_get_attachment_image_src( $attach_id, 'thumbnail-image350_350' );
        //$fullimage_url  = wp_get_attachment_image_src( $attach_id, 'full' );
        $attachment_title = get_the_title($attach_id);
        $fullimage_url = wp_get_attachment_url( $attach_id );
        $ajax_response = array(
            'success'   => true,
            'url' => $thumbnail_url[0],
            'attachment_id'    => $attach_id,
            'full_image'    => $fullimage_url,
            'attach_title'    => $attachment_title,
        );
        //echo json_encode( $fullimage_url );
        echo json_encode(array('location' => $fullimage_url));
        die;
    } else {
        $ajax_response = array( 'success' => false, 'reason' => 'File upload failed!' );
        echo json_encode( $ajax_response );
        die;
    }
}

add_action( 'wp_ajax_fanzalive_get_scores', 'fanzalive_get_scores' );
add_action( 'wp_ajax_nopriv_fanzalive_get_scores', 'fanzalive_get_scores' );
function fanzalive_get_scores() {
    extract($_POST);
    $response = array();
    $results = get_post_meta($eventId, 'sp_results', true);
    foreach ($results as $key => $value) {
        if($key == $teamId) {
            $response['team_nam'] = get_the_title($teamId);
            $response['firsthalf'] = $value['firsthalf'];
            $response['secondhalf'] = $value['secondhalf'];
            $response['goal'] = $value['goals'];
        }
    }
    echo json_encode($response); 
    die;
}
