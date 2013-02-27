<?php
/**
 * ATTENTION: DO NOT MODIFY THIS FILE.  Define a client directory in your settings file,
 * then create /client_folder/templates/footer.php for custom template
 */
if(tb_template_file('footer.php')) return;
?>
	<footer>
		<?php if( function_exists( 'copyright' ) ) : copyright(); endif; ?>
		
		<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => 'nav', 'container_class' => 'nav-footer' ) ); ?>

		<?php if( function_exists( 'rci_social' ) ) : rci_social(); endif; ?>
	</footer>

<?php wp_footer(); ?>

</body>
</html>
