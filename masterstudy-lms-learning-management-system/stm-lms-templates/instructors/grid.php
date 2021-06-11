<?php

stm_lms_register_style('user');
stm_lms_register_style('instructors_grid');

$user_args = array(
	'role'   => STM_LMS_Instructor::role(),
	'number' => -1
);

$user_query = new WP_User_Query($user_args);

if (!empty($user_query->get_results())) : ?>
    <div class="stm_lms_instructors__grid">
		<?php foreach ($user_query->get_results() as $user):
			$user_profile_url = STM_LMS_User::user_public_page_url($user->ID);
			$user = STM_LMS_User::get_current_user($user->ID, false, true);
			$rating = STM_LMS_Instructor::my_rating_v2($user);
			?>
            <div class="person" style="padding: 32px 15px 15px; border-radius: 9px; width: 25%;">
                <a href="<?php echo esc_url($user_profile_url); ?>" class="stm_lms_instructors__singles">
                    <div class="stm_lms_user_side">

                        <?php if (!empty($user['avatar'])): ?>
                            <div class="stm-lms-user_avatar">
                                <?php echo wp_kses_post($user['avatar']); ?>
                            </div>
                        <?php endif; ?>

                        <h3><?php echo esc_attr($user['login']); ?></h3>
                        
                        <?php if (!empty($user['meta']['position'])): ?>
                            <h5><?php echo sanitize_text_field($user['meta']['position']); ?></h5>
                        <?php endif; ?>

                        <?php if (!empty($user['meta']['description'])): ?>
                            <hr class="solid">
                            <h5><?php echo wp_trim_words( $user['meta']['description'], 20, '...' ); ?></h5>
                        <?php endif; ?>
                            

                        <?php if (!empty($rating['total'])): ?>
                            <div class="stm-lms-user_rating ">
                                <div class="star-rating star-rating__big">
                                    <span style="width: <?php echo floatval($rating['percent']); ?>%;"></span>
                                </div>
                                <strong class="rating heading_font"><?php echo floatval($rating['average']); ?></strong>
                                <div class="stm-lms-user_rating__total">
                                    <?php echo sanitize_text_field($rating['total_marks']); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </a>
                <div class="social" style="padding: 2px 15px 15px;">

                    <a href="mailto:<?php echo sanitize_text_field($user['email']); ?>" class="iconos social-icons">
                                <i class="fas fa-envelope"></i>
                    </a>
                    <?php 
                    $socials = array('facebook', 'twitter', 'instagram', 'google-plus'); 
                    $fields = STM_LMS_User::extra_fields();
                    ?>
                    <?php foreach ($socials as $social): ?>
                        <?php if (!empty($user['meta'][$social])): ?>
                            <a href="<?php echo esc_url($user['meta'][$social]); ?>" target="_blank" class="iconos social-icons">
                                <i class="fab fa-<?php echo esc_attr($fields[$social]['icon']) ?>"></i>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <a href="https://api.whatsapp.com/send?phone=<?php echo sanitize_text_field($user['meta']['whatsapp']); ?>&text=Hola,%20soy%20estudiante%20en%20imakina%20elearning%20y%20necesito%20informaci%C3%B3n%20m%C3%A1s%20sobre%20uno%20de%20sus%20cursos" target="_blank" class="iconos social-icons">
                                <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
            
            
            
		<?php endforeach; ?>
    </div>
<?php endif; ?>