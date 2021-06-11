<?php
$forms = get_option('stm_lms_form_builder_forms', array());
$become_instructor_form = array();
if (class_exists('STM_LMS_Form_Builder') && !empty($forms) && is_array($forms)) {
    foreach ($forms as $form) {
        if ($form['slug'] === 'become_instructor') {
            $become_instructor_form = $form['fields'];
        }
    }
}

if (!empty($become_instructor_form)):
    $become_instructor_form = json_encode($become_instructor_form);
    ?>
    <script>
        window.becomeInstructorFormFields = <?php echo sanitize_text_field($become_instructor_form); ?>
    </script>
<?php endif; ?>

<div id="stm-lms-become-instructor" class="stm-lms-become-instructor">

	<div class="stm_lms_bi_wrapper">
        <form @submit.prevent="send()">
            <?php if (!empty($become_instructor_form)): ?>
                <div class="form-group" v-if="additionalFields.length" v-for="(field, index) in additionalFields">
                    <label class="heading_font" v-if="typeof field.label !== 'undefined'" v-html="field.label"></label>
                    <input class="form-control"
                           v-if="field.type === 'text' || field.type === 'tel' || field.type === 'email'"
                           :placeholder="field.placeholder ? field.placeholder : ''"
                           :required="field.required"
                           :type="field.type"
                           v-model="field.value"/>
                    <select class="form-control" v-if="field.type === 'select' && typeof field.choices !== 'undefined'"
                            :required="field.required"
                            v-model="field.value">
                        <option v-if="field.placeholder" v-html="field.placeholder"></option>
                        <option v-for="choice in field.choices" v-html="choice" v-if="choice !== ''"></option>
                    </select>
                    <label v-if="field.type === 'radio' && choice !== ''" v-for="(choice, index) in field.choices" class="radio-label">
                        <input type="radio" :name="field.id" v-bind:value="choice" :checked="index === 0" v-model="field.value" :required="field.required"/>
                        <span v-html="choice"></span>
                    </label>
                    <textarea class="form-control" v-model="field.value" v-if="field.type === 'textarea'"
                              :placeholder="field.placeholder ? field.placeholder : ''"
                              :required="field.required"></textarea>
                    <input type="checkbox" v-if="field.type === 'checkbox'" v-model="field.value" class="form-control checkbox-field"
                           :required="field.required"/>
                    <div class="file-wrap" v-if="field.type === 'file'">
                        <label class="file-browse-wrap">
                            <span class="file-browse">
                            <?php esc_html_e('Browse...', 'masterstudy-lms-learning-management-system'); ?>
                            </span>
                            <input type="file" :ref="'file-' + index" :accept="field.extensions ? field.extensions : '.jpeg,.jpg,.png,.mp4,.pdf'"
                                   @change="loadImage(index)" :required="field.required"/>
                            <span class="filename" v-if="typeof field.value !== 'undefined'" v-html="field.value.split('/').pop()"></span>
                            <span class="filename" v-else-if="!loading"><?php esc_html_e('Select file', 'masterstudy-lms-learning-management-system'); ?></span>
                            <span class="filename" v-else><?php esc_html_e('Loading...', 'masterstudy-lms-learning-management-system'); ?></span>
                        </label>
                        <i v-if="field.value" class="fas fa-times" @click="field.value = ''"></i>
                        <i v-else class="fas fa-paperclip"></i>
                    </div>
                    <div class="field-description" v-if="field.description" v-html="field.description"></div>
                </div>
            <?php else: ?>
                <div class="form-group" v-bind:class="{'error' : !degree_filled }">
                    <label class="heading_font"><?php esc_html_e('Degree', 'masterstudy-lms-learning-management-system'); ?></label>
                    <input class="form-control"
                           type="text"
                           name="degree"
                           v-model="degree"
                           placeholder="<?php esc_html_e('Enter degree', 'masterstudy-lms-learning-management-system'); ?>"/>
                </div>

                <div class="form-group" v-bind:class="{'error' : !expertize_filled }">
                    <label class="heading_font"><?php esc_html_e('Expertise', 'masterstudy-lms-learning-management-system'); ?></label>
                    <input class="form-control"
                           type="text"
                           name="expertize"
                           v-model="expertize"
                           placeholder="<?php esc_html_e('Enter Expertise', 'masterstudy-lms-learning-management-system'); ?>"/>
                </div>
            <?php endif; ?>

            <button type="submit"
                    class="btn btn-default"
                    :disabled="loading"
                    v-bind:class="{'loading': loading}">
                <span><?php esc_html_e('Send Application', 'masterstudy-lms-learning-management-system'); ?></span>
            </button>
        </form>

	</div>

	<transition name="slide-fade">
		<div class="stm-lms-message" v-bind:class="status" v-if="message">
			{{ message }}
		</div>
	</transition>

</div>