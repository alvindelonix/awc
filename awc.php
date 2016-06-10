<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              writerscentre.com.au
 * @since             1.0.0
 * @package           Awc
 *
 * @wordpress-plugin
 * Plugin Name:       AWC Custom
 * Plugin URI:        writerscentre.com.au
 * Description:       A plugin for all custom functionality of AWC website
 * Version:           1.0.0
 * Author:            Alvin
 * Author URI:        writerscentre.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       awc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-awc-activator.php
 */
function activate_awc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-awc-activator.php';
	Awc_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-awc-deactivator.php
 */
function deactivate_awc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-awc-deactivator.php';
	Awc_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_awc' );
register_deactivation_hook( __FILE__, 'deactivate_awc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-awc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_awc() {

	$plugin = new Awc();
	$plugin->run();

}
run_awc();
