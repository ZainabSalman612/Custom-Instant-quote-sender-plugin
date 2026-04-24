<?php
/**
 * Fired during plugin activation.
 */
class Iqe_Activator {
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'iqe_leads';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			email varchar(255) NOT NULL,
			phone varchar(50) NOT NULL,
			service_type varchar(100) NOT NULL,
			inputs text NOT NULL,
			totals text NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}
