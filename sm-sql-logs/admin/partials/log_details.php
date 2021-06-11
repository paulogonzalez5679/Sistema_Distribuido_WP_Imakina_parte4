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

	if($this->options['use_sqlformatter'] == 1)
	{
		require_once plugin_dir_path( __FILE__ ) . '../../includes/sql-formatter-master/SqlFormatter.php';
	}

	$user_data = $log = FALSE;

//////////////////////////////////////////////////////////////////////////////

	$this->print_header_html(array('head'=>'Log id: '.$lid));

//////////////////////////////////////////////////////////////////////////////

	$log = $this->wpdb->get_results( "
		SELECT * FROM ".SM_SQL_LOGS_SQL_TABLE_MAIN.'
		WHERE id='.$lid.''
		, ARRAY_A
	);

	if(!empty($log))
	{
		$type = $log['0']['type'];

		$query = $log['0']['query'];
		$qlen = strlen($query);

		if($this->options['use_sqlformatter'] == 1)
		{
			if($qlen < $this->settings['max_query_len_for_sqlformatter'])
			{
				$query_formated = SqlFormatter::format($query);
			}
			else
			{
				$query_formated = '<pre><em>Query is to long to handle for SqlFormatter</em></pre>';
			}
		}
		else
		{
			$query_formated = '<pre><em>SqlFormatter is set to Off</em></pre>';
		}

		$query_time = $log['0']['query_time'];
		$wp_user_id = $log['0']['wp_user_id'];
		$file = $log['0']['file'];
		$file_parts = explode(', ', $file);

		$date = $log['0']['date'];

		if($wp_user_id > 0)
		{
			$get_userdata = get_userdata( $wp_user_id );

			$user_data = $get_userdata->data;
		}
?>
<div class="sml_entry_detail">

<?php
	if($user_data)
	{
?>
	<div>
		<span class="sml_entry_label">User:</span>
		<pre><?php echo $user_data->user_login; ?> (<?php echo $user_data->display_name; ?>)</pre>
	</div>

<?php
	}
?>

	<div>
		<span class="sml_entry_label">Query execution time:</span>
		<pre><?php echo $query_time; ?></pre>
	</div>

	<div>
		<span class="sml_entry_label">Oryginal query:</span>
		<div class="sml_entry_prelike"><?php echo $query; ?></div>
	</div>

	<div>
		<span class="sml_entry_label">Formated query:</span>
		<?php echo $query_formated; ?>
	</div>

	<div>
		<span class="sml_entry_label">Query location info:</span>
		<pre><?php

			if(is_array($file_parts))
			{
				foreach($file_parts As $p)
				{
					echo '<p>'.$p.'</p>';
				}
			}

		?></pre>
	</div>

</div>
<?php
	}

?>