<?php
/**
 * @package Lis Video Gallery
 */
/*
Plugin Name: Lis Video Gallery
Plugin URI: https://lis.im/
Description: Plugin provides easy way to make video gallery using videos hosted on YouTube or Vimeo.
Version: 0.2.1
Author: Lis
Author URI: https://lis.im/
License: GPLv3
Text Domain: lis_video_gallery
*/

defined( 'ABSPATH' );
define( 'LIS_VIDEO_GALLERY_VERSION', '0.1' );
$plugin = plugin_basename( __FILE__ );


function lis_lvg_add_admin_pages(){
	add_options_page('Video Gallery', 'Video Gallery', 8, 'video_gallery', 'lis_lvg_options_page');
}


function lis_lvg_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=video_gallery">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}


function lis_lvg_options_page(){

	// Default values
	add_option('lis_lvg_box', 'none');
	add_option('lis_lvg_source', 'none');


	$lis_lvg_options['lis_lvg_box'] = get_option('lis_lvg_box');
	$lis_lvg_options['lis_lvg_source'] = get_option('lis_lvg_source');

	// Page's view
	?>
	<h2><?php _e('Video Gallery Plugin Settings Page', 'lis_video_gallery'); ?></h2>

	<?php

		if (isset($_POST['lis_lvg_base_options_submit'])) {
			
			// Verify is nonce exist
		    if ( !wp_verify_nonce( $_POST['lis_lvg_noncename'], plugin_basename(__FILE__) )) {
		    	print_r($_POST);
		    	die ( _e('Internal problems occured!', 'lis_video_gallery') );

		    }

		    // Check permissions
			if ( function_exists('current_user_can') && !current_user_can('manage_options') ) {

				die ( _e('Internal problems occured!', 'lis_video_gallery') );

			}			



			if ( isset( $_POST['box'] )){

			   $valid_values = array(
			   					   'none',
			                       'colorbox',
			                       'fancybox',
			                       'magnific'
			   );

			    $value = sanitize_text_field( $_POST['box'] );

			    if( in_array( $value, $valid_values ) ) {

			        update_option('lis_lvg_box', $value);

			    }

			}


			if ( isset( $_POST['source'] )){

			   $valid_values = array(
			   					   'none',
			                       'plugin',
			   );

			    $value = sanitize_text_field( $_POST['source'] );

			    if( in_array( $value, $valid_values ) ) {

			        update_option('lis_lvg_source', $value);

			    }

			}

			

			$lis_lvg_options['lis_lvg_box'] = get_option('lis_lvg_box');
			$lis_lvg_options['lis_lvg_source'] = get_option('lis_lvg_source');

		}

	?>

	<form name="lis_lvg_base_options" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=video_gallery&update=true">

		<table style="width: 50%; margin: auto;">

			<tr>
				<td></td>
				<td><h2><?php _e('Popup box:', 'lis_video_gallery'); ?></h2></td>
				<td colspan="2" style="text-align: right;"><i><?php _e('Choose any option to set popup box method', 'lis_video_gallery'); ?></i></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<select name="box">
						<option value="none" <?php selected( $lis_lvg_options['lis_lvg_box'], 'none' ); ?>><?php _e('None', 'lis_video_gallery'); ?></option>
						<option value="colorbox" <?php selected( $lis_lvg_options['lis_lvg_box'], 'colorbox' ); ?>>ColorBox</option>
						<option value="fancybox" <?php selected( $lis_lvg_options['lis_lvg_box'], 'fancybox' ); ?>>FancyBox</option>
						<option value="magnific" <?php selected( $lis_lvg_options['lis_lvg_box'], 'magnific' ); ?>>Magnific Popup</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><h2><?php _e('Load From:', 'lis_video_gallery'); ?></h2></td>
				<td colspan="2" style="text-align: right;"><i><?php _e('Choose any option to set loading source of box script', 'lis_video_gallery'); ?></i></td>
			</tr>
			<tr>
				<td></td>
				<td style="padding-bottom: 30px;">
					<select name="source">
						<option value="none" <?php selected( $lis_lvg_options['lis_lvg_source'], 'none' ); ?>><?php _e('Do not load', 'lis_video_gallery'); ?></option>	
						<option value="plugin" <?php selected( $lis_lvg_options['lis_lvg_source'], 'plugin' ); ?>><?php _e('Built in Plugin', 'lis_video_gallery'); ?></option>
					</select>
				</td>
			</tr>

			<tr style="height:  100px;">
				<td style="text-align: center;" colspan="3">
					<button style="min-width: 120px;" name="lis_lvg_base_options_submit"><?php _e('Save', 'lis_video_gallery'); ?></button>
					<?php wp_nonce_field( plugin_basename( __FILE__ ), 'lis_lvg_noncename' ); ?>
				</td>
			</tr>


			<tr style="height: 70px;">
				<td style="text-align: center;" colspan="3">
					<h2><?php _e('Usage', 'lis_video_gallery'); ?></h2>
					<p>
						<?php _e('Plugin already created new video type. It located below simple posts.', 'lis_video_gallery'); ?><br>
						<?php _e('Add titles and url to new video. No need in thumbnail. Plugin supports youtube and vimeo. Url for paste should be like:', 'lis_video_gallery'); ?><br>
						<strong>https://www.youtube.com/watch?v=KiS8rZBeIO0</strong><br>
						<strong>https://vimeo.com/125851592</strong><br><br>
						<?php _e('Plugin provides shortcode to output gallery in any page.', 'lis_video_gallery'); ?><br>
						[the_lis_lvg_post]<br>
						[the_lis_lvg_post load_more="off" gallery="gallery-1" per_page="3"]<br><br>
						<h3><?php _e('Options', 'lis_video_gallery'); ?></h3>
						<ul>
							<li><strong>load_more</strong></li>
							<ul>
								<li><em>on</em> - <?php _e('default value. Enable ajax button.', 'lis_video_gallery'); ?></li>
								<li><em>off</em> - <?php _e('disable ajax button.', 'lis_video_gallery'); ?></li>
							</ul>
							<li><strong>gallery</strong></li>
							<ul>
								<li><em>#gallery-slug#</em> - <?php _e('slug name of gallery.', 'lis_video_gallery'); ?></li>
							</ul>
							<li><strong>per_page</strong></li>
							<ul>
								<li><em>NN</em> - <?php _e('number of videos to display. Default is 12.', 'lis_video_gallery'); ?></li>
							</ul>
						</ul>
					</p>
				</td>
			</tr>

		</table>

	</form>

	<?php
}

		

function lis_lvg_load_languages(){
	load_plugin_textdomain( 'lis_video_gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}


// Import components

require_once( __DIR__ .'/lis_lvg_plugin_basic.php');
require_once( __DIR__ .'/lis_lvg_short.php');
require_once( __DIR__ .'/lis_lvg_vc_short.php');


add_filter( 'plugin_action_links_$plugin', 'lis_lvg_add_settings_link');

add_action( 'plugins_loaded', 'lis_lvg_load_languages' );
add_action('admin_menu', 'lis_lvg_add_admin_pages');
add_action( 'init', 'lis_lvg_basic_install' );