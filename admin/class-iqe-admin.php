<?php
class Iqe_Admin {

	public function enqueue_styles( $hook ) {
		if ( 'quote-leads_page_iqe_settings' === $hook ) {
			wp_enqueue_style( 'wp-color-picker' );
		}
	}

	public function enqueue_scripts( $hook ) {
		if ( 'quote-leads_page_iqe_settings' === $hook ) {
			wp_enqueue_script( 'wp-color-picker' );
			// Custom script for settings page (handling tabs and color pickers)
			wp_enqueue_script( 'iqe-admin-js', IQE_PLUGIN_URL . 'admin/js/iqe-admin.js', array( 'jquery', 'wp-color-picker' ), IQE_VERSION, true );
		}
	}

	public function add_plugin_admin_menu() {
		// Top level menu Quote Leads
		add_menu_page(
			'Quote Leads', 
			'Quote Leads', 
			'manage_options', 
			'iqe_leads', 
			array( $this, 'display_leads_page' ), 
			'dashicons-clipboard', 
			26
		);

		add_submenu_page(
			'iqe_leads',
			'Quote Leads',
			'All Leads',
			'manage_options',
			'iqe_leads',
			array( $this, 'display_leads_page' )
		);

		add_submenu_page(
			'iqe_leads',
			'Quote Estimator Settings',
			'Settings',
			'manage_options',
			'iqe_settings',
			array( $this, 'display_settings_page' )
		);
	}

	public function register_settings() {
		register_setting( 'iqe_pricing_options_group', 'iqe_pricing_options' );
		register_setting( 'iqe_design_options_group', 'iqe_design_options' );
        register_setting( 'iqe_email_options_group', 'iqe_email_options' );
	}

	public function display_settings_page() {
		require_once IQE_PLUGIN_DIR . 'admin/partials/iqe-admin-settings-display.php';
	}

	public function display_leads_page() {
		require_once IQE_PLUGIN_DIR . 'admin/partials/iqe-admin-leads-display.php';
	}

    public function export_leads_csv() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Unauthorized' );
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'iqe_leads';
        $results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A );

        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=quote-leads-' . date('Y-m-d') . '.csv' );
        
        $output = fopen( 'php://output', 'w' );
        
        fputcsv( $output, array( 'ID', 'Name', 'Email', 'Phone', 'Service Type', 'Area', 'Manholes', 'Poor Access', 'Material/Level', 'Total (Ex VAT)', 'Total (Inc VAT)', 'Date Submitted' ) );
        
        if ( !empty($results) ) {
            foreach ( $results as $row ) {
                $inputs = json_decode($row['inputs'], true);
                $totals = json_decode($row['totals'], true);
                
                $material_level = '';
                if (isset($inputs['material'])) $material_level = $inputs['material'];
                if (isset($inputs['level'])) $material_level = $inputs['level'];

                fputcsv( $output, array(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['phone'],
                    $row['service_type'],
                    $inputs['area'] ?? '',
                    $inputs['manholes'] ?? '',
                    $inputs['poor_access'] ?? '',
                    $material_level,
                    $totals['ex_vat'] ?? '',
                    $totals['inc_vat'] ?? '',
                    $row['created_at']
                ));
            }
        }
        
        fclose( $output );
        exit();
    }
}
