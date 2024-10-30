<?php


function the_lis_lvg_post_func( $atts ){

	$shortcode_atts = shortcode_atts( array(
        'load_more' => 'on',
        'per_page' => '',
        'gallery' => '',
    ), $atts );

    $lis_lvg_options['lis_lvg_box'] = get_option('lis_lvg_box');

    $args = array(
    	'posts_per_page' => 12,
    	'post_type' => 'video',
    	);

    if ($shortcode_atts['per_page']){
        $args['posts_per_page'] = sanitize_text_field( $shortcode_atts['per_page'] );
    }

    if ($shortcode_atts['gallery']){
        $args['tax_query'] =  array(
                                array(
                                    'taxonomy' => 'video_gallery',
                                    'field'    => 'slug',
                                    'terms'    => sanitize_text_field( $shortcode_atts['gallery'] ),
                                    ),
                                );
    }

    ob_start();

    $query = new WP_Query( $args );
    ?>


    <div class='grid-gallery row'>
    
    <?php
    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

    	global $post;

		$lis_video_url_val = get_post_meta( $post->ID, '_lis_video_url', true );
		$lis_video_thumb_val = get_post_meta( $post->ID, '_lis_video_thumb', true );
    	$title = get_the_title();

   		$video_link = "";

        if ( $lis_lvg_options['lis_lvg_box'] == "magnific" ){

            $video_link = $lis_video_url_val;

        } else {

            if ( preg_match('/youtube.com/', $lis_video_url_val )){

                parse_str( parse_url( $lis_video_url_val, PHP_URL_QUERY ), $var_arr );
                $video_link = "http://youtube.com/embed/" . $var_arr['v']; 


            } else if ( preg_match('/vimeo.com/', $lis_video_url_val )){

                $vimeo_id = (int) substr(parse_url($lis_video_url_val, PHP_URL_PATH), 1);
                $video_link = "https://player.vimeo.com/video/" . $vimeo_id;

            }

		}

   	?>

    	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 grid-video" style="">
    		<a class="play inline fancybox.iframe" href="<?php echo $video_link; ?>">	
    		    <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
    				<path d="m 26.96,13.67 18.37,9.62 -18.37,9.55 -0.00,-19.17 z" fill="#FFF" stroke="#000" stroke-width="0.5"></path>
    				<path d="M 45.02,23.46 45.32,23.28 26.96,13.67 43.32,24.34 45.02,23.46 z" fill="#FFF"></path>
    			</svg>	
    			<?php the_post_thumbnail( 'lis-video-thumb' ); ?>
    		</a>
            <div class="grid-video-title">
    		  <?php echo $title; ?>
    	   </div>
        </div>
    	<?php

    endwhile; 


    // Load More Button

    if (  $query->max_num_pages > 1 && $shortcode_atts['load_more'] == 'on') { 

        lis_lvg_loadmore_scripts();
        ?>

		<script type="text/javascript">
			var ajaxurl = '<?php echo site_url() ?>/wp-admin/admin-ajax.php';
		</script>

		<div class="load_more_video" data-query='<?php echo serialize($query->query_vars); ?>' data-current-page='<?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>' data-max-pages='<?php echo $query->max_num_pages; ?>'><?php _e('Load More', 'lis_video_gallery'); ?></div>

	<?php } ?>


	<?php 
        endif; 
        wp_reset_postdata();
    ?>

</div>
<?php

    echo "<br><br>";
    return ob_get_clean();

}


function lis_lvg_loadmore_scripts() {

    $translations = array(
            'loading' => __('Loading', 'lis_video_gallery'),
            'load_more' => __('Load More', 'lis_video_gallery')
        );

    wp_register_script( 'lis_lvg_loadmore_video', plugin_dir_url( __FILE__ ) . 'js/lis_lvg_loadmore_video.js', array('jquery')  );
    wp_localize_script( 'lis_lvg_loadmore_video', 'translations', $translations );
    wp_enqueue_script( 'lis_lvg_loadmore_video' );

}


function lis_lvg_load_more_video(){
    global $post;

    $args = unserialize(stripslashes($_POST['query']));
    $args['paged'] = $_POST['page'] + 1;
    $args['post_status'] = 'publish';
    $additional_query = new WP_Query($args);

    if( $additional_query->have_posts() ) : while($additional_query->have_posts()) : $additional_query->the_post(); 

        $lis_video_url_val = get_post_meta( $post->ID, '_lis_video_url', true );
        $lis_video_thumb_val = get_post_meta( $post->ID, '_lis_video_thumb', true );
        $title = get_the_title();

        $video_link = "";
        $lis_lvg_options['lis_lvg_box'] = get_option('lis_lvg_box');
        $lis_lvg_options['lis_lvg_source'] = get_option('lis_lvg_source');

        if ( $lis_lvg_options['lis_lvg_box'] == "magnific" ){

            $video_link = $lis_video_url_val;

        } else {

            if ( preg_match('/youtube.com/', $lis_video_url_val )){

                parse_str( parse_url( $lis_video_url_val, PHP_URL_QUERY ), $var_arr );
                $video_link = "http://youtube.com/embed/" . $var_arr['v']; 


            } else if ( preg_match('/vimeo.com/', $lis_video_url_val )){

                $vimeo_id = (int) substr(parse_url($lis_video_url_val, PHP_URL_PATH), 1);
                $video_link = "https://player.vimeo.com/video/" . $vimeo_id;

            }

        }

    ?>

        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 grid-video" style="">
            <a class="play inline fancybox.iframe" href="<?php echo $video_link; ?>">   
                <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                    <path d="m 26.96,13.67 18.37,9.62 -18.37,9.55 -0.00,-19.17 z" fill="#FFF" stroke="#000" stroke-width="0.5"></path>
                    <path d="M 45.02,23.46 45.32,23.28 26.96,13.67 43.32,24.34 45.02,23.46 z" fill="#FFF"></path>
                </svg>  
                <?php the_post_thumbnail( 'lis-video-thumb' ); ?>
            </a>
            <div class="grid-video-title">
                <?php echo $title; ?>
            </div>
        </div>


            <?php
        endwhile;
    endif;
    wp_reset_postdata();
    die();
}


add_action('wp_ajax_loadmore', 'lis_lvg_load_more_video');
add_action('wp_ajax_nopriv_loadmore', 'lis_lvg_load_more_video');


add_shortcode('the_lis_lvg_post', 'the_lis_lvg_post_func');


////////////////////////////////////////////

function the_lis_lvg_single_func( $atts ){

    $shortcode_atts = shortcode_atts( array(
        'id' => '',
    ), $atts );

    $lis_lvg_options['lis_lvg_box'] = get_option('lis_lvg_box');

    $args = array(
        'posts_per_page' => 1,
        'post_type' => 'video',
    );

    if ($shortcode_atts['id']){
        $args['p'] = sanitize_text_field( $shortcode_atts['id'] );
    }

    ob_start();

    $query = new WP_Query( $args );

    ?>


    <div class='grid-gallery row'>
    
    <?php
    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

        global $post;

        $lis_video_url_val = get_post_meta( $post->ID, '_lis_video_url', true );
        $lis_video_thumb_val = get_post_meta( $post->ID, '_lis_video_thumb', true );
        $title = get_the_title();

        $video_link = "";

        if ( $lis_lvg_options['lis_lvg_box'] == "magnific" ){

            $video_link = $lis_video_url_val;

        } else {

            if ( preg_match('/youtube.com/', $lis_video_url_val )){

                parse_str( parse_url( $lis_video_url_val, PHP_URL_QUERY ), $var_arr );
                $video_link = "http://youtube.com/embed/" . $var_arr['v']; 


            } else if ( preg_match('/vimeo.com/', $lis_video_url_val )){

                $vimeo_id = (int) substr(parse_url($lis_video_url_val, PHP_URL_PATH), 1);
                $video_link = "https://player.vimeo.com/video/" . $vimeo_id;

            }

        }

    ?>

        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 grid-video" style="">
            <a class="play inline fancybox.iframe" href="<?php echo $video_link; ?>">   
                <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                    <path d="m 26.96,13.67 18.37,9.62 -18.37,9.55 -0.00,-19.17 z" fill="#FFF" stroke="#000" stroke-width="0.5"></path>
                    <path d="M 45.02,23.46 45.32,23.28 26.96,13.67 43.32,24.34 45.02,23.46 z" fill="#FFF"></path>
                </svg>  
                <?php the_post_thumbnail( 'lis-video-thumb' ); ?>
            </a>
            <div class="grid-video-title">
              <?php echo $title; ?>
           </div>
        </div>
        <?php

    endwhile; 

        endif; 
        wp_reset_postdata();
    ?>

</div>
<?php

    echo "<br><br>";
    return ob_get_clean();

}


add_shortcode('the_lis_lvg_single', 'the_lis_lvg_single_func');