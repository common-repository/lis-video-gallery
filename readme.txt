=== Plugin Name ===
Contributors: bublick
Donate link: http://lis.im/
Tags: video, video gallery, youtube, vimeo, youtube gallery, vimeo gallery
Requires at least: 4.6
Tested up to: 4.7
Stable tag: 0.2.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Using this plugin is simple and easy way to create video gallery based on youtube or/and vimeo videos.

== Description ==

Using this plugin is a simple and easy way to create video gallery based on youtube or/and vimeo videos. All videos are opening in popup boxes. You can create few galleries and output videos from these galleries using shortcode.

First of all you should setup plugin on settings page. You can choose popup box and method of loading this box. This is general things.

Next on new type "Video" you should create new gallery.
After that you can start to add new videos. You should add video name and provide video url. Also you can set thumbnail of video, but if it won't be setted manually, it would be generated automatically from your video.

To output your gallery you should use shortcode. Pay attention to shortcode options on plugin's setting page.

If you have any questions, need support or want to donate, feel free to email me at k@lis.im

Plugin's demo is located at [this demo page](https://demo.lis.im/lis-video-gallery/). Enjoy it.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/lis-video-gallery` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Video Gallery screen to configure the plugin
4. Add new video gallery and videos in dashboard's menu.
5. Output your videos using shortcodes.


== Frequently Asked Questions ==

= Can I use this plugin for many pages? How much galleries I can create? =

Sure, you can use this plugin for many pages, because it uses shortcodes. You can create infinity amount of galleries. Using shortcode you can define any gallery for any page.


= What if I have no picture for video? =

It's OK. Thumbnail would be generated automatically.


= What is the sense to use Popup Box = None option?

There is no huge sense. No one popup box isn't set because we don't want to make any default popup box and make decision for our users.
Also you may want to use video gallery type, but without loading additional libraries in face of popup box.


= What is the sense to use Load From = Do not load option?

If you already using on chosed library for popups, you don't need to load it anymore. So, you should chose "Do not load" option.


== Screenshots ==

1. Plugin's settings
2. Where apear video type in dashboard
3. Video edit page
4. How video appear from shotcode.
5. Opened video
6. Video Gallery type table
7. Visual Composer shortcode

== Changelog ==

= 0.2 =
Fixed some issues related to basic style bugs.
Added shortcode and image columns to video gallery table.
Added visual composer shortcode. It has abbility to add external video and video from video gallery type.  

= 0.1 =
Initial release. Basic functionality. Support of youtube and vimeo. For this time plugin supports ColorBox, FancyBox and Magnific Popup for select.