<?php
function fanzalive_get_user_first_name($user_id)
{
    $user = get_userdata($user_id);

    if (! empty($user->first_name)) {
        return $user->first_name;
    } else if (! empty($user->display_name)) {
        $name_parts = explode(' ', $user->display_name);
        return $name_parts[0];
    } else {
        return $user->user_login;
    }
}

function fanzalive_get_user_photo($user_id)
{
    $user_photo = get_user_meta($user_id, 'user_photo', true);
    if (! empty($user_photo)) {
        return wp_get_attachment_image_src($user_photo, 'thumbnail')[0];
    }

    return get_template_directory_uri() . '/assets/images/profile-default.png';
}

add_filter('avatar_defaults', 'fanzalive_filter_user_avatar');
function fanzalive_filter_user_avatar($avatar_defaults)
{
    $userId= get_current_user_id();
    $user_photo = get_user_meta($userId, 'user_photo', true);
    if ($user_photo) {
        $img = wp_get_attachment_image_src($user_photo, 'thumbnail');
        $url = $img[0];
    } else {
        $url = "https://www.fanzalive.co.uk/wp-content/uploads/profile-default.png";
    }

    $myavatar = $url;
    $avatar_defaults[$myavatar] = "Default Gravatar";

    return $avatar_defaults;
}
