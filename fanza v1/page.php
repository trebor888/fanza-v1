<?php get_header(); ?>
<div id="content" class="clearfix">
    <div id="primary">
        <?php if(have_posts()) : ?>
        <?php while(have_posts()) : the_post(); ?>
        <div <?php post_class('entry single'); ?>>
			<?php get_template_part('template-parts/breadcrumb'); ?>
            <?php do_action('before_title'); ?>
            <h1><?php the_title(); ?></h1>
            <section>
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
    </div>
</div>
<?php get_footer(); ?>
