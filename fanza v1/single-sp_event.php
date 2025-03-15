<?php
global $team, $current_team_id, $current_reporter_id, $event_id;

$event_id = get_the_ID();
$team = isset($_REQUEST['team']) ? $_REQUEST['team'] : 'home';
$current_team_id = fanzalive_get_event_team_id(get_the_ID(), $team);

$leagues = wp_get_object_terms(get_the_ID(), 'sp_league', ['fields' => 'all']);
if (! empty($leagues)) {
	$league = array_shift($leagues);
	$leagueTableShortcode = get_term_meta($league->term_id, 'league_table_shortcode', true);
	$liveResultsShortcode = get_term_meta($league->term_id, 'live_results_shortcode', true);
}


if(get_field('header_top_advertise', $event_id) == 'Yes' || get_field('header_top_advertise', $event_id) == '') {
    if(get_field('header_top_advertise_content', $event_id)) {
        echo get_field('header_top_advertise_content', $event_id);
    }else {
        if(get_option('event_header_top', true) == 'Yes'){
            if(get_option('event_header_top_desc', true)) {
                echo get_option('event_header_top_desc', true);
            }
        }
    } 
}

?>
<?php get_header();

$banner_style = '';
if ($bg_color = get_field('team_background_color', $current_team_id)) {
	$banner_style .= 'background-color:'. $bg_color .';';
}

$match_status = get_post_meta(get_the_ID(), '_status', true);
if (! $match_status) {
    $match_status = 'fixture';
}

?>
<div class="clubbanner" <?php echo $banner_style ? ' style="'. $banner_style .'"' : ''; ?>>
<p style="color:#fff;text-align: center;"><span style="padding-right: 10px"><?php echo get_the_date(); ?></span><span><?php the_time( 'g:i a' ); ?> </span></p>
<h1><?php the_title(); ?></h1>

</div>

<?php 

if(get_field('header_bottom_advertise', $event_id) == 'Yes' || get_field('header_bottom_advertise', $event_id) == '') {
    if(get_field('header_bottom_advertise_content', $event_id)) {
        echo get_field('header_bottom_advertise_content', $event_id);
    }else {
        if(get_option('event_header_bottom', true) == 'Yes'){
            if(get_option('event_header_bottom_desc', true)) {
                echo get_option('event_header_bottom_desc', true);
            }
        }
    } 
}

?>
<div id="content">
	<div id="primary">
		<div <?php post_class('entry single'); ?>>
		<?php
		if (have_posts()) :
			while (have_posts()) :
			the_post();
			?>
			<?php //get_template_part('template-parts/breadcrumb'); ?>
			<div class="match-tabs tab-all tab-style-one">
                <ul class="tabs-menu tabs-keep-history" data-target="#match-content">
                <?php if ('live' === $match_status) : ?>
                    <li class="active"><a href="#report"><?php _e('Live Reporting'); ?></a></li>
                <?php else:; ?>
                    <li class="active"><a href="#report"><?php _e('Match Report'); ?></a></li>
                <?php endif; ?>

                <?php if (in_array($match_status, ['live', 'completed'])) : ?>
                    <li><a href="#statistics"><?php _e('Stats'); ?></a></li>
                    <li><a href="#lineup"><?php _e('Lineup'); ?></a></li>
                <?php endif; ?>

				<?php if (! empty($liveResultsShortcode)) : ?>
                    <li><a href="#results"><?php _e('Scores'); ?></a></li>
		        <?php endif; ?>
				<?php if (! empty($leagueTableShortcode)) : ?>
                    <li><a href="#standings"><?php _e('Tables'); ?></a></li>
				<?php endif; ?>
                  <li><a href="#news"><?php _e('News'); ?></a></li>
                   </ul>

				<div id="match-content">
					<div id="report" class="tab-content active liTab">
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
                            }
                        ?>
                        <?php if (in_array($match_status, ['postponed'])) : ?>
                            <p style="color:red;"><?php _e('Match Posponded', 'faaf'); ?></p>
                        <?php else: ?>
						<div class="match-report-tabs tab-all tab-style-two">
							<ul class="tabs-menu" data-target="#match-report-tab-contents">
								<li class="<?php echo 'home' == $team ? 'active' : ''; ?>"><a href="#match-report-home">
                                <?php
									echo '<div class="tem-name" data-name='.fanzalive_get_event_team_name($event_id, 'home').'>'.fanzalive_get_event_team_name($event_id, 'home');
                                    echo '<span class="hlp_goals home_scor"> ('.fanzalive_get_event_team_goals($event_id, 'home').')</span></div>';
                                   
								?></a></li>
								<li class="<?php echo 'away' == $team ? 'active' : ''; ?>"><a href="#match-report-away"><?php
									echo '<div class="tem-name" data-name='.fanzalive_get_event_team_name($event_id, 'away').'>'.fanzalive_get_event_team_name($event_id, 'away');
                                    echo '<span class="hlp_goals away_scor"> ('.fanzalive_get_event_team_goals($event_id, 'away').')</span></div>';
								?></a></li>
							</ul>
							<div id="match-report-tab-contents">
								<div class="tab-content  <?php echo 'home' == $team ? 'active' : ''; ?>" id="match-report-home">
									<?php
									$match_report_team_side = 'home';
									get_template_part('template-parts/team-match-report');
									?>
								</div>
								<div class="tab-content <?php echo 'away' == $team ? 'active' : ''; ?>" id="match-report-away">
									<?php
									$match_report_team_side = 'away';
									get_template_part('template-parts/team-match-report');
									?>
								</div>
							</div>
						</div>
                        <?php endif; ?>
					</div><!--.report-->
                    <?php if (in_array($match_status, ['live', 'completed'])) : ?>
                        <div id="statistics" class="tab-content liTab">
                            <?php
                            if(get_field('bottom_of_status_tab', $event_id) == 'Yes' || get_field('bottom_of_status_tab', $event_id) == '') {
                                if(get_field('bottom_of_status_tab_content', $event_id)) {
                                    echo get_field('bottom_of_status_tab_content', $event_id);
                                }else {
                                    if(get_option('event_bottom_status_tab', true) == 'Yes'){
                                        if(get_option('event_bottom_status_tab_desc', true)) {
                                            echo get_option('event_bottom_status_tab_desc', true);
                                        }
                                    }
                                } 
                            }

                            if(get_field('right_side_of_status_tab', $event_id) == 'Yes' || get_field('right_side_of_status_tab', $event_id) == '') {
                                if(get_field('right_side_of_status_tab_content', $event_id)) {
                                    $stat_class = 'col-md-9';
                                }else {
                                    if(get_option('event_right_status_tab', true) == 'Yes'){
                                        if(get_option('event_right_status_tab_desc', true)) {
                                            $stat_class = 'col-md-9';
                                        }
                                    }
                                } 
                            }

                            ?>
    						<?php
                            $statistics = get_field('statistics', get_the_ID());
                            echo '<div class="match-statistics '.$stat_class.'">';
                            if (is_array($statistics)) {
                                foreach ($statistics as $stat) {
                                    printf('<div class="label">%s</div>', $stat['name']);
                                    $total = absint($stat['home']) + absint($stat['away']);
                                    printf(
                                        '<div class="bar">
                                            <span class="home_bar" style="width:%s%%">%s</span>
                                            <span class="away_bar" style="width:%s%%">%s</span>
                                        </div>',
                                        number_format(100 / $total * absint($stat['home']), 2),
                                        $stat['home'],
                                        number_format(100 / $total * absint($stat['away']), 2),
                                        $stat['away']
                                    );
                                }
                            } else {
                                echo _e('Statistics not available for this match', 'fanzalive');
                            }
                            echo '</div>';
                            if(get_field('right_side_of_status_tab', $event_id) == 'Yes' || get_field('right_side_of_status_tab', $event_id) == '') {
                                if(get_field('right_side_of_status_tab_content', $event_id)) {
                                    echo '<div class="col-md-3">'.get_field('right_side_of_status_tab_content', $event_id).'</div>';
                                }else {
                                    if(get_option('event_right_status_tab', true) == 'Yes'){
                                        if(get_option('event_right_status_tab_desc', true)) {
                                            echo '<div class="col-md-3">'.get_option('event_right_status_tab_desc', true).'</div>';
                                        }
                                    }
                                } 
                            }
    						?>
    					</div><!--.lineups-->
                        <div id="lineup" class="tab-content liTab">
                            <?php
                                if(get_field('bottom_of_lineup_tab', $event_id) == 'Yes' || get_field('bottom_of_lineup_tab', $event_id) == '') {
                                    if(get_field('bottom_of_lineup_tab_content', $event_id)) {
                                        echo get_field('bottom_of_lineup_tab_content', $event_id);
                                    }else {
                                        if(get_option('event_bottom_lineup_tab', true) == 'Yes'){
                                            if(get_option('event_bottom_lineup_tab_desc', true)) {
                                                echo get_option('event_bottom_lineup_tab_desc', true);
                                            }
                                        }
                                    } 
                                }

                                if(get_field('right_side_of_lineup_tab', $event_id) == 'Yes' || get_field('right_side_of_lineup_tab', $event_id) == '') {
                                    if(get_field('right_side_of_lineup_tab_content', $event_id)) {
                                        $line_class = 'col-md-9';
                                    }else {
                                        if(get_option('event_right_lineup_tab', true) == 'Yes'){
                                            if(get_option('event_right_lineup_tab_desc', true)) {
                                                $line_class = 'col-md-9';
                                            }
                                        }
                                    } 
                                }
                            ?>
    						<?php
                                $sp_players = get_post_meta(get_the_ID(), 'sp_players', true);
                                if (! empty($sp_players)) {
                                    #FAAF_Utils::p($sp_players);
                                    echo '<div class="match-players '.$line_class.'">';
                                    foreach ($sp_players as $team_id => $players) {
                                        $sub_replacements = [];
                                        foreach ($players as $player_id => $player) {
                                            if (isset($player['sub'])) {
                                                $sub_replacements[$player['sub']] = $player_id;
                                            }
                                        }
                                        #$sub_replacements = array_filter(wp_list_pluck($players, 'sub'));
                                        #$sub_replacements = array_flip($sub_replacements);
                                        #FAAF_Utils::p($sub_replacements);

                                        echo '<div class="team">';
                                        $team_post = get_post($team_id);
                                        printf('<div class="label">%s</div>', $team_post->post_title);
                                        echo '<ul class="players main-players">';
                                        foreach ($players as $player_id => $player) {
                                            if (empty($player_id) || empty($player['status'])) {
                                                continue;
                                            }

                                            if ('lineup' === $player['status']) {
                                                $player_post = get_post($player_id);

                                                $player_name = $player_post->post_title;
                                                if (isset($player['goals']) && $player['goals'] > 0) {
                                                    for($i=0; $i<$player['goals']; $i++) {
                                                        $player_name = '<i class="sp-icon-soccerball goal sp-icon" title="'. __('Goal') .'"></i> '. $player_name;
                                                    }
                                                }
                                                if (isset($player['owngoals']) && $player['owngoals'] > 0) {
                                                    $player_name = '<i class="sp-icon-soccerball-alt owngoal sp-icon" title="'. __('Own Goal') .'"></i> '. $player_name;
                                                }
                                                if (isset($player['yellowcards']) && $player['yellowcards'] > 0) {
                                                    $player_name = $player_name . ' <i class="sp-icon-card card-yellow sp-icon" title="'. __('Yellow Card') .'"></i>';
                                                }
                                                if (isset($player['redcards']) && $player['redcards'] > 0) {
                                                    $player_name = $player_name . ' <i class="sp-icon-card card-red sp-icon" title="'. __('Red Card') .'"></i>';
                                                }
                                                if (isset($sub_replacements[$player_id])) {
                                                   $player_name = $player_name . ' <i class="sp-icon-sub sp-icon" title="'. __('Substituted') .'"></i> '. get_the_title($sub_replacements[$player_id]);
                                                }

                                                printf(
                                                    '<li class="player">
                                                        <span class="joursey">%s</span>
                                                        <span class="name">%s</span>
                                                    </li>',
                                                    ! empty($player['number']) ? $player['number'] : '',
                                                    $player_name
                                                );
                                            }
                                        }
                                        echo '</ul>';

                                        printf('<div class="label">%s</div>', __('Substitutes'));
                                        echo '<ul class="players subs-players">';
                                        foreach ($players as $player_id => $player) {
                                            if (empty($player_id) || empty($player['status'])) {
                                                continue;
                                            }

                                            if ('sub' === $player['status']) {
                                                $player_post = get_post($player_id);
                                                $player_name = $player_post->post_title;
                                                for($i=0; $i<$player['goals']; $i++) {
                                                    $player_name = '<i class="sp-icon-soccerball goal sp-icon" title="'. __('Goal') .'"></i> '. $player_name;
                                                }
                                                if (isset($player['yellowcards']) && $player['yellowcards'] > 0) {
                                                    $player_name = $player_name . ' <i class="sp-icon-card sp-icon card-yellow"></i>';
                                                }

                                                printf(
                                                    '<li class="player">
                                                        <span class="joursey">%s</span>
                                                        <span class="name">%s</span>
                                                    </li>',
                                                    ! empty($player['number']) ? $player['number'] : '',
                                                    $player_name
                                                );
                                            }
                                        }
                                        echo '</ul>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                    # FAAF_Utils::p($players);
                                } else {
                                    echo '<div class="match-players '.$line_class.'">';
                                        _e('Lineup not available for this match', 'fanzalive');
                                    echo '</div>';
                                }

                                if(get_field('right_side_of_lineup_tab', $event_id) == 'Yes' || get_field('right_side_of_lineup_tab', $event_id) == '') {
                                    if(get_field('right_side_of_lineup_tab_content', $event_id)) {
                                        echo '<div class="col-md-3">'.get_field('right_side_of_lineup_tab_content', $event_id).'</div>';
                                    }else {
                                        if(get_option('event_right_lineup_tab', true) == 'Yes'){
                                            if(get_option('event_right_lineup_tab_desc', true)) {
                                                echo '<div class="col-md-3">'.get_option('event_right_lineup_tab_desc', true).'</div>';
                                            }
                                        }
                                    } 
                                }
    						?>
    					</div><!--.results-->
                    <?php endif; ?>
					<?php if (! empty($liveResultsShortcode)) : ?>
					<div id="results" class="tab-content liTab">
					    <?php
                            if(get_field('bottom_of_scores_tab', $event_id) == 'Yes' || get_field('bottom_of_scores_tab', $event_id) == '') {
                                if(get_field('bottom_of_scores_tab_content', $event_id)) {
                                    echo get_field('bottom_of_scores_tab_content', $event_id);
                                }else {
                                    if(get_option('event_bottom_scores_tab', true) == 'Yes'){
                                        if(get_option('event_bottom_scores_tab_desc', true)) {
                                            echo get_option('event_bottom_scores_tab_desc', true);
                                        }
                                    }
                                } 
                            }

                            if(get_field('right_side_of_scores_tab', $event_id) == 'Yes' || get_field('right_side_of_scores_tab', $event_id) == '') {
                                    if(get_field('right_side_of_scores_tab_content', $event_id)) {
                                        $score_class = 'col-md-9';
                                    }else {
                                        if(get_option('event_right_scores_tab', true) == 'Yes'){
                                            if(get_option('event_right_scores_tab_desc', true)) {
                                                $score_class = 'col-md-9';
                                            }
                                        }
                                    } 
                                }
                        ?>
                    	<?php
                            echo '<div class="sportspress '.$score_class.'">';
							     echo do_shortcode($liveResultsShortcode);
                            echo '</div>';
                            if(get_field('right_side_of_scores_tab', $event_id) == 'Yes' || get_field('right_side_of_scores_tab', $event_id) == '') {
                                if(get_field('right_side_of_scores_tab_content', $event_id)) {
                                    echo '<div class="col-md-3 mar78">'.get_field('right_side_of_scores_tab_content', $event_id).'</div>';
                                }else {
                                    if(get_option('event_right_scores_tab', true) == 'Yes'){
                                        if(get_option('event_right_scores_tab_desc', true)) {
                                            echo '<div class="col-md-3 mar78">'.get_option('event_right_scores_tab_desc', true).'</div>';
                                        }
                                    }
                                } 
                            }
						?>

					</div><!--.results-->
					<?php endif; ?>
					<?php if (! empty($leagueTableShortcode)) : ?>
						<div id="standings" class="tab-content liTab">
                             <?php
                                if(get_field('bottom_of_table_team', $event_id) == 'Yes' || get_field('bottom_of_table_team', $event_id) == '') {
                                    if(get_field('bottom_of_table_team_content', $event_id)) {
                                        echo get_field('bottom_of_table_team_content', $event_id);
                                    }else {
                                        if(get_option('event_bottom_table_team', true) == 'Yes'){
                                            if(get_option('event_bottom_table_team_desc', true)) {
                                                echo get_option('event_bottom_table_team_desc', true);
                                            }
                                        }
                                    } 
                                }

                                if(get_field('right_side_of_table_team', $event_id) == 'Yes' || get_field('right_side_of_table_team', $event_id) == '') {
                                    if(get_field('right_side_of_table_team_content', $event_id)) {
                                        $class="col-md-9";
                                    }else {
                                        if(get_option('event_right_table_team', true) == 'Yes'){
                                            $class="col-md-9";
                                        }
                                    } 
                                }
                            ?>
                            <div class="match-table <?= $class; ?>">
    							<?php
    								echo do_shortcode($leagueTableShortcode);
    							?>
                            </div>
                            <?php
                                if(get_field('right_side_of_table_team', $event_id) == 'Yes' || get_field('right_side_of_table_team', $event_id) == '') {
                                    if(get_field('right_side_of_table_team_content', $event_id)) {
                                        echo '<div class="col-md-3 mar78">'. get_field('right_side_of_table_team_content', $event_id). '</div>';
                                    }else {
                                        if(get_option('event_right_table_team', true) == 'Yes'){
                                            if(get_option('event_right_table_team_desc', true)) {
                                                echo '<div class="col-md-3 mar78">'. get_option('event_right_table_team_desc', true). '</div>';
                                            }
                                        }
                                    } 
                                }   
                            ?>
						</div><!--.standings-->
					<?php endif; ?>
                    <div id="news" class="tab-content liTab">
                        <?php
                            if(get_field('bottom_of_news_tab', $event_id) == 'Yes' || get_field('bottom_of_news_tab', $event_id) == '') {
                                if(get_field('bottom_of_news_tab_content', $event_id)) {
                                    echo get_field('bottom_of_news_tab_content', $event_id);
                                }else {
                                    if(get_option('event_bottom_news_tab', true) == 'Yes'){
                                        if(get_option('event_bottom_news_tab_desc', true)) {
                                            echo get_option('event_bottom_news_tab_desc', true);
                                        }
                                    }
                                } 
                            }

                            if(get_field('right_side_of_news_tab', $event_id) == 'Yes' || get_field('right_side_of_news_tab', $event_id) == '') {
                                if(get_field('right_side_of_news_tab_content', $event_id)) {
                                    $news_class = "col-md-9";
                                }else {
                                    if(get_option('event_right_news_tab', true) == 'Yes'){
                                        $news_class = "col-md-9";
                                    }
                                } 
                            }
                        ?>
                        <div class="match-news <?= $news_class; ?>">
                        <?php 
                            $current_team = get_the_ID();
                            $args = array(  
                                'post_type' => 'post',
                                'posts_per_page' => -1,
                            );
                            $loop = new WP_Query( $args ); 
                            $i = '1';
                            while ( $loop->have_posts() ) : $loop->the_post();
                                $post_teams = get_post_meta(get_the_ID(),'reporter_team',true);
                                foreach ($post_teams as $post_team) {
                                    if($post_team == $current_team_id ) {
                                    if($i == '1') {
                            ?>
                                <div class="news-section">
                                    <div class="news-img">
                                        <a href="<?= get_the_permalink(); ?>">
                                            <img src="<?= get_the_post_thumbnail_url($post->ID); ?>" />
                                        </a>
                                    </div>
                                    <div class="news-details">
                                        <h1><a href="<?= get_the_permalink(); ?>"><?= get_the_title(); ?></a></h1>
                                        <p><?php echo get_the_date('F j, Y');?></p>
                                    </div>
                                    <p><?= get_the_content(); ?></p>
                                </div>
                                
                            <?php } else { ?>

                                <div class="news-section">
                                    <div class="news-img">
                                        <a href="<?= get_the_permalink(); ?>">
                                            <img src="<?= get_the_post_thumbnail_url($post->ID); ?>" />
                                        </a>
                                    </div>
                                    <div class="news-details">
                                        <h1><a href="<?= get_the_permalink(); ?>"><?= get_the_title(); ?></a></h1>
                                        <p><?php echo get_the_date('F j, Y');?></p>
                                    </div>
                                </div>

                            <?php } $i++; } } endwhile;

                            wp_reset_postdata();
                         ?> 
                     </div>
                        <?php 
                            if(get_field('right_side_of_news_tab', $event_id) == 'Yes' || get_field('right_side_of_news_tab', $event_id) == '') {
                                if(get_field('right_side_of_news_tab_content', $event_id)) {
                                    echo '<div class="col-md-3">'. get_field('right_side_of_news_tab_content', $event_id). '</div>';
                                }else {
                                    if(get_option('event_right_news_tab', true) == 'Yes'){
                                        if(get_option('event_right_news_tab_desc', true)) {
                                            echo '<div class="col-md-3">'. get_option('event_right_news_tab_desc', true). '</div>';
                                        }
                                    }
                                } 
                            }
                        ?>
                  </div>
				</div>
			</div><!--.tabs-->
			<?php endwhile; ?>
		<?php else : ?>
			<p><?php esc_html_e('No live reporting yet','fanzalive'); ?></p>
		<?php endif; ?>
		</div><!--.entry single-->
	</div><!--#primary-->
</div><!--#content-->
<?php get_footer(); ?>
