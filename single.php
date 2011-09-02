<?php
/*
filename: single.php
description: this file is called when accessing a single post
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
				
					<?php 
					$thumb_size = 50;
					$image_array = make_timthumb_image_array($post->ID);
					
					if(count($image_array) > 0):
					?>
					
					<div id='media' class="single-entry">
					<h3>media <?php if(count($image_array)) echo '('.count($image_array).')';?></h3>
					
					<div id='media-images'>
						<?php
						foreach($image_array as $image){
							echo '<a href=\'';
							echo $image->timthumb;
							echo '&h=400&zc=1';
							echo '\' rel=\'lightbox[';
							echo $post->ID;
							echo ']\'';
							
							echo 'title="';
							echo $image->post_title;
							echo '">';
						
							echo '<img src="';
							echo $image->timthumb;
							echo '&h='.$thumb_size.'&w='.$thumb_size.'&zc=1';
							echo '"/>';
						
							echo '</a>';
						}
						?>
					</div>
					<div id='media-links'>
					
							<small><a href='#' id='media-toggle'>see media links</a></small>
							<div id='hidden-links' style='display:none'>
								<ul>
								<?php
									foreach($image_array as $image){
										echo '<li>';
										//echo $image->post_title;
										echo '<a href=\'';
										echo $image->guid;
										echo '\'>';
										
										echo $image->post_title;
										//echo $image->guid;
										echo '</a></li>';
									}
								?>
								</ul>
							</div>
					
					
					</div><!-- media-links -->
					<?php endif; ?>
				</div>
				
				
				<?php 
				
					$args = array(
						'post_type' => 'attachment',
						'post_mime_type' => 'video',
						'numberposts' => -1,
						'post_parent' => $post->ID
					);
					$attachments = get_posts($args);
					if ($attachments):?>

					<div class="single-entry" id="attachments">
					<h3>video</h3>
					
					<?php
						foreach ($attachments as $attachment) {
							if (!preg_match("/image/i", $attachment->post_mime_type)){

								echo "<a class=\"video-link\" title=\"attachment\" mime=\"" . $attachment->post_mime_type . "\" href=\"". $attachment->guid ."\">" . $attachment->post_title . "</a>";
								if ($attachment->post_content) echo" - [<a target=\"blank\" href=\"". $attachment->post_content ."\">via</a>]";
								echo "<br>\n";
							}
						}
						?>
					
						</div><!--attachments-->
				<?php endif; ?>
				
				
				

					<?php 
					
						$args = array(
							'post_type' => 'attachment',
							'post_mime_type' => 'application/pdf,application/msword',
							'numberposts' => -1,
							'post_parent' => $post->ID
						);
						$attachments = get_posts($args);
						if ($attachments):?>

						<div class="single-entry" id="attachments">
						<h3>attachments</h3>
						
						<?php
							foreach ($attachments as $attachment) {
								if (!preg_match("/image/i", $attachment->post_mime_type)){

									echo "<a class=\"attachment-link\" title=\"attachment\" mime=\"" . $attachment->post_mime_type . "\" href=\"". $attachment->guid ."\">" . $attachment->post_title . "</a>";
									if ($attachment->post_content) echo" - [<a target=\"blank\" href=\"". $attachment->post_content ."\">via</a>]";
									echo "<br>\n";
								}
							}
							?>
						
							</div><!--attachments-->
					<?php endif; ?>

				
				
				
				
				<div id="info" class="single-entry">
					<h3>info</h3>
					<?php the_content() ?>
				</div><!--.entry-content-->
				
				<div id="metadata" class="single-entry">
					<h3>metadata</h3>
					
					date: <a href="<?php echo get_year_link($year); ?>"><?php the_date_xml(); ?></a>
					<br/>
					people: <?php the_terms( $post->ID, 'people', '', ' / ', ' ' ); ?>
					<br/>
					
					category: <?php the_category(' / '); ?>
					<br/>
					
					tags: <?php the_tags('',' / ',''); ?>
					
					<br/>
					
					
					<?php 
					$location = get_post_meta($post->ID, "location_value", true);
					if($location != ""):?>
						location: <?php echo $location; ?>
						<br /><br />
						
						<div id="map" style="">
							<?php $zoom = get_post_meta($post->ID, "zoom_value", true);?>
							<?php $latlng = get_post_meta($post->ID, "lat_value", true).",".get_post_meta($post->ID, "lng_value", true);  ?>
							<img src="http://maps.google.com/maps/api/staticmap?center=<?php echo $latlng; ?>&zoom=<?php echo $zoom;?>&size=200x200&markers=color:red|<?php echo $latlng; ?>&maptype=hybrid&sensor=false">
						</div>
					
					<?php endif; ?>
					<br />
					<br/>
					
					created by <?php the_author() ?> on <?php the_time('F jS, Y') ?>
					<br/>
					
					<?php 
						echo 'last modified by ';
						echo the_modified_author(); 
						echo ' on ';
						echo the_modified_date(); 
					?>
					
				</div>
				
			</div><!-- #single-post -->
	<?php get_footer() ?>
