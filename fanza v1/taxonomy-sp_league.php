<?php 

$queried_object = get_queried_object();
$term_id = $queried_object->term_id;

if(get_field('header_top_advertise', $queried_object) == 'Yes' || get_field('header_top_advertise', $queried_object) == '') {
    if(get_field('header_top_advertise_content', $queried_object)) {
        echo get_field('header_top_advertise_content', $queried_object);
    }else {
        if(get_option('league_header_top', true) == 'Yes'){
            if(get_option('league_header_top_desc', true)) {
                echo get_option('league_header_top_desc', true);
            }
        }
    } 
}


get_header(); 


if(get_field('header_bottom_advertise', $queried_object) == 'Yes' || get_field('header_bottom_advertise', $queried_object) == '') {
    if(get_field('header_bottom_advertise_content', $queried_object)) {
        echo get_field('header_bottom_advertise_content', $queried_object);
    }else {
        if(get_option('league_header_bottom', true) == 'Yes'){
            if(get_option('league_header_bottom_desc', true)) {
                echo get_option('league_header_bottom_desc', true);
            }
        }
    } 
}


?>
<div id="content">
    <div id="primary">
        <?php get_template_part('template-parts/breadcrumb'); ?>
        <h1><?php echo single_term_title(); ?></h1>

        <div class="tab-all tab-style-three">
            <ul class="tabs-menu tabs-keep-history" data-target="#tab-contents">
                <li class="active"><a href="#todays-matches">Fixtures/Scores</a></li>
                <li><a href="#table">Table</a></li>
            </ul>
            <div id="tab-contents" class="tab-content-wrap">
                    <div id="todays-matches" class="tab-content active">
                        <?php echo do_shortcode('[fanzalive_matches league_id="'. get_queried_object_id() .'"]'); ?>
                    </div>
                    <div id="table" class="tab-content">
                        <?php 
                            if(get_field('right_side_of_leagues_in_tab', $queried_object) == 'Yes' || get_field('right_side_of_leagues_in_tab', $queried_object) == '') {
                                if(get_field('right_side_of_leagues_in_tab_content', $queried_object)) {
                                    $leag_class = 'col-md-9';
                                }else {
                                    if(get_option('league_right_tables_tab', true) == 'Yes'){
                                        if(get_option('league_right_tables_tab_desc', true)) {
                                            $leag_class = 'col-md-9';
                                        }
                                    }
                                } 
                            }
                        ?>
                        <div class="<?= $leag_class; ?>">
                            <?php echo do_shortcode('[fanzalive_standing_table league_id="'. get_queried_object_id() .'"]'); ?>
                        </div>
                        <?php 
                            if(get_field('right_side_of_leagues_in_tab', $queried_object) == 'Yes' || get_field('right_side_of_leagues_in_tab', $queried_object) == '') {
                                if(get_field('right_side_of_leagues_in_tab_content', $queried_object)) {
                                    echo '<div class="col-md-3">'.get_field('right_side_of_leagues_in_tab_content', $queried_object). '</div>';
                                }else {
                                    if(get_option('league_right_tables_tab', true) == 'Yes'){
                                        if(get_option('league_right_tables_tab_desc', true)) {
                                            echo '<div class="col-md-3">'.get_option('league_right_tables_tab_desc', true). '</div>';
                                        }
                                    }
                                } 
                            }
                        ?>
                    </div>
            </div>
        </div>
    </div><!--#primary-->
</div><!--#content-->

<?php get_footer(); ?>
