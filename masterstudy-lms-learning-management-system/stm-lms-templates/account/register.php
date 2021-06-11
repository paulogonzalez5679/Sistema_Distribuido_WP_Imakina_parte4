<?php
stm_lms_register_style('register');
enqueue_register_script();
$r_enabled = STM_LMS_Helpers::g_recaptcha_enabled();

$disable_instructor = STM_LMS_Options::get_option('register_as_instructor', false);

if ($r_enabled):
    $recaptcha = STM_LMS_Helpers::g_recaptcha_keys();
endif;

$site_key = (!empty($recaptcha['public'])) ? $recaptcha['public'] : '';

if (class_exists('STM_LMS_Form_Builder')):
    $additional_forms = STM_LMS_Form_Builder::register_form_fields();
    $register_form = $additional_forms['register'];
    $become_instructor = $additional_forms['become_instructor'];
    ?>
    <script>
        window.additionalRegisterFields = <?php echo sanitize_text_field(json_encode($register_form)); ?>;
        window.additionalInstructorsFields = <?php echo sanitize_text_field(json_encode($become_instructor)); ?>;
    </script>
<?php
endif;
?>

<div id="stm-lms-register"
     class="vue_is_disabled"
     v-init="site_key = '<?php echo stm_lms_filtered_output($site_key); ?>'"
     v-bind:class="{'is_vue_loaded' : vue_loaded}">
    <h3><?php esc_html_e('Sign Up', 'masterstudy-lms-learning-management-system'); ?></h3>

    <form @submit.prevent="register()" class="stm_lms_register_wrapper">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="heading_font"><?php esc_html_e('Username', 'masterstudy-lms-learning-management-system'); ?></label>
                    <input class="form-control"
                           type="text"
                           name="login"
                           v-model="login"
                           placeholder="<?php esc_html_e('Enter username', 'masterstudy-lms-learning-management-system'); ?>"/>
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="heading_font"><?php esc_html_e('E-mail', 'masterstudy-lms-learning-management-system'); ?></label>
                    <input class="form-control"
                           type="email"
                           name="email"
                           v-model="email"
                           placeholder="<?php esc_html_e('Enter your E-mail', 'masterstudy-lms-learning-management-system'); ?>"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="heading_font"><?php esc_html_e('Password', 'masterstudy-lms-learning-management-system'); ?></label>
                    <input class="form-control"
                           type="password"
                           name="password"
                           v-model="password"
                           placeholder="<?php esc_html_e('Enter password', 'masterstudy-lms-learning-management-system'); ?>"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="heading_font"><?php esc_html_e('Password again', 'masterstudy-lms-learning-management-system'); ?></label>
                    <input class="form-control"
                           type="password"
                           name="password_re"
                           v-model="password_re"
                           placeholder="<?php esc_html_e('Confirm password', 'masterstudy-lms-learning-management-system'); ?>"/>
                </div>
            </div>
        </div>
        <div class="row additional-fields" v-if="additionalRegisterFields.length"
             v-for="(field, index) in additionalRegisterFields">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="heading_font" v-if="typeof field.label !== 'undefined'" v-html="field.label"></label>
                    <input class="form-control"
                           v-if="field.type === 'text' || field.type === 'tel' || field.type === 'email'"
                           :placeholder="field.placeholder ? field.placeholder : ''"
                           :required="field.required"
                           :type="field.type"
                           v-model="field.value"/>
                    <select class="form-control disable-select"
                            v-if="field.type === 'select' && typeof field.choices !== 'undefined'"
                            :required="field.required"
                            v-model="field.value">
                        <option v-if="field.placeholder" v-html="field.placeholder"></option>
                        <option v-for="choice in field.choices" v-html="choice" v-if="choice !== ''"></option>
                    </select>
                    <label v-if="field.type === 'radio' && choice !== ''" v-for="(choice, index) in field.choices"
                           class="radio-label">
                        <input type="radio" :name="field.id" v-bind:value="choice" :checked="index === 0"
                               v-model="field.value" :required="field.required"/>
                        <span v-html="choice"></span>
                    </label>
                    <textarea class="form-control" v-model="field.value" v-if="field.type === 'textarea'"
                              :placeholder="field.placeholder ? field.placeholder : ''"
                              :required="field.required"></textarea>
                    <input type="checkbox" v-if="field.type === 'checkbox'" v-model="field.value"
                           class="form-control checkbox-field"
                           :required="field.required"/>
                    <div class="file-wrap" v-if="field.type === 'file'">
                        <label class="file-browse-wrap">
                            <span class="file-browse">
                            <?php esc_html_e('Browse...', 'masterstudy-lms-learning-management-system'); ?>
                            </span>
                            <input type="file" :ref="'file-' + index"
                                   :accept="field.extensions ? field.extensions : '.jpeg,.jpg,.png,.mp4,.pdf'"
                                   @change="loadImage(index, 'register')" :required="field.required"/>
                            <input type="hidden" v-model="field.extensions"/>
                            <span class="filename" v-if="typeof field.value !== 'undefined' && field.value" v-html="field.value.split('/').pop()"></span>
                            <span class="filename" v-else-if="!loading"><?php esc_html_e('Select file', 'masterstudy-lms-learning-management-system'); ?></span>
                            <span class="filename" v-else><?php esc_html_e('Loading...', 'masterstudy-lms-learning-management-system'); ?></span>
                        </label>
                        <i v-if="field.value" class="fas fa-times" @click="field.value = ''"></i>
                        <i v-else class="fas fa-paperclip"></i>
                    </div>
                    <div class="field-description" v-if="field.description" v-html="field.description"></div>
                </div>
            </div>
        </div>
        <transition name="slide-fade">
            <div class="row" v-if="become_instructor && !additionalInstructorsFields.length">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="heading_font"><?php esc_html_e('Degree', 'masterstudy-lms-learning-management-system'); ?></label>
                        <input class="form-control"
                               type="text"
                               name="degree"
                               v-model="degree"
                               placeholder="<?php esc_html_e('Enter Your Degree', 'masterstudy-lms-learning-management-system'); ?>"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="heading_font"><?php esc_html_e('Expertise', 'masterstudy-lms-learning-management-system'); ?></label>
                        <input class="form-control"
                               type="text"
                               name="expertize"
                               v-model="expertize"
                               placeholder="<?php esc_html_e('Enter your Expertize', 'masterstudy-lms-learning-management-system'); ?>"/>
                    </div>
                </div>
            </div>
        </transition>

        <div class="row additional-fields" v-if="become_instructor && additionalInstructorsFields.length"
             v-for="(field, index) in additionalInstructorsFields">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="heading_font" v-if="typeof field.label !== 'undefined'" v-html="field.label"></label>
                    <input class="form-control"
                           v-if="field.type === 'text' || field.type === 'tel' || field.type === 'email'"
                           :placeholder="field.placeholder ? field.placeholder : ''"
                           :required="field.required"
                           :type="field.type"
                           v-model="field.value"/>
                    <select class="form-control disable-select"
                            v-if="field.type === 'select' && typeof field.choices !== 'undefined'"
                            :required="field.required"
                            v-model="field.value">
                        <option v-if="field.placeholder" v-html="field.placeholder"></option>
                        <option v-for="choice in field.choices" v-html="choice" v-if="choice !== ''"></option>
                    </select>
                    <label v-if="field.type === 'radio' && choice !== ''" v-for="(choice, index) in field.choices"
                           class="radio-label">
                        <input type="radio" :name="field.id" v-bind:value="choice" :checked="index === 0"
                               v-model="field.value" :required="field.required"/>
                        <span v-html="choice"></span>
                    </label>
                    <textarea class="form-control" v-model="field.value" v-if="field.type === 'textarea'"
                              :placeholder="field.placeholder ? field.placeholder : ''"
                              :required="field.required"></textarea>
                    <input type="checkbox" v-if="field.type === 'checkbox'" v-model="field.value"
                           class="form-control checkbox-field"
                           :required="field.required"/>
                    <div class="file-wrap" v-if="field.type === 'file'">
                        <label class="file-browse-wrap">
                            <span class="file-browse">
                            <?php esc_html_e('Browse...', 'masterstudy-lms-learning-management-system'); ?>
                            </span>
                            <input type="file" :ref="'file-' + index"
                                   :accept="field.extensions ? field.extensions : '.jpeg,.jpg,.png,.mp4,.pdf'"
                                   @change="loadImage(index, 'becomeInstructor')" :required="field.required"/>
                            <input type="hidden" v-model="field.extensions"/>
                            <span class="filename" v-if="typeof field.value !== 'undefined' && field.value" v-html="field.value.split('/').pop()"></span>
                            <span class="filename" v-else-if="!loading"><?php esc_html_e('Select file', 'masterstudy-lms-learning-management-system'); ?></span>
                            <span class="filename" v-else><?php esc_html_e('Loading...', 'masterstudy-lms-learning-management-system'); ?></span>
                        </label>
                        <i v-if="field.value" class="fas fa-times" @click="field.value = ''"></i>
                        <i v-else class="fas fa-paperclip"></i>
                    </div>
                    <div class="field-description" v-if="field.description" v-html="field.description"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <?php STM_LMS_Templates::show_lms_template('gdpr/privacy_policy'); ?>

                <?php do_action('stm_lms_register_custom_fields'); ?>

                <div class="stm_lms_register_wrapper__actions">

                    <?php ?>

                    <?php if (!$disable_instructor): ?>
                        <label class="stm_lms_styled_checkbox">
                            <span class="stm_lms_styled_checkbox__inner">
                                <input type="checkbox"
                                       name="become_instructor"
                                       v-model="become_instructor"/>
                                <span><i class="fa fa-check"></i> </span>
                            </span>
                            <span><?php esc_html_e('Register as Instructor', 'masterstudy-lms-learning-management-system'); ?></span>
                        </label>
                    <?php endif; ?>

                    <button type="submit"
                            class="btn btn-default"
                            :disabled="loading"
                            v-bind:class="{'loading': loading}">
                        <span><?php esc_html_e('Register', 'masterstudy-lms-learning-management-system'); ?></span>
                    </button>

                </div>

            </div>
        </div>

    </form>

    <transition name="slide-fade">
        <div class="stm-lms-message" v-bind:class="status" v-if="message">
            {{ message }}
        </div>
    </transition>
</div>