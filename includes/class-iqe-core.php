<?php
/**
 * Core plugin class to bind admin and public hooks.
 */
class Iqe_Core {
	public function run() {
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {
		require_once IQE_PLUGIN_DIR . 'admin/class-iqe-admin.php';
		require_once IQE_PLUGIN_DIR . 'public/class-iqe-public.php';
	}

	private function define_admin_hooks() {
		$plugin_admin = new Iqe_Admin();
		add_action( 'admin_menu', array( $plugin_admin, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $plugin_admin, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
        add_action( 'admin_post_iqe_export_csv', array( $plugin_admin, 'export_leads_csv' ) );
	}

	private function define_public_hooks() {
		$plugin_public = new Iqe_Public();
		add_shortcode( 'instant_quote_estimator', array( $plugin_public, 'render_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_iqe_submit_form', array( $plugin_public, 'handle_form_submission' ) );
		add_action( 'wp_ajax_nopriv_iqe_submit_form', array( $plugin_public, 'handle_form_submission' ) );
	}
}
