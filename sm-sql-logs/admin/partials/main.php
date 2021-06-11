<?php

/**
 * Display logs
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/mi7osz/
 * @since      1.0.0
 *
 * @package    Sm_sql_logs
 * @subpackage Sm_sql_logs/admin/partials
 */

	$this->print_header_html('');

//////////////////////////////////////////////////////////////////////////////

	$no_of_queries_in_db = $this->wpdb->get_row( "SELECT COUNT(id) As no FROM ".SM_SQL_LOGS_SQL_TABLE_MAIN );

	echo '<p>Currently there is <strong>'.$no_of_queries_in_db->no.'</strong> queries in database.</p>';

	echo '<p>Go <a href="'.admin_url().'/admin.php?page=sm_sql_logs-logs">see Logs</a> or change <a href="'.admin_url().'/admin.php?page=sm_sql_logs-options">Options</a>.</p>';
?>