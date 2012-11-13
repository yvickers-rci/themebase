	<header id="SiteHeader">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="logo"><img src="http://placehold.it/300x100/000/fff&amp;text=the+logo" alt="REPLACE ME" class="logo"><!--<img src="<?php bloginfo( 'stylesheet_directory' ); ?>/assets/images/logo.png" alt="<?php echo esc_attr( get_bloginfo( 'name' , 'display') ); ?>">--></a>

		<?php wp_nav_menu( array( 'theme_location' => 'header', 'container' => 'nav', 'container_class' => 'nav-header' ) ); ?>
	</header>
