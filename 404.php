<?php
/**
 * ATTENTION: DO NOT MODIFY THIS FILE.  Define a client directory in your settings file,
 * then create /client_folder/templates/404.php for custom template
 */
if(tb_template_file('404.php')) return;
?>
<?php get_header(); ?>
	<div id="content" role="main">
	<?php get_template_part( 'content' , '404' ); ?>
	</div>

<?php get_footer(); ?>