<?php
/**
 * Side Navigation  :::  if( function_exists( 'side_nav' ) : side_nav(); endif;
 *
 * DEFAULTS:
 *		side_nav( $specific_id ); -- fill in $specific_id with a number if necessary, otherwise, delete
 * EXAMPLE:
 *		side_nav( 1 );
 *		side_nav();
 *
 */
function side_nav( $specific_ID = '' ) {
	global $post;

	$parent_page = array_reverse( get_post_ancestors( $post->ID ) );
	$first_parent = get_page( $parent_page[0] );

	$section_title = get_post_meta( $first_parent->ID,'page-section_title',true );
	$section_title = ( $section_title ) ? $section_title : get_the_title( $first_parent->ID );

	$nav_output = '<nav class="nav-side">';

	$nav_output .= '<b>' . $section_title . '</b>';

	if( is_page() && !$post->post_parent )
		$current = ' current_page_item';

	// SUB PAGE
	if( is_page() && $post->post_parent ) :
		$args = array( 'title_li' => '' , 'child_of' => $first_parent->ID , 'sort_column' => 'menu_order' , 'echo' => 0 );
	// PARENT PAGE
	else :
		if( $specific_ID != '' ) :
			$args = array( 'title_li' => '' , 'child_of' => $specific_ID , 'sort_column' => 'menu_order' , 'echo' => 0 );
		else :
			$args = array( 'title_li' => '' , 'child_of' => $post->ID , 'sort_column' => 'menu_order' , 'echo' => 0 );
		endif;
	endif;

	$nav_output .= '<ul>';	
		if( $specific_ID != '' ) :
			$nav_output .= '<li class="page_item page-item-parent' . $current . '"><a href="' . get_permalink( $specific_ID ) . '">' . get_the_title( $specific_ID ) . '</a></li>';
		elseif( $specific_ID != '' && !$post->post_parent ) :
			$nav_output .= '<li class="page_item page-item-parent' . $current . '"><a href="' . get_permalink( $post->ID ) . '">' . get_the_title( $post->ID ) . '</a></li>';
		else :
			$nav_output .= '<li class="page_item page-item-parent' . $current . '"><a href="' . get_permalink( $first_parent->ID ) . '">' . get_the_title( $first_parent->ID ) . '</a></li>';
		endif;
		$nav_output .= wp_list_pages( $args );
	$nav_output .= '</ul>';

	$nav_output .= '</nav>';

	echo $nav_output;
};