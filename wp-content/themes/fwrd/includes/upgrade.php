<?php

/**
 * Upgrade
 *
 * All the functionality for upgrading Iron Templates
 *
 * @since 1.0.0
 */

function iron_upgrade () {
	global $wpdb;

	# Don't run on theme activation
	if ( isset($_GET['activated']) && $_GET['activated'] == 'true' )
		return;

	$iron_theme  = wp_get_theme();
	$old_version = get_option( 'fwrd' . '_version', '1.0.0' ); // false
	$new_version = $iron_theme->get('Version');

}

add_action('init', 'iron_upgrade');
