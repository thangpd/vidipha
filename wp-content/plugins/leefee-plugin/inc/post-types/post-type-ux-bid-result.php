<?php
/* Register post menu */
register_post_type( 'bidresult',
	array(
		'labels'              => array(
			'add_new_item'       => __( 'Add kết quả đấu thầu', "flatsome" ),
			'name'               => __( 'Kết Quả Đấu Thầu', "flatsome" ),
			'singular_name'      => __( 'Block', "flatsome" ),
			'edit_item'          => __( 'Edit Block', "flatsome" ),
			'view_item'          => __( 'View Block', "flatsome" ),
			'search_items'       => __( 'Search Bidresult', "flatsome" ),
			'not_found'          => __( 'No Bidresult found', "flatsome" ),
			'not_found_in_trash' => __( 'No Bidresult found in Trash', "flatsome" ),
		),
		'public'              => true,
		'has_archive'         => false,
		'show_in_menu'        => true,
		'supports'            => array( 'thumbnail', 'editor', 'title', 'revisions', 'custom-fields' ),
		'show_in_nav_menus'   => true,
		'exclude_from_search' => true,
		'rewrite'             => array( 'slug' => '' ),
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'capability_type'     => 'page',
		'hierarchical'        => true,
		'menu_position'       => null,
		'show_in_rest'        => true,
		'rest_base'           => 'ux-bidresult',
		'menu_icon'           => 'dashicons-book',
	)
);

function my_edit_bidresult_columns() {
	$columns = array(
		'cb'        => '<input type="checkbox" />',
		'title'     => __( 'Title', 'flatsome' ),
		'shortcode' => __( 'Shortcode', 'flatsome' ),
		'date'      => __( 'Date', 'flatsome' ),
	);

	return $columns;
}

add_filter( 'manage_edit-bidresult_columns', 'my_edit_bidresult_columns' );

function my_manage_bidresult_columns( $column, $post_id ) {
	$post_data = get_post( $post_id, ARRAY_A );
	$slug      = $post_data['post_name'];
	add_thickbox();
	switch ( $column ) {
		case 'shortcode':
			echo '<textarea style="min-width:100%; max-height:30px; background:#eee;">[bidresult id="' . $slug . '"]</textarea>';
			break;
	}
}

add_action( 'manage_bidresult_posts_custom_column', 'my_manage_bidresult_columns', 10, 2 );

/**
 * Update bidresult preview URL
 */
function ux_bidresult_scripts() {
	global $typenow;
	if ( 'bidresult' == $typenow && isset( $_GET["post"] ) ) {
		?>
        <script>
            jQuery(document).ready(function ($) {
                var bidresult_id = $('input#post_name').val()
                $('#submitdiv').after('<div class="postbox"><h2 class="hndle">Shortcode</h2><div class="inside"><p><textarea style="width:100%; max-height:30px;">[bidresult id="' + bidresult_id +
                    '"]</textarea></p></div></div>')
            })
        </script>
		<?php
	}
}

add_action( 'admin_head', 'ux_bidresult_scripts' );

function ux_bidresult_frontend() {
	if ( isset( $_GET["bidresult"] ) ) {
		?>
        <script>
            jQuery(document).ready(function ($) {
                $.scrollTo('#<?php echo esc_attr( $_GET["bidresult"] );?>', 300, {offset: -200})
            })
        </script>
		<?php
	}
}

add_action( 'wp_footer', 'ux_bidresult_frontend' );


function bidresult_shortcode( $atts, $content = null ) {
	global $wpdb, $post;

	extract( shortcode_atts( array(
			'id' => '',
		),
			$atts
		)
	);

	// Abort if ID is empty.
	if ( empty ( $id ) ) {
		return '<p><mark>No bidresult ID is set</mark></p>';
	}

	// Get bidresult by ID or slug.
	$where_col = is_numeric( $id ) ? 'ID' : 'post_name';
	$post_id   = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_type = 'bidresult' AND $where_col = '$id'" );

	// Polylang support.
	if ( function_exists( 'pll_get_post' ) && pll_get_post( $post_id ) ) {
		$lang_id = pll_get_post( $post_id );
		if ( $lang_id ) {
			$post_id = $lang_id;
		}
	}

	// WPML Support.
	if ( function_exists( 'icl_object_id' ) ) {
		$lang_id = icl_object_id( $post_id, 'bidresult', false, ICL_LANGUAGE_CODE );
		if ( $lang_id ) {
			$post_id = $lang_id;
		}
	}

	if ( $post_id ) {
		$the_post = get_post( $post_id, null, 'display' );
		$html     = $the_post->post_content;

		if ( empty( $html ) ) {
			$html = '<p class="lead shortcode-error">Open this in UX Builder to add and edit content</p>';
		}

		// Add edit link for admins.
		if ( isset( $post ) && current_user_can( 'edit_pages' )
		     && ! is_customize_preview()
		     && function_exists( 'ux_builder_is_active' )
		     && ! ux_builder_is_active() ) {
			$edit_link         = ux_builder_edit_url( $post->ID, $post_id );
			$edit_link_backend = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
			$html              = '<div class="bidresult-edit-link" data-title="Edit Block: ' . get_the_title( $post_id ) . '"   data-backend="' . esc_url( $edit_link_backend )
			                     . '" data-link="' . esc_url( $edit_link ) . '"></div>' . $html . '';
		}
	} else {
		$html = '<p><mark>Block <b>"' . esc_html( $id ) . '"</b> not found</mark></p>';
	}

	return do_shortcode( $html );
}

add_shortcode( 'bidresult', 'bidresult_shortcode' );


if ( ! function_exists( 'bidresult_categories' ) ) {
	/**
	 * Add bidresult categories support
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
