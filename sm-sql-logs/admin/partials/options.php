<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/mi7osz/
 * @since      1.0.0
 *
 * @package    Sm_sql_logs
 * @subpackage Sm_sql_logs/admin/partials
 */

	$user_data = $log = $update_no_option = FALSE;

//////////////////////////////////////////////////////////////////////////////

	$this->print_header_html(array('head'=>'Options'));

//////////////////////////////////////////////////////////////////////////////



?>

<form action='options.php' method='post' class="sml_options">
	<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
	?>
</form>