<?php
/**
 * Template Name: Edit Profile
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
    'first_name'    => $userdata->first_name,
    'last_name'     => $userdata->last_name,
    'user_photo'    => get_user_meta($userdata->ID, 'user_photo', true),

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
                    <legend>Personal Details</legend>
                    <div class="form-field field-first_name">
                        <label class="field-label" for="first_name">First Name</label>
                        <div class="field-input-wrap"><input type="text" name="first_name" id="first_name" value="<?php echo !empty($data['first_name']) ? $data['first_name'] : ''; ?>" /></div>
                    </div>
                    <div class="form-field field-last_name">
                        <label class="field-label" for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo !empty($data['last_name']) ? $data['last_name'] : ''; ?>" />
                    </div>
                </fieldset>

            <fieldset>
                <legend>Contact Details</legend>
                <div class="form-field field-email">
                    <label class="field-label" for="email">Email</label>
                    <div class="field-input-wrap"><input type="text" name="email" readonly id="email" value="<?php echo !empty($userdata->user_email) ? $userdata->user_email : ''; ?>" /></div>
                </div>

            </fieldset>

            <fieldset>
                <legend>Profile Description</legend>
                <div class="form-field field-email">
                    <label class="field-label" for="description"></label>
                    <textarea name="description" id="description" cols="30" rows="10"><?php echo get_user_meta($user_id,'description', true)?get_user_meta($user_id,'description', true):''; ?></textarea>
                </div>
            </fieldset>

            <fieldset>
                <legend>Profile Image</legend>
                <div class="form-field">
                    <input name="user_photo" type="file" id="user_photo" /><?php
                    if (! empty($data['user_photo'])) {
                        ?><p><?php echo wp_get_attachment_image($data['user_photo'], 'thumbnail'); ?></p><?php
                    }
                    ?>
                </div>
            </fieldset>

            <fieldset>
                <legend>Password (to change)</legend>
                <div class="form-field field-password">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-input-wrap"><input type="password" name="password" id="password" /></div>
                </div>
                <div class="form-field field-password2">
                    <label class="field-label" for="password2">Password again</label>
                    <div class="field-input-wrap"><input type="password" name="password2" id="password2" /></div>
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
