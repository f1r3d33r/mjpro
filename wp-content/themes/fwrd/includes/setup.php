<?php				
/*
 * After Theme Setup
 */
function iron_theme_setup () {

	register_nav_menu('main-menu', 'Main Menu');

	if ( function_exists('add_theme_support') ) {
		add_theme_support('automatic-feed-links');
		add_theme_support('post-thumbnails');
		add_theme_support( 'html5', array( 'comment-form', 'comment-list' ) );
		add_theme_support( 'favicon' );
		add_theme_support('woocommerce');
		add_theme_support( 'title-tag' );
	}

	if ( function_exists('add_image_size') ) {
		add_image_size('iron-image-thumb', 300, 230, true);
	}

	

	// Fix bug with category pages not found after reseting option panel to default
	if ( ! empty($_GET['settings-updated']) ) {
		switch_theme( get_stylesheet() );
	}
	
	$hide_admin_bar = get_iron_option('hide_admin_bar');
	if($hide_admin_bar) {
		add_filter('show_admin_bar', '__return_false');
	}
	
	
	
	// Load theme textdomain
	load_theme_textdomain( 'fwrd', IRON_PARENT_DIR . '/languages' );

}

add_action('after_setup_theme', 'iron_theme_setup');


/*
 * Redirect to options after activation
 */
function iron_theme_activation() {

	flush_rewrite_rules();

	
	if ( ! empty($_GET['activated']) && $_GET['activated'] == 'true' )
	{
		
		update_option('medium_size_w', 559);
		update_option('medium_size_h', 559);
		
		wp_redirect( admin_url('admin.php?page=iron_options') );
		exit;
	}

}
add_action('after_switch_theme', 'iron_theme_activation');


function iron_body_class( $classes ) {

	$lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en';

	$classes[] = 'lang-'.$lang;
	
	if((bool)get_iron_option('enable_fixed_header')) {
		$classes[] = 'fixed_header';
	}
	
	return $classes;
}
add_filter( 'body_class', 'iron_body_class' );



function iron_get_revslider_settings() {

	global $wpdb;
	
	if(function_exists('is_plugin_active') && is_plugin_active('revslider/revslider.php')) {
	
		$styles = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'revslider_css', ARRAY_A);
		$animations = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'revslider_layer_animations', ARRAY_A);
		$sliders = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'revslider_sliders', ARRAY_A);
		$slides = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'revslider_slides', ARRAY_A);
		
		$data = array(
			'styles' => $styles,
			'animations' => $animations,
			'sliders' => $sliders,
			'slides' => $slides
		);
		
		die(json_encode($data));
	}	
}

function iron_get_essgrid_settings() {

	global $wpdb;
	
	if(function_exists('is_plugin_active') && is_plugin_active('essential-grid/essential-grid.php')) {
	
		$data = array();
		
		$data['eg_grids'] = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'eg_grids', ARRAY_A);
		$data['eg_item_elements'] = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'eg_item_elements', ARRAY_A);
		$data['eg_item_skins'] = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'eg_item_skins', ARRAY_A);
		$data['eg_navigation_skins'] = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'eg_navigation_skins', ARRAY_A);

		die(json_encode($data));
	}	
}

if(!empty($_GET["import"])) {
	
	if($_GET["import"] == 'revslider') {
		
		add_action('init', 'iron_get_revslider_settings');
		
	}else if($_GET["import"] == 'essgrid') {
		
		add_action('init', 'iron_get_essgrid_settings');
	}	
}


function iron_get_demos() {

	$themes = false;
	$themes_url = 'http://fwrd.irontemplates.com/import/themes.php';
	$themes = wp_remote_get($themes_url);
	$themes = unserialize($themes['body']);
	die(json_encode($themes));
}
add_action('wp_ajax_iron_get_demos', 'iron_get_demos');
add_action('wp_ajax_nopriv_iron_get_demos', 'iron_get_demos');




function iron_import_assign_templates() {

	$pages = get_pages();

	$data["error"] = false;
	$data["msg"] = "";

	$front_page = false;
	$blog_page = false;

	foreach($pages as $page) {

		$template = false;

		if($page->post_name == 'home') {

			$front_page = $page;
			
		}else if($page->post_name == 'news' || $page->post_name == 'blog') {

			$template = 'index.php';
			$blog_page = $page;

		}else if($page->post_name == 'events') {

			$template = 'archive-event.php';

		}else if($page->post_name == 'albums') {

			$template = 'archive-album.php';

		}else if($page->post_name == 'videos') {

			$template = 'archive-video.php';
		}

		if($template !== false){
			update_post_meta( $page->ID, '_wp_page_template', sanitize_text_field($template) );
			$data['msg'] .= 'Assigned Page: ('.$page->post_title.') To Template: ('.$template.')<br>';

		}

	}


	$data['msg'] .= '<p style="color: green;"><strong>Templates Assigned Successfully!</strong></p>';

	$data['msg'] .= '<hr><p><strong>Assigning Static Pages...</strong></p>';


	// Use a static front page
	$errors = 0;
	if($front_page !== false) {

		update_option( 'page_on_front', $front_page->ID );
		update_option( 'show_on_front', 'page' );
		$data['msg'] .= 'Assigned: ('.$front_page->post_title.') As Static Front Page<br>';

	}else{
		$errors++;
	}

	// Set the blog page
	if($blog_page !== false) {

		update_option( 'page_for_posts', $blog_page->ID );
		$data['msg'] .= 'Assigned: ('.$blog_page->post_title.') As Static Blog Page<br>';

	}else{
		$errors++;
	}

	if($errors == 0)
		$data['msg'] .= '<p style="color: green;"><strong>Static Pages Assigned Successfully!</strong></p>';
	else
		$data['msg'] .= '<p style="color: red;"><strong>Failed Assigning Static Pages!</strong></p>';

	die(json_encode($data));
}

add_action('wp_ajax_iron_import_assign_templates', 'iron_import_assign_templates');
add_action('wp_ajax_nopriv_iron_import_assign_templates', 'iron_import_assign_templates');



/**
 * Adjusts content_width value for video post formats and attachment templates.
 *
 * @return void
 */

function iron_content_width ()
{
	global $content_width;

	if ( is_page() )
		$content_width = 1064;
	elseif ( 'album' == get_post_type() )
		$content_width = 693;
	elseif ( 'event' == get_post_type() )
		$content_width = 700;
}

add_action('template_redirect', 'iron_content_width');



/*
 * Register Widgetized Areas
 */

function iron_widgets_init() {

	global $iron_widgets;
	
	if ( function_exists('get_iron_option') ) :

		$params = array(
			  'before_widget' => '<aside id="%1$s" class="widget atoll %2$s">'
			, 'after_widget'  => '</aside>'
			, 'before_title'  => '<div class="panel__heading"><h3 class="widget-title">'
			, 'after_title'   => '</h3></div>'
		);

		$widget_areas = get_iron_option( 'widget_areas', null, array() );

		

		if ( ! empty($widget_areas) && is_array($widget_areas) )
		{
			ksort( $widget_areas );
			
			foreach ( $widget_areas as $w_id => $w_area )
			{
				$args = array(
					  'id'            => $w_id
					, 'name'          => empty( $w_area['sidebar_name'] ) ? '' : $w_area['sidebar_name']
					, 'description'   => empty( $w_area['sidebar_desc'] ) ? '' : $w_area['sidebar_desc']
					, 'before_widget' => $params['before_widget']
					, 'after_widget'  => $params['after_widget']
					, 'before_title'  => $params['before_title']
					, 'after_title'   => $params['after_title']
				);

				register_sidebar( $args );
			}

		}

	endif;


	foreach($iron_widgets as $key => $widget) {

		register_widget($key);
	}
	
	unregister_widget('nmMailChimp');

}

add_action('widgets_init', 'iron_widgets_init');



/*
 * Swap Widget Semantics
 */

function iron_adjust_widget_areas ($params) {
	global $iron_widgets, $alternative_home;

	$params[0]['before_title'] = str_replace('%1$s', '', $params[0]['before_title']);

	if ( ( is_front_page() || is_page_template('page-home.php') || !empty($alternative_home) ) && did_action('get_footer') === 0 )
	{
		$params[0]['before_widget'] = str_replace('<aside', '<section', $params[0]['before_widget']);
		$params[0]['after_widget']  = str_replace('aside>', 'section>', $params[0]['after_widget']);
	} else {
		$params[0]['before_widget'] = str_replace(' atoll', '', $params[0]['before_widget']);
	}

	return $params;
}

add_filter('dynamic_sidebar_params', 'iron_adjust_widget_areas');



/*
 * Enqueue Theme Styles
 */

function iron_enqueue_styles() {

	if ( is_admin() || iron_is_login_page() ) return;

	global $wp_query, $post;

	// Styled by the theme
	wp_dequeue_style('contact-form-7');
	
	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_style('font-josefin', "$protocol://fonts.googleapis.com/css?family=Josefin+Sans:400,600,700", false, '', 'all' );
	wp_enqueue_style('font-opensans', "$protocol://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,600,600italic,700", false, '', 'all' );
	iron_enqueue_style('iron-fancybox', IRON_PARENT_URL.'/css/fancybox.css', false, '', 'all' );
	iron_enqueue_style('owl-carousel', IRON_PARENT_URL.'/css/owl.carousel.css', false, '', 'all' );
	iron_enqueue_style('owl-theme', IRON_PARENT_URL.'/css/owl.theme.css', false, '', 'all' );
	wp_enqueue_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', false, '', 'all' );
	
	if(get_iron_option('menu_type') == 'classic-menu')
		iron_enqueue_style('iron-classic-menu', IRON_PARENT_URL.'/classic-menu/css/classic.css', false, '', 'all' );
	
	iron_enqueue_style('iron-master', IRON_CHILD_URL.'/style.css', false, '', 'all' );

	if (!empty($_SERVER['HTTP_USER_AGENT']) && preg_match('/(?i)msie [6-8]/',$_SERVER['HTTP_USER_AGENT']) )
		iron_enqueue_style('iron-msie', IRON_PARENT_URL.'/css/ie.css', array('iron-master'), '', 'all');

	$custom_styles_url = home_url('/').'?load=custom-style.css';

	if(is_home() && get_option('page_for_posts') != '') {

		$custom_styles_url .= '&post_id='.get_option('page_for_posts');

	}else if(is_front_page() && get_option('page_on_front') != '') {
	
		$custom_styles_url .= '&post_id='.get_option('page_on_front');
		
	}else if(function_exists('is_shop') && is_shop() && get_option('woocommerce_shop_page_id') != '') {
	
		$custom_styles_url .= '&post_id='.get_option('woocommerce_shop_page_id');
	
	}elseif($wp_query && !empty($wp_query->queried_object) && !empty($wp_query->queried_object->ID)) {
	
		$custom_styles_url .= '&post_id='.$wp_query->queried_object->ID;
		
	}
	
	if (get_option( 'fwrd' )) {
		wp_enqueue_style('custom-styles', $custom_styles_url, array('iron-master'), '', 'all' );
	}else{
		wp_enqueue_style( 'default-style', IRON_PARENT_URL.'/css/default.css', array('iron-master') );
	}

}

add_action('wp_enqueue_scripts', 'iron_enqueue_styles');


/*
 * Enqueue Theme Admin Styles
 */

function iron_enqueue_admin_styles() {

	iron_enqueue_style('iron-vc', IRON_PARENT_URL.'/admin/assets/css/vc.css', false, '', 'all' );
	iron_enqueue_style('iron-acf', IRON_PARENT_URL.'/admin/assets/css/acf.css', false, '', 'all' );

}
add_action('admin_enqueue_scripts', 'iron_enqueue_admin_styles' );


/*
 * Enqueue Theme Scripts
 */

function iron_enqueue_scripts() {

	if ( is_admin() || iron_is_login_page() ) return;

	if ( is_singular() && comments_open() && get_option('thread_comments') )
		wp_enqueue_script('comment-reply');


	if(get_iron_option('menu_type') == 'classic-menu')
		iron_enqueue_script('iron-classic-menu', IRON_PARENT_URL.'/classic-menu/js/classic.js', false, '', true );


	// VENDORS
	iron_enqueue_script('iron-utilities', IRON_PARENT_URL.'/js/utilities.js', array('jquery'), null, true);
	iron_enqueue_script('iron-plugins', IRON_PARENT_URL.'/js/plugins.all.min.js', array('jquery'), null, true);
	iron_enqueue_script('iron-parallax', IRON_PARENT_URL.'/js/jquery.parallax.js', array('jquery'), null, true);
	
	
	if(defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE && ICL_LANGUAGE_CODE != 'en') {
		
		iron_enqueue_script('iron-countdown-l10n', IRON_PARENT_URL.'/js/countdown-l10n/jquery.countdown-'.ICL_LANGUAGE_CODE.'.js', array('jquery'), null, true);
	}
	
	iron_enqueue_script('iron-main', IRON_PARENT_URL.'/js/main.js', array('jquery', 'iron-plugins'), null, true);

	wp_localize_script('iron-main', 'iron_vars', array(
		'theme_url' => IRON_PARENT_URL,
		'ajaxurl' => admin_url('admin-ajax.php').(defined('ICL_LANGUAGE_CODE') ? '?lang='.ICL_LANGUAGE_CODE : ''),
		'enable_nice_scroll' => get_iron_option('enable_nice_scroll') == "0" ? false : true,
		'enable_fixed_header' => get_iron_option('enable_fixed_header') == "0" ? false : true,
		'header_top_menu_hide_on_scroll' => get_iron_option('header_top_menu_hide_on_scroll'),
		'lightbox_transition' => get_iron_option('lightbox-transition'),
		'menu_position' => !empty($_GET["mpos"]) ? $_GET["mpos"] : get_iron_option('menu_position'),
		'menu_transition' => !empty($_GET["mtype"]) ? $_GET["mtype"] : get_iron_option('menu_transition'),
		'lightbox_transition' => get_iron_option('lightbox-transition'),
		'lang' => (defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en'),
		'custom_js' => get_iron_option('custom_js'),
		'portfolio' => array(

			'slider_autoplay' => (bool)get_iron_option( 'portfolio_slider_autoplay'),
			'slider_stop_hover' => (bool)get_iron_option( 'portfolio_slider_stop_hover'),
			'slider_arrows' => (bool)get_iron_option( 'portfolio_slider_arrows'),
			'slider_slide_speed' => (bool)get_iron_option( 'portfolio_slider_slide_speed'),
			'slider_slide_speed' => (bool)get_iron_option( 'portfolio_slider_slide_speed'),
			
		),
		'plugins_url' => (defined('IRON_MUSIC_DIR_URL')? IRON_MUSIC_DIR_URL : ''),
		
	));

}
		

			
add_action('wp_enqueue_scripts', 'iron_enqueue_scripts');


/*
 * Enqueue Theme Admin Scripts
 */

function iron_enqueue_admin_scripts() {

	wp_enqueue_script( 'rome-datepicker', IRON_PARENT_URL . '/js/rome-datepicker/dist/rome.min.js', array('jquery'), '1.0.0', true );	
	wp_enqueue_style( 'rome-datepicker', IRON_PARENT_URL . '/js/rome-datepicker/dist/rome.min.css' );
	
	iron_enqueue_script('iron-admin-custom', IRON_PARENT_URL.'/admin/assets/js/custom.js', array('jquery'), null, true);
	iron_enqueue_script('iron-admin-vc', IRON_PARENT_URL.'/admin/assets/js/vc.js', array('jquery','rome-datepicker'), null, true);
	
	wp_localize_script('iron-admin-vc', 'iron_vars', array(
		'patterns_url' => IRON_PARENT_URL.'/admin/assets/img/vc/patterns/'
	));

}
add_action('admin_enqueue_scripts', 'iron_enqueue_admin_scripts' );

function iron_metadata_icons () {
	if ( function_exists( 'wp_site_icon' ) && has_site_icon() ) {
		wp_site_icon();
	}else{
		$output = array();

		if ( get_iron_option('meta_apple_mobile_web_app_title') ) :
			$output[] = '<meta name="apple-mobile-web-app-title" content="' . esc_attr( get_iron_option('meta_apple_mobile_web_app_title') ) . '">';
		endif;

		if ( get_iron_option('meta_favicon') ) :
			$output[] = '<link rel="shortcut icon" type="image/x-icon" href="' . esc_url( get_iron_option('meta_favicon') ) . '">';
		endif;

		if ( get_iron_option('meta_apple_touch_icon') ) :
			$output[] = '<link rel="apple-touch-icon-precomposed" href="' . esc_url( get_iron_option('meta_apple_touch_icon') ) . '">';
		endif;

		if ( get_iron_option('meta_apple_touch_icon_72x72') ) :
			$output[] = '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="' . esc_url( get_iron_option('meta_apple_touch_icon_72x72') ) . '">';
		endif;

		if ( get_iron_option('meta_apple_touch_icon_114x114') ) :
			$output[] = '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="' . esc_url( get_iron_option('meta_apple_touch_icon_114x114') ) . '">';
		endif;

		if ( get_iron_option('meta_apple_touch_icon_144x144') ) :
			$output[] = '<link rel="apple-touch-icon-precomposed" sizes="144x144" href="' . esc_url( get_iron_option('meta_apple_touch_icon_144x144') ) . '">';
		endif;

		if ( ! empty($output) )
			echo "\n\t" . implode("\n\t", $output);
	}
}

add_action('wp_head', 'iron_metadata_icons', 100);



 
function iron_upload_mimes ( $existing_mimes=array() ) {
 
    // add the file extension to the array
 
    $existing_mimes['ico'] = 'image/x-icon';
 
        // call the modified list of extensions
    return $existing_mimes;
 
}
add_filter('upload_mimes', 'iron_upload_mimes');


/**
 * Disable inline CSS injected by WordPress.
 *
 * Always apply your styles from an external file.
 */

add_filter('use_default_gallery_style', '__return_false');



/*
| -------------------------------------------------------------------
| Loading Dynamic Assets
| -------------------------------------------------------------------
| */

function iron_load_dynamic_assets() {

	if(is_admin() || iron_is_login_page()) return -1;

	if(!empty($_GET["load"])) {

		if($_GET["load"] == 'custom-style.css') {
			include_once(IRON_PARENT_DIR.'/css/custom-style.php');
			exit;
		}

	}
}
add_action( 'init', 'iron_load_dynamic_assets');


/*
| -------------------------------------------------------------------
| Enqueue Latest Script based on timestamp.
| This Avoids flushing browser cache
| -------------------------------------------------------------------
| */

function iron_enqueue_script($handle, $src, $deps = array(), $ver = false, $in_footer = false ) {

	$src_path = str_replace(get_template_directory_uri(), get_template_directory(), $src);
	$file_time = filemtime($src_path);
	$src = $src."?t=".$file_time;

	wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
}

/*
| -------------------------------------------------------------------
| Enqueue Latest Style based on timestamp.
| This Avoids flushing browser cache
| -------------------------------------------------------------------
| */

function iron_enqueue_style($handle, $src, $deps = array(), $ver = false, $media = "all") {

	$src_path = str_replace(get_template_directory_uri(), get_template_directory(), $src);
	$file_time = filemtime($src_path);
	$src = $src."?t=".$file_time;

	wp_enqueue_style($handle, $src, $deps, $ver, $media);
}

function cleanIronDb(&$output){
	global $wpdb;

	if( $wpdb->query("TRUNCATE TABLE $wpdb->posts") ){ $output[] = esc_html__('Posts removed', 'fwrd'); } 
	if( $wpdb->query("TRUNCATE TABLE $wpdb->postmeta") ){ $output[] = ', '. esc_html__('Postmeta removed', 'fwrd'); }
	if( $wpdb->query("TRUNCATE TABLE $wpdb->comments") ){ $output[] = ', '. esc_html__('Comments removed', 'fwrd'); }
	if( $wpdb->query("TRUNCATE TABLE $wpdb->commentmeta") ){ $output[] = ', '. esc_html__('Commentmeta removed', 'fwrd'); }
	if( $wpdb->query("TRUNCATE TABLE $wpdb->links") ){ $output[] = ', '. esc_html__('Links removed', 'fwrd'); }
	if( $wpdb->query("TRUNCATE TABLE $wpdb->terms") ){ $output[] = ', '. esc_html__('Terms removed', 'fwrd'); }
	if( $wpdb->query("TRUNCATE TABLE $wpdb->term_relationships") ){ $output[] = ', '. esc_html__('Term relationships removed', 'fwrd'); }
	if( $wpdb->query("TRUNCATE TABLE $wpdb->term_taxonomy") ){ $output[] = ', '. esc_html__('Term Taxonomy removed', 'fwrd'); }
	if( $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE ('%_transient_%')") ){ $output[] = ', '. esc_html__('Transients removed', 'fwrd'); }
	$wpdb->query("OPTIMIZE TABLE $wpdb->options");

	if( is_plugin_active('revslider/revslider.php') ) {
		$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."revslider_css");
    	$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."revslider_layer_animations");
    	$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."revslider_sliders");
    	$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."revslider_slides");
	}

	if( is_plugin_active('essential-grid/essential-grid.php') ) {
		$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."eg_grids");
    	$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."eg_item_elements");
    	$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."eg_item_skins");
    	$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."eg_navigation_skins");
    }

	$output[] = '<hr>';
}



function iron_custom_slug( $post_id ,$post, $update) {
	global $wpdb;
	
	$post_slug = get_post_field('post_name', $post_id);
	$ironReserveSlug = array(
		get_ironMusic_option( 'events_slug_name', '_iron_music_general_options' ),
		get_ironMusic_option( 'discography_slug_name', '_iron_music_general_options' ),
		get_ironMusic_option( 'artists_slug_name', '_iron_music_general_options' ),
		get_ironMusic_option( 'videos_slug_name', '_iron_music_general_options' )
		);

	if( in_array( $post_slug, $ironReserveSlug)){
		
		$where = array( 'ID' => $post_id );
		$suffix = 2;
		
		$post_slug = _truncate_post_slug( $post_slug, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
		$wpdb->update( $wpdb->posts, array( 'post_name' => $post_slug ), $where ) ;

	}
}

if (!defined('IRON_MUSIC')) {
	add_action( 'save_post', 'iron_custom_slug' ,10 ,3);
}



function iron_check_memory() {
	if (ini_get('memory_limit') >= 32)
		return;
	
	
		echo '<div class="notice notice-error">
			<div class="message">
                <h2>Requirements & Recommendations</h2>
                <p>Please contact your hosting provider to increase the "Memory limit" of you server.</p>
            </div>
            <table class="table table-hover ">
            <thead>
                <tr>
                    <th>Settings</th>
                    <th>Currents</th>
                    <th>Required</th>
                    <th>Recommanded</th>
                    <th>Documentation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Memory Limit</td>
                    <td>'. ini_get('memory_limit') .'</td>
                    <td>32M</td>
                    <td>64M</td>
                    <td><a target="_blank" href="http://php.net/manual/en/ini.core.php">PHP Documentation "php.ini file"</a></td>
                </tr>
            </tbody>
            </table>
	</div>'; 
}
add_action( 'admin_notices', 'iron_check_memory' );