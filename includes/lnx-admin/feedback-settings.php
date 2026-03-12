<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check user capabilities
if (!current_user_can('manage_options')) {
   return;
}

// Save settings
if (isset($_POST['lenxel_settings_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['lenxel_settings_nonce'])), 'lenxel_save_settings')) {
   $feedback_enabled = isset($_POST['lenxel_enable_deactivation_feedback']) ? '1' : '0';
   update_option('lenxel_enable_deactivation_feedback', $feedback_enabled);
   echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved successfully.', 'lenxel-core') . '</p></div>';
}

$feedback_enabled = get_option('lenxel_enable_deactivation_feedback', '0');

// Enqueue feedback settings page styles
wp_enqueue_style('lenxel-feedback-settings', LENXEL_PLUGIN_URL . 'assets/css/ai-page.css', array(), '1.0.0');
?>
<div class="lnx-help lenxel-content-section" id="lnx-feedback-settings" style="display: none;">
   <div id="feedback-settings" class="get-start"></div>
   <div class="adjust-s-on-mobile lnx-pd-r-30">
      <div class="lnx-pd-t-15">
         <p class="lnx-font-poppins lnx-fs-20 lnx-lh-32 lnx-fw-600 lenxel-color-135730" style="margin:0;padding:0 0 15px 0;">
            <?php esc_html_e('Privacy & Feedback Settings', 'lenxel-core'); ?>
         </p>
         <p class="lnx-font-poppins lnx-fs-16 lnx-lh-24 lnx-fw-400 lenxel-color-767171">
            <?php esc_html_e('Manage your privacy preferences and help improve Lenxel Core by optionally sharing feedback when you deactivate the plugin.', 'lenxel-core'); ?>
         </p>
      </div>

      <form method="post" action="" style="margin-top: 30px;">
         <?php wp_nonce_field('lenxel_save_settings', 'lenxel_settings_nonce'); ?>
         
         <div class="lenxel-wp-wide-block">
            <div class="lnx-row-grid lenxel-wp-help-section">
               <div class="lnx-col-grid lnx-m-b-20" style="width: 100%;">
                  <div class="lenxel-wp-large-block">
                     <div class="lenxel-wp-block-outer">
                        <div class="lenxel-wp-block-inner">
                           <p class="lenxel-wp-block-title lnx-font-poppins lnx-fs-20 lnx-lh-32 lnx-fw-600 lenxel-color-135730">
                              <?php esc_html_e('Deactivation Feedback', 'lenxel-core'); ?>
                           </p>
                           <div style="margin-top: 20px;">
                              <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                                 <input 
                                    type="checkbox" 
                                    name="lenxel_enable_deactivation_feedback" 
                                    id="lenxel_enable_deactivation_feedback" 
                                    value="1" 
                                    <?php checked($feedback_enabled, '1'); ?>
                                    style="margin-top: 3px;"
                                 />
                                 <span class="lnx-font-poppins lnx-fs-16 lnx-lh-24 lnx-fw-400 lenxel-color-000000">
                                    <?php esc_html_e('Help improve Lenxel Core by sharing why you deactivate the plugin', 'lenxel-core'); ?>
                                 </span>
                              </label>
                              
                              <div style="margin-top: 15px; margin-left: 30px;">
                                 <p class="lnx-font-poppins lnx-fs-14 lnx-lh-20 lnx-fw-400 lenxel-color-767171" style="margin: 0 0 10px 0;">
                                    <?php
                                    /* translators: 1: opening link tag, 2: closing link tag, 3: opening link tag, 4: closing link tag */
                                    echo wp_kses(
                                       sprintf(
                                          __('When enabled, you\'ll be asked (optionally) to share feedback when deactivating the plugin. This helps us improve Lenxel Core. Data collected: deactivation reason, optional comment, optional email address, and your site URL. %1$sTerms of Service%2$s | %3$sPrivacy Policy%4$s', 'lenxel-core'),
                                          '<a href="https://lenxel.ai/terms-of-service" target="_blank" style="color: #007cba; text-decoration: underline;">',
                                          '</a>',
                                          '<a href="https://lenxel.ai/privacy-and-policy" target="_blank" style="color: #007cba; text-decoration: underline;">',
                                          '</a>'
                                       ),
                                       array(
                                          'a' => array(
                                             'href' => array(),
                                             'target' => array(),
                                             'style' => array()
                                          )
                                       )
                                    );
                                    ?>
                                 </p>
                                 <p class="lnx-font-poppins lnx-fs-14 lnx-lh-20 lnx-fw-400 lenxel-color-767171" style="margin: 0;">
                                    <?php esc_html_e('This is completely optional and disabled by default. You can enable or disable it at any time.', 'lenxel-core'); ?>
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="lenxel-wp-block-button-wrapper lnx-pd-t-40">
                        <button type="submit" class="lenxel-install-banner-button" style="padding: 10px 30px; cursor: pointer;">
                           <?php esc_html_e('Save Settings', 'lenxel-core'); ?>
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </form>

      <hr style="margin: 40px 0; border: 0; border-top: 1px solid #ddd;">

      <div class="lnx-pd-t-15">
         <p class="lnx-font-poppins lnx-fs-20 lnx-lh-32 lnx-fw-600 lenxel-color-135730" style="margin:0;padding:0 0 15px 0;">
            <?php esc_html_e('About External Services', 'lenxel-core'); ?>
         </p>
         <p class="lnx-font-poppins lnx-fs-16 lnx-lh-24 lnx-fw-400 lenxel-color-767171">
            <?php
            echo wp_kses(
               __('Lenxel Core uses several external services to provide its functionality. For complete details about what data is sent, when, and to which services, please see the <strong>External Services</strong> section in the plugin\'s readme.txt file.', 'lenxel-core'),
               array('strong' => array())
            );
            ?>
         </p>
         <div style="margin-top: 20px;">
            <p class="lnx-font-poppins lnx-fs-16 lnx-lh-24 lnx-fw-600 lenxel-color-135730">
               <?php esc_html_e('External Services Used:', 'lenxel-core'); ?>
            </p>
            <ul class="ul" style="margin-top: 10px;">
               <li class="li">
                  <span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e('Lenxel AI API: ', 'lenxel-core'); ?></span>
                  <span><?php esc_html_e('AI-powered course content generation (user-initiated)', 'lenxel-core'); ?></span>
               </li>
               <li class="li">
                  <span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e('Google Maps API: ', 'lenxel-core'); ?></span>
                  <span><?php esc_html_e('Interactive maps for course locations', 'lenxel-core'); ?></span>
               </li>
               <li class="li">
                  <span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e('Google Fonts API: ', 'lenxel-core'); ?></span>
                  <span><?php esc_html_e('Custom typography fonts', 'lenxel-core'); ?></span>
               </li>
               <li class="li">
                  <span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e('Vimeo API: ', 'lenxel-core'); ?></span>
                  <span><?php esc_html_e('Video thumbnail retrieval', 'lenxel-core'); ?></span>
               </li>
               <li class="li">
                  <span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e('Lenxel User Portal: ', 'lenxel-core'); ?></span>
                  <span><?php esc_html_e('API key and AI credit management', 'lenxel-core'); ?></span>
               </li>
               <li class="li">
                  <span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e('Redux.io Custom Fonts API: ', 'lenxel-core'); ?></span>
                  <span><?php esc_html_e('Font file conversion (vendor service)', 'lenxel-core'); ?></span>
               </li>
               <li class="li">
                  <span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e('Feedback Service: ', 'lenxel-core'); ?></span>
                  <span><?php esc_html_e('Optional deactivation feedback (disabled by default, requires opt-in)', 'lenxel-core'); ?></span>
               </li>
            </ul>
         </div>
         <div style="margin-top: 20px;">
            <a href="https://lenxel.ai/external-services-documentation" target="_blank" class="lenxel-install-banner-button" style="display: inline-block; padding: 10px 20px; text-decoration: none;">
               <?php esc_html_e('View Full External Services Documentation', 'lenxel-core'); ?>
            </a>
         </div>
      </div>
   </div>
</div>
