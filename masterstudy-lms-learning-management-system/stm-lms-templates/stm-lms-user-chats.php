<?php if (!defined('ABSPATH')) exit; //Exit if accessed directly ?>

<?php get_header();

$lms_current_user = STM_LMS_User::get_current_user('', true, true);

$tpl = 'account/private/chat/main';

stm_lms_register_style('user');
do_action('stm_lms_template_main');

$style = STM_LMS_Options::get_option('profile_style', 'default');

?>
    <div class="stm-lms-wrapper">
        <div class="container">

            <?php if ($style === 'classic'):
                STM_LMS_Templates::show_lms_template(
                    'account/private/classic/parts/header',
                    array('current_user' => $lms_current_user)
                );

            endif; ?>

            <?php if (!empty($tpl)) STM_LMS_Templates::show_lms_template($tpl, array('current_user' => $lms_current_user)); ?>
        </div>
    </div>

<?php get_footer(); ?>