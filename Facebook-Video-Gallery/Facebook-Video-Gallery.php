<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Facebook_Video_Gallery
 *
 * @wordpress-plugin
 * Plugin Name:       Facebook_Video_Gallery
 * Plugin URI:        https://churchcrm.io
 * Description:       This plugin provides support for displaying a Facebook Page's video gallery on your WordPress Site.
 * Version:           1.0.0
 * Author:            Charles Crossan
 * Author URI:        https://ccrossan.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       Facebook_Video_Gallery
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( PLUGIN_VERSION, '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-Facebook-Video-Gallery-activator.php
 */
function activate_fbvg_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-Facebook-Video-Gallery-activator.php';
	Facebook_Video_Gallery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-Facebook-Video-Gallery-deactivator.php
 */
function deactivate_fbvg_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-Facebook-Video-Gallery-deactivator.php';
	Facebook_Video_Gallery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fbvg_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_fbvg_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-Facebook-Video-Gallery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fbvg_plugin() {

	$plugin = new Facebook_Video_Gallery();
	$plugin->run();

}
run_fbvg_plugin();
