<?php
/**
 * Register a custom post type called "bid".
 *
 * @see get_post_type_labels() for label keys.
 */
function leefee_codex_bid_init() {
	$labels = array(
		'name'                  => _x( 'Đấu thầu', 'Post type general name', 'leefee' ),
		'singular_name'         => _x( 'Bid', 'Post type singular name', 'leefee' ),
		'menu_name'             => _x( 'Đấu thầu', 'Admin Menu text', 'leefee' ),
		'name_admin_bar'        => _x( 'Bid', 'Add New on Toolbar', 'leefee' ),
		'add_new'               => __( 'Add New', 'leefee' ),
		'add_new_item'          => __( 'Add New Bid', 'leefee' ),
		'new_item'              => __( 'New Bid', 'leefee' ),
		'edit_item'             => __( 'Edit Bid', 'leefee' ),
		'view_item'             => __( 'View Bid', 'leefee' ),
		'all_items'             => __( 'All Bids', 'leefee' ),
		'search_items'          => __( 'Search Bids', 'leefee' ),
		'parent_item_colon'     => __( 'Parent Bids:', 'leefee' ),
		'not_found'             => __( 'No bids found.', 'leefee' ),
		'not_found_in_trash'    => __( 'No bids found in Trash.', 'leefee' ),
		'featured_image'        => _x( 'Bid Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'leefee' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'leefee' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'leefee' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'leefee' ),
		'archives'              => _x( 'Bid archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'leefee' ),
		'insert_into_item'      => _x( 'Insert into bid', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'leefee' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this bid', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'leefee' ),
		'filter_items_list'     => _x( 'Filter bids list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'leefee' ),
		'items_list_navigation' => _x( 'Bids list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'leefee' ),
		'items_list'            => _x( 'Bids list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'leefee' ),
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