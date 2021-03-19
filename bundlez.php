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
 * @package           Bundlez
 *
 * @wordpress-plugin
 * Plugin Name:       Bundlez
 * Plugin URI:        http://example.com/bundlez-uri/
 * Description:       Plugin to bundle MemberPress memberships from disparate sites
 * Version:           1.0.0
 * Author:            Associated Educational Technologies
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bundlez
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
define( 'BUNDLEZ_VERSION', '1.0.0' );

define( 'BASE_NAME', plugin_basename( __FILE__ ) );
define( 'BASE_PATH',  plugin_dir_path( __DIR__ ) .'bundlez'   );
define( 'ADMIN_PATH',  plugin_dir_path( __DIR__ ) .'bundlez/admin'   );
define( 'PUBLIC_PATH',  plugin_dir_path( __DIR__ ) .'bundlez/public'   );
define( 'LOG_PATH',  plugin_dir_path( __DIR__ ) .'bundlez/logs/debug.log'   );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bundlez-activator.php
 */
function activate_bundlez() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bundlez-activator.php';
	Bundlez_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bundlez-deactivator.php
 */
function deactivate_bundlez() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bundlez-deactivator.php';
	Bundlez_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bundlez' );
register_deactivation_hook( __FILE__, 'deactivate_bundlez' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bundlez.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bundlez() {

	$plugin = new Bundlez();
	$plugin->run();

}
run_bundlez();
