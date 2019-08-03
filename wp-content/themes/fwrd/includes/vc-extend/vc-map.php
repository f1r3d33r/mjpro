<?php

if(function_exists('vc_map')) {

	function iron_register_js_composer() {

		global $wpdb;
		
		require_once(IRON_PARENT_DIR."/admin/options/fields/fontawesome/field_fontawesome.php");
		$fontawesome = new Redux_Options_fontawesome(array('id'=>'fontawsome_vc_icons'), '', null);
		$font_icons = $fontawesome->icons;
	
	
	
		$css_animations = array(
			'None' 					=> '',
			'Left to Right Effect' 	=> 'wpb_animate_when_almost_visible wpb_left-to-right',
			'Right to Left Effect' 	=> 'wpb_animate_when_almost_visible wpb_right-to-left',
			'Top to Bottom Effect' 	=> 'wpb_animate_when_almost_visible wpb_top-to-bottom',
			'Bottom to Top Effect' 	=> 'wpb_animate_when_almost_visible wpb_bottom-to-top',
			'Appear From Center' 	=> 'wpb_animate_when_almost_visible wpb_appear'
		);
	
		$row_params = array(
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__('Stretch Row', 'js_composer'),
		      "param_name" => "iron_row_type",
		      "description" => esc_html__("Do you want the row spreads fullwidth or keep in boxed ?", "js_composer"),
		      "value" => array(
	                        
	            esc_html__("Boxed", 'js_composer') => 'in_container',
	            esc_html__("Fullwidth", 'js_composer') => 'full_width'
	          ) ,
		    ),
		    
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__('Remove Padding On Medium & Small Screens (1024px)', 'js_composer'),
		      "param_name" => "iron_remove_padding_medium",
		      "value" => array(
	                        
	            esc_html__("No", 'js_composer') => '',
	            esc_html__("Yes", 'js_composer') => 'tabletnopadding'
	          )      
		    ),
		    
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__('Remove Padding On Small Screens Only (700px)', 'js_composer'),
		      "param_name" => "iron_remove_padding_small",
		      "value" => array(
	                        
	            esc_html__("No", 'js_composer') => '',
	            esc_html__("Yes", 'js_composer') => 'mobilenopadding'
	          )      
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("ID Name for Navigation", "js_composer"),
		      "param_name" => "iron_id",
		      "description" => esc_html__('If this row wraps the content of one of your sections, set an ID. You can then use it for navigation.<br>Ex: if you enter "work" then you can add a custom link to the menu as follow: "#work". Once this link is clicked, the page will be scrolled to that specific section.', "js_composer")
		    ), 
		    array(
		      "type" => "colorpicker",
		      "heading" => esc_html__('Overlay Color', 'js_composer'),
		      "param_name" => "iron_overlay_color",
		      "description" => esc_html__("You can set a color over the background image. You can make it more or less opaque, by using the next setting. Default: white ", "js_composer")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__('Overlay Pattern', 'js_composer'),
		      "param_name" => "iron_overlay_pattern",
		      "description" => esc_html__("You can set an overlay pattern over the background image", "js_composer"),
		      "value" => array(
		      	esc_html__("None", 'js_composer') => '',
	            esc_html__("Brick", 'js_composer') => 'brick',
	            esc_html__("Dot", 'js_composer') => 'dot',
	            esc_html__("Zig Zag", 'js_composer') => 'zigzag',
				esc_html__("45 Degrees Dash", 'js_composer') => '45_degree_dash',
	            esc_html__("45 Degrees Grid", 'js_composer') => '45_degree_grid',
	            esc_html__("45 Degrees Line Small", 'js_composer') => '45_degree_line_small',
	            esc_html__("45 Degrees Line Medium", 'js_composer') => '45_degree_line_medium',
	            esc_html__("45 Degrees Line Large", 'js_composer') => '45_degree_line_large'
	          )  
		    ),   
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__('Activate Parallax', 'js_composer'),
		      "param_name" => "iron_parallax",
		      "description" => esc_html__("You will need to add a background image within the design tab.", "js_composer"),
		      "value" => array(
	                        
	            esc_html__("No", 'js_composer') => '',
	            esc_html__("Yes", 'js_composer') => 'parallax'
	          )      
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__('Activate Background Video', 'js_composer'),
		      "param_name" => "iron_bg_video",
		      "value" => array(
	                        
	            esc_html__("No", 'js_composer') => '',
	            esc_html__("Yes", 'js_composer') => 'bg_video'
	          )      
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__('Video Url (Self Hosted MP4)', 'js_composer'),
		      "param_name" => "iron_bg_video_mp4",
		      "value" => ''      
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__('Video Url (Self Hosted WebM)', 'js_composer'),
		      "param_name" => "iron_bg_video_webm",
		      "value" => ''      
		    ),
		    array(
		      "type" => "attach_image",
		      "heading" => esc_html__('Video Image Fallback', 'js_composer'),
		      "description" => esc_html__("This image will replace video if its not supported by device.", "js_composer"),
		      "param_name" => "iron_bg_video_poster",
		      "value" => ''      
		    ),
		);


		$inner_row_params = array(
			array(
		      "type" => "dropdown",
		      "heading" => esc_html__('Activate Background Video', 'js_composer'),
		      "param_name" => "iron_bg_video",
		      "value" => array(
	                        
	            esc_html__("No", 'js_composer') => '',
	            esc_html__("Yes", 'js_composer') => 'bg_video'
	          )      
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__('Video Url (Self Hosted MP4)', 'js_composer'),
		      "param_name" => "iron_bg_video_mp4",
		      "value" => ''      
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__('Video Url (Self Hosted WebM)', 'js_composer'),
		      "param_name" => "iron_bg_video_webm",
		      "value" => ''      
		    ),
		    array(
		      "type" => "attach_image",
		      "heading" => esc_html__('Video Image Fallback', 'js_composer'),
		      "description" => esc_html__("This image will replace video if its not supported by device.", "js_composer"),
		      "param_name" => "iron_bg_video_poster",
		      "value" => ''      
		    ),
		);


		
		foreach($row_params as $param) {
			vc_add_param('vc_row', $param);
		}
		foreach($inner_row_params as $param) {
			vc_add_param('vc_row_inner', $param);
		}
		
		
		vc_remove_param('vc_row', 'font_color');
		vc_remove_param('vc_row_inner', 'font_color');
		vc_remove_param('vc_row', 'full_width');
		vc_remove_param('vc_row', 'full_height');
		vc_remove_param('vc_row', 'columns_placement');		
		vc_remove_param('vc_row', 'parallax');
		vc_remove_param('vc_row', 'parallax_image');
		vc_remove_param('vc_row', 'el_id');
		vc_remove_param('vc_row', 'video_bg');
		vc_remove_param('vc_row', 'video_bg_url');
		vc_remove_param('vc_row', 'video_bg_parallax');
		vc_remove_param('vc_row', 'parallax_speed_video');
		vc_remove_param('vc_row', 'parallax_speed_bg');

		//vc_remove_param('vc_custom_heading', 'el_class');

		vc_add_params('vc_custom_heading', array(
			array(
	            'type' => 'dropdown',
	            'heading' => esc_html__( 'Fit background color to text', 'js_composer' ),
	            'param_name' => 'fit_bg_text',
				'value' => array(
					esc_html_x("No", 'VC', 'fwrd')=> 0,
					esc_html_x("Yes", 'VC', 'fwrd')=> 1,
				),
			),
			/*array(
	            'type' => 'dropdown',
	            'heading' => esc_html__( 'Fit background align', 'js_composer' ),
	            'param_name' => 'fit_bg_align',
				'value' => array(
					esc_html_x("Left", 'VC', 'fwrd')=> 'fit_bg_left',
					esc_html_x("Center", 'VC', 'fwrd')=> "fit_bg_center",
					esc_html_x("Right", 'VC', 'fwrd')=> "fit_bg_right",
				),
			),
			array(
				'type' => 'textfield',
	            'heading' => esc_html__( 'Extra class name', 'js_composer' ),
	            'param_name' => 'el_class',
	            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.<br><br>Insert the class <b><a href="#" onclick="jQuery(this).closest(\'.vc_shortcode-param\').find(\'.el_class\').val(jQuery(this).html()); return false">fittext</a></b> to make heading responsive and fit mobile screens correctly', 'js_composer' ),		
			),*/
			
        ));


		vc_add_params('vc_single_image', array(
			array(
	            'type' => 'dropdown',
	            'heading' => esc_html__( 'Force Full Width', 'js_composer' ),
	            'param_name' => 'img_fullwidth',
				'value' => array(
					esc_html_x("No", 'VC', 'fwrd')=> 0,
					esc_html_x("Yes", 'VC', 'fwrd')=> "img_fullwidth",
				),
			),
        ));
	
		vc_map( array(
		   "name" => esc_html_x("Audio Player", 'VC', 'fwrd'),
		   "base" => "iron_audioplayer",
		   "class" => "",
		   "icon" => "iron_vc_icon_audio_player",
		   "category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
		   "params" => array(
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Title", 'VC', 'fwrd'),
		         "param_name" => "title",
		         "value" => "",
		         "description" => '',
		      ),
		      array(
		         "type" => "post_multiselect",
		         "post_type" => "album",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Albums", 'VC', 'fwrd'),
		         "param_name" => "albums",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Auto Play", 'VC', 'fwrd'),
		         "param_name" => "autoplay",
		         "value" => array(
	                esc_html_x("No", 'VC', 'fwrd')=> 0,
	                esc_html_x("Yes", 'VC', 'fwrd')=> 1,
	              ),
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Show Playlist", 'VC', 'fwrd'),
		         "param_name" => "show_playlist",
		         "value" => array(
	                esc_html_x("No", 'VC', 'fwrd')=> 0,
	                esc_html_x("Yes", 'VC', 'fwrd')=> 1,
	              ),
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Title", 'VC', 'fwrd'),
		         "param_name" => "action_title",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "post_select",
		         "post_type" => "page",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Page", 'VC', 'fwrd'),
		         "param_name" => "action_obj_id",
		         "value" => '',
		         "description" => ''
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action External Link", 'VC', 'fwrd'),
		         "param_name" => "action_ext_link",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("CSS Animation", 'VC', 'fwrd'),
		         "param_name" => "css_animation",
		         "value" => $css_animations,
		         "description" => '',
		      ),
		      	      
		   )
	
		));
	
		vc_map( array(
		   "name" => esc_html_x("Discography", 'VC', 'fwrd'),
		   "base" => "iron_discography",
		   "class" => "",
		   "icon" => "iron_vc_icon_discography",
		   "category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
		   "params" => array(
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Title", 'VC', 'fwrd'),
		         "param_name" => "title",
		         "value" => "",
		         "description" => '',
		      ),
		      array(
		         "type" => "post_multiselect",
		         "post_type" => "album",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Albums", 'VC', 'fwrd'),
		         "param_name" => "albums",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "post_multiselect",
		         "post_type" => "artist",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Filter by Artists", 'VC', 'fwrd'),
		         "param_name" => "artists_filter",
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Title", 'VC', 'fwrd'),
		         "param_name" => "action_title",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "post_select",
		         "post_type" => "page",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Page", 'VC', 'fwrd'),
		         "param_name" => "action_obj_id",
		         "value" => '',
		         "description" => ''
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action External Link", 'VC', 'fwrd'),
		         "param_name" => "action_ext_link",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("CSS Animation", 'VC', 'fwrd'),
		         "param_name" => "css_animation",
		         "value" => $css_animations,
		         "description" => '',
		      ),
		      	      
		   )
	
		));
	
		vc_map( array(
		   "name" => esc_html_x("Twitter", 'VC', 'fwrd'),
		   "base" => "iron_twitter",
		   "class" => "",
		   "icon" => "iron_vc_icon_twitter",
		   "category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
		   "params" => array(
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Title", 'VC', 'fwrd'),
		         "param_name" => "title",
		         "value" => "",
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x('Screen Name (ex: @IronTemplates)', 'VC', 'fwrd'),
		         "param_name" => "screen_name",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Title", 'VC', 'fwrd'),
		         "param_name" => "action_title",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "post_select",
		         "post_type" => "page",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Page", 'VC', 'fwrd'),
		         "param_name" => "action_obj_id",
		         "value" => '',
		         "description" => ''
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action External Link", 'VC', 'fwrd'),
		         "param_name" => "action_ext_link",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("CSS Animation", 'VC', 'fwrd'),
		         "param_name" => "css_animation",
		         "value" => $css_animations,
		         "description" => '',
		      ),	      
		   )
	
		));
	
	
		vc_map( array(
		   "name" => esc_html_x("News", 'VC', 'fwrd'),
		   "base" => "iron_posts",
		   "class" => "",
		   "icon" => "iron_vc_icon_news",
		   "category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
		   "params" => array(
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Title", 'VC', 'fwrd'),
		         "param_name" => "title",
		         "value" => "",
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Number of posts to show", 'VC', 'fwrd'),
		         "param_name" => "number",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "taxonomy_multiselect",
		         "taxonomy" => "category",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Select one or multiple categories", 'VC', 'fwrd'),
		         "param_name" => "category",
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("View As", 'VC', 'fwrd'),
		         "param_name" => "view",
		         "value" => array(
	                esc_html_x("Grid", 'VC', 'fwrd')=> 'post_grid',
	                esc_html_x("List", 'VC', 'fwrd')=> 'post',
					esc_html_x("Simple", 'VC', 'fwrd')=> 'post_simple',
	              ),
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Show Excerpts", 'VC', 'fwrd'),
		         "param_name" => "enable_excerpts",
		         "value" => array(
	                esc_html_x("No", 'VC', 'fwrd')=> 0,
	                esc_html_x("Yes", 'VC', 'fwrd')=> 1,
	              ),
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Show Date", 'VC', 'fwrd'),
		         "param_name" => "show_date",
		         "value" => array(
	                esc_html_x("Yes", 'VC', 'fwrd')=> 1,
	                esc_html_x("No", 'VC', 'fwrd')=> 0,
	              ),
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Title", 'VC', 'fwrd'),
		         "param_name" => "action_title",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "post_select",
		         "post_type" => "page",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Page", 'VC', 'fwrd'),
		         "param_name" => "action_obj_id",
		         "value" => '',
		         "description" => ''
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action External Link", 'VC', 'fwrd'),
		         "param_name" => "action_ext_link",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("CSS Animation", 'VC', 'fwrd'),
		         "param_name" => "css_animation",
		         "value" => $css_animations,
		         "description" => '',
		      ),	      
		   )
	
		));
	
	
		vc_map( array(
		   "name" => esc_html_x("Videos", 'VC', 'fwrd'),
		   "base" => "iron_recentvideos",
		   "class" => "",
		   "icon" => "iron_vc_icon_videos",
		   "category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
		   "params" => array(
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Title", 'VC', 'fwrd'),
		         "param_name" => "title",
		         "value" => "",
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Number of videos to show (*apply only for categories):", 'VC', 'fwrd'),
		         "param_name" => "number",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "taxonomy_multiselect",
		         "taxonomy" => "video-category",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Select one or multiple categories", 'VC', 'fwrd'),
		         "param_name" => "category",
		         "description" => '',
		      ),
		      array(
		         "type" => "post_multiselect",
		         "post_type" => "video",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Select Videos", 'VC', 'fwrd'),
		         "param_name" => "include",
		         "description" => '',
		      ),
		      array(
		         "type" => "post_multiselect",
		         "post_type" => "artist",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Filter by Artists", 'VC', 'fwrd'),
		         "param_name" => "artists_filter",
		         "description" => '',
		      ),
		       array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("What happens when you click the video's thumbnails ?", 'VC', 'fwrd'),
		         "param_name" => "video_link_type",
		         "value" => array(
	                esc_html_x("Go to detailed video page", 'VC', 'fwrd') => 'single',
	                esc_html_x("Open video in a LightBox", 'VC', 'fwrd') => 'lightbox',
	                esc_html_x("Replace image by video", 'VC', 'fwrd') => 'inline',
	              ),
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("View As", 'VC', 'fwrd'),
		         "param_name" => "view",
		         "value" => array(
	                esc_html_x("Grid", 'VC', 'fwrd')=> 'video_grid',
	                esc_html_x("List", 'VC', 'fwrd')=> 'video_list',
	              ),
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Title", 'VC', 'fwrd'),
		         "param_name" => "action_title",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "post_select",
		         "post_type" => "page",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Page", 'VC', 'fwrd'),
		         "param_name" => "action_obj_id",
		         "value" => '',
		         "description" => ''
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action External Link", 'VC', 'fwrd'),
		         "param_name" => "action_ext_link",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("CSS Animation", 'VC', 'fwrd'),
		         "param_name" => "css_animation",
		         "value" => $css_animations,
		         "description" => '',
		      ),
		   )
	
		));
		

		vc_map( array(
		   "name" => esc_html_x("Events", 'VC', 'fwrd'),
		   "base" => "iron_events",
		   "class" => "",
		   "icon" => "iron_vc_icon_events",
		   "category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
		   "params" => array(
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Title", 'VC', 'fwrd'),
		         "param_name" => "title",
		         "value" => "",
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Number of events to show", 'VC', 'fwrd'),
		         "param_name" => "number",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Filter by", 'VC', 'fwrd'),
		         "param_name" => "filter",
				 "value" => array(
	                esc_html_x("Upcoming Events", 'VC', 'fwrd')=> 'upcoming',
					esc_html_x("Past Events", 'VC', 'fwrd') => 'past'
	              ),
		         "description" => '',
		      ),
		      array(
		         "type" => "post_multiselect",
		         "post_type" => "artist",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Filter by Artists", 'VC', 'fwrd'),
		         "param_name" => "artists_filter",
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Enable Artists Filter ", 'VC', 'fwrd'),
		         "param_name" => "enable_artists_filter",
				 "value" => array(
	                esc_html_x("No", 'VC', 'fwrd')=> '',
					esc_html_x("Yes", 'VC', 'fwrd') => 'yes'
	              ),
		         "description" => '',
		      ),
		       
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Title", 'VC', 'fwrd'),
		         "param_name" => "action_title",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "post_select",
		         "post_type" => "page",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action Page", 'VC', 'fwrd'),
		         "param_name" => "action_obj_id",
		         "value" => '',
		         "description" => ''
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Call To Action External Link", 'VC', 'fwrd'),
		         "param_name" => "action_ext_link",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "dropdown",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("CSS Animation", 'VC', 'fwrd'),
		         "param_name" => "css_animation",
		         "value" => $css_animations,
		         "description" => '',
		      ),
		   )
	
		));

		if (function_exists('is_plugin_active') && is_plugin_active('nmedia-mailchimp-widget/nm_mailchimp.php')) {
	
			$results = $wpdb->get_results('SELECT form_id, form_name FROM '.$wpdb->prefix.'nm_mc_forms ORDER BY form_name');
			$newsletters = array();
			foreach($results as $result) {
			
				$name = !empty($result->form_name) ? $result->form_name : $result->form_id;
				$id = $result->form_id;
				
				$newsletters[$name] = $id;
			}
			
			vc_map( array(
			   "name" => esc_html_x("Newsletter", 'VC', 'fwrd'),
			   "base" => "iron_newsletter",
			   "class" => "",
			   "icon" => "iron_vc_icon_newsletter",
			   "category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
			   "params" => array(
			      array(
			         "type" => "textfield",
			         "holder" => "div",
			         "class" => "",
			         "heading" => esc_html_x("Title", 'VC', 'fwrd'),
			         "param_name" => "title",
			         "value" => esc_html_x("", 'VC', 'fwrd'),
			         "description" => '',
			      ),
			      array(
			         "type" => "textarea",
			         "holder" => "div",
			         "class" => "",
			         "heading" => esc_html_x("Description", 'VC', 'fwrd'),
			         "param_name" => "description",
			         "value" => esc_html_x("", 'VC', 'fwrd'),
			         "description" => '',
			      ),
			      array(
			         "type" => "dropdown",
			         "holder" => "div",
			         "class" => "",
			         "heading" => esc_html_x("Newsletters", 'VC', 'fwrd'),
			         "param_name" => "fid",
					 "value" => $newsletters,
			         "description" => '',
			      ),
			      array(
			         "type" => "dropdown",
			         "holder" => "div",
			         "class" => "",
			         "heading" => esc_html_x("CSS Animation", 'VC', 'fwrd'),
			         "param_name" => "css_animation",
			         "value" => $css_animations,
			         "description" => '',
			      ),
			   )
		
			));
	
		}

		vc_map( array(
			"name" => esc_html_x("Promotion", 'VC', 'fwrd'),
			"base" => "iron_promotion",
			"class" => "",
			"icon" => "iron_vc_icon_promobox",
			"category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
			"params" => array(
				array(
					"param_name" => "image",
					"type" => "attach_image",
					"heading" => esc_html_x('Image', 'VC', 'fwrd'),
					"description" => ''
				),
				array(
					"param_name" => "title",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Title', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
				),
		        array(
		          "param_name" => "title_tag_name",
		          "type" => "dropdown",
		          "heading" => esc_html_x("Title Tag Name", 'VC', 'fwrd'),
		          "value" => array(
		              "h3" => "h3",
		              "h2" => "h2",
		              "h4" => "h4",
		              "h5" => "h5",
		              "h6" => "h6",
		              "h1" => "h1"
		          ),
				),
				array(
			      "param_name" => "title_color",
			      "type" => "colorpicker",
			      "heading" => esc_html_x('Title Color', 'VC', 'fwrd'),
			      "description" => '',
			      "value" => "",
			    ),	
				array(
					"param_name" => "subtitle",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Subtitle', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
				),
		        array(
		          "param_name" => "subtitle_tag_name",
		          "type" => "dropdown",
		          "heading" => esc_html_x("Subtitle Tag Name", 'VC', 'fwrd'),
		          "value" => array(
		              "h3" => "h3",
		              "h2" => "h2",
		              "h4" => "h4",
		              "h5" => "h5",
		              "h6" => "h6",
		              "h1" => "h1"
		          ),
				),
				array(
			      "param_name" => "subtitle_color",
			      "type" => "colorpicker",
			      "heading" => esc_html_x('Subtitle Color', 'VC', 'fwrd'),
			      "description" => '',
			      "value" => "",
			    ),	
				array(
			      "param_name" => "title_align",
			      "type" => "dropdown",
			      "heading" => esc_html_x('Title Align', 'VC', 'fwrd'),
			      "value" => array(
							esc_html_x('Left', 'VC', 'fwrd') => 'left',
							esc_html_x('Center', 'VC', 'fwrd') => 'center',
							esc_html_x('Right', 'VC', 'fwrd') => 'right',
						),
			    ),
				array(
					"param_name" => "line_height",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Line height', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
				),
			    array(
			      "param_name" => "overlay_color",
			      "type" => "colorpicker",
			      "heading" => esc_html_x('Overlay Color', 'VC', 'fwrd'),
			      "description" => '',
			      "value" => "rgb(0,0,0)",
			    ),
				array(
					"param_name" => "link_page",
					"type" => "post_select",
					"post_type" => "page",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x("Link Page", 'VC', 'fwrd'),
					"value" => '',
					"description" => ''
				),
				array(
					"param_name" => "link_product",
					"type" => "post_select",
					"post_type" => "product",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x("Link Product", 'VC', 'fwrd'),
					"value" => '',
					"description" => ''
				),
				array(
					"param_name" => "link_external",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x("Link External", 'VC', 'fwrd'),
					"value" => '',
					"description" => '',
				),
				array(
			      "param_name" => "target",
			      "type" => "dropdown",
			      "heading" => esc_html_x('Open link in', 'VC', 'fwrd'),
			      "value" => array(
							esc_html_x('Same window', 'VC', 'fwrd') => '',
							esc_html_x('New window', 'VC', 'fwrd') => '_blank',
						),
			    ),
				array(
			      "param_name" => "hover_animation",
			      "type" => "dropdown",
			      "heading" => esc_html_x('Hover Animation', 'VC', 'fwrd'),
			      "value" => array(
							esc_html_x('Slide', 'VC', 'fwrd') => 'slide',
							esc_html_x('Zoom', 'VC', 'fwrd') => 'zoom',
						),
			    ),
				array(
			         "type" => "dropdown",
			         "holder" => "div",
			         "class" => "",
			         "heading" => esc_html_x("CSS Animation", 'VC', 'fwrd'),
			         "param_name" => "css_animation",
			         "value" => $css_animations,
			         "description" => '',
			     ),
			)
		));



/*


		vc_map( array(
			"name" => esc_html_x("InfoBox", 'VC', 'fwrd'),
			"base" => "iron_infobox",
			"class" => "",
			"icon" => "iron_vc_icon_infobox",
			"category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
			"params" => array(

				array(
					"param_name" => "icon",
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Icon', 'VC', 'fwrd'),
					"value" => $font_icons,
					"description" => '',
				),
				array(
					"param_name" => "title",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Title', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
				),
				array(
		          "param_name" => "title_tag_name",
		          "type" => "dropdown",
		          "heading" => esc_html_x("Title Tag Name", 'VC', 'fwrd'),
		          "value" => array(
		              "h3" => "h3",
		              "h2" => "h2",
		              "h4" => "h4",
		              "h5" => "h5",
		              "h6" => "h6",
		              "h1" => "h1"
				   )
				), 
				array(
					 "param_name" => "description",
			         "type" => "textarea",
			         "holder" => "div",
			         "class" => "",
			         "heading" => esc_html_x("Description", 'VC', 'fwrd'),
			         "value" => "",
			         "description" => '',
			    ),
			    array(
			         "type" => "dropdown",
			         "holder" => "div",
			         "class" => "",
			         "heading" => esc_html_x("CSS Animation", 'VC', 'fwrd'),
			         "param_name" => "css_animation",
			         "value" => $css_animations,
			         "description" => '',
			    ),
 			)
		));
*/

		vc_map( array(
			"name" => esc_html_x("Image Divider", 'VC', 'fwrd'),
			"base" => "iron_image_divider",
			"class" => "",
			"icon" => "iron_vc_icon_iosslider",
			"category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
			"params" => array(
				array(
					"param_name" => "divider_image",
					"type" => "attach_image",
					"heading" => esc_html_x('Divider Image', 'VC', 'fwrd'),
					"description" => ''
				),
		    array(
		      "param_name" => "divider_color",
		      "type" => "colorpicker",
		      "heading" => esc_html_x('Divider Color', 'VC', 'fwrd'),
		      "description" => 'If no image chosen, the default css divider will be used',
		    ),
		    array(
		      "param_name" => "divider_align",
		      "type" => "dropdown",
		      "heading" => esc_html_x('Divider Align', 'VC', 'fwrd'),
		      "value" => array(
						esc_html_x('Left', 'VC', 'fwrd') => 'left',
						esc_html_x('Center', 'VC', 'fwrd') => 'center',
						esc_html_x('Right', 'VC', 'fwrd') => 'right',
					),
		    ),
				array(
					"param_name" => "divider_padding_top",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Divider Padding Top', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
				),
				array(
					"param_name" => "divider_padding_bottom",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Divider Padding Bottom', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
				),
			)
		));

		vc_map( array(
			'name' => esc_html__( 'Button', 'js_composer' ),
			'base' => 'iron_button',
			'icon' => 'iron_vc_icon_iosslider',
			'category' => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
			'params' => array(
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Text", 'VC', 'fwrd'),
		         "param_name" => "text",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
				  "param_name" => "text_align",
				  "type" => "dropdown",
				  "heading" => esc_html_x('Text Align', 'VC', 'fwrd'),
				  "value" => array(
						esc_html_x('Left', 'VC', 'fwrd') => 'left',
						esc_html_x('Center', 'VC', 'fwrd') => 'center',
						esc_html_x('Right', 'VC', 'fwrd') => 'right',
					),
			  ),
		      array(
		         "type" => "post_select",
		         "post_type" => "page",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Link Page", 'VC', 'fwrd'),
		         "param_name" => "link_page",
		         "value" => '',
		         "description" => ''
		      ),
		      array(
		         "type" => "post_select",
		         "post_type" => "product",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Link Product", 'VC', 'fwrd'),
		         "param_name" => "link_product",
		         "value" => '',
		         "description" => ''
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Link External", 'VC', 'fwrd'),
		         "param_name" => "link_external",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Border Width (px)", 'VC', 'fwrd'),
		         "param_name" => "border_width",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Border Radius (px)", 'VC', 'fwrd'),
		         "param_name" => "border_radius",
		         "value" => '',
		         "description" => '',
		      ),
			  array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Horizontal Padding (px)", 'VC', 'fwrd'),
		         "param_name" => "border_h_padding",
		         "value" => '',
		         "description" => '',
		      ),
			  array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => esc_html_x("Vertical Padding (px)", 'VC', 'fwrd'),
		         "param_name" => "border_v_padding",
		         "value" => '',
		         "description" => '',
		      ),
		      array(
			      "type" => "colorpicker",
				  "heading" => esc_html_x("Border Color", 'VC', 'fwrd'),
			      "param_name" => "border_color",
			      "description" => '',
			    ),
			    array(
			      "type" => "colorpicker",
				  "heading" => esc_html_x("Background Color", 'VC', 'fwrd'),
			      "param_name" => "background_color",
			      "description" => '',
			    ),
			    array(
			      "type" => "colorpicker",
				  "heading" => esc_html_x("Text Color", 'VC', 'fwrd'),
			      "param_name" => "text_color",
			      "description" => '',
			    ),
			    array(
			      "type" => "colorpicker",
				  "heading" => esc_html_x("Hover Border Color", 'VC', 'fwrd'),
			      "param_name" => "hover_border_color",
			      "description" => '',
			    ),
			    array(
			      "type" => "colorpicker",
				  "heading" => esc_html_x("Hover Background Color", 'VC', 'fwrd'),
			      "param_name" => "hover_bg_color",
			      "description" => '',
			    ),
			    array(
			      "type" => "colorpicker",
				  "heading" => esc_html_x("Hover Text Color", 'VC', 'fwrd'),
			      "param_name" => "hover_text_color",
			      "description" => '',
			    )
			),
		));

		vc_map( array(
			"name" => esc_html_x("Countdown", 'VC', 'fwrd'),
			"base" => "iron_countdown",
			"class" => "",
			"icon" => "iron_vc_icon_countdown",
			"category" => esc_html_x('IRON Widgets', 'VC', 'fwrd'),
			"params" => array(
				
				array(
					"param_name" => "end_time",
					"type" => "textfield",
					"holder" => "div",
					"class" => "datetimepicker",
					"heading" => esc_html_x('End Time', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('General', 'VC', 'fwrd'),    

				),
				array(
					"param_name" => "now_time_type",
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Now Time Type', 'VC', 'fwrd'),
					 "value" => array(
						esc_html_x('Use Local Time', 'VC', 'fwrd') => 'timezone',
						esc_html_x('Use server time', 'VC', 'fwrd') => 'server',
					),
					"description" => '',
					"group" => esc_html_x('General', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "show_months",
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Show Months', 'VC', 'fwrd'),
					"value" => array(
						esc_html_x("No", 'VC', 'fwrd')=> 0,
						esc_html_x("Yes", 'VC', 'fwrd')=> 1,
					),
					"description" => '',
					"group" => esc_html_x('General', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "show_days",
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Show Days', 'VC', 'fwrd'),
					"value" => array(
						esc_html_x("No", 'VC', 'fwrd')=> 0,
						esc_html_x("Yes", 'VC', 'fwrd')=> 1,
					),
					"description" => '',
					"group" => esc_html_x('General', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "show_hours",
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Show Hours', 'VC', 'fwrd'),
					"value" => array(
						esc_html_x("No", 'VC', 'fwrd')=> 0,
						esc_html_x("Yes", 'VC', 'fwrd')=> 1,
					),
					"description" => '',
					"group" => esc_html_x('General', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "show_minutes",
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Show Minutes', 'VC', 'fwrd'),
					"value" => array(
						esc_html_x("No", 'VC', 'fwrd')=> 0,
						esc_html_x("Yes", 'VC', 'fwrd')=> 1,
					),
					"description" => '',
					"group" => esc_html_x('General', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "show_seconds",
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Show Seconds', 'VC', 'fwrd'),
					"value" => array(
						esc_html_x("No", 'VC', 'fwrd')=> 0,
						esc_html_x("Yes", 'VC', 'fwrd')=> 1,
					),
					"description" => '',
					"group" => esc_html_x('General', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "show_labels",
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Show labels under countdown', 'VC', 'fwrd'),
					"value" => array(
						esc_html_x("No", 'VC', 'fwrd')=> 0,
						esc_html_x("Yes", 'VC', 'fwrd')=> 1,
					),
					"description" => '',
					"group" => esc_html_x('General', 'VC', 'fwrd'),    
				),
/*
				array(
					"param_name" => "character_separator",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Character separator', 'VC', 'fwrd'),
					"value" => "",
					"description" => 'eg.: : \ - / _',
					"group" => esc_html_x('General', 'VC', 'fwrd'),    
				),
*/			
				array(
					"param_name" => "numbers_font",
					"type" => "google_fonts",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Font Style', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Design', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "labels_font",
					"type" => "google_fonts",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Font Style', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Design', 'VC', 'fwrd'),    
				),
				array(
			      "type" => "colorpicker",
				  "heading" => esc_html_x("Font Color", 'VC', 'fwrd'),
			      "param_name" => "count_color",
			      "description" => '',
				  "value" => "#000",
				  "group" => esc_html_x('Design', 'VC', 'fwrd'),
			    ),
				array(
					"param_name" => "count_splitter",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Countdown Separator', 'VC', 'fwrd'),
					"value" => "",
					"description" => '(Examples: ":" "/" or "-")',
					"group" => esc_html_x('Design', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "labels_align",
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Align labels', 'VC', 'fwrd'),
					"value" => array(
						esc_html_x("Left", 'VC', 'fwrd')=> 'left',
						esc_html_x("Center", 'VC', 'fwrd')=> 'center',
						esc_html_x("Right", 'VC', 'fwrd')=> 'right',
					),
					"description" => '',
					"group" => esc_html_x('Design', 'VC', 'fwrd'),    
					
				),
				
				
				array(
					"param_name" => "numbers_font_size",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Font Size (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Desktop', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "numbers_line_height",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Line Height (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Desktop', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "numbers_letter_spacing",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Letter Spacing (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Desktop', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "labels_font_size",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Font Size (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Desktop', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "labels_line_height",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Line Height (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Desktop', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "labels_letter_spacing",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Letter Spacing (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Desktop', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "numbers_spacing",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Spacing between Numbers (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Desktop', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "numbers_margin",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Margin between Numbers and sub labels (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Desktop', 'VC', 'fwrd'),    
				),
				
				array(
					"param_name" => "tablet_numbers_font_size",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Font Size (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Tablet', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "tablet_numbers_line_height",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Line Height (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Tablet', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "tablet_numbers_letter_spacing",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Letter Spacing (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Tablet', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "tablet_labels_font_size",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Font Size (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Tablet', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "tablet_labels_line_height",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Line Height (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Tablet', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "tablet_labels_letter_spacing",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Letter Spacing (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Tablet', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "tablet_numbers_spacing",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Spacing between Numbers (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Tablet', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "tablet_numbers_margin",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Margin between Numbers and sub labels (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Tablet', 'VC', 'fwrd'),    
				),
				
				array(
					"param_name" => "mobile_numbers_font_size",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Font Size (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Mobile', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "mobile_numbers_line_height",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Line Height (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Mobile', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "mobile_numbers_letter_spacing",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Numbers Letter Spacing (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Mobile', 'VC', 'fwrd'),    
				),
				
				array(
					"param_name" => "mobile_labels_font_size",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Font Size (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Mobile', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "mobile_labels_line_height",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Line Height (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Mobile', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "mobile_labels_letter_spacing",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Labels Letter Spacing (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Mobile', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "mobile_numbers_spacing",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Spacing between Numbers (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Mobile', 'VC', 'fwrd'),    
				),
				array(
					"param_name" => "mobile_numbers_margin",
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html_x('Margin between Numbers and sub labels (px)', 'VC', 'fwrd'),
					"value" => "",
					"description" => '',
					"group" => esc_html_x('Mobile', 'VC', 'fwrd'),    
				),
				
								
			)
		));
	
	}
	add_action('init', 'iron_register_js_composer');
}