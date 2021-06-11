<?php
/**
 * @var $current_user
 */

$bio = get_user_meta($current_user['id'], 'description', true);
if(!empty($bio)):

	stm_lms_register_style('user_bio');
	?>

	<div class="stm_lms_user_bio">
		<h3><?php esc_html_e('Bio', 'masterstudy-lms-learning-management-system'); ?></h3>
        <div class="stm_lms_update_field__description"><?php echo wp_kses_post($bio); ?></div>

	</div>
<div class="stm_lms_user_bio class-btns">
	<?php
	if (STM_LMS_Instructor::is_instructor()):		
		if (is_user_logged_in()) :
			?>
			<a class="btn evaluacion-docente" href="<?php echo home_url() ?>/evaluacion-docente?nombre_instructor=<?php echo esc_attr($current_user['login']); ?>">evaluar docente</a>
			
			<?php
		endif;
	endif;
	
	if (STM_LMS_Instructor::is_instructor()):		
		if (is_user_logged_in()) :
			?>
			<a class="btn evaluacion-docente" href="mailto:<?php echo esc_attr($current_user['email']); ?>">Solicitar Asesoría</a>
			
			<?php
		endif;
	endif;
	?>
	</div>
	
	
<?php endif; ?>
