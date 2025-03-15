<?php
global $event, $event_id;

?><tr id="post-<?php echo $event_id; ?>" class="row-event">
    <td class="col-time"><?php
        printf(
            '<a href="%s">%s</a>',
            get_permalink($event),
            get_post_time(get_option('time_format'), false, $event, true)
        );
    ?></td>
    <td class="col-home">
        <div class="team"><?php
            printf(
                '<a href="%s"%s>%s</a>',
                add_query_arg('team', 'home', get_permalink($event_id)),
                (fanzalive_get_event_team_id($event_id, 'home') && $color = get_post_meta(fanzalive_get_event_team_id($event_id, 'home'), 'team_color', true)) ? ' style="color:'.$color.'"' : '',
                fanzalive_get_event_team_name($event_id, 'home')
            );
        ?></div><?php

        if (fanzalive_has_event_ended($event_id, 'home')) {
            if ($home_goals = fanzalive_get_event_team_goals($event_id, 'home')) {
                echo $home_goals;
            } else {
                echo '0';
            }
        } else if (fanzalive_has_event_started($event_id, 'home')) {
            echo '<span class="live">Live</span>';
        } else if ('publish' == get_post_status($event)) {
            if ($home_goals = fanzalive_get_event_team_goals($event_id, 'home')) {
                echo $home_goals;
            } else {
                echo '0';
            }
        } else if ($home_reporter_id = fanzalive_get_event_reporter_id($event_id, 'home')) {
            printf(
                'Reporter: %s',
                get_user_option('display_name', $home_reporter_id)
            );
        } else if(fanzalive_user_can_report_for_event_team($event_id, 'home')) {
            printf(
                '<div class="report-it"><a href="%s">Report It</a></div>',
                fanzalive_get_event_team_report_url($event_id, 'home')
            );
        }
        ?>
    </td>
    <td class="col-away">
        <div class="team"><?php
            printf(
                '<a href="%s"%s>%s</a>',
                add_query_arg('team', 'away', get_permalink($event_id)),
                (fanzalive_get_event_team_id($event_id, 'away') && $color = get_post_meta(fanzalive_get_event_team_id($event_id, 'away'), 'team_color', true)) ? ' style="color:'.$color.'"' : '',
                fanzalive_get_event_team_name($event_id, 'away')
            );
        ?></div><?php
        if (fanzalive_has_event_ended($event_id, 'away')) {
            if ($home_goals = fanzalive_get_event_team_goals($event_id, 'away')) {
                echo $home_goals;
            } else {
                echo '0';
            }
        } else if (fanzalive_has_event_started($event_id, 'away')) {
            echo '<span class="live">Live</span>';
        } else if ('publish' == get_post_status($event)) {
            if ($away_goals = fanzalive_get_event_team_goals($event_id, 'away')) {
                echo $away_goals;
            } else {
                echo '0';
            }
        } else if ($home_reporter_id = fanzalive_get_event_reporter_id($event_id, 'away')) {
            printf(
                'Reported by: %s',
                get_user_option('display_name', $home_reporter_id)
            );
        } else if(fanzalive_user_can_report_for_event_team($event_id, 'away')) {
            printf(
                '<div class="report-it"><a href="%s">Report It</a></div>',
                fanzalive_get_event_team_report_url($event_id, 'away')
            );
        }
        ?>
    </td>
</tr>
