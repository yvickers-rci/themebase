<?php
/* @plugin plugin dependent - move out of theme */
// Dynamically updating copyright info in footer
if(!function_exists('copyright')){
	function copyright( $pre_text = '', $post_text = '' ) {
		global $wpdb;

		if( get_option( 'rci_copyright_pre_text' ) )
			$pre_text = get_option( 'rci_copyright_pre_text' ) . ' ';

		if( get_option( 'rci_copyright_post_text' ) )
			$post_text = get_option( 'rci_copyright_post_text' );

		$copyright_dates = $wpdb->get_results( "SELECT YEAR( min( post_date_gmt ) ) AS firstdate, YEAR( max( post_date_gmt ) ) AS lastdate FROM $wpdb->posts WHERE post_status = 'publish'");
		$output = '';
		if($copyright_dates) :
			$copyright = '&copy; ' . $copyright_dates[0]->firstdate;
			if( $copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate ) :
				$copyright .= '-' . $copyright_dates[0]->lastdate;
			endif;
			$output = '<div class="copyright">' . $pre_text . $copyright . ' ' . get_bloginfo( 'name', 'display' ) . $post_text . '</div>';
		endif;

		echo $output;
	}
}