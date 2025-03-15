<?php
// Set the Current Author Variable $curauth
global $current_user;
if (isset($_POST['action']) && 'fanzalive_update_follow' == $_POST['action'] && is_user_logged_in()) {
    $cuid=$_POST['current-user']; 
    update_user_meta($reporter_id,'followers_id', $cuid);
    $count_follow = get_user_meta($reporter_id, 'user_count_follow', true);
    $get_follower = get_user_meta($reporter_id, 'followers_id', true);
    update_user_meta($reporter_id,'user_count_follow',$count_follow+1);    
}
$follower =  get_user_meta($cuid, 'followers_id', true);
$current_user_ids=get_current_user_id();

global $match_report_team_side, $event_id;

$team = $match_report_team_side;
$current_team_id = fanzalive_get_event_team_id(get_the_ID(), $team);

if ($reporter_id = fanzalive_get_event_reporter_id(get_the_ID(), $team)) {
    if ($reporter_id == get_current_user_id()) {
        $current_reporter_id = $reporter_id;
        get_template_part('template-parts/event-reporting-form');
    } else {
        $user_photo = get_user_meta($reporter_id, 'user_photo', true);
        if ($user_photo) {
            $img = wp_get_attachment_image_src($user_photo, 'thumbnail');
            $url = $img[0];
        } else {
            $url = get_avatar_url($reporter_id, array('size' => 100));
        }
        ?>
        <div class="match-reporter">
            <div class="match-col">
                <div class="match-report-in">
                    <a style="text-decoration: none;" href="<?php echo get_author_posts_url($reporter_id);?>" title="">
                        <img class="avatar" src="<?php echo $url; ?>" alt="<?php echo get_the_title() ?>" />
                        <h2 style="color: #000;"><?php
                            printf(
                                    __('Match Reporter: %s'), get_user_option('display_name', $reporter_id)
                            );
                            ?>
                        </h2>
    				</a>
    			</div>
				<?php
					if ( is_user_logged_in() ) {
				?>
				<div class="match-report-in">
    				<form action="" method="post">
                        <input type="hidden" name="action" value="fanzalive_update_follow" />
                        <input type="hidden" name="current-user" value="<?php echo get_current_user_id(); ?>" /> 
                        <button name="user_follow" type="submit" <?= ($follower == $current_user_ids ) ? 'disabled' : '' ; ?>>
                        <i class="fa fa-user-plus fa-2x"></i></button> &nbsp;<?= ($follower == $current_user_ids ) ? 'Followed' : 'Follow' ; ?> &nbsp;<?php echo get_user_meta($reporter_id, 'user_count_follow', true);?>
                    </form>
                 </div>
    				<?php
    					} else {
    				?>
    			<div class="match-report-in">
        			<form action="" method="post">
                        <input type="hidden" name="action" value="fanzalive_update_follow" />
                        <input type="hidden" name="current-user" value="<?php echo get_current_user_id(); ?>" />
                        <button name="user_follow" id="follow_btn" type="button"><i class="fa fa-user-plus fa-2x"></i></button>&nbsp;Follow&nbsp;<?php echo get_user_meta($reporter_id, 'user_count_follow', true);?>
                </div>
                <?php
					}
					?>
					<?php if (get_user_option('description', $reporter_id)) {
                    ?><p><?php echo get_user_option('description', $reporter_id); ?></p><?php }
                ?>
            </div>
        </div>
        <?php
            if(get_field('bottom_of_match_reports', $event_id) == 'Yes' || get_field('bottom_of_match_reports', $event_id) == '') {
                if(get_field('bottom_of_match_reports_content', $event_id)) {
                    echo get_field('bottom_of_match_reports_content', $event_id);
                }else {
                    if(get_option('event_bottom_match_report', true) == 'Yes'){
                        if(get_option('event_bottom_match_report_desc', true)) {
                            echo get_option('event_bottom_match_report_desc', true);
                        }
                    }
                } 
            } ?>
        <?php 

        if(is_user_logged_in()) { ?>
        <form method="post" class="fanzalive-form fanzalive-team-commentary-form" id="frm-<?php echo $match_report_team_side ?>" enctype="multipart/form-data" action="<?php echo fanzalive_get_event_team_report_url($event_id, $match_report_team_side); ?>">
            <h3>Commentary</h3>
            <div class="form-field">
                <textarea name="comment" class="usercommentaryComment"></textarea>
            </div>
            <div class="form-submit" style="text-align: right;">
                <input type="submit" value="Submit" />
            </div>
            <input type="hidden" name="id" value="<?php the_ID(); ?>" />
            <input type="hidden" name="team" value="<?php echo $match_report_team_side; ?>" />
            <input type="hidden" name="team_id" value="<?php echo fanzalive_get_event_team_id(get_the_ID(), $match_report_team_side); ?>" />
            <input type="hidden" name="home_team_id" value="<?php echo fanzalive_get_event_team_id($event_id, 'home'); ?>" />
            <input type="hidden" name="away_team_id" value="<?php echo fanzalive_get_event_team_id($event_id, 'away'); ?>" />
            <input type="hidden" name="action" value="fanzalive_insert_user_commentary" />
        </form>
<?php  } }
} else if (fanzalive_user_can_report_for_event_team(get_the_ID(), $team)) {
    if ('result' == fanzalive_get_event_status(get_the_ID())) {
        ?><p><?php _e('Match has ended.'); ?></p><?php
    } else if ('cancelled' == fanzalive_get_event_status(get_the_ID())) {
        ?><p><?php _e('Match was cancelled.'); ?></p><?php
    } else {
        ?><form method="post" action="">
            <input type="hidden" name="team" value="<?php echo $team; ?>" />
            <input type="hidden" name="action" value="fanzalive_assign_reporter" />
            <input type="submit" value="Report for <?php echo fanzalive_get_event_team_name(get_the_ID(), $team); ?>" />
        </form><?php
    }
} else if (is_user_logged_in()) {
    if ('result' == fanzalive_get_event_status(get_the_ID())) {
        ?><p><?php _e('Match has ended.'); ?></p><?php
    } else if ('cancelled' == fanzalive_get_event_status(get_the_ID())) {
        ?><p><?php _e('Match was cancelled.'); ?></p><?php
    } else {
        ?><p><?php _e('Awaiting match reporter'); ?></p><?php
    }
} else {
    if ('result' == fanzalive_get_event_status(get_the_ID())) {
        ?><p><?php _e('Match has ended.'); ?></p><?php
    } else if ('cancelled' == fanzalive_get_event_status(get_the_ID())) {
        ?><p><?php _e('Match was cancelled.'); ?></p><?php
    } else {
        ?><p><?php _e('Become the club reporter.'); ?></p>
        <h4><a href="<?php echo home_url('join-us'); ?>"><?php _e('Join us'); ?></a></h4><?php
    }
}



?>
<div id="LoadingImage-<?php echo $team ?>">
    <img src="<?php echo get_template_directory_uri() . '/assets/images/LoaderIcon.gif'; ?>" />
</div>
<?php 
if(get_field('right_side_of_match_reports', $event_id) == 'Yes' || get_field('right_side_of_match_reports', $event_id) == '') {
    if(get_field('right_side_of_match_reports_content', $event_id)) {
        $evnt_class = 'col-md-9';
    }else {
        if(get_option('event_right_match_report', true) == 'Yes'){
            if(get_option('event_right_match_report_desc', true)) {
                $evnt_class = 'col-md-9';
            }
        }
    } 
}
?>
<div class='event-commentaries single-event-commentaries <?= $evnt_class; ?> ' id="team-comment-<?php echo $team ?>">

</div>
<?php 
if(get_field('right_side_of_match_reports', $event_id) == 'Yes' || get_field('right_side_of_match_reports', $event_id) == '') {
    if(get_field('right_side_of_match_reports_content', $event_id)) {
        echo '<div class="col-md-3">'.get_field('right_side_of_match_reports_content', $event_id). '</div>';
    }else {
        if(get_option('event_right_match_report', true) == 'Yes'){
            if(get_option('event_right_match_report_desc', true)) {
                echo '<div class="col-md-3">'. get_option('event_right_match_report_desc', true). '</div>';
            }
        }
    } 
}
?>
<script>
    jQuery(document).ready(function ($) {
        $.ajax({
            type: 'GET',
            dataType: "html",
            url: '?action=load_comments&team=<?php echo $team; ?>&post_id=<?php echo get_the_ID() ?>',
            //data: jQuery(this).serialize(),
            success: function (response) {
                $("#LoadingImage-<?php echo $team ?>").hide();
                $('#team-comment-<?php echo $team ?>').html(response);
            }
        });
        setInterval(function () {
            var preId = $(".event-commentaries > .event-commentary").attr('id');
            $.ajax({
                type: 'GET',
                dataType: "html",
                url: '?action=load_comments&team=<?php echo $team; ?>&post_id=<?php echo get_the_ID() ?>',
                success: function (response) {
                    response = JSON.parse(response);
                    var latestId = $(response).find('.event-commentaries > .event-commentary').attr('id');
                    if(preId != latestId){
                        $('#team-comment-<?php echo $team ?>').html(response);
                    }
                    
                }
            });
        }, 20000);
        $(document).on('submit', 'form#frm-<?php echo $team; ?>', function (e) {
            e.preventDefault();
            $("#LoadingImage-<?php echo $team ?>").show();
            $form = $(this);
            var formData = new FormData($(this)[0]);
            $.ajax({
                type: 'post',
                url: $form.attr('action'),
                enctype: 'multipart/form-data',
                processData: false,  // Important!
                contentType: false,
                cache: false,
                data: formData,//$form.serialize(),
                success: function () {
                    $.ajax({
                        type: 'GET',
                        dataType: "html",
                        url: '?action=load_comments&team=<?php echo $team; ?>&post_id=<?php echo get_the_ID() ?>',
                        //data: jQuery(this).serialize(),
                        success: function (response) {
                            $("#LoadingImage-<?php echo $team ?>").hide();
                            $('#team-comment-<?php echo $team ?>').html(response);
                            $('#frm-<?php echo $team; ?>').get(0).reset();
                        }
                    });

                setTimeout(function () {   
                   $.ajax({
                        type : "post",
                        url : fanzalive.ajaxurl,
                        data : {
                            action: 'fanzalive_get_scores',
                            eventId: jQuery('.fanzalive-team-commentary-form').find('[name=id]').val(),
                            teamId: jQuery('.fanzalive-team-commentary-form').find('[name=team_id]').val(),
                        },
                        success: function(response) {
                            var results = jQuery.parseJSON(response);
                            var team_nam = $('.tem-name').html();
                            $('#score-firsthalf').val(results.firsthalf);
                            $('#score-secondhalf').val(results.secondhalf);
                            window.location.reload();
                            //$('#latest-news').html(response);
                        }
                    });  
                }, 1000);   
                }
            });
        });
    });
</script>