<?php
global $match;

?><tr id="match-<?php echo $match['id']; ?>" class="row-match status-<?php echo $match['status']; ?>">
    <td class="col-home"><?php
        printf(
            '<a href="%s" class="match-team-link">%s</a>',
            add_query_arg('team', 'home', $match['url']),
            $match['team_home']['name']
        );

        if ($match['team_home']['reporting_ended']) {
            // reporting has ended
        } else if ($match['team_home']['reporting_started']) {
            printf(
                '<div class="team-reporting-live"><a href="%s">Live</a></div>',
                add_query_arg('team', 'home', $match['url'])
            );
        } else if (! empty($match['team_home']['reporter'])) {
            printf(
                '<div class="team-reporter">Reporter: %s</div>',
                $match['team_home']['reporter']
            );
        } else if (! empty($match['team_home']['can_report'])) {
            printf(
                '<div class="report-it"><a href="%s">Report It</a></div>',
                add_query_arg('team', 'home', $match['url'])
            );
        }
        ?>
    </td>
    <td class="col-status"><?php
        if (! empty($match['match_day'])) {
            printf(
                '<div class="match-day">%s</div>',
                $match['match_day']
            );
        }

        if ('live' === $match['status']) {
            printf(
                '<a href="%s">
                    <div class="scores"><span>%s</span><span>%s</span></div>
                    <div class="status-code"><span class="live">%s</span> <span class="minutes-played">%s<span></div>
                </a>',
                get_permalink($match['id']),
                $match['team_home']['score'],
                $match['team_away']['score'],
                __('LIVE'),
                in_array($match['status_code'], ['HT']) ? $match['status_code'] : $match['minutes_played'] . '\''
            );
        } elseif ('completed' === $match['status']) {
            printf(
                '<a href="%s">
                    <div class="scores"><span>%s</span><span>%s</span></div>
                    <div class="status-code">%s</div>
                </a>',
                $match['url'],
                $match['team_home']['score'],
                $match['team_away']['score'],
                $match['status_code']
            );
        } elseif (in_array($match['status'], ['cancelled', 'postponed'])) {
            printf(
                '<a href="%s">
                    <div class="scores"><span>%s</span><span>%s</span></div>
                    <div class="status-text">%s</div>
                </a>',
                $match['url'],
                mysql2date('H', $match['date']),
                mysql2date('i', $match['date']),
                $match['status_text']
            );
        } else {
            printf(
                '<a href="%s">
                    <div class="hours">%s:%s</div>
                </a>',
                $match['url'],
                mysql2date('H', $match['date']),
                mysql2date('i', $match['date'])
            );
        }
    ?></td>
    <td class="col-away"><?php
        printf(
            '<a href="%s" class="match-team-link">%s</a>',
            add_query_arg('team', 'away', $match['url']),
            $match['team_away']['name']
        );

        if ($match['team_away']['reporting_ended']) {
            // reporting has ended
        } else if ($match['team_away']['reporting_started']) {
            printf(
                '<div class="team-reporting-live"><a href="%s">Live</a></div>',
                add_query_arg('team', 'away', $match['url'])
            );
        } else if (! empty($match['team_away']['reporter'])) {
            printf(
                '<div class="team-reporter">Reporter: %s</div>',
                $match['team_away']['reporter']
            );
        } else if (! empty($match['team_away']['can_report'])) {
            printf(
                '<div class="report-it"><a href="%s">Report It</a></div>',
                add_query_arg('team', 'away', $match['url'])
            );
        }
    ?>
    </td>
</tr>
