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

<?php if( file_exists( ABSPATH . '/humans.txt' ) ) : ?>
<link type="text/plain" rel="author" href="<?php bloginfo( 'url' ); ?>/humans.txt">
<?php endif; ?>
<?php if( file_exists( ABSPATH . '/favicon.ico' ) ) : ?>
<link rel="shortcut icon" href="<?php echo bloginfo( 'url' ); ?>/favicon.ico">
<?php endif; ?>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

<?php wp_head(); ?>

</head>

<body <?php body_class( str_replace( array( 'templates/', '.php' ), '', get_post_meta( $post->ID, '_wp_page_template', true) ) ); ?>>
	<!--[if lte IE 7]><p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p><![endif]-->

	<header id="SiteHeader">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="logo"><img src="http://placehold.it/300x100/000/fff&amp;text=the+logo" alt="REPLACE ME" class="logo"><!--<img src="<?php bloginfo( 'stylesheet_directory' ); ?>/assets/images/logo.png" alt="<?php echo esc_attr( get_bloginfo( 'name' , 'display') ); ?>">--></a>

		<?php wp_nav_menu( array( 'theme_location' => 'header', 'container' => 'nav', 'container_class' => 'nav-header' ) ); ?>
	</header>
