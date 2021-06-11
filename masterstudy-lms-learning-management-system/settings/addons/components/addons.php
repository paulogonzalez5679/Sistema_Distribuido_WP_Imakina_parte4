<div>

	<div class="stm-lms-addons" v-bind:class="{'loading' : loading}">
		<div class="stm-lms-addon" v-for="(addon, key) in addons_list" v-bind:class="{'active' : addons[key]}">
			<div class="wpcfto-addon__image">
				<img v-bind:src="addon.url"/>
			</div>
			<div class="wpcfto-addon__install">
                <h4 class="addon-name">{{addon.name}}</h4>
                <div class="addon-description">{{addon.description}}</div>
                <?php if(defined('STM_LMS_PRO_PATH')): ?>
                    <div class="wpcfto-admin-checkbox section_2-enable_courses_filter">
                        <label @click.prevent="enableAddon(key)">
                            <div class="wpcfto-admin-checkbox-wrapper is_toggle" v-bind:class="{'active' : addons[key]}">

                                <div class="wpcfto-checkbox-switcher"></div>
                                <input type="checkbox" name="enable_courses_filter" id="section_2-enable_courses_filter">
                            </div>
                        </label>
                    </div>
                    <a v-bind:href="addon.settings" class="addon-settings" target="_blank" v-if="addons[key] && addon.settings">
                        <i class="fa fa-cog"></i>
                    </a>
                <?php else: ?>
                    <a v-bind:href="addon.pro_url" class="btn stm-pro-btn" target="_blank">Buy now</a>
                <?php endif; ?>

			</div>
		</div>
	</div>
</div>