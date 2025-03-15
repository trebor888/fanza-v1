<?php get_header(); ?>

<div id="content">

    <div id="primary">

    <?php if(have_posts()) : ?>
    <?php while(have_posts()) : the_post(); ?>

    <div class="entry">

        <h1><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail();} ?>
        <?php the_content(); ?>
        <p class="postmetadata">
            <?php _e('Category&#58;','fanzalive'); ?>
            <?php the_category(', '); ?> |
            <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> |
            <?php _e('Tags: ','fanzalive'); ?>
            <?php the_tags('', ', ', ''); ?>
            <?php edit_post_link('Edit', ' &#124; ', ''); ?>
        </p>

    </div>

    <?php endwhile; ?>

    <?php else : ?>

    <div <?php post_class(); ?>>

        <h1><?php esc_html_e('Not Found','fanzalive'); ?></h1>

    </div>

    <?php endif; ?>

    <?php posts_nav_link(' &#8212; ', esc_html__('&laquo; Older Posts','fanzalive'), esc_html__('Newer Posts &raquo;','fanzalive')); ?>
    </div>
</div>

<?php get_footer(); ?>
