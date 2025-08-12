<div class="lnx-help lenxel-content-section" id="lnx-activation" style="display: none;">
	<div id="activation" class="get-start"></div>
	<div class="adjust-s-on-mobile lnx-pd-r-30">
		<p class="lnx-font-poppins lnx-pd-t-15 lnx-pd-b-15 lnx-fs-16 lnx-lh-24 lnx-fw-400 lenxel-color-767171"><?php esc_html_e("In order to activate the premium features of Lenxel you must obtain and enter an activation key.","lenxel-core"); ?></p>
		<div class="">
			<p class="lnx-font-poppins lnx-inline-flex lnx-pd-b-15 lnx-fs-20 lnx-lh-32 lnx-fw-600 lenxel-color-135730"><?php esc_html_e("Steps to find the activation key for Lenxel Premium", "lenxel-core"); ?></p>
			<a href="#" alt="upgrade to premium" onclick="<?php echo esc_attr(lenxel_get_template_restrict()->has_premium ? '' : 'lenxel_triggerPremium(); return false;'); ?>" class="lenxel-install-banner-button-sec lnx-m-t--12 lnx-remove-this lnx-float-r <?php echo esc_attr(lenxel_get_template_restrict()->has_premium ? 'has-premium' : ''); ?>" <?php echo esc_attr(lenxel_get_template_restrict()->has_premium ? 'disabled' : ''); ?>><?php esc_html_e("Upgrade to premium","lenxel-core"); ?></a>
		</div>
		<div class="lnx-mobile-pd-t-30">
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 1:","lenxel-core"); ?> </span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Click the 'Upgrade to Premium' button.", "lenxel-core"); ?></span></p>
			<div class=""><img style="width:400px;" src="<?php  echo esc_url(LENXEL_THEME_URL .'/images/premium.png');?>"></div>
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 2:", "lenxel-core");?> </span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Enter payment information and purchase premium access.", "lenxel-core"); ?></span></p>
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 3:", "lenxel-core");?> </span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Follow instructions on the page to retrieve your designated Activation Key","lenxel-core"); ?></span></p>
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 4:", "lenxel-core");?></span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Copy the provided Activation Key and enter it below.","lenxel-core"); ?></span></p>
		</div>
		<div class="lnx-pd-t-50">
 				<form id="activation-form" method="post" action="#" data-url="<?php  echo esc_url(admin_url('admin-ajax.php')); ?>">
 					<p class="lenxel-color-000000 lnx-m-b-0 lnx-fs-20 lnx-lh-36 lnx-fw-400"><?php esc_html_e('Activation Key','lenxel-core'); ?></p>
 					<input id="activation-input" name="activation_input" value="<?php echo esc_attr(get_option( 'lenxel_activation_key' )); ?>" placeholder="Activation Key" style="width: 100%;height: 40px;"type="text">
 					<input id="activation-hidden" name="activation_hidden" value="passed" style="width: 70%;height: 40px;"type="hidden">
 					<input type="hidden" name="action" value="lenxel_activation_key_actions">
 					<button id="activation-button" class="lenxel-install-banner-button-sec" name="Activate"><?php esc_html_e('Activate','lenxel-core'); ?></button>
 					<p class="error"></p>
 					<?php wp_nonce_field('ajax-l-activation-nonce', 'ajax_l_activation_ajax'); ?>
 				</form>
 				<div id="snackbar"><?php esc_html_e("Some text some message..", "lenxel-core"); ?></div>
 				<style>
 					@-webkit-keyframes fadein {
 					from {bottom: 0; opacity: 0;} 
 					to {bottom: 30px; opacity: 1;}
 					}

 					@keyframes fadein {
 					from {bottom: 0; opacity: 0;}
 					to {bottom: 30px; opacity: 1;}
 					}

 					@-webkit-keyframes fadeout {
 					from {bottom: 30px; opacity: 1;} 
 					to {bottom: 0; opacity: 0;}
 					}

 					@keyframes fadeout {
 					from {bottom: 30px; opacity: 1;}
 					to {bottom: 0; opacity: 0;}
 					}
 				</style>
 			</div>
		<p class="lnx-font-poppins lnx-fs-24 lnx-lh-24 lnx-fw-400"><?php esc_html_e("Learn more about activation key from", "lenxel-core");?> <a href="#" onclick="lenxel_triggerDocumentation('#activation-key'); return false;" alt="documentation" class="lnx-fw-600 lenxel-color-135730"><?php esc_html_e("Lenxel Documentation","lenxel-core"); ?></a></p>
	</div>
</div>
