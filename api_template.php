<?php
/*
Template Name: API Page

filename: api_template.php
description: should generate an xml/json file
*/
?>


	<?php
	echo '<?xml version="1.0" standalone="no"?>
	<content>';
		$cat = $_GET['cat'];
		$tags = $_GET['tag'];
		$year = $_GET['y'];
		$format = $_GET['format']; //xml or json --json preferred I think

		$catID = get_term_by('name', $cat, 'category') -> term_id;

		// The Query
		$args = array(
			'cat' => $catID,
			'tag' => $tags,
			'year' => $year
		);
		query_posts( $args );

		// The Loop
		while ( have_posts() ) : the_post(); ?>

		<item>
			<title><?php the_title();?></title>
			<link><?php the_permalink() ?></link>
			<year><?php the_date_xml(); ?></year>
			<categories>
				<?php
				$cat_args = array(
					'type'	=> 'post',
					'child_of'	=> 0,
					'orderby'	=> 'name',
					'order'	=> 'ASC',
					'hide_empty'	=> 1,
					'hierarchical'	=> 1,
					'taxonomy'	=> 'category',
					'pad_counts'	=> false );

				$categories = get_categories($cat_args);

				foreach($categories as $cat){
					echo "<cat>" . $cat->name . "</cat>";
				}

				?>
			</categories>
			<tags>
				<?php 
				$tags = get_tags();
				$html = "";
				foreach ($tags as $tag){
					echo "<tag>" . $tag->name . "</tag>";
				}
				?>
			</tags>
			<pubDate><?php the_time('F jS, Y') ?></pubDate>
			<pubAuthor><?php the_author() ?></pubAuthor>
			<modDate><?php the_modified_date(); ?></modDate>
			<modAuthor><?php echo the_modified_author(); ?></modAuthor>
			<content><![CDATA[<?php echo htmlspecialchars ( get_the_content() );?>]]></content>
			<attachments>
				<images>
					<?php 
					$thumb_size = 50;
					$image_array = make_timthumb_image_array($post->ID);
					
					if(count($image_array) > 0): 
					
						foreach($image_array as $image){
							echo '<image>'; 
							echo '<title>';
							echo $image->post_title;
							echo '</title>';
							echo '<url>';
							echo $image->guid;
							echo '</url>';
							echo '</image>';
						}
						?>
					<?php endif; ?>
				</images>
				<video>
					<?php 
						$args = array(
							'post_type' => 'attachment',
							'post_mime_type' => 'video',
							'numberposts' => -1,
							'post_parent' => $post->ID
						);
						$attachments = get_posts($args);
						if ($attachments):
							foreach ($attachments as $attachment) {
								if (!preg_match("/image/i", $attachment->post_mime_type)){
									echo '<vid>';
									echo '<title>';
									echo $attachment->post_title;
									echo '</title>';
									echo '<url>';
									echo $attachment->guid;
									echo '</url>';
									echo '</vid>';
								}
							}
							?>
					<?php endif; ?>
				</video>
				<files>
					<?php 
					$args = array(
						'post_type' => 'attachment',
						'post_mime_type' => 'application/pdf,application/msword',
						'numberposts' => -1,
						'post_parent' => $post->ID
					);
					$attachments = get_posts($args);
					if ($attachments):
						foreach ($attachments as $attachment) {
							if (!preg_match("/image/i", $attachment->post_mime_type)){
								echo '<file>';
								echo '<title>';
								echo $attachment->post_title;
								echo '</title>';
								echo '<url>';
								echo $attachment->guid;
								echo '</url>';
								echo '</file>';
							}
						}
						?>
				<?php endif; ?>
				</files>
			<attachments>
		</item>

		<?php endwhile;
		// Reset Query
		wp_reset_query();
	echo '</content>';
	?>
