<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $current_user
 */

stm_lms_register_style('user');

stm_lms_register_style('account/user');
stm_lms_register_script('account/user');

if(empty($current_user)) $current_user = STM_LMS_User::get_current_user('', false, true);

$is_instructor = STM_LMS_Instructor::is_instructor($current_user['id']);
$tpl = ($is_instructor) ? 'instructor' : 'student';


STM_LMS_Templates::show_lms_template("account/public/{$tpl}", array('current_user' => $current_user));