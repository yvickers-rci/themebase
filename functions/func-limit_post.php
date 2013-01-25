<?php
/**
 * Limit Post Content
 *
 * Replace the_content() with below
 * the_content_limit( 'number', 'text link for read more', 0, '', 'change trailing ellipses to anything else [...]', force read more link TRUE or FALSE, 'redirect to a different page' )
 *
 */

function the_content_limit( $max_char, $more_link_text = 'read more', $stripteaser = 0, $more_file = '', $more_link_trailer = '...', $displayLink = TRUE, $newLink = '' ) {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = strip_tags($content);

	if (strlen($_GET['p']) > 0) {
		return $content;
	}
	else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
		$content = substr($content, 0, $espacio);
		$content = $content;
		$content .= $more_link_trailer;
	}
	else {
		$content = $content;
	}

	if( $displayLink == TRUE ) {
		if($newLink != ''){
			$content .= ' <a href="' . $newLink . '">'.$more_link_text.'</a>';
		} else {
			$content .= ' <a href="' . get_permalink() . '">' . $more_link_text . '</a>';
		}
	}

	return '<p>' . $content . '</p>';
}
