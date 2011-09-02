<?php
/*
filename: index.php
description: This is the file that is called by default when accessing the root url
*/
?>

<?php get_header() ?>
<?php get_sidebar() ?>

<!-- rollover sidebar only on index pages -->
<div class="sidebar" id='right-sidebar'>
	<div id="rollover">
		<div id='rollover-image'><img src='<?php bloginfo('template_directory');?>/assets/images/default_large.png'></div>
		<h2 id='project-name'></h2>
		<h4></h4>
		<p></p>
	</div>
</div>
<!--  end sidebar -->

	<div id="container">
		<div id="content">
			
			<?php
				if(is_search()){
					echo '<h2>Search: '.get_search_query().'</h2>';
				}elseif(is_category()){
					echo '<h2>Category: '.single_cat_title( '', false ).'</h2>';
				}elseif(is_tag()){
					echo '<h2>Tag: '.single_tag_title( '', false ).'</h2>';
				}elseif(is_tax()){
					$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					echo '<h2>Person: '.$term->name.'</h2>';;
				}elseif(is_year()){
					ob_start(); // enable collecting into buffer
					the_date_xml(); // call the function and catch output into buffer
					$year = ob_get_contents(); // read buffer
					ob_end_clean(); // end collecting into buffer and flush the buffer content
					$year = preg_replace('/-(.)*/','',$year);
					
					echo '<h2>Year: '. $year .'</h2>';
				}
			
			
			?>
			
			

			<?php //query_posts($query_string . '&meta_key=date_value&orderby=meta_value&order=DESC'); ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
			<?php //echo "tester: ".print_r(get_post_meta($post->ID)); ?>
			<?php //echo "tester: ".get_post_meta($post->ID, "date_value", true); ?>
			
			<div class='grid-wrapper <?php
					$sort_class = "";
					//place each category in the class field
					foreach(get_the_category() as $cat){
						$sort_class = $sort_class.$cat->category_nicename." ";
					}
					//place each tag in the class field
					foreach(get_the_tags() as $tag){
						$sort_class = $sort_class.$tag->slug." ";
					}
					//place each person in the class field
					foreach(wp_get_object_terms($post->ID, "people") as $person){
						$sort_class = $sort_class.$person->slug." ";
					}
					
					ob_start(); // enable collecting into buffer
					the_date_xml(); // call the function and catch output into buffer
					$year = ob_get_contents(); // read buffer
					ob_end_clean(); // end collecting into buffer and flush the buffer content
					$year = preg_replace('/-(.)*/','',$year);
					
					if( get_post_meta($post->ID,'curated_value', TRUE) ) $sort_class .= ' curated';

					if( get_post_meta($post->ID,'stage2_value', TRUE) ) $sort_class .= ' stage2';
					
					
					$sort_class .= " ".$year;
					
					echo trim($sort_class);
				?>'>
				
			<div class="grid-post">
				
					<?php 
					
					$grid_size = 75;
					$rollover_size = 200;
					
					$image_array = make_timthumb_image_array();
					if(count($image_array) > 0){
						foreach($image_array as $image){
							echo '<a href="';
							echo the_permalink();
							echo '"';				
							
							echo ' data-image-url="';
							
							echo $image->timthumb;
							echo '&h='.$rollover_size.'&w='.$rollover_size.'&zc=1';
							
							echo '"';
							
							echo ' data-title="';
							echo the_title();
							echo '"';
							
							echo ' data-excerpt="';
							echo get_the_excerpt();
							echo '"';
							
							echo ' data-media-count="';
							echo count($image_array);
							echo '"';
														
							echo'>';
							
							//echo $post ->post_name
												
							//output image
							echo '<img src=\'';
							echo $image->timthumb;
							echo '&h='.$grid_size.'&w='.$grid_size.'&zc=1';
							echo'\' />';
						
							echo '</a>';
							break; //only do once
						}
					}else{
						//defaults in case there's no image associated with the post
						echo '<a href="';
						echo the_permalink();
						echo '" ';
						
						echo ' data-image-url="';
						echo bloginfo('template_directory');
						echo '/assets/images/default_large.png';
						echo '"';
						
						echo ' data-title="';
						echo the_title();
						echo '"';
						
						echo ' data-excerpt="';
						echo get_the_excerpt();
						echo '"';
						
						echo ' data-media-count="0"';
						
						echo '></a>';
					}
					?>
					
				
			
			</div><!-- .grid-post -->
		</div><!-- .grid-wrapper -->

		<?php endwhile; ?>
		<?php else : ?>
	
			<h2>no results</h2>
	
		<?php endif; ?>

		</div><!-- #content -->
		
		

<?php get_footer() ?>