<?php
/**
 * Plugin Name:       Instant Quote Estimator with Lead Capture
 * Description:       A custom calculator and lead capture form for estimating quotes.
 * Version:           1.0.0
 * Author:            Your Name
 * Text Domain:       instant-quote-estimator
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'IQE_VERSION', '1.0.0' );
define( 'IQE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'IQE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_instant_quote_estimator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-iqe-activator.php';
	Iqe_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_instant_quote_estimator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-iqe-deactivator.php';
	Iqe_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_instant_quote_estimator' );
register_deactivation_hook( __FILE__, 'deactivate_instant_quote_estimator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-iqe-core.php';

function run_instant_quote_estimator() {
	$plugin = new Iqe_Core();
	$plugin->run();
}

run_instant_quote_estimator();
