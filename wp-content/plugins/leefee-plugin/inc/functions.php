<?php
/**
 * Date: 1/17/21
 * Time: 2:52 PM
 */


if ( ! function_exists( 'leefee_template_include' ) ) {
	function leefee_template_include( $template ) {
		//we can't use query arg because can't coverage multiple dimension like : test/test/test
		//add_query_arg( [] ) return request uri when empty array is pass as parameter.
		//if ( str_replace( '/', '', add_query_arg( [] ) ) == 'test' ) {
		$get_queried_object = get_queried_object();
		if ( $get_queried_object->post_name == 'test-test' ) {
			$plugin_dir_path = plugin_dir_path( __FILE__ ) . 'templates/test-template.php';
			$template        = $plugin_dir_path;
		}

		return $template;
	}

	add_filter( 'template_include', 'leefee_template_include' );
}











