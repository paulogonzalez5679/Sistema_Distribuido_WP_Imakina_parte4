<?php

/**
 * Fired during plugin activation
 *
 * @link       https://profiles.wordpress.org/mi7osz/
 * @since      1.0.0
 *
 * @package    Sm_sql_logs
 * @subpackage Sm_sql_logs/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sm_sql_logs
 * @subpackage Sm_sql_logs/includes
 * @author     Mi7osz <esemwp@gmail.com>
 */
class Sm_sql_logs_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
  {
  	global $wpdb;

  	$charset_collate = $wpdb->get_charset_collate();

  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  	$sql = 'CREATE TABLE IF NOT EXISTS '.SM_SQL_LOGS_SQL_TABLE_MAIN.' (
  		id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  		cycle_no INT(10) UNSIGNED NOT NULL,
  		type ENUM("ins","sel","upd","del","other") NOT NULL,
  		query TEXT NOT NULL,
  		query_time FLOAT(17,16) UNSIGNED NOT NULL,
  		wp_user_id INT(10) UNSIGNED NOT NULL,
  		file TEXT NOT NULL,
  		date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  		PRIMARY  KEY (id)
  	) '.$charset_collate.';';

  	$r = dbDelta( $sql );

  	update_option('sql_logs_db_version', SM_SQL_LOGS_VERSION);

		$set_sm_sql_logs_option = array(
			'on_off'		=> 1,
			'max_logs'	=> 500,
			'qt_insert'	=> 1,
			'qt_select'	=> 1,
			'qt_update'	=> 1,
			'qt_delete'	=> 1,
			'qt_other'	=> 1,
			'use_sqlformatter'	=> 1,
			'max_new_queries'	=> '',
		);

		update_option('sm_sql_logs_options', $set_sm_sql_logs_option);

		$max_logs_forced = 10000;

		$set_sm_sql_logs_settings = array(
			'max_logs_forced'	=> $max_logs_forced,
			'max_logs_set'	=> min(
				$set_sm_sql_logs_option['max_logs'],
				$max_logs_forced
			),
			'max_query_len_in_list'	=> 400, // $this->max_len_of_string_to_display_in_list
			'max_query_len_for_sqlformatter'	=> 15000, // $this->max_len_of_string_to_parse
		);

		update_option('sm_sql_logs_settings', $set_sm_sql_logs_settings);

	}
}
