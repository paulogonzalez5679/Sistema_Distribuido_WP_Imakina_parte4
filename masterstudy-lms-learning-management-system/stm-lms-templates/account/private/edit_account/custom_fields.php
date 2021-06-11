<?php
$forms = get_option('stm_lms_form_builder_forms', array());
$profile_form = array();
if (class_exists('STM_LMS_Form_Builder') && !empty($forms) && is_array($forms)) {
    foreach ($forms as $form) {
        if ($form['slug'] === 'profile_form') {
            $profile_form = $form['fields'];
        }
    }
}
if (!empty($profile_form)):
    $profile_form = json_encode($profile_form);
    ?>
    <script>
        window.profileForm = <?php echo sanitize_text_field($profile_form); ?>
    </script>
<?php endif; ?>
<div class="row" v-if="additionalFields.length" v-for="(field, index) in additionalFields">
    <div class="col-md-12">
        <div class="form-group">
            <label class="heading_font" v-if="typeof field.label !== 'undefined'" v-html="field.label"></label>
            <input class="form-control"
                   v-if="field.type === 'text' || field.type === 'tel' || field.type === 'email'"
                   :placeholder="field.placeholder ? field.placeholder : ''"
                   :required="field.required"
                   :type="field.type"
                   v-bind:value="!data.meta[field.id] || data.meta[field.id] === 'false' ? false : true"
                   v-model="data.meta[field.id]"/>
            <select class="form-control disable-select" v-if="field.type === 'select' && typeof field.choices !== 'undefined'"
                    :required="field.required"
                    v-model="data.meta[field.id]">
                <option v-if="field.placeholder" v-html="field.placeholder"></option>
                <option v-for="choice in field.choices" v-html="choice" v-if="choice !== ''"></option>
            </select>
            <label v-if="field.type === 'radio' && choice !== ''" v-for="(choice, index) in field.choices" class="radio-label">
                <input type="radio" name="data.meta[field.id]" v-bind:value="choice" :checked="index === 0" v-model="data.meta[field.id]" :required="field.required"/>
                <span v-html="choice"></span>
            </label>
            <textarea class="form-control" v-model="data.meta[field.id]" v-if="field.type === 'textarea'"
                      :placeholder="field.placeholder ? field.placeholder : ''"
                      :required="field.required"></textarea>
            <input type="checkbox" v-if="field.type === 'checkbox'" v-model="data.meta[field.id]" class="form-control checkbox-field"
                   :required="field.required"/>
            <div class="file-wrap" v-if="field.type === 'file'">
                <label class="file-browse-wrap">
                    <span class="file-browse">
                    <?php esc_html_e('Browse...', 'masterstudy-lms-learning-management-system'); ?>
                    </span>
                    <input v-if="!data.meta[field.id]" type="file" :ref="'file-' + index" :accept="field.extensions ? field.extensions : '.jpeg,.jpg,.png,.mp4,.pdf'"
                           @change="loadImage(index)" :required="field.required"/>
                    <span class="filename" v-if="typeof field.value !== 'undefined' && field.value" v-html="field.value.split('/').pop()"></span>
                    <span class="filename" v-else-if="!loading"><?php esc_html_e('Select file', 'masterstudy-lms-learning-management-system'); ?></span>
                    <span class="filename" v-else><?php esc_html_e('Loading...', 'masterstudy-lms-learning-management-system'); ?></span>
                </label>
                <i v-if="data.meta[field.id]" class="fas fa-times" @click="data.meta[field.id] = ''"></i>
                <i v-else class="fas fa-paperclip"></i>
                <div v-if="data.meta[field.id]" class="file-value" v-html="data.meta[field.id]"></div>
                <input type="hidden" v-model="field.extensions" />
            </div>
            <div class="field-description" v-if="field.description" v-html="field.description"></div>
        </div>
    </div>

</div>