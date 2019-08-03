<?php
/*
 *
 * Set the text domain for the theme or plugin.
 *
 */

define('Redux_TEXT_DOMAIN', 'fwrd');

if ( ! defined('Redux_ASSETS_URL') ) {
	define('Redux_ASSETS_URL', IRON_PARENT_URL . '/admin/assets/');
}

if ( ! defined('Redux_OPTIONS_URL') ) {
	define('Redux_OPTIONS_URL', IRON_PARENT_URL . '/admin/options/');
}

if ( ! defined('Redux_OPTIONS_DIR') ) {
	define('Redux_OPTIONS_DIR', IRON_PARENT_DIR . '/admin/options/');
}

$redux_args = $redux_sections = $redux_tabs = array();


/*
 *
 * Require the framework class before doing anything else, so we can use the defined URLs and directories.
 * If you are running on Windows you may have URL problems which can be fixed by defining the framework url first.
 *
 */

if ( ! class_exists('Redux_Options') ) {
	require_once(Redux_OPTIONS_DIR . 'defaults.php');
}


/*
 * Load custom reduc assets
 *
 */

function redux_custom_assets() {

	wp_enqueue_script('redux-custom', Redux_ASSETS_URL.'js/redux.custom.js', array('jquery'), null, true);
}
add_action( 'admin_enqueue_scripts', 'redux_custom_assets' );



/*
 *
 * Most of your editing will be done in this section.
 *
 * Here you can override default values, uncomment args and change their values.
 * No $redux_args are required, but they can be over ridden if needed.
 *
 */

function iron_setup_framework_options() {
	global $redux_args, $redux_sections, $wp_version, $wpdb;

	$use_dashicons = floatval($wp_version) >= 3.8;

	// Setting dev mode to true allows you to view the class settings/info in the panel.
	// Default: true
	$redux_args['dev_mode'] = false;

	// Set the icon for the dev mode tab.
	// If $redux_args['icon_type'] = 'image', this should be the path to the icon.
	// If $redux_args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: info-sign
	//$redux_args['dev_mode_icon'] = 'info-sign';


	// The defaults are set so it will preserve the old behavior.
	$redux_args['std_show'] = true; // If true, it shows the std value


	// Set the class for the dev mode tab icon.
	// This is ignored unless $redux_args['icon_type'] = 'iconfont'
	// Default: null
	$redux_args['dev_mode_icon_class'] = 'fa-lg';

	// If you want to use Google Webfonts, you MUST define the api key.
	$redux_args['google_api_key'] = 'AIzaSyCQdHHTp_ttcRUygzBKIpwa6b8iiCJyjqo';

	// Define the starting tab for the option panel.
	// Default: '0';
	//$redux_args['last_tab'] = '0';

	// Define the option panel stylesheet. Options are 'standard', 'custom', and 'none'
	// If only minor tweaks are needed, set to 'custom' and override the necessary styles through the included custom.css stylesheet.
	// If replacing the stylesheet, set to 'none' and don't forget to enqueue another stylesheet!
	// Default: 'standard'
	$redux_args['admin_stylesheet'] = 'custom';


	// Add content after the form.
	$redux_args['footer_text'] = wp_kses(__('<p>Brought to you by <a target="_blank" href="http://irontemplates.com">IronTemplates</a></p>', 'fwrd'),iron_get_allowed_html());


	// Setup custom links in the footer for share icons
	$redux_args['share_icons']['twitter'] = array(
		'link' => 'http://twitter.com/irontemplates',
		'title' => esc_html__('Follow us on Twitter', 'fwrd'),
		'img' => Redux_OPTIONS_URL . 'img/social/Twitter.png'
	);

	// Enable the import/export feature.
	// Default: true
	//$redux_args['show_import_export'] = false;

	// Set the icon for the import/export tab.
	// If $redux_args['icon_type'] = 'image', this should be the path to the icon.
	// If $redux_args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: refresh
	//$redux_args['import_icon'] = 'refresh';

	// Set the class for the import/export tab icon.
	// This is ignored unless $redux_args['icon_type'] = 'iconfont'
	// Default: null
	$redux_args['import_icon_class'] = 'fa-lg';

	// Set a custom option name. Don't forget to replace spaces with underscores!
	$redux_args['opt_name'] = 'fwrd';

	// Set a custom menu icon.
	
	if($use_dashicons)
		$redux_args['menu_icon'] = 'dashicons-admin-generic';

	// Set a custom title for the options page.
	// Default: Options
	$redux_args['menu_title'] = esc_html__('FWRD', 'fwrd');

	// Set a custom page title for the options page.
	// Default: Options
	$redux_args['page_title'] = esc_html__('FWRD Options', 'fwrd');

	// Set a custom page slug for options page (wp-admin/themes.php?page=***).
	// Default: redux_options
	$redux_args['page_slug'] = 'iron_options';

	// Set a custom page capability.
	// Default: manage_options
	$redux_args['page_cap'] = 'manage_options';

	$currently_in_options = !empty($_GET["page"]) && ($_GET["page"] == $redux_args['page_slug']);
	    
	// Set the menu type. Set to "menu" for a top level menu, or "submenu" to add below an existing item.
	// Default: menu
	//$redux_args['page_type'] = 'submenu';

	// Set the parent menu.
	// Default: themes.php
	// A list of available parent menus is available at http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	//$redux_args['page_parent'] = 'options-general.php';

	// Set a custom page location. This allows you to place your menu where you want in the menu order.
	// Must be unique or it will override other items!
	// Default: null
	//$redux_args['page_position'] = null;

	// Set a custom page icon class (used to override the page icon next to heading)
	//$redux_args['page_icon'] = 'icon-themes';

	// Set the icon type. Set to "iconfont" for Font Awesome, or "image" for traditional.
	// Redux no longer ships with standard icons!
	// Default: iconfont
	//$redux_args['icon_type'] = 'image';
	//$redux_args['dev_mode_icon_type'] = 'image';
	//$redux_args['import_icon_type'] == 'image';

	// Disable the panel sections showing as submenu items.
	// Default: true
	//$redux_args['allow_sub_menu'] = false;

	// Set ANY custom page help tabs, displayed using the new help tab API. Tabs are shown in order of definition.
/*
	$redux_args['help_tabs'][] = array(
		'id' => 'redux-opts-1',
		'title' => esc_html__('Theme Information 1', 'fwrd'),
		'content' => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'fwrd')
	);
	$redux_args['help_tabs'][] = array(
		'id' => 'redux-opts-2',
		'title' => esc_html__('Theme Information 2', 'fwrd'),
		'content' => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'fwrd')
	);

	// Set the help sidebar for the options page.
	$redux_args['help_sidebar'] = esc_html__('<p>This is the sidebar content, HTML is allowed.</p>', 'fwrd');
*/


	if(file_exists(IRON_PARENT_DIR . '/admin/inc/docs.php')) {

		ob_start();
		include IRON_PARENT_DIR . '/admin/inc/docs.php';
		$docs = ob_get_contents();
		ob_end_clean();

		$redux_sections[] = array(
			// Redux uses the Font Awesome iconfont to supply its default icons.
			// If $redux_args['icon_type'] = 'iconfont', this should be the icon name minus 'icon-'.
			// If $redux_args['icon_type'] = 'image', this should be the path to the icon.
			// Icons can also be overridden on a section-by-section basis by defining 'icon_type' => 'image'
			'icon' => 'book',
			'icon_class' => 'fa-lg',
			'title' => esc_html__('Getting Started', 'fwrd'),
			'desc' => '',
			'fields' => array(
				array(
					'id' => 'font_awesome_info',
					'type' => 'raw_html',
					'html' => $docs
				)
			)
		);
	}

	if( file_exists( IRON_PARENT_DIR . '/admin/inc/import.php') ) {

		ob_start();
		include IRON_PARENT_DIR . '/admin/inc/import.php';
		$importData = ob_get_contents();
		ob_end_clean();

		$redux_sections[] = array(
			'icon' => 'cloud-download',
			'icon_class' => 'fa-lg',
			'title' => esc_html__('Import Default Data', 'fwrd'),
			'desc' => '<p class="description">' . esc_html__('Here you can clone our theme demo contents.', 'fwrd') . '</p>',
			'fields' => array(
				array(
					'id' => 'import_default_data',
					'type' => 'raw_html',
					'title' => esc_html__('Import Default Data', 'fwrd'),
					'sub_desc' => '<p class="description">' . wp_kses(__('This will flush all your current data and clone our theme demo contents.<br><font color="red">Please note that this could take up to 3 minutes.</font>', 'fwrd'),iron_get_allowed_html()) . '</p>
									<br><p><strong>'.esc_html__('Important','fwrd').'</strong></p><p>'.wp_kses(__('If the spinner is still spinning for more than 3 minutes and you can\'t import the data, please follow this <a target="_blank" href="http://docs.irontemplates.com/fwrd/import-our-live-demo/downloads-data/">link</a> for another solution.','fwrd'),iron_get_allowed_html())
					,
					'html' => $importData
				)
			)
		);

	}

	$redux_sections[] = array(
		'icon' => 'cogs',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('General Settings', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('These are some general settings that you can edit.', 'fwrd') . '</p>',
		'fields' => array(
			
			array(
				'id' => 'enable_nice_scroll',
				'type' => 'checkbox',
				'title' => esc_html__('Enable NiceScroll', 'fwrd'),
				'sub_desc' => '<p class="description">' . esc_html__('This will style the default scrollbar and will add a nice scrolling effect', 'fwrd') . '</p>',
				'switch' => true,
				'std' => '0'
			),
			array(
				'id' => 'hide_admin_bar',
				'type' => 'checkbox',
				'title' => esc_html__('Hide admin bar on the frontend', 'fwrd'),
				'switch' => true,
				'std' => '0'
			),
			
			array(			
				'id' => 'custom_js',
				'type' => 'textarea',
				'title' => esc_html__('Custom Javascript', 'fwrd'),
				'sub_desc' => wp_kses(__('This is for advanced users.<br>The code will be executed within jQuery(document).ready($);', 'fwrd'),iron_get_allowed_html()),
				'rows' => '20',
				'std' => '',
			)		
		)
	);

	$redux_sections[] = array(
		'icon' => 'eye',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Look and feel', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('These are some look & feel options that you can edit. These options will override the selected preset.', 'fwrd') . '</p>',
		'fields' => array(

			array(
				'id' => 'featured_color',
				'type' => 'color',
				'title' => esc_html__('Color 1', 'fwrd'),
				'class' => 'greybg',
				'std' => '#e80e50'
			),
			array(
				'id' => 'primary_color_light',
				'type' => 'color',
				'title' => esc_html__('Color 2', 'fwrd'),
				'class' => 'greybg',
				'std' => '#ffffff'
			),
			array(
				'id' => 'primary_color_dark',
				'type' => 'color',
				'title' => esc_html__('Color 3', 'fwrd'),
				'class' => 'greybg',
				'std' => '#f7f7f7'
			),
			array(
				'id' => 'secondary_color_light',
				'type' => 'color',
				'title' => esc_html__('Color 4', 'fwrd'),
				'class' => 'greybg',
				'std' => '#353535'
			),
			array(
				'id' => 'secondary_color_dark',
				'type' => 'color',
				'title' => esc_html__('Color 5', 'fwrd'),
				'class' => 'greybg',
				'std' => '#2e2e2e'
			),
			array(
				'id' => 'text_color_light',
				'type' => 'color',
				'title' => esc_html__('Text Color 1', 'fwrd'),
				'class' => 'greybg',
				'std' => '#ffffff'
			),
			array(
				'id' => 'text_color_dark',
				'type' => 'color',
				'title' => esc_html__('Text Color 2', 'fwrd'),
				'class' => 'greybg',
				'std' => '#353535'
			),

			array(
				'id' => 'content_background',
				'type' => 'background',
				'title' => esc_html__('Main Background', 'fwrd'),
				'sub_desc' => esc_html__('Content background options / Upload a custom background image', 'fwrd'),
				'class' => 'greybg',
				'hide' => array('attachment'),
				'std' => array(
					'color' => '#ffffff'
				)			
			),	
	
			array(
				'id' => 'body_background',
				'type' => 'background',
				'title' => esc_html__('Sub Background', 'fwrd'),
				'sub_desc' => esc_html__('This is the background that will shows when you use the pusher menu', 'fwrd'),
				'class' => 'greybg',
				'hide' => array('attachment'),
				'std' => array(
					'color' => '#ffffff'
				)
			),
			

			array(
				'id'    => 'page_title_divider_image',
				'type'  => 'upload',
				'title' => esc_html__('Page Title Divider Image', 'fwrd'),
				'sub_desc' => esc_html__('Upload a custom divider', 'fwrd'),
				'std' => '',
				'class' => 'greybg'
			),
			array(
				'id'    => 'page_title_divider_color',
				'type'  => 'color',
				'title' => esc_html__('Page Title Divider Color', 'fwrd'),
				'std' => '#000000',
				'class' => 'greybg'
			),	
			array(
				'id'    => 'page_title_divider_margin_top',
				'type'  => 'text',
				'title' => esc_html__('Page Title Divider Margin Top', 'fwrd'),
				'sub_desc' => esc_html__('In Pixels', 'fwrd'),
				'std' => '',
			),	
			array(
				'id'    => 'page_title_divider_margin_bottom',
				'type'  => 'text',
				'title' => esc_html__('Page Title Divider Margin Bottom', 'fwrd'),
				'sub_desc' => esc_html__('In Pixels', 'fwrd'),
				'std' => '',
			),
			

			array(
				'id'    => 'widget_title_divider_image',
				'type'  => 'upload',
				'title' => esc_html__('Widget Title Divider Image', 'fwrd'),
				'sub_desc' => esc_html__('Upload a custom divider', 'fwrd'),
				'std' => '',
				'class' => 'greybg'
			),
			array(
				'id'    => 'widget_title_divider_color',
				'type'  => 'color',
				'title' => esc_html__('Widget Title Divider Color', 'fwrd'),
				'std' => '#000000',
				'class' => 'greybg'
			),	
			array(
				'id'    => 'widget_title_divider_margin_top',
				'type'  => 'text',
				'title' => esc_html__('Widget Title Divider Margin Top', 'fwrd'),
				'sub_desc' => esc_html__('In Pixels', 'fwrd'),
				'std' => '',
			),	
			array(
				'id'    => 'widget_title_divider_margin_bottom',
				'type'  => 'text',
				'title' => esc_html__('Widget Title Divider Margin Bottom', 'fwrd'),
				'sub_desc' => esc_html__('In Pixels', 'fwrd'),
				'std' => '',
			),		
			/*array(
				'id' => 'custom_css',
				'type' => 'textarea',
				'title' => esc_html__('Custom CSS', 'fwrd'),
				'sub_desc' => esc_html__('This is for advanced users', 'fwrd'),
				'rows' => '20',
				'std' => ''
			),*/
	
						
		)
	);

	$redux_sections[] = array(
		'icon' => 'edit',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Typography', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('These are some typography options that you can edit. These options will override the selected preset.', 'fwrd') . '</p>',
		'fields' => array(

			array(
				'id' => 'body_typography',
				'type' => 'typography',
				'title' => esc_html__('Body Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => array()
			),
			array(
				'id' => 'call_to_action_typography',
				'type' => 'typography',
				'title' => esc_html__('Widget Call To Action Button Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'cta_spacing',
				'type' => 'text',
				'title' => esc_html__('Widget Call To Action Button Letter Spacing', 'fwrd'),
				'sub_desc' => esc_html__('Letter Spacing (px)', 'fwrd'),
				'std' => '1px'
			),
			array(
				'id' => 'button_widget_typography',
				'type' => 'typography',
				'title' => esc_html__('Button Widget Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'button_widget_spacing',
				'type' => 'text',
				'title' => esc_html__('Button Widget Letter Spacing', 'fwrd'),
				'sub_desc' => esc_html__('Letter Spacing (px)', 'fwrd'),
				'std' => '1px'
			),
			array(
				'id' => 'h1_typography',
				'type' => 'typography',
				'title' => esc_html__('Heading 1 Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => array(
					'font' => "Open+Sans:600",
					'font_readable' => "Open Sans",
				)
			),
			array(
				'id' => 'h1_spacing',
				'type' => 'text',
				'title' => esc_html__('Heading 1 Letter Spacing', 'fwrd'),
				'sub_desc' => esc_html__('Heading 1 Letter Spacing (px)', 'fwrd'),
				'std' => '1px'
			),
			array(
				'id' => 'h2_typography',
				'type' => 'typography',
				'title' => esc_html__('Heading 2 Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => array(
					'font_readable' => "Open Sans",
					'font' => "Open+Sans:300",
					'weight' => '300'
				)
			),
			array(
				'id' => 'h2_spacing',
				'type' => 'text',
				'title' => esc_html__('Heading 2 Letter Spacing', 'fwrd'),
				'sub_desc' => esc_html__('Heading 2 Letter Spacing (px)', 'fwrd'),
				'std' => '1px'
			),
			array(
				'id' => 'h3_typography',
				'type' => 'typography',
				'title' => esc_html__('Heading 3 Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => array(
					'font_readable' => "Open Sans",
					'font' => "Open+Sans:300",
					'weight' => '300'
				)
			),
			array(
				'id' => 'h3_spacing',
				'type' => 'text',
				'title' => esc_html__('Heading 3 Letter Spacing', 'fwrd'),
				'sub_desc' => esc_html__('Heading 3 Letter Spacing (px)', 'fwrd'),
				'std' => '1px'
			),
			array(
				'id' => 'h4_typography',
				'type' => 'typography',
				'title' => esc_html__('Heading 4 Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'h4_spacing',
				'type' => 'text',
				'title' => esc_html__('Heading 4 Letter Spacing', 'fwrd'),
				'sub_desc' => esc_html__('Heading 4 Letter Spacing (px)', 'fwrd'),
				'std' => '1px'
			),
			array(
				'id' => 'h5_typography',
				'type' => 'typography',
				'title' => esc_html__('Heading 5 Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'h5_spacing',
				'type' => 'text',
				'title' => esc_html__('Heading 5 Letter Spacing', 'fwrd'),
				'sub_desc' => esc_html__('Heading 5 Letter Spacing (px)', 'fwrd'),
				'std' => '1px'
			),
			array(
				'id' => 'h6_typography',
				'type' => 'typography',
				'title' => esc_html__('Heading 6 Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'h6_spacing',
				'type' => 'text',
				'title' => esc_html__('Heading 6 Letter Spacing', 'fwrd'),
				'sub_desc' => esc_html__('Heading 6 Letter Spacing (px)', 'fwrd'),
				'std' => '1px'
			),
		)
	);

	$redux_sections[] = array(
		'icon' => 'chevron-up',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Logos', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('These are some header options that you can edit.', 'fwrd') . '</p>',
		'fields' => array(
			array(
				'id' => 'header_logo',
				'type' => 'upload',
				'title' => esc_html__('Header Logo', 'fwrd'),
				'sub_desc' => esc_html__('Upload your logo', 'fwrd'),
				'std' => get_template_directory_uri().'/images/logo.png',
				'class' => 'greybg'
			),
			array(
				'id' => 'retina_header_logo',
				'type' => 'upload',
				'title' => esc_html__('Retina Header Logo', 'fwrd'),
				'sub_desc' => esc_html__('Upload your retina logo (2x). Should be 2X larger than your standard logo', 'fwrd'),
				'std' => get_iron_option('header_logo'),
				'class' => 'greybg'
			),
			/*array(
				'id' => 'classic_menu_logo_max_height',
				'class' => 'greybg',
				'type' => 'text',
				'title' => esc_html__('Logo Height (px)', 'fwrd'),
				'sub_desc' => esc_html__('If your logo is too big, you can specify its height. Work only if you use the Classic Menu', 'fwrd'),
				'std' => '30px'
			),		*/		
		)
	);

	
	


	$redux_sections[] = array(
		'icon' => 'bars',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Menu Options', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('Choose between a classic or push menu.', 'fwrd') . '</p>',
		'fields' => array(
			array(
				'id' => 'menu_type',
				'type' => 'select_hide_below',
				'title' => esc_html__('Menu Type', 'fwrd'),
				'options' => array(
					'push-menu' => esc_html__('Push Menu', 'fwrd'),
					'classic-menu' => esc_html__('Classic Menu', 'fwrd'),
				),
				'std' => 'push-menu'
			),
			array(
				'id' => 'menu_transition',
				'class' => 'push-menu',
				'type' => 'radio',
				'title' => esc_html__('Menu Effect on Push', 'fwrd'),
				'options' => array(
					'type1' => esc_html__('Push only', 'fwrd'),
					'type2' => esc_html__('Rotate and push', 'fwrd'),
					'type3' => esc_html__('3D Perspective', 'fwrd')
				),
				'std' => 'type1'
			),
			
			// PUSH MENU
			array(
				'id' => 'enable_fixed_header',
				'class' => 'push-menu',
				'type' => 'checkbox',
				'title' => esc_html__('Fix menu at the top of the screen', 'fwrd'),
				'sub_desc' => '<p class="description">' . esc_html__('This will make the menu fixed on page scroll', 'fwrd') . '</p>',
				'switch' => true,
				'std' => '1'
			),
			array(
				'id' => 'header_menu_toggle_enabled',
				'class' => 'push-menu',
				'type' => 'radio',
				'title' => esc_html__('Display Main Menu Icon', 'fwrd'),
				'options' => array(
					'1' => esc_html__('Show', 'fwrd'),
					'2' => esc_html__('Show on mobile only', 'fwrd'),
					'0' => esc_html__('Hide', 'fwrd'),
				),
				'std' => '1'
			),			
			array(
				'id' => 'menu_position',
				'type' => 'radio',
				'class' => 'push-menu',
				'title' => esc_html__('Menu Position', 'fwrd'),
				'options' => array(
					'lefttype' => esc_html__('Left', 'fwrd'),
					'righttype' => esc_html__('Right', 'fwrd')
				),
				'std' => 'righttype'
			),	
			array(
				'id' => 'menu_logo',
				'class' => 'push-menu',
				'type' => 'upload',
				'title' => esc_html__('Logo', 'fwrd'),
				'sub_desc' => esc_html__('Upload your menu logo', 'fwrd'),
				'std' => get_template_directory_uri().'/images/menu-logo.png',
			),
			array(
				'id' => 'retina_menu_logo',
				'class' => 'push-menu',
				'type' => 'upload',
				'title' => esc_html__('Retina Logo', 'fwrd'),
				'sub_desc' => esc_html__('Upload your retina logo (should be 2x larger than your standard logo)', 'fwrd'),
				'std' => get_iron_option('menu_logo'),
			),
			array(
				'id' => 'push_menu_logo_max_height',
				'class' => 'push-menu',
				'type' => 'text',
				'title' => esc_html__('Logo Height (px)', 'fwrd'),
				'sub_desc' => esc_html__('If your logo is too big, you can specify its height.', 'fwrd'),
				'std' => '150px'
			),						
			array(
				'id' => 'menu_background',
				'class' => 'push-menu',
				'type' => 'background',
				'title' => esc_html__('Background', 'fwrd'),
				'sub_desc' => esc_html__('Menu background options / Upload a custom background image', 'fwrd'),
				'hide' => array('size', 'attachment'),
				'std' => array(
					'color' => '#353535'
				)	
			),

			array(
				'id' => 'menu_open_icon_color',
				'class' => 'push-menu',
				'type' => 'color',
				'title' => esc_html__('Menu Open Icon Color', 'fwrd'),
				'std' => '#000000'
			),	
			array(
				'id' => 'menu_close_icon_color',
				'class' => 'push-menu',
				'type' => 'color',
				'title' => esc_html__('Menu Close Icon Color', 'fwrd'),
				'std' => '#ffffff'
			),	
			array(
				'id' => 'menu_typography',
				'class' => 'push-menu',
				'type' => 'typography',
				'title' => esc_html__('Menu Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => array()
			),	
			array(
				'id' => 'menu_margin',
				'class' => 'push-menu',
				'type' => 'text',
				'title' => esc_html__('Item Margin (px)', 'fwrd'),
				'sub_desc' => esc_html__('Set a menu item margin', 'fwrd'),
				'std' => '0'
			),
			
			// CLASSIC MENU

			// GENERAL
			array(
				'id' => 'classic_menu_general_settings',
				'class' => 'classic-menu',
				'type' => 'info',
				'desc' => '<br><h4 class="title">' . esc_html__('General Options', 'fwrd') . '</h4>'
			),
						
			array(
				'id' => 'classic_menu_width',
				'class' => 'classic-menu',
				'type' => 'radio',
				'title' => esc_html__('Menu Width', 'fwrd'),
				'options' => array(
					'fullwidth' => esc_html__('Full Width', 'fwrd'),
					'incontainer' => esc_html__('In Container', 'fwrd')
				),
				'std' => 'fullwidth'
			),
						
			array(
				'id' => 'classic_menu_align',
				'class' => 'classic-menu',
				'type' => 'radio',
				'title' => esc_html__('Items Alignment', 'fwrd'),
				'options' => array(
					'pull-left' => esc_html__('Left', 'fwrd'),
					'pull-right' => esc_html__('Right', 'fwrd'),
					'pull-center' => esc_html__('Center', 'fwrd')
				),
				'std' => 'pull-center'
			),

			array(
				'id' => 'classic_menu_position',
				'class' => 'classic-menu',
				'type' => 'select_hide_below',
				'title' => esc_html__('Menu Position', 'fwrd'),
				'options' => array(
					'absolute absolute_before' => esc_html__('Not Fixed', 'fwrd'),
					'fixed fixed_before' => esc_html__('Fixed', 'fwrd'),
				),
				'std' => 'absolute absolute_before'
			),
			
			array(
				'id' => 'classic_menu_effect',
				'class' => 'classic-menu',
				'type' => 'radio',
				'title' => esc_html__('Menu Effect On Scroll', 'fwrd'),
				'options' => array(
					'reset' => esc_html__('Default', 'fwrd'),
					'mini-active' => esc_html__('Mini', 'fwrd'),
					'mini-fullwidth-active' => esc_html__('Mini + Full Width', 'fwrd'),
				),
				'std' => 'reset'
			),
			array(
				'id' => 'classic_menu_header_logo_mini',
				'class' => 'classic-menu',
				'type' => 'upload',
				'title' => esc_html__('Header Logo On Mini', 'fwrd'),
				'sub_desc' => esc_html__('This will override the main logo by this logo when you will start scrolling your website. Menu Effect On Scroll above shall be set to Mini.', 'fwrd'),
				'std' => '',
			),
			
			// LOGO
			array(
				'id' => 'classic_menu_logo_settings',
				'class' => 'classic-menu',
				'type' => 'info',
				'desc' => '<br><h4 class="title">' . esc_html__('Logo Options', 'fwrd') . '</h4>'
			),

			array(
				'id' => 'classic_menu_logo_align',
				'class' => 'classic-menu',
				'type' => 'radio',
				'title' => esc_html__('Logo Alignment', 'fwrd'),
				'options' => array(
					'pull-left' => esc_html__('Left', 'fwrd'),
					'pull-right' => esc_html__('Right', 'fwrd'),
					'pull-center' => esc_html__('Center', 'fwrd'),
					'pull-top' => esc_html__('Center & Above items', 'fwrd')
				),
				'std' => 'pull-center'
			),
			array(
				'id' => 'classic_menu_logo_padding_left',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Logo Padding Left (px)', 'fwrd'),
				'std' => '0px'
			),
			array(
				'id' => 'classic_menu_logo_padding_right',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Logo Padding Right (px)', 'fwrd'),
				'std' => '0px'
			),
			array(
				'id' => 'classic_menu_logo_padding_top',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Logo Padding Top (px)', 'fwrd'),
				'std' => '0px'
			),
			array(
				'id' => 'classic_menu_logo_padding_bottom',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Logo Padding Bottom (px)', 'fwrd'),
				'std' => '0px'
			),
			// CONTAINER
			array(
				'id' => 'classic_menu_container_settings',
				'class' => 'classic-menu',
				'type' => 'info',
				'desc' => '<br><h4 class="title">' . esc_html__('Menu Container', 'fwrd') . '</h4>'
			),

			array(
				'id' => 'classic_menu_background',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Menu Background Color', 'fwrd'),
				'sub_desc' => esc_html__('If you would like to display the menu above or over your page content, go to your page > page settings > Show Classic Menu & Header Over Content.', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'classic_menu_background_mini',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Menu Background Color When "Menu Effect On Scroll > MINI is activated"', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'classic_menu_inner_background',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Menu Inner/boxed Background Color', 'fwrd'),
				'std' => ''
			),

			array(
				'id' => 'classic_menu_top_margin',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Top Margin (px)', 'fwrd'),
				'std' => '8px'
			),
			array(
				'id' => 'classic_menu_bottom_margin',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Bottom Margin (px)', 'fwrd'),
				'std' => '8px'
			),						
			array(
				'id' => 'classic_menu_hmargin',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Horizontal Margin (px)', 'fwrd'),
				'std' => '0px'
			),
			array(
				'id' => 'classic_menu_hpadding',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Horizontal Padding (px)', 'fwrd'),
				'std' => '0px'
			),
			array(
				'id' => 'classic_menu_vpadding',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Vertical Padding (px)', 'fwrd'),
				'std' => '8px'
			),

			// MOBILE MENU
			array(
				'id' => 'classic_menu_mobile_settings',
				'class' => 'classic-menu',
				'type' => 'info',
				'desc' => '<br><h4 class="title">' . esc_html__('Mobile Settings', 'fwrd') . '</h4>'
			),

			array(
				'id' => 'classic_menu_background_mobile',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Mobile Menu Background Color', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'classic_mobile_icon_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Mobile Menu Icon Color', 'fwrd'),
				'std' => '#ffffff'
			),
			array(
				'id' => 'classic_mobile_icon_hover_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Mobile Menu Icon Hover Color', 'fwrd'),
				'std' => '#ffffff'
			),

			// ITEMS		
			
			array(
				'id' => 'classic_menu_item_settings',
				'class' => 'classic-menu',
				'type' => 'info',
				'desc' => '<br><h4 class="title">' . esc_html__('Items', 'fwrd') . '</h4>'
			),
										
			array(
				'id' => 'classic_menu_item_hmargin',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Item Horizontal Margin (px)', 'fwrd'),
				'std' => '0px'
			),
			array(
				'id' => 'classic_menu_item_vmargin',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Item Vertical Margin (px)', 'fwrd'),
				'std' => '8px'
			),
			
			array(
				'id' => 'classic_menu_item_hpadding',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Item Horizontal Padding (px)', 'fwrd'),
				'std' => '6px'
			),
			array(
				'id' => 'classic_menu_item_vpadding',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Item Vertical Padding (px)', 'fwrd'),
				'std' => '15px'
			),	
			array(
				'id' => 'classic_menu_typography',
				'class' => 'classic-menu',
				'type' => 'typography',
				'title' => esc_html__('Main Item Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => array()
			),
			array(
				'id' => 'classic_menu_letter_spacing',
				'class' => 'classic-menu',
				'type' => 'text',
				'title' => esc_html__('Item Letter Spacing (px)', 'fwrd'),
				'std' => '1px'
			),			
			array(
				'id' => 'classic_menu_hover_bg_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Main Item Hover Background Color', 'fwrd'),
				'std' => '#000000'
			),
			array(
				'id' => 'classic_menu_hover_text_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Main Item Hover Text Color', 'fwrd'),
				'std' => '#ffffff'
			),
			array(
				'id' => 'classic_menu_active_bg_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Main Item Active Background Color', 'fwrd'),
				'std' => '#e80e50'
			),
			array(
				'id' => 'classic_menu_active_text_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Main Item Active Text Color', 'fwrd'),
				'std' => '#ffffff'
			),
			array(
				'id' => 'menu_item_hover_effect',
				'class' => 'classic-menu',
				'type' => 'select',
				'title' => esc_html__('Menu Item Hover Effect', 'fwrd'),
				'options' => array(
					'none' => esc_html__('None', 'fwrd'),
					'underline' => esc_html__('Underline', 'fwrd'),
					'line-through' => esc_html__('Strikethrough', 'fwrd')
				),
				'std' => 'righttype'
			),	
			
			
			// SUB ITEMS

			array(
				'id' => 'classic_menu_sub_item_settings',
				'class' => 'classic-menu',
				'type' => 'info',
				'desc' => '<br><h4 class="title">' . esc_html__('Sub Items', 'fwrd') . '</h4>'
			),
						
			array(
				'id' => 'classic_sub_menu_typography',
				'class' => 'classic-menu',
				'type' => 'typography',
				'title' => esc_html__('Sub Item Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => array(
					'color' => '#ffffff',
					'bgcolor' => 'rgba(0,0,0,0.7)'
				)
			),
			array(
				'id' => 'classic_sub_menu_hover_bg_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Sub Item Hover Background Color', 'fwrd'),
				'std' => '#000000'
			),
			array(
				'id' => 'classic_sub_menu_hover_text_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Sub Item Hover Text Color', 'fwrd'),
				'std' => '#ffffff'
			),
			array(
				'id' => 'classic_sub_menu_active_bg_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Sub Item Active Background Color', 'fwrd'),
				'std' => '#e80e50'
			),
			array(
				'id' => 'classic_sub_menu_active_text_color',
				'class' => 'classic-menu',
				'type' => 'color',
				'title' => esc_html__('Sub Item Active Text Color', 'fwrd'),
				'std' => '#ffffff'
			),
			
			
			// HOT LINKS
			
			array(
				'id' => 'classic_menu_hotlinks_settings',
				'class' => 'classic-menu push-menu',
				'type' => 'info',
				'desc' => '<br><h4 class="title">' . esc_html__('Hot Links', 'fwrd') . '</h4>'
			),
			
			array(
				'id' => 'header_top_menu_enabled',
				'class' => 'classic-menu push-menu',
				'type' => 'checkbox',
				'title' => esc_html__('Enable Hot Links', 'fwrd'),
				'switch' => true,
				'std' => '0'
			),	
			array(
				'id' => 'header_top_menu_hide_on_scroll',
				'class' => 'push-menu',
				'type' => 'checkbox',
				'title' => esc_html__('Hot Links Hide on scroll', 'fwrd'),
				'switch' => true,
				'std' => '1'
			),			
			array(
				'id'       => 'header_top_menu',
				'class' => 'classic-menu push-menu',
				'type'     => 'group',
				'title'    => esc_html_x('Hot Links Items', 'Theme Options', 'fwrd'),
				'std'      => array(
					0 => array(
						'menu_page_name' => '',
						'menu_page_is_menu' => '',
						'menu_page_url' => '',
						'pages_select' => '',
						'menu_page_external_url' => '',
						'menu_page_icon' => '',
						'order_no'     => 1
					),
				),
				'options' => array(
					'group' => array(
						'name'      => esc_html_x('Hot Links', 'Theme Options', 'fwrd'),
						'title_key' => 'menu_page_name'
					),
					'fields' => array(
						array(
							'id'    => 'menu_page_name',
							'type'  => 'text',
							'title' => esc_html_x('Hot Link Label', 'Theme Options', 'fwrd')
						),
						array(
							'id'    => 'menu_hide_page_title',
							'type'  => 'checkbox',
							'title' => esc_html_x('Hide Label & Keep Icon Only', 'Theme Options', 'fwrd'),
							'sub_desc'=> '',
						),
						array(
							'id'    => 'menu_page_is_menu',
							'type'  => 'checkbox',
							'title' => esc_html_x('Is menu toggle', 'Theme Options', 'fwrd'),
							'sub_desc'=> '',
						),
						array(
							'id'    => 'menu_page_url',
							'type'  => 'pages_select',
							'title' => esc_html_x('Page URL', 'Theme Options', 'fwrd'),
							'sub_desc'=> '',
							'args' => array()
						),
						array(
							'id'    => 'menu_page_external_url',
							'type'  => 'text',
							'title' => esc_html_x('Page URL (External)', 'Theme Options', 'fwrd'),
							'sub_desc'=> '',
						),	
						array(
							'id'    => 'menu_page_url_target',
							'type'  => 'select',
							'title' => esc_html_x('Page URL Target)', 'Theme Options', 'fwrd'),
							'options' => array(
								'_self' => 'Same Window',
								'_blank' => 'New Window'
							),
							'sub_desc'=> '',
						),						
						array(
							'id'    => 'menu_page_icon',
							'type'  => 'fontawesome',
							'title' => esc_html_x('Page URL Icon Class', 'Theme Options', 'fwrd')
						)
					)
				)
			),
			array(
				'id' => 'header_top_menu_background',
				'class' => 'push-menu',
				'type' => 'color',
				'title' => esc_html__('Hot Links Background', 'fwrd'),
				'std' => '#ffffff'
			),
			array(
				'id' => 'header_top_menu_typography',
				'class' => 'push-menu',
				'type' => 'typography',
				'title' => esc_html__('Hot Links Typography', 'fwrd'),
				'sub_desc' => esc_html__('Choose a font, font size and color', 'fwrd'),
				'std' => ''
			),


																	
		)
	);
	
	$redux_sections[] = array(
		'icon' => 'chevron-down',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Footer Options', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('These are some footer options that you can edit.', 'fwrd') . '</p>',
		'fields' => array(
			array(
				'id' => 1,
				'type' => 'info',
				'desc' => '<h4 class="title">' . esc_html__('Site Footer', 'fwrd') . '</h4>'
			),
			array(
				'id'    => 'footer-area_id',
				'type'  => 'widget_area_select',
				'title' => esc_html__('Widget Area', 'fwrd'),
				'std'   => 'fwrd_sidebar_2'
			),
			array(
				'id' => 'footer_social_media_enabled',
				'type' => 'checkbox',
				'title' => esc_html__('Show Social Media Bar in the Footer', 'fwrd'),
				'switch' => true,
				'std' => '1'
			),
			array(
				'id'       => 'social_icons',
				'type'     => 'group',
				'title'    => esc_html_x('Social Media Icons', 'Theme Options', 'fwrd'),
				'sub_desc' => esc_html_x('The icons will show in the footer bar. Make sure to enabled the option above (Show Social Media Bar in the Footer)', 'Theme Options', 'fwrd'),
				'std'      => array(
					0 => array(
						'social_media_name' => esc_html__('Facebook', 'fwrd'),
						'social_media_url' => 'https://facebook.com/envato',
						'social_media_icon_class' => 'facebook',
						'social_media_icon_url' => '',
						'order_no'     => 1
					),
					1 => array(
						'social_media_name' => esc_html__('Twitter', 'fwrd'),
						'social_media_url' => 'https://twitter.com/envato',
						'social_media_icon_class' => 'twitter',
						'social_media_icon_url' => '',
						'order_no'     => 1
					),
				),
				'options' => array(
					'group' => array(
						'name'      => esc_html_x('Social Media', 'Theme Options', 'fwrd'),
						'title_key' => 'social_media_name'
					),
					'fields' => array(
						array(
							'id'    => 'social_media_name',
							'type'  => 'text',
							'title' => esc_html_x('Social Media Name', 'Theme Options', 'fwrd')
						),
						array(
							'id'    => 'social_media_url',
							'type'  => 'text',
							'title' => esc_html_x('Social Media URL', 'Theme Options', 'fwrd'),
							'sub_desc'=> 'Ex: http://www.facebook.com/IronTemplates<br>',
						),
						array(
							'id'    => 'social_media_icon_class',
							'type'  => 'fontawesome',
							'title' => esc_html_x('Social Media Icon Class', 'Theme Options', 'fwrd')
						),
						array(
							'id'    => 'social_media_icon_url',
							'type'  => 'upload',
							'title' => esc_html_x('Social Media Icon Image', 'Theme Options', 'fwrd')
						)
					)
				)
			),
			array(
				'id' => 'footer_back_to_top_enabled',
				'type' => 'checkbox',
				'title' => esc_html__('Back To Top Button', 'fwrd'),
				'switch' => true,
				'std' => '1'
			),
			array(
				'id' => 'footer_bg_color',
				'type' => 'color',
				'title' => esc_html__('Background Color', 'fwrd'),
				'std' => '#000000'
			),
			array(
				'id' => 'footer_padding',
				'type' => 'text',
				'title' => esc_html__('Footer Padding (px)', 'fwrd'),
				'sub_desc' => esc_html__('Enter unit with px. (eg: 20px)', 'fwrd'),
				'std' => '0px'
			),
			array(
				'id' => 3,
				'type' => 'info',
				'desc' => '<h4 class="title">' . esc_html__('Copyright & Notices', 'fwrd') . '</h4>'
			),
			array(
				'id' => 'footer_bottom_logo',
				'type' => 'upload',
				'title' => esc_html__('Bottom Logo', 'fwrd'),
				'sub_desc' => esc_html__('Upload a mini logo that will appear on the bottom. Could be a partner or a record label, for example.', 'fwrd'),
				'desc' => esc_html__('Maximum Size : 200 x 100 px', 'fwrd'),
				'std' => get_template_directory_uri().'/images/logo-irontemplates-footer.png',
				'class' => 'greybg'
			),
			array(
				'id' => 'footer_bottom_link',
				'type' => 'text',
				'title' => esc_html__('Bottom Logo URL', 'fwrd'),
				'sub_desc' => esc_html__('Add a URL to the mini logo that will appear on the bottom. The link opens in a new window.', 'fwrd'),
				'std' => 'http://irontemplates.com/'
			),
			array(
				'id' => 'footer_copyright',
				'type' => 'editor',
				'title' => esc_html__('Footer Copyright Text', 'fwrd'),
				'std' => 'Copyright &copy; '.date('Y').' Iron Templates<br>All rights reserved'
			),
			array(
				'id' => 'footer_credits',
				'type' => 'editor',
				'title' => esc_html__('Footer Credits Text', 'fwrd'),
				'std' => 'Template crafted by <b>IronTemplate</b>'
			),
		)
	);

$redux_sections[] = array(
		'icon' => 'file-o',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Posts Settings', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('These are some post options that you can edit.', 'fwrd') . '</p>',
		'fields'     => array(
		
			array(
				'id' => 'post_archive_default_template',
				'type' => 'radio',
				'title' => esc_html__('Post Archives/Categories Template', 'fwrd'),
				'options' => array(
					'archive-posts-list' => esc_html__('1-column (list)', 'fwrd'),
					'archive-posts-grid' => esc_html__('2-columns (grid)', 'fwrd')
				),
				'std' => 'index'
			),	
			array(
				'id' => 'single_post_featured_image',
				'type' => 'radio',
				'title' => esc_html__('Single Post Featured Image', 'fwrd'),
				'options' => array(
					'fullwidth' => esc_html__('Full Width', 'fwrd'),
					'original' => esc_html__('Original', 'fwrd'),
					'none' => esc_html__('None', 'fwrd')
				),
				'std' => 'fullwidth'
			),	
			array(
				'id' => 'show_post_date',
				'type' => 'checkbox',
				'title' => esc_html__('Show post date in post archive and single posts', 'fwrd'),
				'switch' => true,
				'std' => '1'
			),
			array(
				'id' => 'show_post_author',
				'type' => 'checkbox',
				'title' => esc_html__('Show post author in post archive and single posts', 'fwrd'),
				'switch' => true,
				'std' => '1'
			),
			array(
				'id' => 'show_post_categories',
				'type' => 'checkbox',
				'title' => esc_html__('Show post categories in post archive and single posts', 'fwrd'),
				'switch' => true,
				'std' => '1'
			),	
			array(
				'id' => 'show_post_tags',
				'type' => 'checkbox',
				'title' => esc_html__('Show post tags in post archive and single posts', 'fwrd'),
				'switch' => true,
				'std' => '1'
			),
			array(
				'id' => 'single_post_settings',
				'type' => 'info',
				'desc' => '<br><h4 class="title">' . esc_html__('Single Posts Page Titles', 'fwrd') . '</h4>'
			),
			array(
				'id' => 'single_post_page_title',
				'type' => 'text',
				'title' => esc_html__('Single Post Page Title', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'single_event_page_title',
				'type' => 'text',
				'title' => esc_html__('Single Event Page Title', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'single_video_page_title',
				'type' => 'text',
				'title' => esc_html__('Single Video Page Title', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'single_album_page_title',
				'type' => 'text',
				'title' => esc_html__('Single Discography Page Title', 'fwrd'),
				'std' => ''
			),
			array(
				'id' => 'single_shop_page_title',
				'type' => 'text',
				'title' => esc_html__('Single Shop Page Title', 'fwrd'),
				'std' => ''
			),
		)
	);


	$redux_sections[] = array(
		'icon' => 'forward',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Pagination Settings', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('You can control settings related to the reading and navigation of posts.', 'fwrd') . '</p><p>' . esc_html__('Enter the number of posts per page for each content type to be displayed within archive page templates.', 'fwrd') . '<br>' . esc_html__('You can control the number of posts for the Posts content type on the <a href="options-reading.php">Reading Settings</a> page.', 'fwrd') . '</p>',
		'fields' => array(

			array(
				'id' => 'videos_per_page',
				'type' => 'text',
				'title' => esc_html__('Videos listings show at most', 'fwrd'),
				'std' => 10
			),
			array(
				'id' => 'paginate_method',
				'type' => 'radio',
				'title' => esc_html__('Pagination Style', 'fwrd'),
				'sub_desc' => esc_html__('Choose how to provide "paged" navigation of posts, categories, and archive pages.', 'fwrd') . '<br>' . wp_kses(__('You can set how many posts to list on each page on the <a href="options-reading.php">Reading Settings</a> page.', 'fwrd'),iron_get_allowed_html()),
				'options' => array(
					'posts_nav_link' => esc_html__('Displays links for next and previous pages', 'fwrd') . ' (' . sprintf( esc_html_x('e.g. : %s', 'Abbreviation of Latin exemplī grātiā (“for example”).', 'fwrd'), esc_html__('« Previous Page — Next Page »', 'fwrd') ) . ')',
					'paginate_links' => esc_html__('Displays a row of paginated links', 'fwrd') . ' (' . sprintf( esc_html_x('e.g. : %s', 'Abbreviation of Latin exemplī grātiā (“for example”).', 'fwrd'), esc_html__('« Prev 1 … 3 4 5 6 7 … 9 Next »', 'fwrd') ) . ')',
					'paginate_more' => esc_html__('Displays a single link to dynamically load more items', 'fwrd') . ' (' . sprintf( esc_html_x('e.g. : %s', 'Abbreviation of Latin exemplī grātiā (“for example”).', 'fwrd'), esc_html__('« More Posts »', 'fwrd') ) . ')',
					'paginate_scroll' => esc_html__('Dynamically load more items as you scroll down (infinite scrolling)', 'fwrd')
				),
				'std' => 'posts_nav_link'
			)
		)
	);

	
	/**
	 * Default sidebars also set in /includes/setup.php:iron_import_default_data()
	 */

	$redux_sections[] = array(
		'icon'       => 'th-large',
		'icon_class' => 'fa-lg',
		'title'      => esc_html_x('Widgets Areas', 'Theme Options', 'fwrd'),
		'desc'       => '<p class="description">' . esc_html_x('Manage your WordPress Widget Areas and additional settings related to page templates and widgets.', 'Theme Options', 'fwrd') . '</p>',
		'fields'     => array(
			array(
				'id'       => 'widget_areas',
				'type'     => 'group',
				'title'    => esc_html_x('Widget Areas', 'Theme Options', 'fwrd'),
				'sub_desc' => esc_html_x('Manage dynamic sidebars for your widgets.', 'Theme Options', 'fwrd'),
				'std'      => array(
					'fwrd_sidebar_0' => array(
						'sidebar_name' => 'Default Blog Sidebar',
						'sidebar_desc' => esc_html_x('Sidebar widget area found on Blog post-related page templates.', 'Theme Options', 'fwrd'),
						'sidebar_grid' => 1,
						'order_no'     => 1
					),
					'fwrd_sidebar_1' => array(
						'sidebar_name' => 'Default Video Sidebar',
						'sidebar_desc' => esc_html_x('Sidebar widget area found on Video-related page templates.', 'Theme Options', 'fwrd'),
						'sidebar_grid' => 1,
						'order_no'     => 2
					),
					'fwrd_sidebar_2' => array(
						'sidebar_name' => 'Default Footer',
						'sidebar_desc' => esc_html_x('Site footer widget area.', 'Theme Options', 'fwrd'),
						'sidebar_grid' => 1,
						'order_no'     => 3
					)
				),
				'options' => array(
					'group' => array(
						'name'      => esc_html_x('Widget Area', 'Theme Options', 'fwrd'),
						'title_key' => 'sidebar_name'
					),
					'fields' => array(
						array(
							'id'    => 'sidebar_name',
							'type'  => 'text',
							'title' => esc_html_x('Sidebar name', 'Theme Options', 'fwrd')
						),
						array(
							'id'    => 'sidebar_desc',
							'type'  => 'textarea',
							'title' => esc_html_x('Sidebar description (optional)', 'Theme Options', 'fwrd')
						)
					)
				)
			),
			array(
				'id'    => 'single_post_default_sidebar',
				'type'  => 'widget_area_select',
				'title' => esc_html__('Single Post Default Sidebar', 'fwrd'),
				'std'   => 'fwrd_sidebar_0'
			),
			array(
				'id'    => 'single_video_default_sidebar',
				'type'  => 'widget_area_select',
				'title' => esc_html__('Single Video Default Sidebar', 'fwrd'),
				'std'   => 'fwrd_sidebar_1'
			),
			array(
				'id'    => 'single_event_default_sidebar',
				'type'  => 'widget_area_select',
				'title' => esc_html__('Single Event Default Sidebar', 'fwrd'),
				'std'   => ''
			),	
			array(
				'id'    => 'single_discography_default_sidebar',
				'type'  => 'widget_area_select',
				'title' => esc_html__('Single Discography Default Sidebar', 'fwrd'),
				'std'   => ''
			),				
		)
	);

	$redux_sections[] = array(
		'icon' => 'calendar',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Events Settings', 'fwrd'),
		'desc' => '<p class="description">' . sprintf( esc_html__('The events setting are in the plugin %s', 'fwrd'), '<a href="' . esc_url(get_admin_url( null, 'admin.php?page=iron-music#iron_music_event' )) . '">Iron Music</a>') . '</p>',
	);

			
	$redux_sections[] = array(
		'icon' => 'music',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Music Player Settings', 'fwrd'),
		'desc' => '<p class="description">' . sprintf( esc_html__('The music player setting are in the plugin %s', 'fwrd'), '<a href="' . esc_url(get_admin_url( null, 'admin.php?page=iron-music#iron_music_music_player' )) . '">Iron Music</a>') . '</p>',
	);
						
							
	$redux_sections[] = array(
		'icon' => 'bullhorn',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Share Module', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('Here are some social settings that you can edit.', 'fwrd') . '</p>',
		'fields'     => array(
			array(
				'id' => 'facebook_appid',
				'type' => 'text',
				'title' => esc_html__('Facebook App ID', 'fwrd'),
				'sub_desc' => wp_kses(__('When you <a target="_blank" href="https://developers.facebook.com/setup/">register your website as an app</a>, you can get detailed analytics about the demographics of your users and how users are sharing from your website with <a target="_blank" href="https://www.facebook.com/insights/">Insights</a>.', 'fwrd'),iron_get_allowed_html()),
				'std' => ''
			),
			array(
				'id' => 'custom_social_actions',
				'type' => 'textarea',
				'title' => esc_html__('Custom Social Buttons', 'fwrd'),
				'sub_desc' => wp_kses(__('Add your favorite drop-in bookmarking and social link-sharing scripts.<br /><br />e.g., <a target="_blank" href="//sharethis.com/">ShareThis</a>, <a target="_blank" href="//www.addthis.com/">AddThis</a>', 'fwrd'),iron_get_allowed_html()),
				'rows' => '20',
				'std' => '
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
	<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
	<a class="addthis_button_tweet"></a>
	<a class="addthis_button_pinterest_pinit"></a>
	<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>
<!-- AddThis Button END -->
'
			),					
			
		)
	);
		


	$redux_sections[] = array(
		'icon' => 'code',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Fav Icons & Metadata', 'fwrd'),
		'desc' => '<p class="description">' . wp_kses(__('These are some metadata options that you can edit, including favicons and a short title for mobile home screens.<br>You can use this online tool to easily generate your favicons. <a href="http://iconifier.net" target="_blank" />http://iconifier.net/</a>', 'fwrd'),iron_get_allowed_html()) . '</p>',
		'fields' => array(
			array(
				'id' => 'meta_favicon',
				'type' => 'upload',
				'title' => esc_html__('Favicon', 'fwrd'),
				'sub_desc' => esc_html__('Upload your shortcut icon', 'fwrd'),
				'std' => get_template_directory_uri().'/images/icons/favicon.ico',
				'desc' => esc_html__('Icon Size: 32px x 32px', 'fwrd'),
				'class' => 'greybg'
			),
			array(
				'id' => 'meta_apple_touch_icon',
				'type' => 'upload',
				'title' => esc_html__('Apple Touch Icon (57×57)', 'fwrd'),
				'sub_desc' => esc_html__('Upload your shortcut icon', 'fwrd'),
				'std' => get_template_directory_uri().'/images/icons/apple-touch-icon-57x57-precomposed.png',
				'desc' => esc_html__('Icon Size: 57px x 57px. For iPhone 3GS, 2011 iPod Touch and older Android devices.', 'fwrd'),
				'class' => 'greybg'
			),
			array(
				'id' => 'meta_apple_touch_icon_72x72',
				'type' => 'upload',
				'title' => esc_html__('Apple Touch Icon (72×72)', 'fwrd'),
				'sub_desc' => esc_html__('Upload your shortcut icon', 'fwrd'),
				'std' => get_template_directory_uri().'/images/icons/apple-touch-icon-72x72-precomposed.png',
				'desc' => esc_html__('Icon Size: 72px x 72px. For 1st generation iPad, iPad 2 and iPad mini.', 'fwrd'),
				'class' => 'greybg'
			),
			array(
				'id' => 'meta_apple_touch_icon_114x114',
				'type' => 'upload',
				'title' => esc_html__('Apple Touch Icon (114×114)', 'fwrd'),
				'sub_desc' => esc_html__('Upload your shortcut icon', 'fwrd'),
				'std' => get_template_directory_uri().'/images/icons/apple-touch-icon-114x114-precomposed.png',
				'desc' => esc_html__('Icon Size: 114px x 114px. For iPhone 4, 4S, 5 and 2012 iPod Touch.', 'fwrd'),
				'class' => 'greybg'
			),
			array(
				'id' => 'meta_apple_touch_icon_144x144',
				'type' => 'upload',
				'title' => esc_html__('Apple Touch Icon (144×144)', 'fwrd'),
				'sub_desc' => esc_html__('Upload your shortcut icon', 'fwrd'),
				'std' => get_template_directory_uri().'/images/icons/apple-touch-icon-144x144-precomposed.png',
				'desc' => esc_html__('Icon Size: 144px x 144px. For iPad 3rd and 4th generation.', 'fwrd'),
				'class' => 'greybg'
			),
			array(
				'id' => 'meta_apple_mobile_web_app_title',
				'type' => 'text',
				'title' => esc_html__('Apple Mobile Web App Title', 'fwrd'),
				'desc' => esc_html__('Sets a different title for an iOS Home Screen icon. By default, Mobile Safari crops document titles to 13 characters.', 'fwrd'),
				'std' => ''
			),
		)
	);


	
	$redux_sections[] = array(
		'icon' => 'file',
		'icon_class' => 'fa-lg',
		'title' => esc_html__('Custom CSS', 'fwrd'),
		'desc' => '<p class="description">' . esc_html__('All the custom CSS goes here', 'fwrd') . '</p>',
		'fields' => array(
			array(
				'id' => 'custom_css',
				'type' => 'textarea',
				'title' => esc_html__('Custom CSS', 'fwrd'),
				'rows' => '40',
				'std' => ''
			)
		)
	);
	
	
	
	if (function_exists('is_plugin_active') && is_plugin_active('woocommerce/woocommerce.php')) {

		$redux_sections[] = array(
			'icon' => 'shopping-cart ',
			'icon_class' => 'fa-lg',
			'title' => esc_html__('WooCommerce', 'fwrd'),
			'desc' => '<p class="description">' . esc_html__('Here are some WooCommerce settings that you can edit.', 'fwrd') . '</p>',
			'fields' => array(
				array(
					'id' => 'woo_backgrounds',
					'type' => 'checkbox',
					'title' => esc_html__('Enable WooCommerce Backgrounds', 'fwrd'),
					'sub_desc' => wp_kses(__('This will add a background to product items and descriptions.<br><b>Primary Color 2</b> will be used for the background.', 'fwrd'),iron_get_allowed_html()),
					'switch' => true,
					'std' => '0'
				),
			)
		);
		
	}	





	$redux_tabs = array();

	if (function_exists('wp_get_theme')){

		$theme_data = wp_get_theme();
		$item_uri = $theme_data->get('ThemeURI');
		$name = $theme_data->get('Name');
		$description = $theme_data->get('Description');
		$author = $theme_data->get('Author');
		$author_uri = $theme_data->get('AuthorURI');
		$version = $theme_data->get('Version');
		$tags = $theme_data->get('Tags');


		$item_info = '<div class="redux-opts-section-desc">';
		$item_info .= '<p class="redux-opts-item-data description item-description"><h4>' . $name . '</h4>' . $description . '</p>';
		$item_info .= '<p class="redux-opts-item-data description item-uri"><strong>' . esc_html__('Theme URL: ', 'fwrd') . '</strong><a href="' . esc_url($item_uri) . '" target="_blank">' . $item_uri . '</a></p>';
		$item_info .= '<p class="redux-opts-item-data description item-author"><strong>' . esc_html__('Author: ', 'fwrd') .'</strong>' . ($author_uri ? '<a href="' . esc_url($author_uri) . '" target="_blank">' . $author . '</a>' : $author) . '</p>';
		$item_info .= '<p class="redux-opts-item-data description item-version"><strong>' . esc_html__('Version: ', 'fwrd') .'</strong>'. $version . '</p>';
		$item_info .= '<p class="redux-opts-item-data description item-tags"><strong>' . esc_html__('Tags: ', 'fwrd') .'</strong>'. implode(', ', $tags) . '</p>';
		$item_info .= '</div>';

		$redux_tabs['item_info'] = array(
			'icon' => 'info',
			'icon_class' => 'fa-lg',
			'title' => esc_html__('Theme Information', 'fwrd'),
			'content' => $item_info
		);

	}

	global $Redux_Options;
	$Redux_Options = new Redux_Options($redux_sections, $redux_args, $redux_tabs);

}


add_action('init', 'iron_setup_framework_options');

/*
 *
 * Get Theme Option by ID
 *
 * Optinal Params:
 * $key, if value is an array get by array key
 */

function get_iron_option($id, $key = null, $default = null) {
	global $Redux_Options;

	if ( method_exists($Redux_Options, 'get') )
		$value = $Redux_Options->get($id, $default);

	else {
		global $redux_args, $redux_sections, $iron_option;

		if ( empty($iron_option) )
			$options = get_option('fwrd');
		else
			$options = $iron_option;

		$options_defaults = null;

		if ( isset($options[$id]) )
			$value = $options[$id];

		else {
			if ( !empty($redux_args['std_show']) )
			{
				if ( is_null($options_defaults) ) // fill the cache
				{
					if( ! is_null($redux_sections) && is_null($options_defaults) )
					{
						foreach ( $redux_sections as $section )
						{
							if ( isset($section['fields']) ) {
								foreach ( $section['fields'] as $field ) {
									if ( isset($field['std']) )
										$options_defaults[ $field['id'] ] = $field['std'];
								}
							}
						}
					}
				}

				$default = array_key_exists($id, $options_defaults) ? $options_defaults[$id] : $default;
			}

			$value = $default;
		}
	}

	if ( $key && is_array($value) && isset($value[$key]) )
		$value = $value[$key];

	return $value;
}

/*
 *
 * Get Theme Options Array
 *
 */

function get_iron_options() {

	global $Redux_Options;
	return $Redux_Options->options;
}

/*
 *
 * Set Theme Option by ID
 *
 * Optinal Params:
 * $key, if value is an array get by array key
 */

function set_iron_option($id, $value = null) {
	global $Redux_Options;

	if ( null === $value )
		$value = $Redux_Options->_get_std($id);

	$Redux_Options->set($id, $value);
}

/*
 *
 * Reset Theme Option by ID
 *
 * Optinal Params:
 * $key, if value is an array get by array key
 */

function reset_iron_option($id) {
	global $Redux_Options;
	$value = $Redux_Options->_get_std($id);

	$Redux_Options->set($id, $value);
}

/*
 *
 * Get Theme Presets Array
 *
 */

function get_iron_presets() {

	$files = scandir(Redux_OPTIONS_DIR.'/presets');
	$presets = array();

	foreach($files as $file) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		$id = str_replace(".".$ext, "", $file);
		$filename = ucFirst($id);

		if($ext == 'png' ) {
			$presets[$id] = array('title' => $filename, 'img' => Redux_OPTIONS_URL.'/presets/'.$file);
		}
	}

	return $presets;
}


function iron_page_for_content_update ( $option ) {

	set_transient('fwrd' . '_flush_rules', true);

}

add_action('update_option_' . 'fwrd', 'iron_page_for_content_update', 10, 1);
