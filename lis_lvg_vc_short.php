<?php

if(class_exists('WPBakeryShortCodesContainer')){

    class WPBakeryShortCode_lis_lvg_shortcode extends WPBakeryShortCode  {
        protected function content( $attrs, $content = null ){
            //if( !$attrs['id'] ) return false;

            $lvg_source_media = __( "Media", "lis_video_gallery" );
            $lvg_source_ext = __( "External", "lis_video_gallery");

            if ($attrs['lis_lvg_source'] == $lvg_source_media){

                $lis_video_title = $attrs['video_post'];
                $lis_video_obj = get_page_by_title($lis_video_title, OBJECT, 'video');
                $lis_video_id = $lis_video_obj->ID;
                $lis_video_url_val = get_post_meta( $lis_video_id, '_lis_video_url', true );
                $lis_video_img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $lis_video_id ), 'lis-video-thumb' )[0];

            } else if ($attrs['lis_lvg_source'] == $lvg_source_ext ){

                $lis_video_title = $attrs['video_title'];
                $lis_video_id = 0;
                $lis_video_url_val = $attrs['video_url'];
                $lis_video_img_url = wp_get_attachment_url( $attrs['video_img'] );

            }
            
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

            ob_start(); ?>

            <div class="grid-video vc-grid-video <?php echo $attrs['video_class']; ?>" style="">
                <a class="play inline fancybox.iframe" href="<?php echo $video_link; ?>">   
                    <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                        <path d="m 26.96,13.67 18.37,9.62 -18.37,9.55 -0.00,-19.17 z" fill="#FFF" stroke="#000" stroke-width="0.5"></path>
                        <path d="M 45.02,23.46 45.32,23.28 26.96,13.67 43.32,24.34 45.02,23.46 z" fill="#FFF"></path>
                    </svg>  
                    <img src="<?php echo $lis_video_img_url ?>"/>
                </a>
                <div class="grid-video-title">
                    <?php echo $lis_video_title; ?>
                </div>
            </div>


    <?php
        return ob_get_clean();
        }
    }   


    function lis_lvg_posts() {

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'video',
        );

        $lvg_posts_query = new WP_Query( $args );
        $posts_list = array("");

        if ($lvg_posts_query->have_posts()) : while ($lvg_posts_query->have_posts()) : $lvg_posts_query->the_post();

            array_push($posts_list, get_the_title());

        endwhile; endif;    


        return $posts_list;

    }


    function add_lis_lvg_shortcode() {
       vc_map( array(
          "name" => "Lis Video Gallery",
          "base" => "lis_lvg_shortcode",
          "category" => __( "Content", "js_composer"),
          "icon" => plugin_dir_url( __FILE__ ) . "img/icon-128x128.png",
          "params" => array(

            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => __( "Source", "lis_video_gallery" ),
                "param_name" => "lis_lvg_source",
                "value" => array( 
                    __( "", "lis_video_gallery" ), 
                    __( "Media", "lis_video_gallery" ), 
                    __( "External", "lis_video_gallery") 
                ),
                "description" => __( "Source for Video Shortcode", "lis_video_gallery" ),
                'save_always' => true
            ),

            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => __( "Video", "lis_video_gallery" ),
                "param_name" => "video_post",
                "description" => __( "Video for output", "lis_video_gallery"),
                'value' => lis_lvg_posts(),
                "dependency" => array(
                    "element" => "lis_lvg_source",
                    "value" => __("Media", "lis_video_gallery")
                ),
                'save_always' => true
             ),

            

            array(
                "type" => "attach_image",
                "class" => "",
                "heading" => __( "Video image", "lis_video_gallery" ),
                "param_name" => "video_img",
                "description" => __( "Video image", "lis_video_gallery" ),
                "dependency" => array(
                    "element" => "lis_lvg_source",
                    "value" => __("External", "lis_video_gallery")
                )
             ),

            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => __( "Video Title", "lis_video_gallery" ),
                "param_name" => "video_title",
                "value" => __( "", "lis_video_gallery" ),
                "description" => __( "Video Title", "lis_video_gallery" ),
                "dependency" => array(
                    "element" => "lis_lvg_source",
                    "value" => __("External", "lis_video_gallery")
                )
            ),

            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Video URL", "lis_video_gallery" ),
                "param_name" => "video_url",
                "value" => __( "", "lis_video_gallery" ),
                "description" => __( "Video URL", "lis_video_gallery" ),
                "dependency" => array(
                    "element" => "lis_lvg_source",
                    "value" => __("External", "lis_video_gallery")
                )
            ),

            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __( "Custom CSS class", "lis_video_gallery" ),
                "param_name" => "video_class",
                "value" => __( "", "lis_video_gallery" ),
                "description" => __( "This is custom option. You can leave it empty. Also, you can assign few classes separating them with space.", "lis_video_gallery" ),
             )
        )
       ) );
    }
    add_action( 'vc_before_init', 'add_lis_lvg_shortcode' );

}