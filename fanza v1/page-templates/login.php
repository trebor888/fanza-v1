<?php
/*
 * Template Name: Login
*/

$form_error = '';
$data = [];
if (isset($_POST['action']) && 'fanzalive_login' == $_POST['action']) {
    $data = stripslashes_deep($_POST);
    unset($data['action']);

    $login_user = fanzalive_login_user($data);
    if (is_wp_error($login_user)) {
        $form_error = $login_user->get_error_message();
    } else {
        $redirect_to = ! empty($data['redirect_to']) ? $data['redirect_to'] : fanzalive_edit_profile_page_url();
        wp_redirect($redirect_to);
        exit;
    }
}
?>
<?php get_header(); ?>
<div id="content" class="clearfix">
    <div id="primary"><?php

        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?><div class="entry">
                    <h1><?php the_title(); ?></h1>
                    <section><?php
                        the_content();
                        if (! empty($form_error)) {
                            printf('<p class="form-notice error">%s</p>', $form_error);
                        }
                        ?><form method="post" class="fanzalive-form login-form">
                            <div class="form-field field-email">
                                <label class="field-label" for="user_login">Email or Username</label>
                                <div class="field-input-wrap"><input type="text" name="log" id="user_login" value="<?php echo !empty($data['log']) ? $data['log'] : ''; ?>" /></div>
                            </div>
                            <div class="form-field field-password">
                                <label class="field-label" for="user_pass">Password</label>
                                <div class="field-input-wrap"><input type="password" name="pwd" id="user_pass" /></div>
                            </div>
                            <div class="form-field field-rememberme">
                                <label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember Me</label>
                            </div>
                            <div class="form-field field-submit">
                                <input type="submit" id="submit" value="Login" />
                                <input type="hidden" name="action" value="fanzalive_login" />
                                <input type="hidden" name="redirect_to" value="<?php echo isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '' ?>" />
                            </div>
                        </form><?php
                        # wp_login_form(array('redirect' => site_url()));
                    ?></section>
                </div><?php
            endwhile;
        else :
            ?><h1><?php _e('Not Found','ld'); ?></h1><?php
        endif;
    ?></div>
</div>
<?php get_footer(); ?>
