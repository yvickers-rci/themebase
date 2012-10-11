<?php
/* Related Items  :::  if( function_exists( 'related_items' ) : related_items(); endif;
 *
 * DEFAULTS:
 *		related_items( $related_post_type );
 * EXAMPLE:
 *		related_items( 'subpages' ); -- Show subpages of current page
 *		related_items( 'a_post_type' ); -- Show related items of 'a_post_type' for current page
 *
 */
function related_items( $related_type = '' ) {
	global $post;
	global $custom;
	
	$show_list = false;

	$related = $custom['related_' . $related_type][0];

	if( $related_type != '' ) :
	
		if( $related_type == 'subpage' || $related_type == 'subpages' ) :
		
			$show_list = true;
	
			$args = array( 
				'post_type' => 'page',
				'posts_per_page' => -1,
				'post_parent' => $post->ID,
				'orderby' => 'menu_order',
				'order' => ASC
			 );
		
		elseif( !empty( $related ) ):
		
			$show_list = true;
		
			$related = explode( ',' , $related );
			foreach( $related as $r ) :
				$related[] = $r;
			endforeach;
		
			$args = array( 
				'post_type' => $related_type,
				'posts_per_page' => -1,
				'post__in' => $related,
				'orderby' => 'menu_order',
				'order' => ASC
			 );
	
		endif;
		
		if( $show_list == true ) :
		
			echo '<section class="related">';
			
			if( $related_type != 'subpage' )
				echo '<h2>Related</h2>';
	
			query_posts( $args ); if ( have_posts() ) : while ( have_posts() ) : the_post();
		
				$related_custom = get_post_custom();
			
				echo '<article '; post_class( 'related-item' ); echo '>';
					// thumbnail
					if( class_exists( 'MultiPostThumbnails' ) && MultiPostThumbnails::has_post_thumbnail( $related_type , $related_type . '-thumbnail' ) || has_post_thumbnail() ) :
						echo '<a href="' . get_permalink() . '">';
						if ( class_exists( 'MultiPostThumbnails' ) && MultiPostThumbnails::has_post_thumbnail( $related_type , $related_type . '-thumbnail' ) ) :
							MultiPostThumbnails::the_post_thumbnail( $related_type , $related_type . '-thumbnail' );
						elseif( has_post_thumbnail() && $related_type == 'subpage' ) :
							the_post_thumbnail( 'subpage-thumbnail' , array( 'class' => 'subpage-thumbnail' , 'title' => get_the_title() , 'alt' => get_the_title() ) );
						endif;
						echo '</a>';
					endif;
		
					// title
					echo '<h3>';
					echo '<a href="' . get_permalink() . '">';
					echo get_the_title();
					echo '</a>';
					echo '</h3>';
		
					// content
					if( has_excerpt() && $related_type == 'subpage' ) :
						echo apply_filters( 'the_content' , get_the_excerpt() );
					elseif( ! has_excerpt() && $related_type == 'subpage' ) :
						if( function_exists( 'the_content_limit' ) ) :
							the_content_limit( 300,'' );
						else :
							the_excerpt();
						endif;
						echo '<a href="' . get_permalink() . '" class="button">Learn More</a>';
					else :
						the_content();
					endif;
				echo '</article>';
			
			endwhile; endif; wp_reset_query();
			
			echo '</section>';
	
		endif;
	
	endif;
}
?>