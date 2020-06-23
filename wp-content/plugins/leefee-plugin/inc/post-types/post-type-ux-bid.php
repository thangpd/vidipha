<?php
/**
 * Register a custom post type called "bid".
 *
 * @see get_post_type_labels() for label keys.
 */
function leefee_codex_bid_init() {
	$labels = array(
		'name'                  => _x( 'Đấu thầu', 'Post type general name', 'leefee' ),
		'singular_name'         => _x( 'Đấu thầu', 'Post type singular name', 'leefee' ),
		'menu_name'             => _x( 'Đấu thầu', 'Admin Menu text', 'leefee' ),
		'name_admin_bar'        => _x( 'Đấu thầu', 'Add New on Toolbar', 'leefee' ),
		'add_new'               => __( 'Add New', 'leefee' ),
		'add_new_item'          => __( 'Add New Đấu thầu', 'leefee' ),
		'new_item'              => __( 'New Đấu thầu', 'leefee' ),
		'edit_item'             => __( 'Edit Đấu thầu', 'leefee' ),
		'view_item'             => __( 'View Đấu thầu', 'leefee' ),
		'all_items'             => __( 'All Đấu thầu', 'leefee' ),
		'search_items'          => __( 'Search Đấu thầu', 'leefee' ),
		'parent_item_colon'     => __( 'Parent Đấu thầu:', 'leefee' ),
		'not_found'             => __( 'No Đấu thầu found.', 'leefee' ),
		'not_found_in_trash'    => __( 'No Đấu thầu found in Trash.', 'leefee' ),
		'featured_image'        => _x( 'Đấu thầu Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'leefee' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'leefee' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'leefee' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'leefee' ),
		'archives'              => _x( 'Đấu thầu archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'leefee' ),
		'insert_into_item'      => _x( 'Insert into Đấu thầu', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'leefee' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Đấu thầu', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'leefee' ),
		'filter_items_list'     => _x( 'Filter Đấu thầu list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'leefee' ),
		'items_list_navigation' => _x( 'Đấu thầu list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'leefee' ),
		'items_list'            => _x( 'Đấu thầu list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'leefee' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'bid' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'bid', $args );
}

add_action( 'init', 'leefee_codex_bid_init' );


if ( ! function_exists( 'bid_categories' ) ) {
	/**
	 * Add block categories support
	 */
	function bid_categories() {
		$args = array(
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
		);
		register_taxonomy( 'bid_categories', array( 'bid' ), $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'bid_categories', 0 );
}