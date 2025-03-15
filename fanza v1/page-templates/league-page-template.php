<?php
/**
 * Template Name: All League Page
**/
?>
<?php get_header(); ?>
<div id="content" class="clearfix">
    <div id="primary">
        <?php if(have_posts()) : ?>
            <?php while(have_posts()) : the_post(); ?>
                <div <?php post_class('entry single'); ?>>
                    <?php get_template_part('template-parts/breadcrumb'); ?>
                    <h1><?php the_title(); ?></h1>
                    <section><?php
                        $featured = get_theme_mod('featured_pages_use','checked');
                        if ( has_post_thumbnail()) {
                            the_post_thumbnail('large');
                        } ?>
                        <?php the_content(); ?>
                        <?php wp_link_pages(); ?>
                        <?php edit_post_link('Edit', '<p>', '</p>'); ?>
                    </section>
                    <?php comments_template( '', true ); ?>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="entry single notfound">
                <h1><?php esc_html_e('Not Found','fanzalive'); ?></h1>
            </div>
        <?php endif; ?>
        <?php

        $args = array(
            'post_parent' => 0,
            'post_type'=>'hasan_league',
            'orderby' => 'menu_order', 
            'order' => 'ASC',
        );

        $the_query = new WP_Query( $args);
        ?>
        <?php if ( $the_query->have_posts() ) : ?>

            <!-- pagination here -->

            <!-- the loop -->
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <h2 style="font-size:2rem"><?php the_title(); ?></h2>
            <?php
                $query = new WP_Query(
                        array('post_parent' => get_the_ID(),
                            'post_type'=>'hasan_league',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order', 
                            'order' => 'ASC', ) );
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $link = get_post_meta($post->ID, 'league_url', true)?get_post_meta($post->ID, 'league_url', true):'#';
                    ?>
                    <a class="hasan_league_link" href="<?php echo get_the_permalink($post->ID);?>"><?php echo  get_the_title();?></a>
                <?php }
                ?>

                <div style="clear: both; margin-bottom: 30px"></div>
            <?php endwhile; ?>
            <!-- end of the loop -->

            <!-- pagination here -->

            <?php wp_reset_postdata(); ?>

        <?php else : ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
