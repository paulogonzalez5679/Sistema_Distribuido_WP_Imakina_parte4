<?php
add_filter('wpcfto_field_addons', function () {
	return STM_LMS_PATH . '/settings/addons/fields/addons.php';
});