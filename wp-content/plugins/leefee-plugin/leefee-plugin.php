<?php
/**
 * Plugin Name: Leefee Plugin
 */

define('LEEFEE_URI_PATH',plugin_dir_url(__FILE__));
require plugin_dir_path( __FILE__ ) . 'inc/assets/register-assets.php';
require plugin_dir_path( __FILE__ ) . 'inc/post-types/post-type-ux-bid.php';
require plugin_dir_path( __FILE__ ) . 'inc/post-types/post-type-ux-bid-result.php';
require plugin_dir_path( __FILE__ ) . 'inc/shortcode/dauthau.php';
require plugin_dir_path( __FILE__ ) . 'inc/shortcode/ketquadauthau.php';
$str = plugin_dir_path( __FILE__ ) . 'inc/functions.php';
require $str;














