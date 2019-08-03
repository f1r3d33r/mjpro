<?php

$el_class = $bg_image = $full_height = $equal_height = $flex_row = $columns_placement = $content_placement = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = $iron_id = $iron_row_type = $iron_parallax = '';
$output = $after_output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );


wp_enqueue_script( 'wpb_composer_front_js' );

$css_classes = array(
);

if(empty($iron_row_type))
	$iron_row_type = "in_container";
	
if(!empty($iron_parallax))
	$iron_parallax = " parallax";

if(!empty($iron_overlay_color)) {
	$iron_overlay_color = 'background-color: '.$iron_overlay_color.';';
}
if(!empty($iron_overlay_pattern)) {
	$iron_overlay_pattern = 'background-image: url('.IRON_PARENT_URL.'/admin/assets/img/vc/patterns/'.$iron_overlay_pattern.'.png)';
}

if (!empty($atts['gap'])) {
	$css_classes[] = 'vc_column-gap-'.$atts['gap'];
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = ' vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = ' vc_row-o-columns-' . $columns_placement;
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = ' vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = ' vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = ' vc_row-flex';
}

	
	
if(!empty($iron_bg_video)) {

	include_once(IRON_PARENT_DIR.'/includes/classes/mobiledetect.class.php');
	$detect = new Mobile_Detect();

	$iron_parallax = "";
	
	$iron_bg_video = " has-bg-video";

	$video_poster = "";
	if(!empty($iron_bg_video_poster)) {
		$data = wp_get_attachment_image_src($iron_bg_video_poster, 'large');
		if(!empty($data[0]))
			$video_poster = $data[0];
	}
	
	
	
	$override_bg_image = false;
	
	if (!$detect->isMobile() && !$detect->isTablet()) {
	
		$bg_video = '<div class="bg-video-wrap">';
			
			$bg_video .= '<video class="bg-video" poster="'.$video_poster.'" preload="auto" loop autoplay>';
		
			if(!empty($iron_bg_video_mp4)) {
				$bg_video .= '<source type="video/mp4" src="'.$iron_bg_video_mp4.'">';
			}
					
			if(!empty($iron_bg_video_webm)) {
				$bg_video .= '<source type="video/webm" src="'.$iron_bg_video_webm.'">';
			}
	
			$bg_video .= '</video>';
			
		$bg_video .= '</div>';
		
	}else if(!empty($video_poster)){
	
		$bg_video = '<div style="position:absolute;top:0;left:0;width:100%;height:100%;background-size:cover;background-repeat:no-repeat;background-image:url('.$video_poster.');"></div>';
		
	}	
	

}	
		
$el_class = $this->getExtraClass($el_class);

$custom = vc_settings()->get( 'row_css_class' );
if( empty($custom)){
	$custom = 'vc_row-fluid';
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,  implode( ' ', array_filter( $css_classes )).' '.'vc_row wpb_row '. ( $this->settings('base')==='vc_row_inner' ? 'vc_inner ' : '' ) . $custom . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts ).' '.$iron_row_type.$iron_parallax.$iron_bg_video;


if(!empty($iron_remove_padding_medium)) {
	
	$css_class .= ' tabletnopadding';
}

if(!empty($iron_remove_padding_small)) {
	
	$css_class .= ' mobilenopadding';
}

$output .= '<div '.(!empty($iron_id) ? 'id="'.$iron_id.'"' : '').' class="'.$css_class.'">';
		    
	if(!empty($bg_video)){
		$output .= $bg_video;
	}
	
    if( !empty($iron_overlay_color) || !empty($iron_overlay_pattern)){
        $output .= '<div class="background-overlay" style="'.$padding.' '.$iron_overlay_color.' '.$iron_overlay_pattern.';"></div>';
    }

	$output .= wpb_js_remove_wpautop($content);

$output .= '</div>'.$this->endBlockComment('row');

echo $output;