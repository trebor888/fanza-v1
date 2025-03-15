<?php
/**
 * Template Name: Home V3
 */

if(get_field('header_top_advertise') == "Yes") {
    echo '<div class="header-top-advertise">'.get_field('header_top_advertise_content'). '</div>';
}

get_header();

if(get_field('header_bottom_advertise') == "Yes") {
    echo '<div class="header-bottom-advertise">'.get_field('header_bottom_advertise_content'). '</div>';
}


?>

<div id="content">
    <div id="primary">
        <div class="tab-all tab-style-three">
            <ul class="tabs-menu tabs-keep-history" data-target="#tab-contents">
                <li class="active"><a href="#todays-matches">Todays Matches</a></li>
                <li><a href="#latest-news">Latest</a></li>
            </ul>
            <div id="tab-contents" class="tab-content-wrap">
                <div id="todays-matches" class="tab-content active">
                    <?php echo do_shortcode('[fanzalive_matches]') ?>
                </div>
                <div id="latest-news" class="tab-content">
                    <div class="home-cols">
                        <div class="home-col home-col-three">
                            <?php if (is_active_sidebar('home-col-two')) : ?>
                                <?php  dynamic_sidebar('home-col-two'); ?>
                            <?php endif; ?>
                        </div>
                        <div class="home-col home-col-four">
                            <?php if (is_active_sidebar('home-col-three')) : ?>
                                <?php  dynamic_sidebar('home-col-three'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
