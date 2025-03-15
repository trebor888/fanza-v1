<?php get_header(); ?>
	<?php
	// for standing tables
    $__cat = get_queried_object();
	while( $__cat->parent > 0 ) {
        $__cat = get_category($__cat->parent);
    }
	$leagueTableShortcode = get_term_meta($__cat->term_id, 'league_table_shortcode', true);
	$liveResultsShortcode = get_term_meta($__cat->term_id, 'live_results_shortcode', true);
	unset($__cat);

	$category = get_category(get_queried_object_id());
	$args = array('posts_per_page'=> 1,'cat' => $cat);

	$category_parent_id = $category->category_parent;
	if ( $category_parent_id != 0 ) {
		$category_parent = get_term( $category_parent_id, 'category' );
		$cat_slug = $category_parent->slug;
		$cat_name = $category_parent->name;
	} else {
		$cat_slug = $category->slug;
		$cat_name = $category_parent->name;
	}

	$league = 'EPL';
	if ($category_parent_id == 5)     $league = 'CHP';
	if ($category_parent_id == 40)    $league = 'SPL';
	if ($category_parent_id == 37)    $league = 'SLC';
	if ($category_parent_id == 9)     $league = 'LG1';
	if ($category_parent_id == 10)    $league = 'LG2';

	$breadcrumbs =  '<a href="'.esc_url( home_url( '/' ) ).'">Home</a> / <a href="'.esc_url( home_url( '/' ) ).$cat_slug.'">'.$cat_name.'</a> / <a href="'.esc_url( home_url( '/' ) ).$category->slug.'">'.$category->name.'</a> / ';

	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts()) : ?>
	<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>

	<?php
	global $post;
	$userid = get_current_user_id();
	$usercat = get_user_meta($userid, 'cat', true);

	$get_author_id = get_the_author_meta('ID');

	if (isset ($_POST['report'])) {
		$arg = array(
			'ID' => $post->ID,
			'post_author' => $userid,
		);
		wp_update_post( $arg );
		echo '<meta http-equiv="refresh" content="0">';
	}
	$key= get_the_title();
	$title = explode("&#8211;", $key, 2);
	?>

	<div class = "clubbanner <?php echo $category->slug; ?>"><h1><?php echo $title[0]; ?></h1><h2 style="color:#DCDCDC;text-align:center;"><?php echo $title[1]; ?></h2></div>
	<div id="content">
		<div id="primary">

			<div class="entry single">
				<section>
					<?php echo $breadcrumbs; ?><?php the_title(); ?>
				</section>

				<div class="tabs">
					<input id="tab1" type="radio" name="tabs" checked>
					<label for="tab1">Match Report</label>

                    <?php if (! empty($liveResultsShortcode)) : ?>
					<input id="tab2" type="radio" name="tabs">
					<label for="tab2">Latest Results</label>
                    <?php endif; ?>

                    <?php if (! empty($leagueTableShortcode)) : ?>
					<input id="tab3" type="radio" name="tabs">
					<label for="tab3">League Table</label>
                    <?php endif; ?>

					<div class="tabcontent">
					<div id="tabcontent1">
					<section>
						<?php if (get_the_author() == 'Scheduled') : ?>
							<?php if ($usercat == $category->term_id) : ?>
								<form method="post" action="" enctype="multipart/form-data">
								<div class="acf-style">
									<p>There are no reporters for this match.</p>
									<input type="submit" value="Report on this Match" id="submit" name="report" />
								</div>
								</form>
							<?php else : ?>
								<p>Become a reporter for <?php echo $category->name; ?>.</p>
								<h4><a href="http://fanzalive.co.uk/join-us/">Join us</a></h4>
							<?php endif; ?>
						<?php else : ?>
							<?php
							$profileimage = get_user_meta($get_author_id,'profileimage');
							$url = $profileimage[0];
							if (!$url) $url = get_avatar_url($get_author_id, array('size' => 50));
							echo '<img class="avatar" src="'.$url.'" alt="'.get_the_title().'" />';
							?>
					   <div style="font-size: 16px;font-weight:900"><?php echo get_the_author(); ?></div>
					   <div style="color:#666;font-size: 12px;">
						  Today's Reporter
					   </div>

						<?php endif; ?>
					</section>

					<?php comments_template( '', true ); ?>
					</div>

                    <?php if (! empty($liveResultsShortcode)) : ?>
					<div id="tabcontent2">
						<section>
							<?php
							echo do_shortcode('[statsfc-results key=8L98IAWCJHuFce166l2KCRGCid6ZoF6DyC6QgMGw competition='.$league.']'); ?>
						</section>
					</div>
                    <?php endif; ?>
                    <?php if (! empty($leagueTableShortcode)) : ?>
	                <div id="tabcontent3">
                        <section><?php
                            echo do_shortcode('[statsfc-table key=8L98IAWCJHuFce166l2KCRGCid6ZoF6DyC6QgMGw competition='.$league.']');
                        ?></section>
	                </div>
                    <?php endif; ?>
				</div>

				</div>

			</div>
		</div>
	</div>

	<?php endwhile; ?>
	<?php else : ?>

	<div class = "clubbanner <?php echo $category->slug; ?>"><h1><?php single_cat_title(); ?></h1></div>
	<div id="content">
		<div id="primary">

			<?php echo $breadcrumbs; ?>

			<div class="entry single">
				<section>
					<p>There is no live reporting for <?php single_cat_title(); ?>.</p>
					<h4><a href="http://fanzalive.co.uk/join-us/">Become a Reporter</a></h4>
				</section>
			</div>
		</div>
	</div>

	<?php endif; ?>

<?php get_footer(); ?>
