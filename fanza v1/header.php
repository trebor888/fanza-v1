<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head><meta name="p:domain_verify" content="5120e70bb27a7446ee696b1a3f534ab4"/><!-- <script data-ad-client="ca-pub-9734613839794911" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><script data-ad-client="ca-pub-9734613839794911" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
    <meta http-equiv="content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="google-site-verification" content="PWkqIB44dEaB77wogPP6htuWtGrxgKDq0RmVSbC5PyE" />	
<meta name="msvalidate.01" content="1163C476BAF1BD96E0AFA13D673F52C3" />

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0', shrink-to-fit=no">
    <?php wp_head(); ?>

<meta name="theme-color" content="#001a39" /></head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-3TNH6GPS55"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-3TNH6GPS55');
</script>	
<body <?php body_class(); ?>>
    <div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v8.0" nonce="4DiMZTIy"></script>
<div id="wrapper">
    <header id="header" class="dark">
        <div class="container">
         <nav class="user-menu">
                <ul class="menu"><?php
                global $current_user;
                if (is_user_logged_in()) {
                    $current_user = wp_get_current_user(); ?>
                        <li class="menu-item menu-item-has-children">
                            <a href="#"><?php
                                printf(
                                    '<img src="%s" class="user-avatar" />',
                                    fanzalive_get_user_photo($current_user->ID)
                                );
                                printf(
                                    '<span>%s</span>',
                                    fanzalive_get_user_first_name($current_user->ID)
                                );
                                get_currentuserinfo();
                            ?></a>
                            <ul class="sub-menu">
                                <li class="menu-item"><a href="<?php echo get_author_posts_url($current_user->ID); ?>"><i class="fa fa-pencil-square-o menu-icon"></i>My Page </a></li>
                                <li class="menu-item"><a href="<?php echo home_url('/edit-profile'); ?>"><i class="fa fa-pencil-square-o menu-icon"></i> Edit profile</a></li>
                                 <li class="menu-item"><a href="<?php echo home_url('/edit-team'); ?>"><i class="fa fa-pencil-square-o menu-icon"></i> Change Team</a></li>
                                <li class="menu-item"><a href="<?php echo home_url('/add-posts'); ?>"><i class="fa fa-info menu-icon"></i>Post News </a></li>
                                <li class="menu-item"><a href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa fa-sign-out menu-icon"></i> Log Out</a></li>
                            </ul>
                        </li>
                    <?php
                } else {
                    $redirect_to = is_singular() ? get_permalink() : home_url();
                    ?>
                    <li><a href="<?php echo home_url('/login?redirect_to='. esc_url($redirect_to)); ?>"><i class="fa fa-user-circle-o"></i> Sign In</a></li>
                    <?php
                }
                ?>
                </ul>
                <div class="tagline-pro">
                    <a class="logo-link" href="<?php echo home_url(); ?>">
                        <span class="logo-tagline" ><?php echo get_bloginfo ('description'); ?></span>
                    </a>
                </div>
            </nav>
            <div class="logo">
                <div class="inner">
                    <a class="logo-link" href="<?php echo home_url(); ?>">
                        <span class="logo-title"><?php echo get_bloginfo('name'); ?></span>
                    </a>
                </div>
            </div>
           
        </div>
        <nav id="main-menu">
            <div class="container"><?php wp_nav_menu([
                'theme_location' => is_user_logged_in() ? 'secondary' : 'primary',
                'container' => false
            ]); ?></div>
        </nav>
    </header>
