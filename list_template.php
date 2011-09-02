<?php
/*
Template Name: List page

filename: list_template.php
description: should generate a flat list of stage
*/
?>

<?php get_header() ?>


<ul>
<?php query_posts('meta_key='.$_GET["r"].'_value&posts_per_page=1000&orderby=title&order=DESC'); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<li style='margin:10px;padding: 5px; border:1px solid #ccc;overflow:hidden;width:90%'>
	<div style='clear:both;overflow:hidden'>
		
		<div><h3><?php echo the_title(); ?></h3></div>
		
		<?php 
		
		$grid_size = 50;
		$rollover_size = 200;
		
		$image_array = make_timthumb_image_array();
		if(count($image_array) > 0){
			echo '<div style="float:left; margin-right:10px">';
			foreach($image_array as $image){
				echo '<img style="margin-left:1px" src=\'';
				echo $image->timthumb;
				echo '&h='.$grid_size.'&w='.$grid_size.'&zc=1';
				echo'\' />';
				//break; //only do once
			}
			echo '</div>';
		}
		?>
		<div style="float:left">
			<div>
				tags: 
				<?php
				//place each tag in the class field
				foreach(get_the_tags() as $tag){
					echo $tag->name." / ";
				}
				?>
			
			</div>
			<div class="">
				categories:
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
						echo $cat->name." /";
					}

					?>
			</div>
		</div>
	</div>
</li>

<?php endwhile; endif;?>
</ul>


<?php get_footer() ?>