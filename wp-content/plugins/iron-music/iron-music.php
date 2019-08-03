<?php
/*
Plugin Name: Iron Music
Description: A Music manager for the themes FWRD by IronTemplates
Plugin URI:  http://irontemplates.com
Author: IronTemplates
Author URI: http://irontemplates.com
Version: 1.2
License: GPL2
Text Domain: iron-music
*/

/*

    Copyright (C) 2015  IronTemplates  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


define( 'IRON_MUSIC', TRUE );

if (!defined('ACF_LITE'))
    define( 'ACF_LITE', FALSE );

define( 'IRON_MUSIC_DIR_PATH', plugin_dir_path(__FILE__ ) );
define( 'IRON_MUSIC_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'IRON_MUSIC_PREFIX', 'IRONMUSIC: ' );
load_plugin_textdomain('iron-music', false, basename( dirname( __FILE__ ) ) . '/languages' );

if (!defined( 'IRON_MUSIC_TEXT_DOMAIN')) {
    define( 'IRON_MUSIC_TEXT_DOMAIN', 'IRON_MUSIC' );
}

require IRON_MUSIC_DIR_PATH . 'includes/functions.php';
require IRON_MUSIC_DIR_PATH . 'includes/class/template-loader-class.php';
require IRON_MUSIC_DIR_PATH . 'includes/posttypes.php';
require IRON_MUSIC_DIR_PATH . 'includes/options.php';
require IRON_MUSIC_DIR_PATH . 'includes/advanced-custom-fields/acf.php';
require IRON_MUSIC_DIR_PATH . 'includes/custom-fields.php';
require IRON_MUSIC_DIR_PATH . 'includes/class/import.php';





// Template loader instantiated elsewhere, such as the main plugin file
$iron_music_template_loader = new Iron_Features_Template_Loader;

// ...

// This function can live wherever is suitable in your plugin
function iron_music_get_template_part($slug, $name = null, $load = true ) {
    global $iron_music_template_loader;
    $iron_music_template_loader->get_template_part( $slug, $name, $load );
}

// Load Widgets
require_once IRON_MUSIC_DIR_PATH . 'includes/class/widget.class.php';
require_once IRON_MUSIC_DIR_PATH . 'includes/shortcodes.php';
require_once IRON_MUSIC_DIR_PATH . 'includes/widgets.php';

if ( ! class_exists( 'Dynamic_Styles' ) ) {
    require IRON_MUSIC_DIR_PATH . 'includes/class/styles.class.php';
}





add_action( 'admin_enqueue_scripts', 'ironMusic_load_script' );
add_action( 'wp_enqueue_scripts', 'ironMusic_load_frontend', 12);
add_action( 'init', 'ironMusic_load_dynamic_assets');









function ironMusic_load_script($hook){

    wp_enqueue_style( 'ironMusic_css', IRON_MUSIC_DIR_URL . 'css/ironMusicAdmin.css', array(), NULL );
    if ( 'toplevel_page_iron-music' != $hook )
        return;

    wp_enqueue_script( 'color', IRON_MUSIC_DIR_URL . '/js/jqColorPicker.min.js', array( 'jquery' ), NULL, TRUE );
    wp_enqueue_script( 'fontSelector', IRON_MUSIC_DIR_URL . '/includes/fontselect-jquery-plugin/jquery.fontselect.min.js', array('jquery'), NULL, TRUE );
    wp_enqueue_style( 'fontSelectorCss', IRON_MUSIC_DIR_URL . '/includes/fontselect-jquery-plugin/fontselect.css', array(), NULL );

    wp_enqueue_style( 'ironMusic_css', IRON_MUSIC_DIR_URL . 'css/ironMusicAdmin.css', array('fontSelectorCss'), NULL );
    wp_enqueue_script( 'iron_feature', IRON_MUSIC_DIR_URL . '/js/ironFeatures.js', array( 'jquery', 'color', 'fontSelector' ), NULL, TRUE );

}

function ironMusic_load_frontend(){
    $custom_styles_url = home_url('/') .'?loadIronMusic=iron_feature.css';
    wp_enqueue_style('iron_feature_css', $custom_styles_url, array(), NULL, 'all' );
    wp_enqueue_script( 'jquery.plugin', IRON_MUSIC_DIR_URL . 'js/countdown/jquery.plugin.min.js', array( 'jquery' ), NULL, TRUE );
    wp_enqueue_script( 'jquery.countdown_js', IRON_MUSIC_DIR_URL . 'js/countdown/jquery.countdown.min.js', array( 'jquery', 'jquery.plugin' ), NULL, TRUE );
    wp_enqueue_script( 'ironMusic-js', IRON_MUSIC_DIR_URL.'js/ironMusic.js', false );
    wp_enqueue_script( 'iron-twitter', IRON_MUSIC_DIR_URL.'js/twitter/jquery.tweet.min.js', array('jquery'), null, TRUE);
}

function get_ironMusic_option($option_singular = NULL, $option_name = '_iron-music_options' ){

    $iron_music_options = get_option( $option_name );

    if (!$iron_music_options)
        return;

    if ( is_null( $option_singular ) || !array_key_exists( $option_singular , $iron_music_options ) )
        return $iron_music_options;

    return $iron_music_options[$option_singular];

}

function ironMusic_option($option_singular = NULL, $option_name = '_iron-music_options' ){
    $iron_music_option = get_ironMusic_option( $option_singular, $option_name );
    if ( is_array( $iron_music_option ) ) {
        print_r( $iron_music_option );
    }else{
        echo $iron_music_option;
    }
}

function ironMusic_load_dynamic_assets() {
    if( is_admin() )
        return;

    if ( !isset( $_GET["loadIronMusic"] )  )
        return;

    $loadIronMusic = $_GET["loadIronMusic"];
    if(!empty( $loadIronMusic )) {

        if( $loadIronMusic == 'iron_feature.css' ) {
            include_once( IRON_MUSIC_DIR_PATH . 'css/custom-style.php');
            exit;
        }

    }
}

function ironMusic_ajax($data){
    $data = $_POST['data'];
    $data = json_decode( stripcslashes( $data ) ,true);

    $data_update = array();
    foreach ($data as $key => $options) {
        foreach ($options as $key => $value) {
            update_option( $key , $value );
        }

        $value = get_option( $key );
        if ( !empty( $value ) ) {
            array_push($data_update, array($key => $value) );
        }

    }
    echo json_encode($data_update);

    wp_die();

}

add_action('wp_ajax_ironMusic_ajax', 'ironMusic_ajax');