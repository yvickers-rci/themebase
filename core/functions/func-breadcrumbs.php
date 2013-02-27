<?php
/**
 * Breadcrumbs  :::   if( function_exists( 'breadcrumbs' ) : breadcrumbs(); endif;
 *
 * DEFAULTS:
 * 		breadcrumbs($home = 'Home', $delimiter = '/', $before_crumbs = '<div class="breadcrumbs">', $after_crumbs = '</div>', $before_current = '<span class="current">', $after_current = '</span>');
 * EXAMPLE:
 * 		breadcrumbs( 'Home', '/' );
 *
 */
if(!function_exists('breadcrumbs')){
	function breadcrumbs($home = 'Home', $delimiter = '/', $before_crumbs = '<div class="breadcrumbs">', $after_crumbs = '</div>', $before_current = '<span class="current">', $after_current = '</span>') {

		// $home = 'Home' link text
		// $delimiter = between crumbs
		// $before_crumbs = start crumbs
		// $after_crumbs = end crumbs
		// $before_current = current crumb before
		// $after_current = current crumb after

		if ( !is_home() && !is_front_page() || is_paged() ) {

			echo $before_crumbs;

			global $post;
			$homeLink = get_bloginfo( 'url' );
			echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

			if ( is_category() ) {
				global $wp_query;
				$cat_obj = $wp_query->get_queried_object();
				$thisCat = $cat_obj->term_id;
				$thisCat = get_category($thisCat);
				$parentCat = get_category($thisCat->parent);
				if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
				echo $before_current . single_cat_title('', false) . $after_current;

			} elseif ( is_day() ) {
				echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
				echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
				echo $before_current . get_the_time('d') . $after_current;

			} elseif ( is_month() ) {
				echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
				echo $before_current . get_the_time('F') . $after_current;

			} elseif ( is_year() ) {
				echo $before_current . get_the_time('Y') . $after_current;

			} elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					echo '<a href="' . $homeLink . '/' . $slug['slug'] . 's/">' . $post_type->labels->name . '</a> ' . $delimiter . ' ';
					echo $before_current . get_the_title() . $after_current;
				} else {
					$cat = get_the_category(); $cat = $cat[0];
					echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
					echo $before_current . get_the_title() . $after_current;
				}

			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object(get_post_type());
				echo $before_current . $post_type->labels->singular_name . $after_current;

			} elseif ( is_attachment() ) {
				$parent = get_post($post->post_parent);
				$cat = get_the_category($parent->ID); $cat = $cat[0];
				echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
				echo $before_current . get_the_title() . $after_current;

			} elseif ( is_page() && !$post->post_parent ) {
				echo $before_current . get_the_title() . $after_current;

			} elseif ( is_page() && $post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
					$parent_id  = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
				echo $before_current . get_the_title() . $after_current;

			} elseif ( is_search() ) {
				echo $before_current . 'Search results for "' . get_search_query() . '"' . $after_current;

			} elseif ( is_tag() ) {
				echo $before_current . 'Posts tagged "' . single_tag_title('', false) . '"' . $after_current;

			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata($author);
				echo $before_current . 'Articles posted by ' . $userdata->display_name . $after_current;

			} elseif ( is_404() ) {
				echo $before_current . 'Error 404' . $after_current;
			}

			if ( get_query_var('paged') ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
					echo __('Page') . ' ' . get_query_var('paged');
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
			}

		echo $after_crumbs;

		}
	}
}