<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

<title><?php wp_title( '|', true, 'right' ); ?></title>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php // FaceBook Meta Tags ?>
<meta property="og:image" content="<?php bloginfo( 'stylesheet_directory' ); ?>/assets/images/logo.png">
<meta property="og:title" content="<?php wp_title( '|', true, 'right' ); ?>">
<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">

<link type="text/plain" rel="author" href="<?php bloginfo( 'url' ); ?>/humans.txt">

<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-1.8.2.min.js"><\/script>')</script>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<!--[if lte IE 7]>
		<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
	<![endif]-->

<?php
	// Use Bootstrap's navbar if enabled in config.php
	if ( current_theme_supports( 'bootstrap-top-navbar' ) ) :
		get_template_part( 'theme-parts/header-top-navbar' );
	else :
		get_template_part( 'theme-parts/header-normal' );
	endif;
?>