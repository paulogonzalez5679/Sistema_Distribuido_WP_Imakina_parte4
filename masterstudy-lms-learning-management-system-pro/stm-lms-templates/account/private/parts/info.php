<?php
/**
 * @var $current_user
 */


$current_user = (array) $current_user;
?>

<div class="stm_lms_user_side">

    <div class="stm-lms-user-avatar-edit ">
        <?php $my_avatar = get_user_meta($current_user['id'], 'stm_lms_user_avatar', true); ?>
        <input type="file"/>
        <?php if (!empty($my_avatar)): ?>
            <i class="lnricons-cross delete_avatar"></i>
        <?php endif; ?>
        <i class="lnricons-pencil"></i>
        <?php if (!empty($current_user['avatar'])): ?>
            <div class="stm-lms-user_avatar">
                <?php echo wp_kses_post($current_user['avatar']); ?>
            </div>
        <?php endif; ?>
    </div>

    <h3 class="student_name stm_lms_update_field__first_name"><?php echo esc_attr($current_user['login']); ?></h3>

    <div class="stm_lms_profile_buttons_set 2">
        <div class="stm_lms_profile_buttons_set__inner">

            <?php do_action('stm_lms_before_profile_buttons_all', $current_user); ?>

            <?php STM_LMS_Templates::show_lms_template('account/private/parts/messages_btn', array('current_user' => $current_user)); ?>


            <?php
            if (STM_LMS_Instructor::is_instructor()) {
                STM_LMS_Templates::show_lms_template('account/private/parts/create_announcement_btn', array('current_user' => $current_user));
            }
            ?>

            <?php STM_LMS_Templates::show_lms_template('account/private/parts/my_certificates_btn', array('current_user' => $current_user)); ?>

            <?php STM_LMS_Templates::show_lms_template('account/private/parts/edit_profile_btn', array('current_user' => $current_user)); ?>


            <?php

            if (STM_LMS_Instructor::is_instructor()) {
                do_action('stm_lms_after_profile_buttons', $current_user);
            }

            do_action('stm_lms_after_profile_buttons_all', $current_user); ?>

            <?php do_action('stm_lms_after_profile_buttons_block', $current_user); ?>
        </div>
    </div>

</div>