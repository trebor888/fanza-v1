<?php

add_action('user_register', 'mg_registration', 10, 1);
function mg_registration($user_id) {
    $admin_email = get_option('admin_email');
    // get user data
    $user_info = get_userdata($user_id);
    // create md5 code to verify later
    $code = md5(time());
    // make it into a code to send it to user via email
    $string = array('id' => $user_id, 'code' => $code);
    // create the activation code and activation status
    update_user_meta($user_id, 'account_activated', 0);
    update_user_meta($user_id, 'activation_code', $code);
    // create the url
    $url = get_page_link(get_permalink( get_page_by_title( 'Confirm user' ) )) . '/?uid=' . $user_id . '&act=' . base64_encode(serialize($string));
    $headers = "Reply-To: Fanzalive <" . $admin_email . ">\r\n"
            . "MIME-Version: 1.0\r\n"
            . "Content-Type: text/html; charset=\"utf-8\"\r\n";
    // basically we will edit here to make this nicer
    $html = $user_info->first_name . ' ' . $user_info->last_name . ', in order to complete your registration at Fanzalive you need to confirm your account by following the link below.<br><br> <a href="' . $url . '">' . $url . '</a>';
    // send an email out to user
    wp_mail($user_info->user_email, __('Welcome to Fanzalive.co.uk ', 'text-domain'), $html, $headers);
}


add_action('init', 'mg_verify_user_code');
function mg_verify_user_code() {
    if (isset($_GET['act'])) {
        $admin_email = get_option('admin_email');
        $data = unserialize(base64_decode($_GET['act']));
        $code = get_user_meta($data['id'], 'activation_code', true);
        $user_id = $_GET['uid'];
        $user_info = get_userdata($user_id);

        // verify whether the code given is the same as ours
        if ($code == $data['code']) {
            // update the user meta
            update_user_meta($data['id'], 'is_activated', 1);
            $headers = "Reply-To: Fanzalive <" . $admin_email . ">\r\n"
                    . "MIME-Version: 1.0\r\n"
                    . "Content-Type: text/html; charset=\"utf-8\"\r\n";
            // basically we will edit here to make this nicer
            $html = $user_info->first_name . ' ' . $user_info->last_name . ', your register is now complete. Contact us: <a href="'.get_page_link(13926).'">Contact Us</a>';
            // send an email out to user
            wp_mail($user_info->user_email, __('Welcome to Fanzaline.com - your register is now complete.', 'text-domain'), $html, $headers);
        }
    }
}


add_filter('wp_new_user_notification_email', 'fanzalive_new_user_notification_email', 10, 3);
function fanzalive_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {

    global $fanzalive_new_user_key;
    $auto = fanzalive_welcome_stored();

    if (empty($auto['fromemail'])) {
        $auto['fromemail'] = get_bloginfo('admin_email');
    }
    if (empty($auto['fromname'])) {
        $auto['fromname'] = $blogname;
    }

    $headers = "From: {$auto['fromname']} <{$auto['fromemail']}>\r\n"
. "MIME-Version: 1.0\r\n"
. "Content-Type: text/html; charset=\"utf-8\"\r\n";

    $subject = $auto['subject'];

    $message = '<html>'.$auto['message'];
    $message = str_replace('[username]', $user->user_login, $message );
    $message = str_replace('[password]', network_site_url("wp-login.php?action=rp&key=$fanzalive_new_user_key&login=" . rawurlencode($user->user_login), 'login'), $message );
    $message .= '<html>';

    $wp_new_user_notification_email['header'] = $headers;
    $wp_new_user_notification_email['subject'] = $subject;
    $wp_new_user_notification_email['message'] = $message;

    return $wp_new_user_notification_email;
}

function fanzalive_login_user($data) {
    return wp_signon([
        'user_login'    => $data['log'],
        'user_password' => $data['pwd'],
        'remember'      => isset($data['rememberme'])
    ]);
}

function fanzalive_register_user($data) {
    $password=$data['password'];
    if (empty($data['email'])) {
        return new WP_Error('email_empty', 'Email is required');
    } else if(! is_email($data['email'])) {
        return new WP_Error('email_invalid', 'Invalid email address.');
    } else if(email_exists($data['email'])) {
        return new WP_Error('email_exists', 'Someone has already registered with this email.');
    } else if(empty($data['first_name'])) {
        return new WP_Error('first_name_empty', 'First name is requred.');
    } else if(empty($data['last_name'])) {
        return new WP_Error('last_name_empty', 'Last name is requred.');
    } else if(empty($data['password'])) {
        return new WP_Error('password_empty', 'Password is requred.');
    }else if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password)){
        return new WP_Error('password_notmatch', 'The password does not meet the requirements!');   
    } else if(empty($data['password2'])) {
        return new WP_Error('password2_empty', 'Please retype Password.');
    } else if($data['password'] <> $data['password2']) {
       return new WP_Error('password_mismatched', 'Passwords do not match.');
    } else if(empty($data['term'])) {
       return new WP_Error('term_mismatched', 'Please check term.');
    }

    $email = trim($data['email']);
    $first_name = trim($data['first_name']);
    $last_name = trim($data['last_name']);
    $password = trim($data['password']);
    $password2 = trim($data['password2']);
    $teams = $data['teams'];

    $user_id = wp_insert_user( array(
        'user_email'  => $email,
        'user_login'  => $email,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'user_pass'   => $password
    ));

    if ( is_wp_error($user_id)) {
        return $user_id;
    }

    $teams = [];
    if (array_key_exists('teams', $data)) {
        $teams = get_posts([
            'post_type' => 'sp_team',
            'post__in' => $data['teams'],
            'posts_per_page' => -1
        ]);

        fanzalive_set_user_report_teams($user_id, $data['teams']);
    }

    //do_action('user_register', $user_id);
    if (isset($_FILES['user_photo'])) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $image_id = media_handle_upload('user_photo', 0);
            if (! is_wp_error($image_id)) {
                    update_user_meta($user_id, 'user_photo', $image_id);
            }
    }
    if(isset($data['dob']) && $data['dob'] != ''){
        //update_user_meta($user_id, 'birthday', $data['dob']);
        $date = DateTime::createFromFormat('d/m/Y', $data['dob']);
        $date = $date->format('Ymd');

        update_field('birthday', $date, 'user_'.$user_id);
    }
    $admin_email = get_option('admin_email');

    $headers = "Reply-To: Fanzalive <". $admin_email .">\r\n"
    . "MIME-Version: 1.0\r\n"
    . "Content-Type: text/html; charset=\"utf-8\"\r\n";

    $message = '<html>';
    $message .= '<p>Name: '. $first_name . '</p>';
    $message .= '<p>Email: '. $email . '</p>';
    if (! empty($teams)) {
        $team_names = wp_list_pluck($teams, 'post_title');
        $message .= '<p>Teams: '. join($team_names, ', ') . '</p>';
    }
    $message .= '</html>';

    wp_mail($admin_email, 'New member registered at fanzalive.com', $message, $headers);

    $message = '<html><p>Hi '. $first_name.',</p>';
    $message .= '<p>Thank you for your registering with Fanzalive.</p>';
    $message .= '<p><a href="'. fanzalive_login_page_url() .'">Click Here to Login</a>.</p>';
    $message .= '<p>If you can\'t see the link, copy and paste this URL into your browser: '. fanzalive_login_page_url() .'</p>';
    $message .= '<p>If you have any problems contact us on '. $admin_email .'.</p>';
    $message .= '<p>Regards,</br>The Fanzalive Team</p></html>';

    //wp_mail($email, 'Welcome to Fanzaline.com', $message, $headers);

    return $user_id;
}

function fanzalive_update_profile($data) {
    $user_id = $data['id'];

    $user_data = [
        'ID'            => $user_id,
        'first_name'    => ! empty($data['first_name']) ? $data['first_name'] : '',
        'last_name'     => ! empty($data['last_name']) ? $data['last_name'] : '',
        'description'   => ! empty($data['description']) ? $data['description'] : '',
    ];

    if(! empty($data['password'])) {
        if(empty($data['password2'])) {
            return new WP_Error('password2_empty', 'Please retype Password.');
        } else if($data['password'] <> $data['password2']) {
           return new WP_Error('password_mismatched', 'Passwords do not match.');
        }
        $user_data['user_pass'] = $data['password'];
    }

    $update = wp_update_user($user_data);
    if ( is_wp_error($update)) {
        return $update;
    }

	if (isset($_FILES['user_photo'])) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$image_id = media_handle_upload('user_photo', 0);
		if (! is_wp_error($image_id)) {
			update_user_meta($user_id, 'user_photo', $image_id);
		}
	}
        if($data['phone_number']){
            update_user_meta($user_id, 'phone_number', $data['phone_number']);
            }
        if($data['description']){
            update_user_meta($user_id, 'description', $data['description']);
            }

    if (array_key_exists('teams', $data)) {
        fanzalive_set_user_report_teams($user_id, $data['teams']);
    }

    return $user_id;
}
