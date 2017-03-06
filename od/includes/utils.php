<?php
/* Useful utils
/* ------------------------------------ */

/**
 * Adds an extra class on content paragraphs
 * @param      string  $content  The content
 * @param      string  $class    The class
 * @return     string  Pretty content with added class
 */
function od_add_content_class( $content, $class = NULL ) {
	return str_replace( '<p>', '<p class="'.$class.'">', apply_filters( 'the_content', $content ) );
}

/**
 * Creates a link with attributes (if provided) => no more dirty HTML in php
 * @param      string  $url    The url (can be post id too)
 * @param      string  $text   The text
 * @param      array   $attr   The attribute
 * @return     string  The compiled <a href...>
 */
function od_get_link( $url, $text, $attr = NULL ) {
	// early exit on no values
	if( empty($url) || empty($text) || $url == 'mailto:' || $url == 'tel:' ) { return; }

	// check if url is just a post id
	$url = ( is_int($url) ) ? get_the_permalink( $url ) : $url;
	if( empty($url) ) { return; } // early exit

	// setup start of a href
	$html = '<a href="'.str_replace( ' ', '', $url ).'"';

	// add attributes
	if( $attr != NULL ) {
		foreach( $attr as $name => $value ) { $html .= ' '.$name.'="'.$value.'"'; }
	}

	// finish off the link
	$html .= '>'.$text.'</a>';

	// and return
	return $html;
}
// direct echo
function od_link( $url, $text, $attr = NULL ) {
	echo od_get_link( $url, $text, $attr = NULL );
}

/**
 * Sprinkle some salt on the string.
 * @param      string  $string     The string
 * @return     string  The salted string.
 */
function od_get_salt( $string ) {
	if( !defined(BEFORE_SALT) || !defined(AFTER_SALT) ) {
		if( in_array(OD_ENV, array('dev', 'local')) ) {
			wp_die( '<div class="error" style="padding: 1em; font-size: 1.5em; background-color: crimson; color: white;">'.__("WP is not able to sprinkle salt, when you haven't told what kind of salt to use before & after...<br>Better define them in the wp-config", "od").'</div>' );
		}
		return NULL;
	}
	return md5( BEFORE_SALT.$string.AFTER_SALT );
}

/**
 * Handy util to get the string between string-parts
 * @param      string  $string  The string
 * @param      string  $from    The from
 * @param      string  $to      The to
 * @return     string  The part between $to & $from
 */
function od_get_string_between( $string, $from, $to ) {
	$substring = substr( $string, strpos($string, $from) + strlen($from), strlen($string) );
	return substr( $substring, 0, strpos($substring, $to) );
}
// direct echo
function od_string_between( $string, $from, $to ) {
	echo od_get_string_between( $string, $from, $to );
}

/**
 * Strips url from obsolete stuff, for prettier display
 * @param      string  $url    The url
 * @return     string  The cleaned up & pretty url for displaying
 */
function od_get_stripped_url( $url ) {
	return rtrim( str_replace(array('https://', 'http://', 'www.'), '', $url), '/' );
}
// direct echo
function od_stripped_url( $url ) {
	echo od_get_stripped_url( $url );
}
