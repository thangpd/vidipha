<?php
/**
 * Date: 6/22/20
 * Time: 12:37 PM
 */

function leefee_shortcode( $atts = [], $content = null, $tag = '' ) {
	// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	// override default attributes with user attributes
	$wporg_atts = shortcode_atts( [
		'title' => 'WordPress.org',
	], $atts, $tag );

	// start output
	$o = '';

	// start box
	$o .= '<div class="wporg-box">';

	// title
	$o .= '<h2>' . esc_html__( $wporg_atts['title'], 'wporg' ) . '</h2>';

	// enclosing tags
	if ( ! is_null( $content ) ) {
		// secure output by executing the_content filter hook on $content
		$o .= apply_filters( 'the_content', $content );

		// run shortcode parser recursively
		$o .= do_shortcode( $content );
	}

	// end box
	$o .= '</div>';

	// return output
	return $o;
}

function wporg_shortcodes_init() {
	add_shortcode( 'wporg', 'wporg_shortcode' );
}

add_action( 'init', 'wporg_shortcodes_init' );