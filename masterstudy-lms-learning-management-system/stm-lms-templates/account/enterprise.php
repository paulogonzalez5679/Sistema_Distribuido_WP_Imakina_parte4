<?php
$enterprise_form = array();
if (class_exists('STM_LMS_Form_Builder')) {
    $enterprise_form = STM_LMS_Form_Builder::get_enterprise_form_fields();
}
if (!empty($enterprise_form)):
    $enterprise_form = json_encode($enterprise_form);
    ?>
    <script>
        window.enterpriseFormFields = <?php echo sanitize_text_field($enterprise_form); ?>
    </script>
<?php endif; ?>

<div id="stm-lms-enterprise" class="stm-lms-enterprise">

    <div class="stm_lms_enterprise_wrapper">
        <form @submit.prevent="send()">
            <?php if (!empty($enterprise_form)): ?>
                <div class="form-group" v-if="additionalFields.length > 0" v-for="(field, index) in additionalFields">
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
                    <input type="checkbox" v-if="field.type === 'checkbox'" v-model="field.value"
                           class="form-control checkbox-field"
                           :required="field.required"/>
                    <div class="file-wrap" v-if="field.type === 'file'">
                        <label class="file-browse-wrap">
                            <span class="file-browse">
                            <?php esc_html_e('Browse...', 'masterstudy-lms-learning-management-system'); ?>
                            </span>
                            <input type="file" :ref="'file-' + index" :accept="field.extensions ? field.extensions : '.jpeg,.jpg,.png,.mp4,.pdf'"
                                   @change="loadImage(index)" :required="field.required"/>
                            <input type="hidden" v-model="field.extensions" />
                            <span class="filename" v-if="typeof field.value !== 'undefined' && field.value" v-html="field.value.split('/').pop()"></span>
                            <span class="filename" v-else-if="!loading"><?php esc_html_e('Select file', 'masterstudy-lms-learning-management-system'); ?></span>
                            <span class="filename" v-else><?php esc_html_e('Loading...', 'masterstudy-lms-learning-management-system'); ?></span>
                        </label>
                        <i v-if="field.value" class="fas fa-times" @click="field.value = ''"></i>
                        <i v-else class="fas fa-paperclip"></i>
                    </div>
                    <div class="field-description" v-if="field.description" v-html="field.description"></div>
                </div>
            <?php else: ?>
                <div class="form-group">
                    <label class="heading_font"><?php esc_html_e('Name', 'masterstudy-lms-learning-management-system'); ?></label>
                    <input class="form-control"
                           type="text"
                           name="name"
                           v-model="name"
                           placeholder="<?php esc_html_e('Enter your name', 'masterstudy-lms-learning-management-system'); ?>"/>
                </div>

                <div class="form-group">
                    <label class="heading_font"><?php esc_html_e('E-mail', 'masterstudy-lms-learning-management-system'); ?></label>
                    <input class="form-control"
                           type="text"
                           name="email"
                           v-model="email"
                           placeholder="<?php esc_html_e('Enter Your Email', 'masterstudy-lms-learning-management-system'); ?>"/>
                </div>

                <div class="form-group">
                    <label class="heading_font"><?php esc_html_e('Message', 'masterstudy-lms-learning-management-system'); ?></label>
                    <textarea class="form-control"
                              type="text"
                              name="text"
                              v-model="text"
                              placeholder="<?php esc_html_e('Enter Your Message', 'masterstudy-lms-learning-management-system'); ?>"></textarea>
                </div>
            <?php endif; ?>
            <button type="submit"
                    class="btn btn-default"
                    :disabled="loading"
                    v-bind:class="{'loading': loading}">
                <span><?php esc_html_e('Send Enquiry', 'masterstudy-lms-learning-management-system'); ?></span>
            </button>
        </form>
    </div>

    <transition name="slide-fade">
        <div class="stm-lms-message" v-bind:class="status" v-if="message">
            {{ message }}
        </div>
    </transition>

</div>