<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="lnx-help lenxel-content-section" id="lnx-activation" style="display: none;">
	<div id="activation" class="get-start"></div>
	<div class="adjust-s-on-mobile lnx-pd-r-30">
		<p class="lnx-font-poppins lnx-pd-t-15 lnx-pd-b-15 lnx-fs-16 lnx-lh-24 lnx-fw-400 lenxel-color-767171"><?php esc_html_e("Activate your plugin by connecting to the Lenxel AI portal. Your unique secret token provides secure access to our AI infrastructure and enables all pro features.","lenxel-core"); ?></p>
		<div class="lnx-group">
			<p class="lnx-font-poppins lnx-inline-flex lnx-pd-b-15 lnx-fs-20 lnx-lh-32 lnx-fw-600 lenxel-color-135730"><?php esc_html_e("Quick Activation Process", "lenxel-core"); ?></p>
			<a href="#" alt="upgrade to pro" onclick="<?php echo esc_attr(lenxel_get_template_restrict()->has_premium ? '' : 'lenxel_triggerPremium(); return false;'); ?>" class="lenxel-install-banner-button-sec lnx-m-t--12 lnx-remove-this lnx-float-r <?php echo esc_attr(lenxel_get_template_restrict()->has_premium ? 'has-premium' : ''); ?>" <?php echo esc_attr(lenxel_get_template_restrict()->has_premium ? 'disabled' : ''); ?>><?php esc_html_e("🚀 Go to portal","lenxel-core"); ?></a>
		</div>
		<div class="lnx-mobile-pd-t-30">
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 1:","lenxel-core"); ?> </span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Visit the Lenxel portal at", "lenxel-core"); ?> <a href="https://portal.lenxel.ai" class="lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("https://portal.lenxel.ai.", "lenxel-core"); ?></a></span></p>
			<div class=""><img style="width:400px;" src="<?php  echo esc_url(LENXEL_THEME_URL .'/images/portal.png');?>"></div>
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 2:", "lenxel-core");?> </span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Create your account on the portal.", "lenxel-core"); ?></span></p>
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 3:", "lenxel-core");?> </span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Enter your website's URL to complete the registration.","lenxel-core"); ?></span></p>
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 4:", "lenxel-core");?> </span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Choose a content generation plan that fits your needs.","lenxel-core"); ?></span></p>
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 5:", "lenxel-core");?> </span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Complete the purchase to add credits to your account.", "lenxel-core"); ?></span></p>
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 6:", "lenxel-core");?> </span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Find your new secret token in your portal dashboard.","lenxel-core"); ?></span></p>
			<p class="lnx-flex"><span class="lnx-ws-10 lenxel-color-000000 lnx-fs-20 lnx-lh-36 lnx-fw-700"><?php esc_html_e("Step 7:", "lenxel-core");?></span> <span class="lnx-ws-90 lenxel-color-767171 lnx-fs-16 lnx-lh-24 lnx-fw-400 lnx-mr-auto"><?php esc_html_e("Copy the token, paste it into the 'Secret Token' field below, and click Activate.","lenxel-core"); ?></span></p>
		</div>
		<div class="lnx-pd-t-50">
 				<form id="activation-form" method="post" action="#" data-url="<?php  echo esc_url(admin_url('admin-ajax.php')); ?>">
 					<p class="lenxel-color-000000 lnx-m-b-0 lnx-fs-20 lnx-lh-36 lnx-fw-400"><?php esc_html_e('Secret Token','lenxel-core'); ?></p>
 					<input id="activation-input" name="activation_input" value="<?php echo esc_attr(get_option( 'lenxel_activation_key' )); ?>" placeholder="Secret Token" style="width: 100%;height: 40px;"type="text">
 					<input id="activation-hidden" name="activation_hidden" value="passed" style="width: 70%;height: 40px;"type="hidden">
 					<input type="hidden" name="action" value="lenxel_api_key_actions">
 					<div class="success-div"></div>
					<button id="activation-button" class="lenxel-install-banner-button-sec" name="Activate"><?php esc_html_e('Activate','lenxel-core'); ?></button>
 					<p class="error"></p>
 					<?php wp_nonce_field('ajax-l-activation-nonce', 'ajax_l_activation_ajax'); ?>
 				</form>
 				<div id="snackbar"><?php esc_html_e("Some text some message...", "lenxel-core"); ?></div>
 			</div>
		<p class="lnx-font-poppins lnx-fs-24 lnx-lh-24 lnx-fw-400"><?php esc_html_e("Learn more about secret token from", "lenxel-core");?> <a href="#" onclick="lenxel_triggerDocumentation('#activation-key'); return false;" alt="documentation" class="lnx-fw-600 lenxel-color-135730"><?php esc_html_e("Lenxel Documentation","lenxel-core"); ?></a></p>
	</div>
</div>
