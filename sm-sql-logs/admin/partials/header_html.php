<?php

/**
 * HTML header for Admin area
 *
 * @link       https://profiles.wordpress.org/mi7osz/
 * @since      1.0.0
 *
 * @package    Sm_sql_logs
 * @subpackage Sm_sql_logs/admin/partials
 */

	$head = FALSE;

	if(!empty($att))
	{
		if(!empty($att['head']))
		{
			$head = ' / '.$att['head'];
		}
	}
?>
<h3>SM - SQL logs<?php echo $head; ?></h3>
<hr />