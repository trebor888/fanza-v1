<?php
/**
 * Fanzalive Theme functions and definitions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define theme constants
define('FANZALIVE_THEME_DIR', get_template_directory());
define('FANZALIVE_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function fanzalive_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Register navigation menus
    register_nav_menus(array(
        'main-menu' => esc_html__('Main Menu', 'fanzalive'),
        'user-menu' => esc_html__('User Menu', 'fanzalive'),
        'footer-menu' => esc_html__('Footer Menu', 'fanzalive'),
    ));
}
add_action('after_setup_theme', 'fanzalive_theme_setup');

/**
 * Enqueue scripts and styles
 */
function fanzalive_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('fanzalive-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0');
    
    // Enqueue jQuery
    wp_enqueue_script('jquery');
    
    // Enqueue custom JavaScript
    wp_enqueue_script('fanzalive-script', FANZALIVE_THEME_URI . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script
    wp_localize_script('fanzalive-script', 'fanzalive', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'fanzalive_scripts');

/**
 * Enqueue Owl Carousel scripts and styles
 */
function fanzalive_enqueue_owl_carousel() {
    // Enqueue Owl Carousel CSS
    wp_enqueue_style('owl-carousel', get_template_directory_uri() . '/assets/css/owl.carousel.min.css', array(), '2.3.4');
    wp_enqueue_style('owl-theme-default', get_template_directory_uri() . '/assets/css/owl.theme.default.min.css', array(), '2.3.4');
    
    // Enqueue Owl Carousel JS
    wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array('jquery'), '2.3.4', true);
}
add_action('wp_enqueue_scripts', 'fanzalive_enqueue_owl_carousel');

/**
 * Register widget areas
 */
function fanzalive_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Footer Widget Area', 'fanzalive'),
        'id'            => 'footer-widget-area',
        'description'   => esc_html__('Add widgets here to appear in the footer.', 'fanzalive'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'fanzalive_widgets_init');

/**
 * Add AJAX object for the matches load function
 */
function fanzalive_enqueue_ajax_object() {
    wp_localize_script('jquery', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('fanzalive_matches_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'fanzalive_enqueue_ajax_object');

/**
 * AJAX handler for loading matches
 */
function fanzalive_load_matches() {
    // Verify nonce
    check_ajax_referer('fanzalive_matches_nonce', 'nonce');
    
    // Get parameters
    $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : date('Y-m-d');
    $league_id = isset($_POST['league_id']) ? intval($_POST['league_id']) : 0;
    
    // Query matches
    $args = array(
        'post_type' => 'sp_event',
        'posts_per_page' => 10,
        'meta_query' => array(
            array(
                'key' => 'sp_date',
                'value' => $date,
                'compare' => '='
            )
        )
    );
    
    // Add league filter if specified
    if ($league_id > 0) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'sp_league',
                'field' => 'term_id',
                'terms' => $league_id
            )
        );
    }
    
    $matches = new WP_Query($args);
    
    if ($matches->have_posts()) {
        while ($matches->have_posts()) {
            $matches->the_post();
            $home_team = get_post_meta(get_the_ID(), 'sp_team', true)[0];
            $away_team = get_post_meta(get_the_ID(), 'sp_team', true)[1];
            $match_time = get_post_meta(get_the_ID(), 'sp_time', true);
            ?>
            <tr>
                <td class="match-home-team"><?php echo get_the_title($home_team); ?></td>
                <td class="match-time">
                    <a href="<?php the_permalink(); ?>">
                        <?php echo $match_time; ?>
                    </a>
                </td>
                <td class="match-away-team"><?php echo get_the_title($away_team); ?></td>
            </tr>
            <?php
        }
        wp_reset_postdata();
    } else {
        ?>
        <tr>
            <td colspan="3">No matches scheduled for this date</td>
        </tr>
        <?php
    }
    
    die();
}
add_action('wp_ajax_load_matches', 'fanzalive_load_matches');
add_action('wp_ajax_nopriv_load_matches', 'fanzalive_load_matches');

function fanzalive_enqueue_scripts() {
    // Other scripts...
    wp_enqueue_script('fanzalive-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'fanzalive_enqueue_scripts');

/**
 * Include custom functions
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Register navigation menus
 */
function fanzalive_register_menus() {
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'fanzalive'),
        'user'    => esc_html__('User Menu', 'fanzalive'),
        'footer'  => esc_html__('Footer Menu', 'fanzalive'),
    ));
}
add_action('after_setup_theme', 'fanzalive_register_menus');


/**
 * Add to functions.php
 */
function fanzalive_additional_scripts() {
    // Enqueue additional styles
    wp_enqueue_style('fanzalive-additional-styles', get_template_directory_uri() . '/assets/css/additional-styles.css', array('fanzalive-style'), '1.0.0');
}
add_action('wp_enqueue_scripts', 'fanzalive_additional_scripts', 20);