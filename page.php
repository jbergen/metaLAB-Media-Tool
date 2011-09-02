<?php
/*
filename: page.php
description: This is file is rendered when a page is accessed by the user
*/
?>

	<?php get_header() ?>
	<?php get_sidebar() ?>

		<div id="single-container">
			<div class="content">

			</div>
				<?php the_post() ?>

				<div id="single-post">
					<h1><?php the_title(); ?></h1>

					<?php edit_post_link('edit this record', '<p>', '</p>'); ?>


					<div id="info" class="single-entry">
						<?php the_content() ?>
					</div><!--.entry-content-->
					
					<div id="metadata" class="single-entry">
						<h3>metadata</h3>

						added by <?php the_author() ?> on <?php the_time('F jS, Y') ?> 
						<br/>

						<?php

						$post_ID = get_the_ID(); 

						if ( $last_id = get_post_meta($post_ID, '_edit_last', true) ) {
							$last_user = get_userdata($last_id);
							printf(__('last edited by %1$s on %2$s at %3$s'), wp_specialchars( $last_user->display_name ), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), 

						$post->post_modified));
						};

						?>

						<br/>
						

						<?php the_category('Discipline: ','/ '); ?>
						<br/>

						<?php the_tags('Themes: ',' / ','<br />'); ?>
					</div>

				</div><!-- #single-post -->
			</div><!-- content -->
		</div><!-- #container -->
	<?php get_footer() ?>