<?php
/**
 * Register a custom post type called "bidresult".
 *
 * @see get_post_type_labels() for label keys.
 */
function leefee_codex_bidresult_init() {
	$labels = array(
		'name'                  => _x( 'Kết Quả Đấu thầu', 'Post type general name', 'leefee' ),
		'singular_name'         => _x( 'Kết Quả Đấu thầu', 'Post type singular name', 'leefee' ),
		'menu_name'             => _x( 'Kết Quả Đấu thầu', 'Admin Menu text', 'leefee' ),
		'name_admin_bar'        => _x( 'Bidresult', 'Add New on Toolbar', 'leefee' ),
		'add_new'               => __( 'Add New', 'leefee' ),
		'add_new_item'          => __( 'Add New Bidresult', 'leefee' ),
		'new_item'              => __( 'New Bidresult', 'leefee' ),
		'edit_item'             => __( 'Edit Bidresult', 'leefee' ),
		'view_item'             => __( 'View Bidresult', 'leefee' ),
		'all_items'             => __( 'All Bidresultsresult', 'leefee' ),
		'search_items'          => __( 'Search Bidresultsresult', 'leefee' ),
		'parent_item_colon'     => __( 'Parent Bidresultsresult:', 'leefee' ),
		'not_found'             => __( 'No bidresultresult found.', 'leefee' ),
		'not_found_in_trash'    => __( 'No bidresultresult found in Trash.', 'leefee' ),
		'featured_image'        => _x( 'Bidresult Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'leefee' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'leefee' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'leefee' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'leefee' ),
		'archives'              => _x( 'Bidresult archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'leefee' ),
		'insert_into_item'      => _x( 'Insert into bidresult', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'leefee' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this bidresult', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'leefee' ),
		'filter_items_list'     => _x( 'Filter bidresultresult list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'leefee' ),
		'items_list_navigation' => _x( 'Bidresultsresult list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'leefee' ),
		'items_list'            => _x( 'Bidresultsresult list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'leefee' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'bidresult' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'bidresult', $args );
}

add_action( 'init', 'leefee_codex_bidresult_init' );





if ( ! function_exists( 'bidresult_categories' ) ) {
	/**
	 * Add block categories support
	 */
	function bidresult_categories() {
		$args = array(
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
		);
		register_taxonomy( 'bidresult_categories', array( 'bidresult' ), $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'bidresult_categories', 0 );
}





