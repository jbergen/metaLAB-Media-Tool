<?php
/*
filename: 404.php
description: Displayed when an invalid url is accessed
*/
?>

<?php get_header() ?>
<?php get_sidebar() ?>

	<div id="container">
		<div id="content">

			<div class="post error404 not-found">
				<h1>Not Found</h1>
				<div class="entry-content">
					<p>Apologies, but we were unable to find what you were looking for.</p>
				</div><!--.entry-content-->
			</div><!-- .post -->

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>