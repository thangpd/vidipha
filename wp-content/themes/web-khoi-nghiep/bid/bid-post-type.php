<?php
/**
 * Date: 6/22/20
 * Time: 10:58 AM
 */

/**
 * Register a custom post type called "bid".
 *
 * @see get_post_type_labels() for label keys.
 */
function wpdocs_codex_bid_init() {
	$labels = array(
		'name'                  => _x( 'Đấu thầu', 'Post type general name', 'flatsome' ),
		'singular_name'         => _x( 'Book', 'Post type singular name', 'flatsome' ),
		'menu_name'             => _x( 'Books', 'Admin Menu text', 'flatsome' ),
		'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'flatsome' ),
		'add_new'               => __( 'Add New', 'flatsome' ),
		'add_new_item'          => __( 'Add New Book', 'flatsome' ),
		'new_item'              => __( 'New Book', 'flatsome' ),
		'edit_item'             => __( 'Edit Book', 'flatsome' ),
		'view_item'             => __( 'View Book', 'flatsome' ),
		'all_items'             => __( 'All Books', 'flatsome' ),
		'search_items'          => __( 'Search Books', 'flatsome' ),
		'parent_item_colon'     => __( 'Parent Books:', 'flatsome' ),
		'not_found'             => __( 'No bids found.', 'flatsome' ),
		'not_found_in_trash'    => __( 'No bids found in Trash.', 'flatsome' ),
		'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'flatsome' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'flatsome' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'flatsome' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'flatsome' ),
		'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'flatsome' ),
		'insert_into_item'      => _x( 'Insert into bid', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'flatsome' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this bid', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'flatsome' ),
		'filter_items_list'     => _x( 'Filter bids list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'flatsome' ),
		'items_list_navigation' => _x( 'Books list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'flatsome' ),
		'items_list'            => _x( 'Books list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'flatsome' ),
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

add_action( 'init', 'wpdocs_codex_bid_init' );