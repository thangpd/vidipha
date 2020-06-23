<?php
/**
 * Date: 6/23/20
 * Time: 10:22 AM
 */

function wpdocs_theme_name_scripts() {
	wp_register_style( 'leefee-dauthau-css', plugin_dir_url( __DIR__ ) . 'shortcode/assets/css/dauthau.css' );
}

add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );