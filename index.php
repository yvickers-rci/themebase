<?php
/**
 * ATTENTION: DO NOT MODIFY THIS FILE.  Define a client directory in your settings file,
 * then create /client_folder/templates/index.php for custom template
 */
if(tb_template_file('index.php')) return;
?>
<?php get_header(); ?>

	<div id="content" role="main">
		<?php
			// begin - The Loop
			if ( have_posts() ) :
	
				// items are returned OPEN TAGS here
	
				while ( have_posts() ) : the_post();
	
					// title
					the_title( '<h1>', '</h1>' );
	
					// image
					if( has_post_thumbnail() ) {
						the_post_thumbnail( 'medium', array( 'class' => 'alignright', 'title' => get_the_title(), 'alt' => get_the_title() ) );
					};
	
					// content
					the_content();
	
				// End The Loop
				endwhile;
	
				// items are returned CLOSE TAGS here

				// wp-page-numbers plugin
				if( function_exists( 'wp_page_numbers' ) ) : wp_page_numbers(); endif;
			
			// NO items returned
			else :
	
				// 404 content
				get_template_part( 'content' , '404' );
	
			// end - The Loop
			endif;
	
			// reset the query
			wp_reset_query();
		?>
	</div>

<?php get_footer(); ?>