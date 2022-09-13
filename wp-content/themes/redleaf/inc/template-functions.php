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
		'menu_icon' => 'dashicons-welcome-view-site',
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

function virtual_tour_loader ($video, $loader_text = false, $loader_subtext = false) {
	ob_start();
	$preview_text = $loader_text ? $loader_text : 'Virtual Tour Loading';
	$preview_subtext = $loader_subtext ? $loader_subtext : '';
	if ( !$loader_subtext ) {
		$loader_type = $video ? 'Video' : '360';
		$preview_subtext = 'Enjoy This ' . $loader_type . ' Preview While You Wait!';
	}
	if ( $loading_text = get_field('loading_text')) ?>
	<div class="loader-container">
		<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
		<span class="loading-virtual-tour"><?php echo $preview_text; ?></span>
		<span class="loading-text"><?php echo $preview_subtext; ?></span>
	</div>
	<?php
	echo ob_get_clean();
}

function my_acf_update_value_pre_save( $post_id, $post ) {
	// log_it($field['name'] . ' start');
	// log_it($original);
	// log_it($field['name'] . ' end');
	// add  && ( $value !== $original )
	if ( $post->post_status == 'auto-draft' ) return;
	global $wp_filesystem;
		if ( ! $wp_filesystem ) {
			WP_Filesystem();
			if ( ! $wp_filesystem ) 
			return;
		}

	$values = $_POST['acf'];
	$old_tiles = get_field('field_62db15cc35644', $post_id);
	$tiles = $values['field_62db15cc35644'];
    if ( $tiles ) {
		if ( $old_tiles && $old_tiles['id'] == $tiles ) {
			return;
		}
		require_once(ABSPATH .'/wp-admin/includes/file.php');

		$contentdir = trailingslashit( $wp_filesystem->wp_content_dir() ); 
		$wp_filesystem->mkdir( 'tiles' );
		$tiles_url = get_attached_file($tiles);
		$tiles_dir = wp_get_upload_dir()['basedir'] . '/tiles/tiles_' . $post_id;
		
		// $wp_filesystem->put_contents(  $contentdir . 'tiles/test.txt', 'Test file contents', FS_CHMOD_FILE) ) 
		
		$tiles_unzipped = unzip_file( $tiles_url, $tiles_dir );   
		$path = pathinfo($tiles_url);
		$newfile = $path['dirname'] .'/' . 'tiles-site-' . $post_id . '.zip';

		$placeholder_file = fopen($newfile, "a");
		fwrite($placeholder_file, 'placeholder text');
		fclose($placeholder_file);//close file
		log_it('tiles');	
		log_it($tiles_url);
		log_it('tiles');	
		update_attached_file( $tiles, 'tiles-site-' . $post_id . '.zip' );
		wp_update_attachment_metadata( $tiles, [ 'filesize' => filesize($newfile) ]);
		
		unlink($tiles_url);
		

		// $placeholder_attachment = wp_insert_attachment(
		// 	array(
		// 		'guid' => wp_get_upload_dir()['path'] . '/tiles-site-' . $post_id . '.temp'
		// 	),
		// 	$placeholder_file,
		// 	$post_id
		// );
		// log_it('placeholder');
		// log_it(wp_get_upload_dir()['path']);
		// log_it('placeholder');
		// update_field('upload_tiles', $placeholder_attachment, $post_id);
		if ( isset($values['field_62dad141ce715']) ) {
			$xml = $values['field_62dad141ce715'];
			$tile_nodes = scandir($tiles_dir . '/tiles/');
			$node_dir = '';
			foreach( $tile_nodes as $dir ) {
				if ( str_contains($dir, 'node') ) {
					$node_dir = $dir;
				}
			}
			// log_it($tiles);
			// if ( !is_wp_error( $tiles ) ) wp_delete_file($tiles_url);
			$pano_url = get_attached_file($xml);
			$pano_content = file_get_contents($pano_url);
			$doc = new DOMDocument();
			$doc->loadXML($pano_content);
			$xml_input = $doc->getElementsByTagName('input');
			$xml_input->item(0)->setAttribute("leveltileurl", get_site_url() . '/wp-content/uploads/tiles/tiles_' . $post_id . '/tiles/' . $node_dir . '/cf_%c/l_%l/c_%x/tile_%y.jpg');
			// $new_xml_path = $tiles_dir . '/' . basename($pano_url);
			$doc->save($pano_url);
			$path = pathinfo($pano_url);

			$newfilename = 'pano-site-' . $post_id . '.xml';
			$newfile = $path['dirname']."/".$newfilename;
			log_It($newfile);
			rename($pano_url, $newfile);    
			$update_xml = update_attached_file( $xml, $newfile );
			log_it($update_xml);
			
			// $update_xml = update_field('pano_xml', $new_xml, $post_id);
    	}
	}
}

// Apply to all fields.
add_action('save_post_site', 'my_acf_update_value_pre_save', 0, 2);

function my_acf_update_value_post_save( $post_id ) {
	$tiles = get_field('field_62db15cc35644', $post_id);
	if ( $tiles ) {

	}
}
add_action('acf/save_post', 'my_acf_update_value_post_save', 10);

add_filter( 'show_admin_bar' , '__return_false' ); 