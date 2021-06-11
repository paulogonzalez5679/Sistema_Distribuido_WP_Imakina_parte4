<?php

/**
 * Revalidate options/settings on Save Options
 * included in this file.
 */

	$options = get_option( 'sm_sql_logs_options' );

	if(empty($options['qt_insert'])) { $update_no_option['qt_insert'] = 0; }
	if(empty($options['qt_select'])) { $update_no_option['qt_select'] = 0; }
	if(empty($options['qt_update'])) { $update_no_option['qt_update'] = 0; }
	if(empty($options['qt_delete'])) { $update_no_option['qt_delete'] = 0; }
	if(empty($options['qt_other'])) { $update_no_option['qt_other'] = 0; }

	if(!empty($update_no_option) && is_array($update_no_option))
	{
			update_option('sm_sql_logs_options', array_merge($options, $update_no_option));
			$options = get_option( 'sm_sql_logs_options' );
	}

	$set_sm_sql_logs_settings = get_option( 'sm_sql_logs_settings' );

		$set_sm_sql_logs_settings['max_logs_set'] = min(
					$options['max_logs'],
					$set_sm_sql_logs_settings['max_logs_forced']
		);

		update_option('sm_sql_logs_settings', array_merge($set_sm_sql_logs_settings));

/**
 * Options saving functions
 */

function sm_sql_logs_options_init(  )
{
	register_setting( 'pluginPage', 'sm_sql_logs_options', 'sm_sql_logs_options_validation' );

	add_settings_section(
		'sm_sql_logs_pluginPage_section',
		__( '', 'sm_sql_logs' ),
		'sm_sql_logs_options_section_callback',
		'pluginPage'
	);

	// On / Off

	add_settings_field(
		'sm_sql_logs_on_off',
		__( 'Turn logging ON / OFF', 'sm_sql_logs' ),
		'sm_sql_logs_on_off_render',
		'pluginPage',
		'sm_sql_logs_pluginPage_section'
	);

	add_settings_field(
		'sm_sql_logs_max_new_queries',
		__( 'Maximum of new queries to add to log:', 'sm_sql_logs' ),
		'sm_sql_logs_max_new_queries_render',
		'pluginPage',
		'sm_sql_logs_pluginPage_section'
	);

	add_settings_field(
		'sm_sql_logs_max_logs',
		__( 'Maximum number of logs stored in Database', 'sm_sql_logs' ),
		'sm_sql_logs_max_logs_render',
		'pluginPage',
		'sm_sql_logs_pluginPage_section'
	);

	add_settings_field(
		'sm_sql_logs_qt_insert',
		__( 'Log <u>Insert</u> queries', 'sm_sql_logs' ),
		'sm_sql_logs_qt_insert_render',
		'pluginPage',
		'sm_sql_logs_pluginPage_section'
	);

	add_settings_field(
		'sm_sql_logs_qt_select',
		__( 'Log <u>Select</u> queries', 'sm_sql_logs' ),
		'sm_sql_logs_qt_select_render',
		'pluginPage',
		'sm_sql_logs_pluginPage_section'
	);

	add_settings_field(
		'sm_sql_logs_qt_update',
		__( 'Log <u>Update</u> queries', 'sm_sql_logs' ),
		'sm_sql_logs_qt_update_render',
		'pluginPage',
		'sm_sql_logs_pluginPage_section'
	);

	add_settings_field(
		'sm_sql_logs_qt_delete',
		__( 'Log <u>Delete</u> queries', 'sm_sql_logs' ),
		'sm_sql_logs_qt_delete_render',
		'pluginPage',
		'sm_sql_logs_pluginPage_section'
	);

	add_settings_field(
		'sm_sql_logs_qt_other',
		__( 'Log <em>Other</em> queries', 'sm_sql_logs' ),
		'sm_sql_logs_qt_other_render',
		'pluginPage',
		'sm_sql_logs_pluginPage_section'
	);

	add_settings_field(
		'sm_sql_logs_use_sqlformatter',
		__( 'Use SqlFormatter?', 'sm_sql_logs' ),
		'sm_sql_logs_use_sqlformatter_render',
		'pluginPage',
		'sm_sql_logs_pluginPage_section'
	);
}


function sm_sql_logs_on_off_render(  )
{
	$options = get_option( 'sm_sql_logs_options' );
?>
	<label>On
		<input type='radio' name='sm_sql_logs_options[on_off]' <?php checked( $options['on_off'], 1 ); ?> value='1'>
	</label>
	/
	<label>
		<input type='radio' name='sm_sql_logs_options[on_off]' <?php checked( $options['on_off'], 0 ); ?> value='0'>
		Off
	</label>

	<div class="sml_options_descr">If "Off" no new logs will be added. Old logs still available.</div>
<?php
}

function sm_sql_logs_max_logs_render(  )
{
	$options = get_option( 'sm_sql_logs_options' );
	$settings = get_option( 'sm_sql_logs_settings' );
?>
	<input type='text' name='sm_sql_logs_options[max_logs]' value='<?php echo $options['max_logs']; ?>'>

	<div class="sml_options_descr">Maximum number of logs to store in DataBase. Older logs will be deleted if that number will be overrun.<br />If no number or wrong number will be provided, maximum of <?php echo $settings['max_logs_forced']; ?> entries will be stored.</div>
<?php

}

function sm_sql_logs_qt_insert_render(  )
{
	$options = get_option( 'sm_sql_logs_options' );
?>
	<input type='checkbox' name='sm_sql_logs_options[qt_insert]' <?php checked( $options['qt_insert'], 1 ); ?> value='1'>
<?php
}

function sm_sql_logs_qt_select_render(  )
{
	$options = get_option( 'sm_sql_logs_options' );
?>
	<input type='checkbox' name='sm_sql_logs_options[qt_select]' <?php checked( $options['qt_select'], 1 ); ?> value='1'>
<?php
}

function sm_sql_logs_qt_update_render(  )
{
	$options = get_option( 'sm_sql_logs_options' );
?>
	<input type='checkbox' name='sm_sql_logs_options[qt_update]' <?php checked( $options['qt_update'], 1 ); ?> value='1'>
<?php
}

function sm_sql_logs_qt_delete_render(  )
{
	$options = get_option( 'sm_sql_logs_options' );
?>
	<input type='checkbox' name='sm_sql_logs_options[qt_delete]' <?php checked( $options['qt_delete'], 1 ); ?> value='1'>
<?php
}

function sm_sql_logs_qt_other_render(  )
{
	$options = get_option( 'sm_sql_logs_options' );
?>
	<input type='checkbox' name='sm_sql_logs_options[qt_other]' <?php checked( $options['qt_other'], 1 ); ?> value='1'>
<?php
}

function sm_sql_logs_use_sqlformatter_render(  )
{
	$options = get_option( 'sm_sql_logs_options' );
?>
	<label>Yes
		<input type='radio' name='sm_sql_logs_options[use_sqlformatter]' <?php checked( $options['use_sqlformatter'], 1 ); ?> value='1'>
	</label>
	/
	<label>
		<input type='radio' name='sm_sql_logs_options[use_sqlformatter]' <?php checked( $options['use_sqlformatter'], 0 ); ?> value='0'>
		No
	</label>

	<div class="sml_options_descr"><a href="http://jdorn.github.io/sql-formatter/" target="_blank">SqlFormatter</a> can crash browser when parsing long or quirky queries. Turn it off if pages with SqlFormatter don't want to load.</div>
<?php
}

function sm_sql_logs_max_new_queries_render(  )
{
	$options = get_option( 'sm_sql_logs_options' );
?>
	<input type='text' name='sm_sql_logs_options[max_new_queries]' value='<?php echo $options['max_new_queries']; ?>'>

	<div class="sml_options_descr">
		Leave blank if unlimited.<br />
		Value "0" (zero) will force plugin to stop collecting new logs.<br />
		If you don't want to permanently collect new logs, you can specify here how many new logs do you want. After that number, no new logs will be added.
	</div>
<?php
}

function sm_sql_logs_options_section_callback(  )
{

}



function sm_sql_logs_options_validation($input)
{
	$output = array();

	foreach( $input as $key => $i_value )
	{
			if($key == 'max_new_queries')
			{
				if(empty($i_value)) {
					$value = '';
				} else {
					$n = (int)$input[$key];

					if($n <= 0)
					{
						$value = '';
					}
					else
					{
						$value = $n;
					}
				}
			}
			else if($key == 'max_logs')
			{
				$value = (int)$input[$key];
			}
			else
			{
				$value = $input[$key];
			}

			$output[$key] = $value;
	}

	return apply_filters( 'sm_sql_logs_options_validation', $output, $input );
//	return $output;




}

?>