<?php
/*
Template Name: Registration
*/

$registered = isset($_REQUEST['registered']) ? true : false;
$form_error = '';
$data = [];
if (isset($_POST['action']) && 'fanzalive_register' == $_POST['action']) {
    $data = stripslashes_deep($_POST);
    unset($data['action']);

    $registered_user = fanzalive_register_user($data);
    if (is_wp_error($registered_user)) {
        $form_error = $registered_user->get_error_message();
    } else {
        wp_redirect(add_query_arg(array(
            'registered' => 1,
            'id' => $registered_user
        )));
        exit;
    }

} else if (is_user_logged_in()) {
    wp_redirect(fanzalive_edit_profile_page_url());
    exit;
}

?>
<?php get_header();  ?>
<div id="content" class="clearfix">
    <div id="primary" >
    <?php
    if(have_posts()) :
        while(have_posts()) :
        the_post(); ?>
        <div class="entry single">
            <?php the_title('<h1>', '</h1>'); ?>
            <section><?php

            the_content();

            if ($registered) : 
                $code = md5(time());
                update_user_meta( $registered_user, 'is_activated', $code );
                $user = get_userdata($_REQUEST['id']); ?>
                <h2>Thanks <?php echo $user->data->display_name; ?></h2>
                <p class="sucess-msg">You're almost done! To complete sign up, please follow the link in the email that's been sent to you.</p>
            <?php else : ?>
                <?php if (! empty($form_error)) { ?>
                    <p class="form-notice error"><?php echo $form_error; ?></p>
                <?php } else { ?>
                    <p class="form-notice error">All fields are required</p>
                <?php } ?>
                <form method="post" class="fanzalive-form registration-form" enctype="multipart/form-data">
                    <div class="form-field field-email">
                        <label class="field-label" for="email">Email</label>
                        <div class="field-input-wrap"><input type="text" name="email" id="email" value="<?php echo !empty($data['email']) ? $data['email'] : ''; ?>" /></div>
                        <p class="field-description">You cannot change this once you have registered</p>
                    </div>
                    <div class="form-field field-password">
                        <label class="field-label" for="password">Password</label>
                        <div class="field-input-wrap"><input type="password" name="password" id="password" />
                         <p class="field-description">Passwords contain capital letter and at least a number.</p>
						<div id="errorDiv"></div>
						</div>
                    </div>
                    <div class="form-field field-password2">
                        <label class="field-label" for="password2">Password again</label>
                        <div class="field-input-wrap"><input type="password" name="password2" id="password2" /></div>
                    </div>
                    <div class="form-field">
                        <label for="user_photo">Profile Photo</label>
                        <input name="user_photo" type="file" id="user_photo" />
                    </div>
                    <div class="form-field field-teams">
                        <span class="field-label">Select teams you want to report for? Up to 2 teams from different divisions only</span>
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
                    <div class="form-field field-first_name half">
                        <!-- <label class="field-label" for="first_name">First Name</label> -->
                        <div class="field-input-wrap"><input type="text" name="first_name" id="first_name" value="<?php echo !empty($data['first_name']) ? $data['first_name'] : ''; ?>" placeholder="First Name" /></div>
                    </div>
                    <div class="form-field field-last_name half">
                        <!-- <label class="field-label" for="last_name">Last Name</label> -->
                        <input type="text" name="last_name" id="last_name" value="<?php echo !empty($data['last_name']) ? $data['last_name'] : ''; ?>" placeholder="Last Name" />
					</div>
                    <div class="form-field half">
                        <?php echo do_shortcode('[bws_google_captcha]') ?>
                    </div>
                    <div class="form-field half">
                        <input type="checkbox" name="term" value="1" /> I agree to the <a href="https://www.fanzalive.co.uk/terms/">Terms</a> and <a href="https://www.fanzalive.co.uk/privacy-policy/">Privacy Policy.</a>
                    </div>
                    <div class="form-field field-submit">
                        <input type="submit" id="submit" value="Join Us" />
                        <input type="hidden" name="action" value="fanzalive_register" />
                    </div>
                </form><?php
            endif;
            ?></section>
        </div><?php
        endwhile;
    endif; ?>
    </div>
</div>
<!-- <script>
jQuery("#password").keyup(function () {
//alert('hello');
    var firstCharacter = jQuery("#password").val().substring(0, 1);
    if (jQuery("#password").val().length < 6) {
        jQuery("#errorDiv").html("Password must be at least 6 characters long");
        setTimeout(function () { 
            jQuery("#errorDiv").html("");                                                  
            jQuery("#submit").prop('disabled', true); 
        }, 1000);
    }
    else if (/^[A-Z]+$/.test(firstCharacter) == false) {
       jQuery("#errorDiv").html("Password should start with capital alphabet");
        setTimeout(function () { 
           jQuery("#errorDiv").html(""); 
           jQuery("#submit").prop('disabled', true); 
        }, 1000);
    }
    else if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test($("#password").val()) == false) {
        jQuery("#errorDiv").html("Password must have at least 1 special character");
        setTimeout(function () { 
            jQuery("#errorDiv").html(""); 
            jQuery("#submit").prop('disabled', true); 
         }, 1000);
    }else
        jQuery("#submit").prop('disabled', false);
 
});</script> -->
<?php get_footer(); ?>
