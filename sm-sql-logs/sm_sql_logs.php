<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/mi7osz/
 * @since             1.0.0
 * @package           Sm_sql_logs
 *
 * @wordpress-plugin
 * Plugin Name:       SM - SQL logs
 * Plugin URI:        https://wordpress.org/plugins/sm_sql_logs/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.1.1
 * Author:            Mi7osz
 * Author URI:        https://profiles.wordpress.org/mi7osz/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sm_sql_logs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

	require_once plugin_dir_path( __FILE__ ) . 'config.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sm_sql_logs-activator.php
 */
function activate_sm_sql_logs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sm_sql_logs-activator.php';
	Sm_sql_logs_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sm_sql_logs-deactivator.php
 */
function deactivate_sm_sql_logs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sm_sql_logs-deactivator.php';
	Sm_sql_logs_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sm_sql_logs' );
register_deactivation_hook( __FILE__, 'deactivate_sm_sql_logs' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sm_sql_logs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sm_sql_logs() {

	$plugin = new Sm_sql_logs();
	$plugin->run();

}
run_sm_sql_logs();
