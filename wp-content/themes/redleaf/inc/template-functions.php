<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package redleaf
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function redleaf_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'redleaf_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function redleaf_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'redleaf_pingback_header' );

function register_custom_post_types() {

	$labels = [
    'name' => _x('Sites', 'post type general name'),
    'singular_name' => _x('Site', 'post type singular name'),
    'add_new' => _x('Add New', 'Site'),
    'add_new_item' => __('Add New Site'),
    'edit_item' => __('Edit Site'),
    'new_item' => __('New Site'),
    'view_item' => __('View Site'),
    'search_items' => __('Search Sites'),
    'not_found' =>  __('Nothing found'),
    'not_found_in_trash' => __('Nothing found in Trash'),
    'parent_item_colon' => ''
  ];

  $args = [
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 5,
		'menu_icon' => 'dashicons-Site-alt3',
    'has_archive' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes']
  ];

  register_post_type( 'site' , $args );

}

if ( !function_exists('log_it') ) {
	function log_it( $message ) {
	 if( WP_DEBUG === true ){
	   if( is_array( $message ) || is_object( $message ) ){
	     error_log( print_r( $message, true ) );
	   } else {
	     error_log( $message );
	   }
	 }
	}
}

add_action('init', 'register_custom_post_types');

if ( !function_exists('log_it') ) {
	function log_it( $message ) {
	 if( WP_DEBUG === true ){
	   if( is_array( $message ) || is_object( $message ) ){
	     error_log( print_r( $message, true ) );
	   } else {
	     error_log( $message );
	   }
	 }
	}
}

function virtual_tour_loader () {
	ob_start(); ?>
	<div class="loader-container">
		<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
		<span class="loading-virtual-tour">Virtual Tour Loading</span>
		<span class="loading-text">Enjoy This 360 Preview While You Wait!</span>
	</div>
	<?php
	echo ob_get_clean();
}

function my_acf_update_value( $value, $post_id, $field, $original ) {
	// log_it($field['name'] . ' start');
	// log_it($original);
	// log_it($field['name'] . ' end');
	// add  && ( $value !== $original )
    if ( $value && $field['name'] == 'upload_tiles' ) {
		require_once(ABSPATH .'/wp-admin/includes/file.php');

		global $wp_filesystem;
		if ( ! $wp_filesystem ) {
			WP_Filesystem();
			if ( ! $wp_filesystem ) 
			return $value;
		}

		$contentdir = trailingslashit( $wp_filesystem->wp_content_dir() ); 
		$wp_filesystem->mkdir( 'tiles' );
		// if ( ! $wp_filesystem->put_contents(  $contentdir . 'tiles/test.txt', 'Test file contents', FS_CHMOD_FILE) ) 
		// {
		// 	echo "error saving file!";
		// }
		// 	unset($_POST);
		// }
		// log_it($value);
		$tiles_upload_url = wp_get_attachment_url($value);
		$tiles_url = get_attached_file($value);
		$tiles_dir = wp_get_upload_dir()['basedir'] . '/tiles/tiles_' . $post_id;
		// $wp_filesystem->put_contents(  $contentdir . 'tiles/test.txt', 'Test file contents', FS_CHMOD_FILE) ) 
		$tiles = unzip_file( $tiles_url, $tiles_dir );
    }

	if ( $value && $field['name'] == 'pano_xml') {
		
		$tiles_dir = wp_get_upload_dir()['basedir'] . '/tiles/tiles_' . $post_id . '/tiles/';
		$tile_nodes = scandir($tiles_dir);
		log_it($tile_nodes);
		$node_dir = '';
		foreach( $tile_nodes as $dir ) {
			if ( str_contains($dir, 'node') ) {
				$node_dir = $dir;
			}
		}
		// log_it($tiles);
		// if ( !is_wp_error( $tiles ) ) wp_delete_file($tiles_url);
		$pano_url = get_attached_file($value);
		$pano_content = file_get_contents($pano_url);
		$doc = new DOMDocument();
		$doc->loadXML($pano_content);
		$xml_input = $doc->getElementsByTagName('input');
	
		log_it($node_dir);
		$xml_input->item(0)->setAttribute("leveltileurl", get_site_url() . '/wp-content/uploads/tiles/tiles_' . $post_id . '/tiles/' . $node_dir . '/cf_%c/l_%l/c_%x/tile_%y.jpg');
		$doc->save($pano_url);
		// wp_delete_file($pano_url);
		// update_field('pano_xml', $tiles_dir . '/pano.xml');
		
	}

    return $value;
}

// Apply to all fields.
add_filter('acf/update_value', 'my_acf_update_value', 10, 4);

add_filter( 'show_admin_bar' , '__return_false' ); 