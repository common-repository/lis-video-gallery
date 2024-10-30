<?php

function lis_lvg_basic_install(){

	$labels = array(
		'name'               => _x( 'Video', 'post type general name', 'lis_video_gallery' ),
		'singular_name'      => _x( 'Video', 'post type singular name', 'lis_video_gallery' ),
		'menu_name'          => _x( 'Videos', 'admin menu', 'lis_video_gallery' ),
		'name_admin_bar'     => _x( 'Video', 'add new on admin bar', 'lis_video_gallery' ),
		'add_new'            => _x( 'Add New', 'video', 'lis_video_gallery' ),
		'add_new_item'       => __( 'Add New Video', 'lis_video_gallery' ),
		'new_item'           => __( 'New Video', 'lis_video_gallery' ),
		'edit_item'          => __( 'Edit Video', 'lis_video_gallery' ),
		'view_item'          => __( 'View Video', 'lis_video_gallery' ),
		'all_items'          => __( 'All Videos', 'lis_video_gallery' ),
		'search_items'       => __( 'Search Videos', 'lis_video_gallery' ),
		'parent_item_colon'  => __( 'Parent Video:', 'lis_video_gallery' ),
		'not_found'          => __( 'No videos found.', 'lis_video_gallery' ),
		'not_found_in_trash' => __( 'No videos found in Trash.', 'lis_video_gallery' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'lis_video_gallery' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'video' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'author', 'thumbnail' ),
		'menu_position'		 => 5,
		'menu_icon'			 => 'dashicons-format-video'
	);


	register_post_type( 'video', $args );



	$labels = array(
		'name'              => _x( 'Video Gallery', 'taxonomy general name', 'lis_video_gallery' ),
		'singular_name'     => _x( 'Video Gallery', 'taxonomy singular name', 'lis_video_gallery' ),
		'search_items'      => __( 'Search Video Galleries', 'lis_video_gallery' ),
		'all_items'         => __( 'All Video Galleries', 'lis_video_gallery' ),
		'parent_item'       => __( 'Parent Video Gallery', 'lis_video_gallery' ),
		'parent_item_colon' => __( 'Parent Video Gallery:', 'lis_video_gallery' ),
		'edit_item'         => __( 'Edit Video Gallery', 'lis_video_gallery' ),
		'update_item'       => __( 'Update Video Gallery', 'lis_video_gallery' ),
		'add_new_item'      => __( 'Add New Gallery', 'lis_video_gallery' ),
		'new_item_name'     => __( 'New Gallery Name', 'lis_video_gallery' ),
		'menu_name'         => __( 'Gallery', 'lis_video_gallery' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'gallery' ),
	);

	register_taxonomy( 'video_gallery', 'video', $args );
}

function add_video_url_meta_box() {
    add_meta_box(
        'metabox_id',
        __( 'Video URL', 'lis_video_gallery' ),
        'video_url_metabox_callback',
        'video',
        'normal',
        'high'
    );
}


// New Columns For Video Post Type


function lis_lvg_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
        return $post_thumbnail_img[0];
    }
}


function lis_vlg_columns_head($defaults) {
    $defaults['featured_image'] = 'Featured Image';
    return $defaults;
}
 

function lis_lvg_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
        $post_featured_image = lis_lvg_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img src="' . $post_featured_image . '" />';
        }
        else {
            echo '<img src="' . plugin_dir_url( __FILE__ ) . '/img/no_image.jpg" />';
        }
    }
}


add_filter('manage_video_posts_columns', 'lis_lvg_columns_head_only_video', 10);
add_action('manage_video_posts_custom_column', 'lis_lvg_columns_content_only_video', 10, 2);


function lis_lvg_columns_head_only_video($defaults) {

    $defaults['lis_video_img'] = __('Video Image', 'lis_video_gallery');
    $defaults['lis_video_shortcode'] = __('Video Shortcode', 'lis_video_gallery');
    return $defaults;
}


function lis_lvg_columns_content_only_video($column_name, $post_ID) {

    if ($column_name == 'lis_video_img') {
        $post_featured_image = lis_lvg_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img src="' . $post_featured_image . '" class="video_post_thumb" />';
        }
        else {
            echo '<img src="' . plugin_dir_url( __FILE__ ) . '/img/no_image.png" class="video_post_thumb"/>';
        }
    }

    if ($column_name == 'lis_video_shortcode') {
    	$video_id = get_the_ID();
    	$shortcode = "[the_lis_lvg_single id='". $video_id ."']";
    	echo "<textarea disabled>" . $shortcode . "</textarea>";
    }

}


// Video Edit Page


function video_url_metabox_callback( $post, $metabox ) {

    wp_nonce_field( plugin_basename( __FILE__ ), 'video_url_noncename' );

	 
	$lis_video_url_val = get_post_meta( $post->ID, '_lis_video_url', true );
	$lis_video_thumb_val = get_post_meta( $post->ID, '_lis_video_thumb', true );

	// Markup for metabox display
	?>

	<table>
		<tr>
			<td>
				<?php if ( $lis_video_thumb_val ){ ?>
					<div style="display:block; width: 100px; height: 100px; background-size: cover; background-image:url(<?php echo esc_url_raw($lis_video_thumb_val); ?>)"></div>
				<?php } ?>
			</td>
			<td></td>
			<td>
				<input type="url" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=q1K9EH90CyA" value="<?php echo esc_url_raw( $lis_video_url_val ); ?>" size="60" />
			</td>
		</tr>

	</table>

	<?php
 
}


function video_url_save_meta_box( $post_id ) {
 	
	$lis_video_url_val = get_post_meta( $post_id, '_lis_video_url', true );
	$lis_video_thumb_val = get_post_meta( $post_id, '_lis_video_thumb', true );


    // Verify if nonce exist
    if ( !wp_verify_nonce( $_POST['video_url_noncename'], plugin_basename(__FILE__) )) {
    	return $post_id;
    }

    if ( 'video' == $_POST['post_type'] ) {
        if ( function_exists('current_user_can') && !current_user_can( 'edit_post', $post_id ))
          return $post_id;
      } 


    // If was autosaved
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
 
    // Sanitize the user input.
    $video_url_data = sanitize_text_field( $_POST['video_url'] );
 
    // Update the meta field.
    update_post_meta( $post_id, '_lis_video_url', $video_url_data );

    $lis_video_url_val = $video_url_data;
    
    // Getting and saving thumb
    if ( preg_match('/youtube.com/', $lis_video_url_val )){
		   	
		parse_str( parse_url( $lis_video_url_val, PHP_URL_QUERY ), $var_arr );
		$img_src = "https://img.youtube.com/vi/" . $var_arr['v'] . "/hqdefault.jpg"; 

    } elseif ( preg_match('/vimeo.com/', $lis_video_url_val )){
    	
	    	$arr = preg_split("/[\\/]+/", $lis_video_url_val);

	    	if ( strlen($arr[count( $arr )-1]) > 1 ){
	    		$vimeo_id = $arr[count( $arr )-1];
	    	} else {
	    		$vimeo_id = $arr[count( $arr )-2];
	    	}

			set_error_handler(
			    create_function(
			        '$severity, $message, $file, $line',
			        'throw new ErrorException($message, $severity, $severity, $file, $line);'
			    )
			);

			try {
			    $hash = unserialize(file_get_contents( "http://vimeo.com/api/v2/video/$vimeo_id.php" ));
			}
			catch (Exception $e) {
			    _e("Wrong URL", "lis_video_gallery");
			    exit;
			}

			restore_error_handler();
			$img_src = $hash[0]['thumbnail_large'];


    } else {

    	$img_src = null;

    }
 	
    // Update the meta field.
    update_post_meta( $post_id, '_lis_video_thumb', $img_src );

    
    // Generate feature image
    if (! $img_src || $_POST['_thumbnail_id'] != '-1'){
    	return $post_id;
    }

    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($img_src);
    $filename = basename($img_src);

    if(wp_mkdir_p($upload_dir['path']))
    	$file = $upload_dir['path'] . '/' . "post-" . $post_id . "-" . $filename;
    else
    	$file = $upload_dir['basedir'] . '/' . "post-" . $post_id . "-" . $filename;

    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename . $post_id),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    $res1 = wp_update_attachment_metadata( $attach_id, $attach_data );
    $res2 = set_post_thumbnail( $post_id, $attach_id );


}


// Adding div and script to head
function lis_lvg_add_head_script() {

	$lis_lvg_options['lis_lvg_box'] = get_option('lis_lvg_box');
	$lis_lvg_options['lis_lvg_source'] = get_option('lis_lvg_source');
	$load_script = '';


	// Load colorbox JS from plugin directory
	if ( $lis_lvg_options['lis_lvg_box'] == 'colorbox' && $lis_lvg_options['lis_lvg_source'] == 'plugin' ){

    	wp_enqueue_script( 'colorbox', plugin_dir_url( __FILE__ ) . '/js/jquery.colorbox-min.js', array(), LIS_VIDEO_GALLERY_VERSION, true );
    	wp_register_style( 'colorbox-style', plugin_dir_url( __FILE__ ) . 'inc/jquery.colorbox.css', array(), "3.1" );
   	    wp_enqueue_style( 'colorbox-style');

    } else if ( $lis_lvg_options['lis_lvg_box'] == 'fancybox' && $lis_lvg_options['lis_lvg_source'] == 'plugin' ){
    	
    	wp_enqueue_script( 'fancybox', plugin_dir_url( __FILE__ ) . '/js/jquery.fancybox.min.js', array(), LIS_VIDEO_GALLERY_VERSION, true );
   	    wp_register_style( 'fancybox-style', plugin_dir_url( __FILE__ ) . 'inc/jquery.fancybox.min.css', array(), "3.1" );
   	    wp_enqueue_style( 'fancybox-style');

	} else if ( $lis_lvg_options['lis_lvg_box'] == 'magnific' && $lis_lvg_options['lis_lvg_source'] == 'plugin' ){

    	wp_enqueue_script( 'magnific-popup', plugin_dir_url( __FILE__ ) . '/js/jquery.magnific-popup.min.js', array(), LIS_VIDEO_GALLERY_VERSION, true );
   	    wp_register_style( 'magnific-popup-style', plugin_dir_url( __FILE__ ) . 'inc/jquery.magnific-popup.css', array(), "1.1.0" );
   	    wp_enqueue_style( 'magnific-popup-style');

    }


    // Enqueue styles
    wp_register_style( 'lis_video_gallery_style.css', plugin_dir_url( __FILE__ ) . 'inc/lis_video_gallery_style.css', array(), LIS_VIDEO_GALLERY_VERSION );
	wp_enqueue_style( 'lis_video_gallery_style.css');

    wp_register_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'inc/bootstrap.min.css', array(), "4.0.0.6" );
	wp_enqueue_style( 'bootstrap');





    if ( ! is_admin() ) {
        
    	// Run needed type of box
    	if ( $lis_lvg_options['lis_lvg_box'] == 'colorbox' ){ ?>
			<script type="text/javascript">

				function lis_lvg_inline_link($){
					jQuery('a.inline').colorbox({
								  		iframe: true, 
								  		width: 640, 
								  		height: 390, 
									});
				};

			</script>

		<?php } else if ( $lis_lvg_options['lis_lvg_box'] == 'fancybox' ) { ?>

			<script type="text/javascript">

				function lis_lvg_inline_link(){

					jQuery("a.inline").fancybox({
				            /*openEffect : 'none',
				            closeEffect : 'none',
				            prevEffect : 'none',
				            nextEffect : 'none',

				            arrows : false,
				            helpers : {
				                media : {},
				                buttons : {}
				            }
				            */
				    });
				};

			</script>
		<?php } else if ( $lis_lvg_options['lis_lvg_box'] == 'magnific' ) { ?>

			<script type="text/javascript">

				function lis_lvg_inline_link(){

					jQuery('a.inline').magnificPopup({
						disableOn: 700,
						type: 'iframe',
						mainClass: 'mfp-fade',
						removalDelay: 160,
						preloader: false,

						fixedContentPos: false
					});
				};

			</script>

		<?php } ?>
		<script type="text/javascript">
			
				jQuery( document ).ready( function(){
					lis_lvg_inline_link();
				});

		</script>

        <?php
    }
}



function lis_admin_custom_style() {

	wp_register_style( 'lis_video_gallery_admin_style.css', plugin_dir_url( __FILE__ ) . 'inc/lis_video_gallery_admin_style.css', array(), LIS_VIDEO_GALLERY_VERSION );
	wp_enqueue_style( 'lis_video_gallery_admin_style.css');

}


add_image_size( '', 480, 360, true );
add_action( 'save_post', 'video_url_save_meta_box' );
add_action( 'add_meta_boxes', 'add_video_url_meta_box' );
add_action( 'wp_head', 'lis_lvg_add_head_script' );
add_action('admin_init', 'lis_admin_custom_style', 1);
