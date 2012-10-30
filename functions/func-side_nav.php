<?php
/**
 * Side Navigation  :::  if( function_exists( 'side_nav' ) : side_nav(); endif;
 *
 * DEFAULTS:
 *		side_nav( $specific_id, $which_side ); -- fill in $specific_id with a number if necessary, otherwise, delete
 * EXAMPLE:
 *		side_nav( 1, 'left' );
 *		side_nav( '', 'right' );
 *		side_nav();
 *
 */
function side_nav( $specific_ID = '', $which_side = '' ) {
	global $post;

	// direct parent
	$parent_page = array_reverse( get_post_ancestors( $post->ID ) );
	
	// very very top parent
	$first_parent = get_page( $parent_page[0] );

	// begin side nav
	$which_side = ( ! empty( $which_side ) ) ? ' ' . $which_side : '';
	$nav_output = '<nav class="nav-side' . $which_side . '">'."\n";

		// primary side nav item
		// allows for a custom field to be used
		// to override the nav title - 'page_section_title'
		$specific_ID_title = get_post_meta( $specific_ID, 'page_section_title', true ) ? get_post_meta( $specific_ID, 'page_section_title', true ) : get_the_title( $specific_ID );
		$section_title = ( is_single() ) ? $specific_ID_title : get_post_meta( $first_parent->ID, 'page_section_title', true );
		$section_title = ( $section_title ) ? $section_title : get_the_title( $first_parent->ID );
		
		$nav_output .= ( $specific_ID ) ? '<b><a href="' . get_permalink( $specific_ID ) . '">' . $section_title . '</a></b>'."\n" : '<b><a href="' . get_permalink( $first_parent->ID ) . '">' . $section_title . '</a></b>'."\n";

		// build the menu arrays
		if( $specific_ID != '' ) :
			$navigation_array = get_posts( array( 'post_type' => 'page', 'numberposts' => -1, 'post_parent' => $specific_ID, 'orderby' => 'menu_order', 'order' => ASC ) );
		// 2nd level items
		elseif( is_page() && $post->post_parent ) :
			$navigation_array = get_posts( array( 'post_type' => 'page', 'numberposts' => -1, 'post_parent' => $first_parent->ID, 'orderby' => 'menu_order', 'order' => ASC ) );
		// 1st level items
		else :
			$navigation_array = get_posts( array( 'post_type' => 'page', 'numberposts' => -1, 'post_parent' => $post->ID, 'orderby' => 'menu_order', 'order' => ASC ) );
		endif;

		// 1st level
		$nav_output .= '<ul>'."\n";
		
			foreach( $navigation_array as $nav_item ) :
	
				// current page / specific id check
				if( $nav_item->ID == $post->ID ) :
					$current_nav_output = ' class="current"';
				elseif( $nav_item->ID == $post->post_parent ) :
					$current_nav_output = ' class="current-parent"';
				else :
					$current_nav_output = '';
				endif;
	
				// start 1st level list item
				$nav_output .= '<li' . $current_nav_output . '><a href="' . get_permalink( $nav_item->ID ) . '">' . $nav_item->post_title . '</a>';
				
				// 2nd level array
				$child_check = get_posts( array( 'post_type' => 'page', 'numberposts' => -1, 'post_parent' => $nav_item->ID, 'orderby' => 'menu_order', 'order' => ASC ) );
	
					// check for actual 2nd level items
					if( count( $child_check ) > 0 ) :
	
						// 2nd level
						$nav_output .= '<ul class="children">';
					
						foreach( $child_check as $child ) :
	
							// check for current 2nd level item
							$current_child_output = ( $child->ID == $post->ID ) ? ' class="current"' : '';
	
							// 2nd level list item
							$nav_output .= '<li' . $current_child_output . '><a href="' . get_permalink( $child->ID ) . '">' . $child->post_title . '</a>';
	
						endforeach;
						
						// end 2nd level
						$nav_output .= '</ul>';
						
					endif;
				
				// end 1nd level list item
				$nav_output .= '</li>';
	
			endforeach;
	
		// end 1st level
		$nav_output .= '</ul>'."\n";

	$nav_output .= '</nav>'."\n";

	echo $nav_output;
};