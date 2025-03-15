<?php 

if(get_field('header_top_advertise', get_the_ID()) == 'Yes' || get_field('header_top_advertise', get_the_ID()) == '') {
    if(get_field('header_top_advertise_content', get_the_ID())) {
        echo get_field('header_top_advertise_content', get_the_ID());
    }else {
        if(get_option('team_header_top', true) == 'Yes'){
            if(get_option('team_header_top_desc', true)) {
                echo get_option('team_header_top_desc', true);
            }
        }
    } 
}
get_header(); ?>
<div id="content" class="clearfix">
    <div id="primary">
        <?php if(have_posts()) : ?>
        <?php while(have_posts()) : the_post(); ?>
	        <div <?php post_class('entry single'); ?>>
                <?php get_template_part('template-parts/breadcrumb'); ?>
                <?php do_action('before_title');?>
	            <h1><?php the_title(); ?></h1>
	            <section class="team-tabs"><?php
	            if ( has_post_thumbnail()) {
					the_post_thumbnail('large');
				} 

				if(get_field('header_bottom_advertise', get_the_ID()) == 'Yes' || get_field('header_bottom_advertise', get_the_ID()) == '') {
				    if(get_field('header_bottom_advertise_content', get_the_ID())) {
				        echo get_field('header_bottom_advertise_content', get_the_ID());
				    }else {
				        if(get_option('team_header_bottom', true) == 'Yes'){
				            if(get_option('team_header_bottom_desc', true)) {
				                echo get_option('team_header_bottom_desc', true);
				            }
				        }
				    } 
				}
			?>
				<div class="tab-all tab-style-three team-tabs">
					<ul class="tabs-menu tabs-keep-history" data-target="#tab-contents">
					    <li><a href="#news">News</a></li>
					    <li><a href="#fixture">Fixtures</a></li>
					    <li><a href="#results">Results</a></li>
					    <li><a href="#tables">Tables</a></li>
					    <li><a href="#reporters">Reporters</a></li>
					</ul>
					<div id="tabs-contents" class="tab-content-wrap">
					    <div id="news" class="tab-content">
					    	<?php
						    	if(get_field('bottom_of_news_tab', get_the_ID()) == 'Yes' || get_field('bottom_of_news_tab', get_the_ID()) == '') {
								    if(get_field('bottom_of_news_tab_content', get_the_ID())) {
								        echo get_field('bottom_of_news_tab_content', get_the_ID());
								    }else {
								        if(get_option('team_bottom_news_tab', true) == 'Yes'){
								            if(get_option('team_bottom_news_tab_desc', true)) {
								                echo get_option('team_bottom_news_tab_desc', true);
								            }
								        }
								    } 
								}

								if(get_field('right_side_of_news_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_news_tab', get_the_ID()) == '') {
								    if(get_field('right_side_of_news_tab_content', get_the_ID())) {
								        $news_class= 'col-md-9';
								    }else {
								        if(get_option('team_right_news_tab', true) == 'Yes'){
								            $news_class= 'col-md-9';
								        }
								    } 
								}

					    	?>
					      <div class="<?= $news_class; ?>">
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
							    		if($post_team == $current_team ) {
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
									
							    <?php } else {

							    	if(get_field('between_of_news_tab', $current_team) == 'Yes' || get_field('between_of_news_tab', $current_team) == '') {
									    if(get_field('between_of_news_tab_content', $current_team)) {
									        echo get_field('between_of_news_tab_content', $current_team);
									    }else {
									        if(get_option('team_between_news_tab', true) == 'Yes'){
									        	if(get_option('team_between_news_tab_desc', true)) {
									        		echo get_option('team_between_news_tab_desc', true);
									        	}
									        }
									    } 
									}
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
							        </div>

							    <?php } $i++; } } endwhile;

							    wp_reset_postdata();
						    ?>
					      </div>
					      	<?php 

					      		if(get_field('right_side_of_news_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_news_tab', get_the_ID()) == '') {
								    if(get_field('right_side_of_news_tab_content', get_the_ID())) {
								        echo '<div class="col-md-3">'.get_field('right_side_of_news_tab_content', get_the_ID()).'</div>';
								    }else {
								        if(get_option('team_right_news_tab', true) == 'Yes'){
								        	if(get_option('team_right_news_tab_desc', true)) {
								        		echo '<div class="col-md-3">'.get_option('team_right_news_tab_desc', true).'</div>';
								        	}
								        }
								    } 
								}

					      	?>
					    </div>
					    <div id="fixture" class="tab-content">
					    	<div class="sp-fixtures-results">
					    		<?php 
						    		if(get_field('bottom_of_fixture_tab', get_the_ID()) == 'Yes' || get_field('bottom_of_fixture_tab', get_the_ID()) == '') {
									    if(get_field('bottom_of_fixture_tab_content', get_the_ID())) {
									        echo get_field('bottom_of_fixture_tab_content', get_the_ID());
									    }else {
									        if(get_option('team_bottom_fixture_tab', true) == 'Yes'){
									        	if(get_option('team_bottom_fixture_tab_desc', true)) {
									        		echo get_option('team_bottom_fixture_tab_desc', true);
									        	}
									        }
									    } 
									}

									if(get_field('right_side_of_fixture_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_fixture_tab', get_the_ID()) == '') {
									    if(get_field('right_side_of_fixture_tab_content', get_the_ID())) {
									        $fixter_class = 'col-md-9';
									    }else {
									        if(get_option('team_right_fixture_tab', true) == 'Yes'){
									            $fixter_class = 'col-md-9';
									        }
									    } 
									}

					    	
					    		?>
					    		<div class="<?= $fixter_class; ?>">
					    			<?php the_content(); ?>
					    		</div>
							      
							    <?php
							    	if(get_field('right_side_of_fixture_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_fixture_tab', get_the_ID()) == '') {
									    if(get_field('right_side_of_fixture_tab_content', get_the_ID())) {
									        echo '<div class="col-md-3 mar70">'.get_field('right_side_of_fixture_tab_content', get_the_ID()).'</div>';
									    }else {
									        if(get_option('team_right_fixture_tab', true) == 'Yes'){
									        	if(get_option('team_right_fixture_tab_desc', true)) {
									        		echo '<div class="col-md-3 mar70">'.get_option('team_right_fixture_tab_desc', true).'</div>';
									        	}
									        }
									    } 
									} 
							    ?>
							      
							</div>
					    </div>
					    <div id="results" class="tab-content">
					    	<div class="sp-fixtures-results">
					    		<?php 
					    			if(get_field('bottom_of_result_tab', get_the_ID()) == 'Yes' || get_field('bottom_of_result_tab', get_the_ID()) == '') {
									    if(get_field('bottom_of_result_tab_content', get_the_ID())) {
									        echo get_field('bottom_of_result_tab_content', get_the_ID());
									    }else {
									        if(get_option('team_bottom_result_tab', true) == 'Yes'){
									        	if(get_option('team_bottom_result_tab_desc', true)) {
									        		echo get_option('team_bottom_result_tab_desc', true);
									        	}
									        }
									    } 
									}

									if(get_field('right_side_of_result_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_result_tab', get_the_ID()) == '') {
									    if(get_field('right_side_of_result_tab_content', get_the_ID())) {
									        $tem_class = 'col-md-9';
									    }else {
									        if(get_option('team_right_result_tab', true) == 'Yes'){
									            $tem_class = 'col-md-9';
									        }
									    } 
									}
							    ?>
					    		<div class="<?= $tem_class; ?>">
					    			<?php the_content(); ?>
					    		</div>
							    <?php 
							    	if(get_field('right_side_of_result_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_result_tab', get_the_ID()) == '') {
									    if(get_field('right_side_of_result_tab_content', get_the_ID())) {
									        echo '<div class="col-md-3 mar70">'.get_field('right_side_of_result_tab_content', get_the_ID()).'</div>';
									    }else {
									        if(get_option('team_right_result_tab', true) == 'Yes'){
									        	if(get_option('team_right_result_tab_desc', true)) {
									        		echo '<div class="col-md-3 mar70">'.get_option('team_right_result_tab_desc', true).'</div>';
									        	}
									        }
									    } 
									}
								?>
							</div>
					    </div>
					    <div id="tables" class="tab-content">
					    	<?php 
				    			if(get_field('bottom_of_tables_tab', get_the_ID()) == 'Yes' || get_field('bottom_of_tables_tab', get_the_ID()) == '') {
								    if(get_field('bottom_of_tables_tab_content', get_the_ID())) {
								        echo get_field('bottom_of_tables_tab_content', get_the_ID());
								    }else {
								        if(get_option('team_bottom_tables_tab', true) == 'Yes'){
								        	if(get_option('team_bottom_tables_tab_desc', true)) {
								        		echo get_option('team_bottom_tables_tab_desc', true);
								        	}
								        }
								    } 
								}

								if(get_field('right_side_of_tables_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_tables_tab', get_the_ID()) == '') {
								    if(get_field('right_side_of_tables_tab_content', get_the_ID())) {
								        $tabl_class = 'col-md-9';
								    }else {
								        if(get_option('team_right_tables_tab', true) == 'Yes'){
								            $tabl_class = 'col-md-9';
								        }
								    } 
								}
						    ?>
					    	<div class="<?= $tabl_class; ?>">
				    			<?php
									if ( ! isset( $id ) )
										$id = get_the_ID();

									$team = new SP_Team( $id );
									$tables = $team->tables();

									foreach ( $tables as $table ):
										if ( ! $table ) continue;

										sp_get_template( '/template-parts/league-table.php', array( 'id' => $table->ID, 'highlight' => $id ) );
									endforeach;
									wp_reset_postdata();
								?>
				    		</div>
						        <?php 
						        	if(get_field('right_side_of_tables_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_tables_tab', get_the_ID()) == '') {
									    if(get_field('right_side_of_tables_tab_content', get_the_ID())) {
									        echo '<div class="col-md-3 mar70">'.get_field('right_side_of_tables_tab_content', get_the_ID()).'</div>';
									    }else {
									        if(get_option('team_right_tables_tab', true) == 'Yes'){
									        	if(get_option('team_right_tables_tab_desc', true)) {
									        		echo '<div class="col-md-3 mar70">'.get_option('team_right_tables_tab_desc', true).'</div>';
									        	}
									        }
									    } 
									}
							    ?>
					    </div>
					    <div id="reporters" class="tab-content">
					    	<?php 
					    		if(get_field('bottom_of_reporter_tab', get_the_ID()) == 'Yes' || get_field('bottom_of_reporter_tab', get_the_ID()) == '') {
								    if(get_field('bottom_of_reporter_tab_content', get_the_ID())) {
								        echo get_field('bottom_of_reporter_tab_content', get_the_ID());
								    }else {
								        if(get_option('team_bottom_reporter_tab', true) == 'Yes'){
								        	if(get_option('team_bottom_reporter_tab_desc', true)){
								        		echo get_option('team_bottom_reporter_tab_desc', true);
								        	}
								        }
								    } 
								}

								if(get_field('right_side_of_reporter_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_reporter_tab', get_the_ID()) == '') {
								    if(get_field('right_side_of_reporter_tab_content', get_the_ID())) {
								        $rptr_class = 'col-md-9';
								    }else {
								        if(get_option('team_right_reporter_tab', true) == 'Yes'){
								            $rptr_class = 'col-md-9';
								        }
								    } 
								}
						    ?>
					    	<div class="<?= $rptr_class; ?>">
				    			<?php   
						       		$current_team = get_the_ID();
							      	$args = array(  
								        'post_type' => 'sp_event',
								        'posts_per_page' => -1, 
								        'meta_query' => array(
										       array(
										           'key' => 'sp_team',
										           'value' => $current_team,
										           'compare' => '=',
										       )
										   )
								    );

								    $loop = new WP_Query( $args ); 
								    while ( $loop->have_posts() ) : $loop->the_post();
								        $reporter = get_post_meta($post->ID, '_reporter_home', true);
								        $reporter_data = get_userdata( $reporter ); 

								        if($reporter) {
								        	$user_photo = get_user_meta($reporter, 'user_photo', true);
										    if ($user_photo) {
										        $img = wp_get_attachment_image_src($user_photo, 'thumbnail');
										        $url = $img[0];
										    } else {
										        $url = "https://www.fanzalive.co.uk/dev/wp-content/themes/fanzalive/assets/images/profile-default.png";
										    }
								    ?>
								        <div class="reporter-section">
								        	<div class="report-img">
								        		<img src="<?php echo $url;?>" />
								        	</div>
								        	<div class="reporter-details">
								        		<h1><a href="<?= get_author_posts_url($reporter); ?>">
								        			<?php echo $reporter_data->data->display_name; ?></a></h1>
								        		<p><?php echo get_user_meta($reporter, 'description', true);?></p>
								        	</div>
								        </div>
										
								    <?php } endwhile;

								    wp_reset_postdata();
							    ?>
				    		</div>
						        <?php 
						        	if(get_field('right_side_of_reporter_tab', get_the_ID()) == 'Yes' || get_field('right_side_of_reporter_tab', get_the_ID()) == '') {
									    if(get_field('right_side_of_reporter_tab_content', get_the_ID())) {
									        echo '<div class="col-md-3">'.get_field('right_side_of_reporter_tab_content', get_the_ID()).'</div>';
									    }else {
									        if(get_option('team_right_reporter_tab', true) == 'Yes'){
									            if(get_option('team_right_reporter_tab_desc', true)) {echo '<div class="col-md-3">'.get_option('team_right_reporter_tab_desc', true).'</div>';
									        	}
									        }
									    } 
									}
							    ?>
					    </div>
					</div> <!-- END tabs-content -->	
				</div>
	            <?php edit_post_link('Edit', '<p>', '</p>'); ?>
                </section>
	            <?php comments_template( '', true ); ?>
	        </div>
        <?php endwhile; ?>
        <?php else : ?>
	        <div class="entry single notfound">
	            <h1><?php esc_html_e('Not Found','fanzalive'); ?></h1>
	        </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
