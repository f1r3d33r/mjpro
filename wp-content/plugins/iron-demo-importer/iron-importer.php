<?php
/*
Plugin Name: Iron Demo Importer
Description: This extension lets you import demo from IronTemplates. To import a demo, go to Tools > Demo Content Install
Plugin URI: http://irontemplates.com
Author: IronTemplates
Author URI: http://irontemplates.com
Version: 1.0
License: GPL2
Text Domain: iron-demo-importer
Domain Path: /languages
*/


define( 'IRON_DEMO_IMPORTER_DIR_URL', plugin_dir_url( __FILE__ ) );


if (defined('FW')):
    // the framework was already included in another place, so this version will be inactive/ignored
else:
    function _filter_fw_framework_plugin_directory_uri() {
        return plugin_dir_url( __FILE__ ) . '/includes/Unyson/framework/';
    }
    add_filter('fw_framework_directory_uri', '_filter_fw_framework_plugin_directory_uri');

    require dirname(__FILE__) .'/includes/Unyson/framework/bootstrap.php';
endif;


function iron_importer_active(){
    if ( is_admin() ) {
        do_action('fw_after_plugin_activate');
    }
}

add_action('fw_init', 'iron_importer_active');



function iron_importer_settings_link( $links )
{
$links[] = '<a href="' . esc_url(get_admin_url( null, 'tools.php?page=fw-backups-demo-content' )) .'">' . esc_html__('Settings', 'iron-demo-importer') . '</a>';
return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'iron_importer_settings_link' );

add_action('admin_enqueue_scripts', 'iron_importer_script');

function checkIfImporterPage(){
    $page = ( isset(  $_GET["page"] ) )? $_GET["page"] : false;

    if ( 'fw-backups-demo-content' == $page ){
        return true;
    }
    return false;
}

function iron_importer_script(){
    if ( !checkIfImporterPage() )
    return;

    wp_enqueue_style('iron_importer', IRON_DEMO_IMPORTER_DIR_URL . 'css/admin.css', array(), NULL);
}




function checkIfSame( $toCheck = null, $minValue = null, $recommendedValue = null ){

    if ( !isset($toCheck) || !isset($minValue) || !isset($recommendedValue) )
        return;

    if ( (int) $toCheck < $minValue ) {
        return ' class="danger"';

    }elseif( (int) $toCheck < $recommendedValue ){
        return ' class="warning"';

    }else{
        return;

    }
}



function iron_importer_notice() {

    if ( !checkIfImporterPage() )
    return;


	$class = "iron-importer message";
        echo'<div class="' . $class . '">
            <div class="message">
                <h2>Requirements & Recommendations</h2>
                <p>In order to import demos, your server must meet the requirements listed below.<br>
                If the import doesn\'t work, you should contact your web hosting provider and ask them to adjust your server configuration to meet the recommended settings.</p>
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
                    <td>PHP Version</td>
                    <td>'. phpversion() .'</td>
                    <td>5.2</td>
                    <td>5.4</td>
                    <td></td>
                </tr>
                <tr' . ini_set('max_execution_time', 600) . checkIfSame( ini_get('max_execution_time'), 300, 600 ) . '>
                    <td>PHP Time Limit</td>
                    <td>'. ini_get('max_execution_time') .'</td>
                    <td>300</td>
                    <td>600</td>
                    <td><a target="_blank" href="http://php.net/manual/en/function.set-time-limit.php">PHP Documentation "set_time_limit"</a></td>
                </tr>
                <tr' . checkIfSame( ini_get('upload_max_filesize'), 2, 10 ) . '>
                    <td>Max Upload Size</td>
                    <td>'. ini_get('upload_max_filesize') .'</td>
                    <td>2M</td>
                    <td>10M</td>
                    <td><a target="_blank" href="http://php.net/manual/en/ini.core.php">PHP Documentation "php.ini file"</a></td>
                </tr>
                <tr' . checkIfSame( ini_get('memory_limit'), 32, 64 ) . '>
                    <td>Memory Limit</td>
                    <td>'. ini_get('memory_limit') .'</td>
                    <td>32M</td>
                    <td>64M</td>
                    <td><a target="_blank" href="http://php.net/manual/en/ini.core.php">PHP Documentation "php.ini file"</a></td>
                </tr>
                <tr' . checkIfSame( WP_MEMORY_LIMIT, 32, 64 ) . '>
                    <td>WP Memory Limit</td>
                    <td>'. WP_MEMORY_LIMIT .'</td>
                    <td>32M</td>
                    <td>64M</td>
                    <td><a target="_blank" href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP">WordPress Documentation "Increasing memory allocated to PHP"</a></td>
                </tr>
            </tbody>
            </table>
        </div>';
}
