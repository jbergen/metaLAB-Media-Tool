<?php
/*
filename: sidebar.php
description: This partial is called by <?php get_sidebar(); ?>
*/
?>

<div class="sidebar" id="left-sidebar">
	
	<div id="header">
		<strong>
		<h2><a href="<?php bloginfo('home') ?>/" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" rel="home"><?php bloginfo('name') ?></a></h2>
		</strong>
	</div><!--  #header -->

	<?php echo wp_count_posts()->publish; ?> records in database
	<br/><br/>

    <?php if (is_user_logged_in()):?>
		+ <a href="<?php bloginfo("url")?>/wp-admin/post-new.php">create new record</a>
		<br/><br/>
    <?php endif ?>

	<div id="search-wrapper">
		<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
		<div>
			<input type="text" size="14" value="<?php echo wp_specialchars($s, 1); ?>" name="s" id="s" />
			<input type="submit" id="searchsubmit" value="Go" class="btn" />
		</div>
		</form>
	</div>

<br/>

	<?php if( $count_pages = wp_count_posts('page')->publish > 0): ?>

	<h6>pages</h6>
	<ul>
		<?php wp_list_pages('title_li=&sort_column=menu_order' ) ?>
	</ul>
	<br/>
	<?php endif ?>
	
	
	<?php if( !is_singular() ):?>
	
	<a href="#" id="sort-all-on">unfiltered</a> / <a href="#" id="sort-all-off">filter all</a> 
	
		<h6>categories</h6>
		<ul>
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
				echo "<li>";
				echo "<a href='#' class='sort-toggle' id='". $cat->slug . "'>[+]</a>";
				echo " <a href='#' class='sort-only' id='sort-only-". $cat->category_nicename ."' rel='".$cat->category_nicename."'>";
				echo $cat->name;
				echo "</a>";
				echo "</li>";
			}
			
			?>
			
		</ul>
		<h6>tags</h6>
		<ul>
		<?php 
		$tags = get_tags();
		$html = "";
		foreach ($tags as $tag){
			echo "<li>";
			echo "<a href='#' class='sort-toggle' id='". $tag->slug . "'>[+]</a>";
			echo " <a href='#' class='sort-only' id='sort-only-". $tag->slug ."'>";
			echo $tag->name;
			echo "</a>";
			echo "</li>";
		}
		?> 
		</ul>
		
	
	
	
	<br/>
	
	<h6>date</h6>
	<ul>
	 <?php
		$args = array(
		'type'            => 'yearly',
		'format'          => 'html', 
 		'show_post_count' => false,
 		'echo'            => 0
		);
		
		$output = wp_get_archives($args);
		$lines = preg_split("/<li>/", $output);
		$arr = array();
		foreach($lines as $line){
			if(trim($line) != ""){
				$year = trim(preg_replace("/(<\/?)(\w+)([^>]*>)/e","",$line));
				$htm = "<li><a href='#' class='sort-toggle' id='". $year."'>[+]</a>";
				$htm .= " <a href='#' class='sort-only' id='sort-only-". $year ."'>";
				$htm .= $year;
				$htm .= "</a></li>";
				echo $htm;
			}
		}
		
	?> 
	</ul>
	<?php else: ?>
		
		<br/>

			<h6>categories</h6>
			<ul>
			<?php wp_list_categories('title_li='); ?>
			</ul>
			<h6>tags</h6>
			<ul>
			<?php 
			$args = array(
			    'smallest'                  => 9, 
			    'largest'                   => 9,
			    'unit'                      => 'pt', 
			    'number'                    => 0,  
			    'format'                    => 'flat',
			    'orderby'                   => 'name', 
			    'order'                     => 'ASC',
			    'exclude'                   => null, 
			    'include'                   => null, 
			    'topic_count_text_callback' => default_topic_count_text,
			    'link'                      => 'view', 
			    'taxonomy'                  => 'post_tag', 
			    'echo'                      => true ); 
			wp_tag_cloud( $args ); 
			?> 
			</ul>

			<h6>people</h6>
			<ul>
			<?php 
			$args = array(
			    'smallest'                  => 9, 
			    'largest'                   => 9,
			    'unit'                      => 'pt', 
			    'number'                    => 0,  
			    'format'                    => 'flat',
			    'orderby'                   => 'name', 
			    'order'                     => 'ASC',
			    'exclude'                   => null, 
			    'include'                   => null, 
			    'topic_count_text_callback' => default_topic_count_text,
			    'link'                      => 'view', 
			    'taxonomy'                  => 'people', 
			    'echo'                      => true ); 
			wp_tag_cloud( $args ); 
			?> 
			</ul>		


		<br/>

		<h6>date</h6>
		<ul>
		 <?php
			$args = array(
			'type'            => 'yearly',
			'format'          => 'html', 
	 		'before'          => '<li>',
	 		'after'           => '</li>',
	 		'show_post_count' => false,
	 		'echo'            => 1
			);
		 	wp_get_archives( $args ); 
		?> 
		</ul>
		
	<?php endif; ?>
	
	
	
	<br/>

	<?php if (is_user_logged_in()):?>
		<a href="<?php bloginfo('home') ?>/wp-admin/">dashboard</a> / <a href="<?php echo wp_logout_url(); ?>" title="logout">logout</a>
    <?php else: ?>
		<a href="<?php echo wp_login_url(); ?>" title="Login">login</a>
	<?php endif ?>
	

	
</div><!-- .sidebar -->	

