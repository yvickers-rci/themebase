<?php
define('MAGPIE_CACHE_AGE', 180);
define('MAGPIE_INPUT_ENCODING', 'UTF-8');
define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');

// Display Twitter messages
if(!function_exists('twitter_messages')){
	function twitter_messages($username = '', $num = 1, $list = false, $update = true, $linked  = '#', $hyperlinks = true, $twitter_users = true, $encode_utf8 = false) {

		global $twitter_options;

		include_once( ABSPATH . WPINC . '/feed.php' );

		$messages = fetch_feed( 'http://api.twitter.com/1/statuses/user_timeline/' . $username . '.rss' );

		$message_items = $messages->get_items(0, 10);

		if ( $list ) echo '<ul class="twitter-list">';

		if ( $username == '' ) {

			if ( $list ) echo '<li>Enter a username</li>';

		} else {

			if ( empty( $message_items ) ) {

				if ( $list ) echo '<li>No public Twitter messages.</li>';

			} else {

				$i = 0;

				foreach ( $message_items as $message ) {

					$msg = " " . substr( strstr( $message->get_description(), ': ' ), 2, strlen( $message->get_description() ) ) . " ";

					if( $encode_utf8 ) { $msg = utf8_encode( $msg ); }

					$link = $message->get_link();

					if ( $list ) {

						echo '<li class="twitter-item">';

					} elseif ( $num != 1 ) {

						echo '<p class="twitter-message">';

					}

					if ( $hyperlinks ) { $msg = hyperlinks( $msg ); }

					if ( $twitter_users )  { $msg = twitter_users( $msg ); }

					if ( $linked != '' || $linked != false ) {

						if( $linked == 'all' )  {

							$msg = '<a href="' . $link . '" class="twitter-link">' . $msg . '</a>';  // Puts a link to the status of each tweet

						} else {

							$msg = $msg . '<a href="' . $link . '" class="twitter-link">' . $linked . '</a>'; // Puts a link to the status of each tweet

						}
					}

					echo $msg;

					if( $update ) {

						$time = strtotime( $message->get_date() );

						if ( ( abs( time() - $time ) ) < 86400 ) {

							$h_time = sprintf( __('%s ago'), human_time_diff( $time ) );

						} else {

							$h_time = date( 'Y/m/d' , $time );

						}

						echo '<span class="twitter-timestamp"><abbr title="' . date( 'Y/m/d H:i:s' , $time ) . '">' . $h_time . '</abbr></span>';

					}

					if ( $list ) {

						echo '</li>';

					} elseif ( $num != 1 ) {

						echo '</p>';

					}

					$i++;

					if ( $i >= $num ) break;

				}

			}

		}

		if ( $list ) echo '</ul>';
	}
}

// Link discover stuff
if(!function_exists('hyperlinks')){
	function hyperlinks( $text ) {

		// match protocol://address/path/file.extension?some=variable&another=asf%
		$text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $text);

		// match www.something.domain/path/file.extension?some=variable&another=asf%
		$text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $text);

		// match name@address
		$text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);

		// match #trendingtopics.
		$text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"twitter-link\">#$2</a>$3 ", $text);

		return $text;
	}
}

if(!function_exists('twitter_users')){
	function twitter_users( $text ) {

		// match usernames
		$text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);

		return $text;

	}
}