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

//////////////////////////////////////////////////////////////////////////////

	$this->print_header_html(array('head'=>'Logs'));

//////////////////////////////////////////////////////////////////////////////

	$logs = $this->wpdb->get_results( "
		SELECT id, type, query, file, date, query_time FROM ".SM_SQL_LOGS_SQL_TABLE_MAIN.'
		ORDER BY id DESC
		LIMIT 100',
		ARRAY_A
	);

	$qt_max = max(array_column($logs, 'query_time'));
	$qt_min = min(array_column($logs, 'query_time'));

	if(!empty($logs))
	{
?>

			Below is list of logs started from most recent one.<br />
			Click Log ID on the left to is it's details.<br />
			To long queries are trimed. Go to it's details to see whole query.
			<hr />

<div class="sml_logs">

			<div class="sml_head">
				<span class="sml_head_cell sml_entry_id">id</span>
				<span class="sml_head_cell sml_entry_qt">time</span>
				<span class="sml_head_cell sml_entry_query">query</span>
			</div>
<?php

		foreach($logs As $l)
		{
			$id = $l['id'];
			$type = $l['type'];
			$date = $l['date'];
			$file = $l['file'];
			$query_time = $l['query_time'];
				$query_time_percent = round(100 * ($query_time - $qt_min) / ($qt_max - $qt_min));

			$query_org = $l['query'];
			$qlen = strlen($query_org);

      $query = $query_org;

			if($qlen > $this->settings['max_query_len_in_list'])
			{
				$is_query_trimmed = 1;
        $query = substr($query, 0, $this->settings['max_query_len_in_list']);
			}
			else
			{
				$is_query_trimmed = 0;
			}

			if($this->options['use_sqlformatter'] == 1)
			{
	      $query = SqlFormatter::compress($query);
	      $query = SqlFormatter::highlight($query);
			}
			else
			{
				$query = preg_replace('/\s+/S', " ", $query);
	      $query = '<pre>'.$query.'</pre>';
			}

			$change = array(
				'SELECT' => '<span class="sml_entry_'.$type.'">SELECT</span>',
				'INSERT' => '<span class="sml_entry_'.$type.'">INSERT</span>',
				'UPDATE' => '<span class="sml_entry_'.$type.'">UPDATE</span>',
				'DELETE' => '<span class="sml_entry_'.$type.'">DELETE</span>',
			);
			$query =  strtr($query, $change);

			if($is_query_trimmed == 1)
			{
				$query = preg_replace('/<\/pre>/', "<span class=\"sml_query_was_trimmed\"> (...) Query was trimmed </span></pre>", $query);
			}

			$url = esc_url( add_query_arg( 'lid', $id ) );
?>
<div class="sml_entry">
	<span class="sml_entry_cell sml_entry_id"><a href="<?php echo $url; ?>"><?php echo $id; ?></a></span>
	<span class="sml_entry_cell sml_entry_qt"><?php echo $query_time; ?>
	
		<div class="sml_entry_cell sml_entry_qt_percent" style="width: <?php echo $query_time_percent; ?>px !important;"></div>
	
	</span>

	<span class="sml_entry_cell sml_entry_query"><?php echo $query; ?></span>
</div>
<?php
		}

		echo '</div>';
	}
	else
	{
		echo '<p>No logs to show</p>';
	}
?>