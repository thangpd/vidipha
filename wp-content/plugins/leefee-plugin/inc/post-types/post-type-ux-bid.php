<?php
/* Register post menu */
register_post_type( 'bid',
	array(
		'labels'              => array(
			'add_new_item'       => __( 'Add bid', "bid" ),
			'name'               => __( 'Đấu thầu', "bid" ),
			'singular_name'      => __( 'DauThau', "bid" ),
			'edit_item'          => __( 'Edit DauThau', "bid" ),
			'view_item'          => __( 'View DauThau', "bid" ),
			'search_items'       => __( 'Search Bid', "bid" ),
			'not_found'          => __( 'No Bid found', "bid" ),
			'not_found_in_trash' => __( 'No Bid found in Trash', "bid" ),
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
		'rest_base'           => 'ux-bid',
		'menu_icon'           => 'dashicons-book',
	)
);

function my_edit_bid_columns() {
	$columns = array(
		'cb'        => '<input type="checkbox" />',
		'title'     => __( 'Title', 'bid' ),
		'shortcode' => __( 'Shortcode', 'bid' ),
		'date'      => __( 'Date', 'bid' ),
	);

	return $columns;
}

add_filter( 'manage_edit-bid_columns', 'my_edit_bid_columns' );

function my_manage_bid_columns( $column, $post_id ) {
	$post_data = get_post( $post_id, ARRAY_A );
	$slug      = $post_data['post_name'];
	add_thickbox();
	switch ( $column ) {
		case 'shortcode':
			echo '<textarea style="min-width:100%; max-height:30px; background:#eee;">[bid id="' . $slug . '"]</textarea>';
			break;
	}
}

add_action( 'manage_bid_posts_custom_column', 'my_manage_bid_columns', 10, 2 );

/**
 * Update bid preview URL
 */
function ux_bid_scripts() {
	global $typenow;
	if ( 'bid' == $typenow && isset( $_GET["post"] ) ) {
		?>
		<script>
          jQuery(document).ready(function ($) {
            var bid_id = $('input#post_name').val()
            $('#submitdiv').
              after('<div class="postbox"><h2 class="hndle">Shortcode</h2><div class="inside"><p><textarea style="width:100%; max-height:30px;">[bid id="' + bid_id +
                '"]</textarea></p></div></div>')
          })
		</script>
		<?php
	}
}

add_action( 'admin_head', 'ux_bid_scripts' );

function ux_bid_frontend() {
	if ( isset( $_GET["bid"] ) ) {
		?>
		<script>
          jQuery(document).ready(function ($) {
            $.scrollTo('#<?php echo esc_attr( $_GET["bid"] );?>', 300, {offset: -200})
          })
		</script>
		<?php
	}
}

add_action( 'wp_footer', 'ux_bid_frontend' );


function bid_shortcode( $atts, $content = null ) {
	global $wpdb, $post;

	extract( shortcode_atts( array(
			'id' => '',
		),
			$atts
		)
	);

	// Abort if ID is empty.
	if ( empty ( $id ) ) {
		return '<p><mark>No bid ID is set</mark></p>';
	}

	// Get bid by ID or slug.
	$where_col = is_numeric( $id ) ? 'ID' : 'post_name';
	$post_id   = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_type = 'bid' AND $where_col = '$id'" );

	// Polylang support.
	if ( function_exists( 'pll_get_post' ) && pll_get_post( $post_id ) ) {
		$lang_id = pll_get_post( $post_id );
		if ( $lang_id ) {
			$post_id = $lang_id;
		}
	}

	// WPML Support.
	if ( function_exists( 'icl_object_id' ) ) {
		$lang_id = icl_object_id( $post_id, 'bid', false, ICL_LANGUAGE_CODE );
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
			$html              = '<div class="bid-edit-link" data-title="Edit Block: ' . get_the_title( $post_id ) . '"   data-backend="' . esc_url( $edit_link_backend )
			                     . '" data-link="' . esc_url( $edit_link ) . '"></div>' . $html . '';
		}
	} else {
		$html = '<p><mark>Block <b>"' . esc_html( $id ) . '"</b> not found</mark></p>';
	}

	return do_shortcode( $html );
}

add_shortcode( 'bid', 'bid_shortcode' );


if ( ! function_exists( 'bid_categories' ) ) {
	/**
	 * Add bid categories support
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
