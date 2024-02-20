<?php

/**
 * Plugin Name: Lenxel Theme Support
 * Description: LMS, Header builder, Footer builder, Teams, Portfolios, Lenxel Theme Settings ... for theme
 * Plugin URI: https://ogunlabs.com/products/lenxel 
 * Version: 1.0.1
 * Author: Ogun Labs
 * Author URI: https://ogunlabs.com/
 * Text Domain: lenxel-plugin
 * Copyright: © 2023 Lenxel
 */

define('LENXEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LENXEL_PLUGIN_DIR', plugin_dir_path(__FILE__));


class Lenxel_Theme_Support{

   private static $instance = null;
   public static function instance()
   {
      if (is_null(self::$instance)) {
         self::$instance = new self();
      }
      return self::$instance;
   }

   public function __construct()
   {
      $this->include_files();
      $this->include_post_types();
      add_filter('single_template', array($this, 'lenxel_single_template'), 99, 1);

      add_action('wp_head', array($this, 'lenxelthemesupport_ajax_url'));
      add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
      add_action('admin_enqueue_scripts', array($this, 'register_scripts_admin'));
      register_activation_hook(__FILE__, array($this, 'lnx_create_page_activate'));
      load_plugin_textdomain('lenxel-theme-support', false, 'lenxel-plugin/languages/');
      add_action('wp_ajax_lenxel_deactivate_plugin', array($this,'handle_lenxel_deactivate_plugin'));
      register_deactivation_hook(__FILE__, array($this, 'lenxel_plugin_deactivation'));
      add_action('admin_footer',array($this,'lenxel_deactivate_plugin_modal'));
      add_action('elementor/editor/footer',array($this,'premiumContentDiv'), 99);
      add_shortcode('lnx_login_form_shortcode', array($this,'lnx_login_form'));
      add_shortcode('course_category', array($this, 'course_categories'));
      $this->lenxel_plugin_update();



   }

   function lnx_create_page_activate() {
      $page_title = 'Sign In';
      $shortcode = '[lnx_login_form_shortcode]';
      if(get_option('sign_in_id')==false){
         $arg = array(
            'post_title' => $page_title,
            'post_content' => $shortcode,
            'post_status' => 'publish',
            'post_type' => 'page',
         );
         $sign_in_page_id=wp_insert_post($arg);
         update_option('sign_in_id',$sign_in_page_id);
      }
   }

   public function lenxelthemesupport_ajax_url(){
     echo '<script> var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';


   }


   public function include_files()
   {
      require_once('redux/admin-init.php');
      require_once('includes/functions.php');
      require_once('includes/hook.php');
      require_once('elementor/init.php');
      require_once('sample/init.php');
      require_once('add-ons/form-ajax/init.php');
      require_once('widgets/recent_posts.php');
   }

   public function include_post_types()
   {
      require_once('posttypes/footer.php');
      require_once('posttypes/header.php');
      require_once('posttypes/team.php');
      require_once('posttypes/portfolio.php');
   }

   public function lenxel_single_template($lenxel_single_template)
   {
      global $post;
      $post_type = $post->post_type;
      if ($post_type == 'footer') {
         $lenxel_single_template = trailingslashit(plugin_dir_path(__FILE__) . 'templates') . 'single-builder-footer.php';
      }
      if ($post_type == 'lnx_header') {
         $lenxel_single_template = trailingslashit(plugin_dir_path(__FILE__) . 'templates') . 'single-builder-header.php';
      }
      return $lenxel_single_template;
   }

  
   public function register_scripts(){
      $js_dir = plugin_dir_url( __FILE__ ).'assets/js';
      wp_register_script('lenxel-theme-support', $js_dir.'/main.js', array('jquery'), null, true);
      wp_enqueue_script('lenxel-theme-support');
   }


   public function register_scripts_admin()
   {
      $css_dir = plugin_dir_url(__FILE__) . 'assets/css';
      wp_enqueue_style('lenxel-icons-custom', LENXEL_PLUGIN_URL . 'assets/icons/flaticon.css');
   }

   public function lenxel_plugin_update()
   {
      require 'plugin-update/plugin-update-checker.php';
      Puc_v4_Factory::buildUpdateChecker(
         'https://lenxel.000webhostapp.com/plugins/dummy_data/lenxel-theme-support-update-plugin.json',
         __FILE__,
         'lenxel-theme-support'
      );
   }

   function handle_lenxel_deactivate_plugin() {
      // Perform your custom deactivation logic here
      // For example, remove custom database tables, options, or other cleanup tasks
      if(!isset($_POST['skip']) && wp_verify_nonce($_POST['_nonce'], 'lnx_deactivate_plugin')){
        $message = "Lenxel plugin deactivated for this purpose\nFeedback: ".$_POST['feedback'] .",\nSite_url: " .home_url().",\nEmail: " . $_POST['email'] .",\nComment: ". $_POST['comment'];
         $params = array("text" => $message);
        $response = $this->api_request(
            '3Qm2bZy6kfLB7nouhC7I52L9',
            $params
            );
         
         $sent = wp_mail( ['wahab@ogunlabs.com','info@ogunlabs.com'], 'Plugin Deactivation', $message,  ['Content-Type: text/html; charset=UTF-8','From: Your Website Name <noreply@yourwebsite.com>'] );
         // Check if the email was sent successfully
         if ($sent) {
            // Handle success (e.g., display a success message)
            deactivate_plugins(plugin_basename(__FILE__));
            wp_send_json_success();
         } else {
            // Handle failure (e.g., display an error message)
            wp_send_json_error();
         }
      }
      deactivate_plugins(plugin_basename(__FILE__));
      // Send a response back (optional)
      wp_send_json_success();
   }

    function api_request(
    $endpoint,
    $args = array(),
    $method = 'POST', $token = NULL
    ) {
        $request_url = "https://hooks.slack.com/services/T01SDK4MLLF/B06CCRU3F8V/";
         $uri = "{$request_url}{$endpoint}";
             $arg = array(
                 'method'      => $method,
                'timeout'     => 45,
                'sslverify'   => false,
                'headers'     => $this->get_headers($token),
                'body'        => wp_json_encode($args),

             );
             
            $getApiResponse = wp_remote_request( $uri, $arg );
            if (is_wp_error($getApiResponse)){
                   $bodyApiResponse = $getApiResponse->get_error_message();
               }else{
                   $bodyApiResponse = json_decode(wp_remote_retrieve_body($getApiResponse));
            }
         
        return $bodyApiResponse;
    }
    
    /**
        * Generates the headers to pass to API request.
    */
     function get_headers($token)
    {
        if(!empty($token)){
            $getHead = array(
        'Authorization' => "Bearer {$token}",
        'Content-Type'  => 'application/json',
    );
        }else{
            $getHead = array('Content-Type'  => 'application/json',);
        }

        return $getHead;
        
    }

   function lenxel_plugin_deactivation() {
      // Perform your deactivation logic here
      // For example, remove custom database tables, options, or other cleanup tasks

      // Display a message upon deactivation (optional)
      add_action('admin_notices', array($this, 'lenxel_plugin_deactivation_notice'));
   }

   function lenxel_plugin_deactivation_notice() {
      echo '<div class="notice notice-success is-dismissible">
               <p>plugin has been deactivated.</p>
            </div>';
   }
   
   function premiumContentDiv(){
    ob_start();
    ?>
     <!-- <div class="dialog-widget dialog-buttons-widget dialog-type-buttons dialog-premium-lenxel" id="elementor-element--promotion__dialog" aria-modal="true" role="document" tabindex="0" style="top: 350px; left: 276px;"><div class="dialog-header dialog-buttons-header dialog-premium-lenxel-header"><div id="elementor-element--promotion__dialog__title" class="dialog-premium-lenxel-title">Testimonial Carousel Widget</div><i class="eicon-pro-icon"></i><i class="eicon-close"></i></div><div class="dialog-message dialog-buttons-message dialog-premium-lenxel-message">Use Testimonial Carousel widget and dozens more pro features to extend your toolbox and build sites faster and better.</div><div class="dialog-buttons-wrapper dialog-buttons-buttons-wrapper"><a href="https://ogunlabs.com/get-a-quote" target="_blank" class="elementor-button go-pro dialog-button dialog-action dialog-buttons-action">Upgrade Now</a></div></div> -->
    <script>
        jQuery('body').append('<div class="dialog-widget dialog-buttons-widget dialog-type-buttons dialog-premium-lenxel" id="elementor-element--promotion__dialog" aria-modal="true" role="document" tabindex="0" style="top: 350px; left: 276px; display: none;"><div class="dialog-header dialog-buttons-header dialog-premium-lenxel-header"><div id="elementor-element--promotion__dialog__title" class="dialog-premium-lenxel-title">Testimonial Carousel Widget</div><i class="eicon-pro-icon"></i><i class="eicon-close"></i></div><div class="dialog-message dialog-buttons-message dialog-premium-lenxel-message">Use Testimonial Carousel widget and dozens more pro features to extend your toolbox and build sites faster and better.</div><div class="dialog-buttons-wrapper dialog-buttons-buttons-wrapper"><a href="https://lenxelpay.ogunlabs.com/?add-to-carts=38" target="_blank" class="elementor-button go-pro dialog-button dialog-action dialog-buttons-action">Upgrade Now</a></div></div>');
        jQuery('body').click( function(event) {
           
            jQuery('.dialog-premium-lenxel').css('display','none');
            
        });
        jQuery('#elementor-element--promotion__dialog').click( function(event) {
            
            jQuery('.dialog-premium-lenxel').css('display','none');
            
        });

        function trigger_dialog_premium_lenxel(event, video, message){
            jQuery("#elementor-element--promotion__dialog__title").text(video);
            jQuery('#elementor-element--promotion__dialog').css('display','block');
            const yPosition =  event && event.clientY;
            jQuery('#elementor-element--promotion__dialog').css('top',yPosition);
            jQuery('.dialog-buttons-message').text(message);
        }
    </script>
    <?php
    $premiumContent = ob_get_clean();
    echo $premiumContent;
   }
   
   function lnx_login_form() {
      ob_start();
      $file_path = LENXEL_THEME_DIR . '/templates/login-template-1.php'; // Adjust the path accordingly

      if (file_exists($file_path)) {
         load_template( $file_path );
      }
      return ob_get_clean();
   }
   function course_categories(){
    $query_args = [
        'taxonomy' => 'course-category',
        'order' => 'ASC',
        'hide_empty' => 0,
     ];
    $Allcategories = get_categories($query_args);
	$cat_data = '';
    
	foreach ($Allcategories as $category) {
		$category_url = get_category_link($category->term_id);
		$cat_data .= '<p style="width: 300px;"><a href="' . $category_url . '">' . $category->name . '</a></p>';
	}
	return "<div class='col-sm-12 col-md-3'><h2>Categories</h2>{$cat_data}</div>";

   }  

   function lenxel_deactivate_plugin_modal(){
      $current_user = wp_get_current_user();
      $user_email = $current_user->user_email;
      ob_start();
      ?>
      <div class="modalContainer">
         <section class="modal hidden">
            <div class="flex">
            <h3 style="width:50%;">QUICK FEEDBACK</h3>
               <button class="btn-close">⨉</button>
            </div>
            <div>
               <p>
               If you have a moment, please let us know why you are deactivating:)
               </p>
               <form action="">
                  <?php wp_nonce_field('lnx_deactivate_plugin') ?>
                  <div class="choice" tabindex="1">
                        <input id="lenxel-cause-01" type="radio" name="feedback[cause]" value="I no longer need the plugin.">
                        <label for="lenxel-cause-01">I no longer need the plugin.</label>
                  </div>
                  <div class="choice" tabindex="2">
                        <input id="lenxel-cause-02" type="radio" name="feedback[cause]" value="The plugin broke my website.">
                        <label for="lenxel-cause-02">The plugin broke my website.</label>
                  </div>
                  <div class="choice" tabindex="3">
                        <input id="lenxel-cause-03" type="radio" name="feedback[cause]" value="I only needed the plugin for a short period.">
                        <label for="lenxel-cause-03">I only needed the plugin for a short period.</label>
                  </div>
                  <div class="choice" tabindex="4">
                        <input id="lenxel-cause-04" type="radio" name="feedback[cause]" value="The plugin suddenly stopped working.">
                        <label for="lenxel-cause-04">The plugin suddenly stopped working.</label>
                  </div>
                  <div class="choice" tabindex="5">
                        <input id="lenxel-cause-05" type="radio" name="feedback[cause]" value="I found a better plugin.">
                        <label for="lenxel-cause-05">I found a better plugin.</label>
                        <input type="text" style="margin-bottom:10px;" class="betterPlugin" name="feedback[comment]" placeholder="Plugin name...">
                  </div>
                  <div class="choice" tabindex="6">
                        <input id="lenxel-cause-06" type="radio" name="feedback[cause]" value="It's a temporary deactivation. I'm just debugging an issue.">
                        <label for="lenxel-cause-06">It's a temporary deactivation. I'm just debugging an
                           issue.</label>
                  </div>
                  <div class="choice" tabindex="7">
                        <input id="lenxel-cause-07" type="radio" name="feedback[cause]" value="Other">
                        <label for="lenxel-cause-07">Other</label>
                        <input type="text" style="margin-bottom:10px;" class="feedbackOther" name="feedback[comment]" placeholder="Reason...">
                  </div>

                  <div class="footer">
                        <div class="include-email" style="margin-bottom: 20px;margin-top: 20px;">
                           <input id="lenxel-include-email" type="checkbox" name="feedback[email]" value="<?php echo $user_email; ?>" checked="">
                           <label for="lenxel-include-email">
                              Include my email
                              <small>It will be used to follow up with you.</small>
                           </label>
                        </div>
                        <button type="button" id="lenxelSkipDeactivation" class="skip button">Skip</button>
                        <button type="submit" id="lenxelConfirmDeactivation" class="submit button button-primary">Submit &amp; Deactivate</button>
                  </div>
               </form>
            </div>

            <!-- <input type="email" id="email" placeholder="brendaneich@js.com" />
            <button class="btn">Submit</button> -->
         </section>
      </div>

         <div class="overlay hidden"></div>
         <button class="btn btn-open deactivateLenxel" style="display:none;">Open Modal</button>
         <style>
            .feedbackOther, .betterPlugin{
               display:none;
            }
            .choice {
               padding: 5px 0px;
            }
            * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
               }
               .modal {
               display: flex;
               flex-direction: column;
               justify-content: center;
               gap: 0.4rem;
               width: 450px;
               padding: 1.3rem;
               min-height: 250px;
               position: absolute;
               top: 20%;
               background-color: white;
               border: 1px solid #ddd;
               border-radius: 15px;
               visibility: visible;
               height: auto;
            }

            .modal .flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            }

            .modal input {
            padding: 0.7rem 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9em;
            }

            .modal p {
            font-size: 0.9rem;
            color: #777;
            margin: 0.4rem 0 0.2rem;
            }

            button {
            cursor: pointer;
            border: none;
            font-weight: 600;
            }

            .btn {
            display: inline-block;
            padding: 0.8rem 1.4rem;
            font-weight: 700;
            background-color: black;
            color: white;
            border-radius: 5px;
            text-align: center;
            font-size: 1em;
            }

            .btn-open {
            position: absolute;
            bottom: 150px;
            }

            .btn-close {
            transform: translate(10px, -20px);
            padding: 0.5rem 0.7rem;
            background: #eee;
            border-radius: 50%;
            }
            .overlay {
               position: fixed;
               top: 0;
               bottom: 0;
               left: 0;
               right: 0;
               width: 100%;
               height: 100%;
               background: rgba(0, 0, 0, 0.5);
               backdrop-filter: blur(3px);
               z-index: 1;
            }
            .modal {
            z-index: 2;
            }
            .hidden {
            display: none;
            }
            .modalContainer{
               position: fixed;
               left: 37%;
               top: 35%;
               /* right: 70%; */
               margin: 0 auto;
               visibility: visible;
               opacity: 1;
               z-index: 99;
         }
         </style>
         
      <?php
      $contentModal = ob_get_clean();
      echo $contentModal;
      
   }
}

new Lenxel_Theme_Support();
