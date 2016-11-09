<?php
/**
 * @package           Imdbi
 *
 * @wordpress-plugin
 * Plugin Name:       imdbi
 * Plugin URI:        http://www.wordpress.org/plugins/imdbi
 * Description:       This plugin will retrieve movie/series information, all content, images and trailers.
 * Version:           2.0.2
 * Author:            mohammad azami
 * Author URI:        http://www.iazami.ir/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       imdbi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-imdbi-activator.php
 */
function activate_imdbi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-imdbi-activator.php';
	Imdbi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-imdbi-deactivator.php
 */
function deactivate_imdbi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-imdbi-deactivator.php';
	Imdbi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_imdbi' );
register_deactivation_hook( __FILE__, 'deactivate_imdbi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-imdbi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 */
function run_imdbi() {

	$plugin = new Imdbi();
	$plugin->run();

}
run_imdbi();
