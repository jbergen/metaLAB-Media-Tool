<?php
/*
filename: header.php
description: This partial is called by <?php get_header(); ?>
Contains all the header information and any part of the website that you 
want to remain constant at the top of the page.
*/
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php wp_title( '-', true, 'right' ); echo wp_specialchars( get_bloginfo('name'), 1 ) ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory') ?>/assets/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory') ?>/assets/css/typography.css" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory') ?>/assets/jquery-ui/css/smoothness/jquery-ui-1.8.13.custom.css" />
<?php wp_head() // For plugins ?>
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="Latest Posts" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
	<script type="text/javascript" language="Javascript" src="<?php bloginfo('template_directory') ?>/assets/jquery/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" language="Javascript" src="<?php bloginfo('template_directory') ?>/assets/jquery-ui/js/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" language="Javascript" src="<?php bloginfo('template_directory') ?>/assets/jquery.address-1.4/jquery.address-1.4.min.js"></script>
	<script type="text/javascript" language="Javascript" src="<?php bloginfo('template_directory') ?>/assets/support.js"></script>




</head>

<body>

<div id="wrapper">
