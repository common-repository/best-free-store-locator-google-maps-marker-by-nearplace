<?php

/**
 * @link              https://nearplace.com
 * @since             1.0.0
 * @package           Nearplace
 *
 * @wordpress-plugin
 * Plugin Name:       Best Free Store Locator & Google Maps Marker by NearPlace
 * Plugin URI:        https://nearplace.com
 * Description:       Plugin for NearPlace store locator. Please visit nearplace.com for further information.
 * Version:           1.0.0
 * Author:            NearPlace
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nearplace
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NEARPLACE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nearplace-activator.php
 */
function activate_nearplace() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nearplace-activator.php';
	Nearplace_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nearplace-deactivator.php
 */
function deactivate_nearplace() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nearplace-deactivator.php';
	Nearplace_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nearplace' );
register_deactivation_hook( __FILE__, 'deactivate_nearplace' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nearplace.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nearplace() {

	$plugin = new Nearplace();
	$plugin->run();

}
run_nearplace();
