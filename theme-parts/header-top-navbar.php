<header id="banner" class="navbar navbar-fixed-top" role="banner">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?php echo home_url(); ?>/">
				<?php bloginfo('name'); ?>
			</a>
			<?php wp_nav_menu( array( 'theme_location' => 'header', 'container' => 'nav', 'container_class' => 'nav-header' ) ); ?>
		</div>
	</div>
</header>