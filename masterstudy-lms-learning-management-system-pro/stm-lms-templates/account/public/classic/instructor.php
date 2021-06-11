<?php if (!defined('ABSPATH')) {
	exit;
} //Exit if accessed directly ?>

<?php
/**
 * @var $current_user
 */
?>

<div class="row">

    <div class="col-md-12 col-sm-12">
		<?php STM_LMS_Templates::show_lms_template('account/public/classic/parts/top_bar',
			['current_user' => $current_user]); ?>
    </div>

    <div class="col-md-12 col-sm-12">
        <div class="stm_lms_user_infoblock">
            <div class="row">
                <div class="col-md-3">
					<?php STM_LMS_Templates::show_lms_template('account/public/classic/instructor_parts/info',
						['current_user' => $current_user]); ?>
                </div>
                <div class="col-md-9">
					<?php STM_LMS_Templates::show_lms_template('account/private/parts/bio',
						['current_user' => $current_user]); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
		<?php STM_LMS_Templates::show_lms_template('account/public/instructor_parts/courses',
			['current_user' => $current_user]); ?>
    </div>

	<div class="col-md-12 col-sm-12">
		<div class="stm_lms_courses">

			<?php STM_LMS_Templates::show_lms_template('multi_instructor/co_courses/grid',
				['current_user_id' => $current_user['id']]); ?>

			<?php STM_LMS_Templates::show_lms_template('courses/load_more',
				['args' => ['posts_per_page' => 6]]); ?>

		</div>
	</div>

</div>