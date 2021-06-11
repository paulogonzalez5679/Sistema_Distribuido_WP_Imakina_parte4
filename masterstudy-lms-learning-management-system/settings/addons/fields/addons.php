<?php
/**
 * @var $field
 * @var $field_name
 * @var $section_name
 *
 */

$field_key = "data['{$section_name}']['fields']['{$field_name}']";

include STM_LMS_PATH .'/settings/addons/components_js/addons.php';

$addons_enabled = get_option('stm_lms_addons', array());
?>

<stm-addons v-bind:enabled_addons='<?php echo json_encode($addons_enabled); ?>'></stm-addons>

