<?php
/**
 * Template Name: Edit Team
**/

if (! is_user_logged_in()) {
    wp_redirect(fanzalive_login_page_url());
    exit;
}

$updated = isset($_REQUEST['updated']) ? true : false;
$form_error = '';
$user_id=get_current_user_id();
$userdata = get_userdata(get_current_user_id());

#update_user_meta($userdata->ID, 'user_photo', 466859);
#fanzalive_p(get_user_meta($userdata->ID));

$data = [
    'teams'         => fanzalive_get_user_report_teams($userdata->ID),

];

if (isset($_POST['action']) && 'fanzalive_update_profile' == $_POST['action']) {
    $data = stripslashes_deep($_POST);
    unset($data['action']);

    $data['id'] = get_current_user_id();
    $update = fanzalive_update_profile($data);
    if (is_wp_error($update)) {
        $form_error = $update->get_error_message();
    } else {
        wp_redirect(add_query_arg('updated', 1));
        exit;
    }
}


?>
<?php get_header(); ?>

<div id="content" class="clearfix">
    <div id="primary" >
    <?php
    if(have_posts()) :
        while(have_posts()) :
        the_post(); ?>

        <div class="entry single">
            <?php if(is_user_logged_in()) { ?>
                <div class="fanza-author-menu author-menu">
                    <ul>
                        <li>
                            <a href="<?php echo home_url('/edit-profile'); ?>">Edit Profile</a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/edit-team'); ?>">Change Team</a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/add-posts'); ?>">Post News
                                <?php 
                                    $today = getdate();
                                    $args = array(
                                        'author'        =>  $current_user->ID, 
                                        'orderby'       =>  'post_date',
                                        'order'         =>  'ASC',
                                        'year' => $today['year'],
                                        'monthnum' => $today['mon'], 
                                    );
                                    $the_query = new WP_Query( $args );
                                    if($the_query->post_count>=2) {
                                        echo '<span class="post-count green">'.$the_query->post_count.'</span>'; 
                                    }else{ 
                                        echo '<span class="post-count red">'.$the_query->post_count.'</span>';
                                    }               
                                ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/messages'); ?>">Messages</a>
                        </li>
                        <li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            <?php } ?>
            <?php the_title('<h1>', '</h1>'); ?>
            <section><?php

            the_content();

            if ($updated) { ?>
                <p>Profile updated.</p>
            <?php } ?>
            <?php if (! empty($form_error)) { ?>
                <p style="color:red;"><?php echo $form_error; ?></p>
    		<?php } ?>
            <form method="post" class="fanzalive-form profile-edit-form" enctype="multipart/form-data">

            <fieldset>
                <legend>Change Teams</legend>
                <div class="form-field field-teams">
                    <span class="field-label">Select teams you want to report for?</span>
                    <div class="tab-content-wrap tab-all"><?php
                        $leagues = get_terms([
                            'taxonomy'  => 'sp_league',
                            'orderby'   => 'meta_value_num',
                            'meta_key'  => 'sp_order',
                            'hide_empty'=> false
                        ]);
                        ?><select class="tabs-dropdown" data-target="#leagues-content"><?php
                        foreach ($leagues as $league) {
                            printf(
                                '<option value="#league-%d">%s</option>',
                                $league->term_id,
                                $league->name
                            );
                        }
                        ?></select>
                        <div id="leagues-content"><?php
                            $active = false;
                            foreach ($leagues as $league) {
                                ?><div id="league-<?php echo $league->term_id; ?>" class="tab-content<?php if (! $active) {$active=true; echo ' active';} ?>">
                                    <div class="field-input-wrap">
                                    <?php
                                    $teams = get_posts([
                                        'post_type' => 'sp_team',
                                        'posts_per_page' => -1,
                                        'tax_query' => [
                                            [
                                                'taxonomy' => 'sp_league',
                                                'terms' => $league->term_id
                                            ]
                                        ]
                                    ]);
                                    foreach ($teams as $team) {
                                        printf(
                                            '<label><input type="checkbox" name="teams[]" value="%d"%s /> %s</label>',
                                            $team->ID,
                                            ! empty($data['teams']) && in_array($team->ID, $data['teams']) ? ' checked="checked"' : '',
                                            $team->post_title
                                        );
                                    }
                                    ?></div>
                                </div><?php
                                }
                            ?></div>
                        </div>
                    </div>
                </fieldset>


                <div class="form-field field-submit">
                    <input type="submit" id="submit" value="Update" />
                    <input type="hidden" name="action" value="fanzalive_update_profile" />
                </div>
            </form>
            </section>
        </div><?php
        endwhile;
    endif; ?>
    </div>
</div>

<?php get_footer(); ?>
