<?php
global $standings;
?><table class="standing-table">
<thead><tr><?php
    foreach($standings['headers'] as $header) {
        printf('<th class="%s">%s</th>', $header['css_class'], $header['value']);
    }
?></tr></thead><tbody><?php
if (! empty($standings['rows'])) {
    foreach($standings['rows'] as $row) {
        if (isset($row['team_id'])) {
            echo '<tr class="team-'. $row['team_id'] .'">';
        } else {
            echo '<tr>';
        }

        $link_posts = get_option( 'sportspress_link_players', 'yes' ) == 'yes' ? true : false;

        if ( $link_posts ):
            $permalink = get_post_permalink( $row['team_id'] );
            $column .= '<a href="' . $permalink . '">' . $name . '</a>';
        endif;

        
            
        foreach($row as $key => $column) {
            if (isset($standings['headers'][$key])) {
                if($standings['headers'][$key]['css_class'] == 'col-team') {
                    echo '<td class="data-name' . $name_class . $td_class . '"><a href="' . $permalink . '">' . $column . '</a></td>';
                }else {
                    printf('<td class="%s">%s</td>', $standings['headers'][$key]['css_class'], $column);
                }
                
                
            }
            
        }
        echo '</tr>';
    }
} else {
    printf('<tr><td colspan="%d" class="no-items">%s</td></tr>', count($standings['headers']), __('Standing unavailable'));
}
?></tbody></table>
