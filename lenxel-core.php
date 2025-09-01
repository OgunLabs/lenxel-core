<?php

/**
 * Plugin Name: Lenxel Core
 * Description: LMS, Header builder, Footer builder, Teams, Portfolios, Lenxel Theme Settings ... for theme
 * Plugin URI: https://ogunlabs.com/products/lenxel 
 * Version: 1.2.7
 * Requires PHP: 7.4
 * Author: Ogun Labs
 * Requires at least: 6.3
 * Author URI: https://ogunlabs.com/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain: lenxel-core
 * Copyright: © 2024 Lenxel
 * Domain Path:  /languages
 * Icon: assets/logo.png
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
define('LENXEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LENXEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define( 'LENXEL_CORE_VERSION', '1.1' );

class Lenxel_Theme_Support{

   public $firstLesson;
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
      add_action('init', array($this, 'lenxel_generate_reset_pwd_password'));
      //add_action('init', array($this, 'lenxel_get_course_first_lesson'));
      add_action('user_register', array($this, 'lnx_user_registration_hook'), 10, 2);
      add_filter('body_class', array($this, 'add_custom_body_class'), 99, 1);
      //add_action('wp_head', array($this, 'lenxel_core_head_ajax_url'));
      add_action('wp_enqueue_scripts', array($this, 'lenxel_core_register_scripts'));
      add_action('admin_enqueue_scripts', array($this, 'lenxel_core_register_scripts_admin'));
      register_activation_hook(__FILE__, array($this, 'lenxel_create_page_activate'));
      load_plugin_textdomain('lenxel-core', false, 'lenxel-core/languages/');
      add_action('wp_ajax_lenxel_deactivate_plugin', array($this,'lenxel_core_handle_deactivate_plugin'));
      register_deactivation_hook(__FILE__, array($this, 'lenxel_core_plugin_deactivation'));
      add_action('admin_footer',array($this,'lenxel_core_deactivate_plugin_modal'));
      add_action('elementor/editor/footer',array($this,'lenxel_core_premium_content_div'), 99);
      add_shortcode('lenxel_core_login_form_shortcode', array($this,'lenxel_core_login_form'));
      add_shortcode('lenxel_core_course_category', array($this, 'lenxel_core_course_categories'));
      add_action('wp_head', array($this, 'lenxel_custom_login'),5);
      add_filter('lenxel_admin_theme_menu', array($this, 'lenxel_extended_admin_theme_menu'), 10);
      add_action( 'wp_ajax_lenxel_activation_key_actions', array($this, 'lenxel_activation_key_actionss_callback'));
      add_action( 'init', array($this, 'lenxel_duplicate_course') );
      add_action('tutor_admin_middle_course_list_action', array($this, 'lenxel_duplicate_post_link'), 10,1);
      add_filter('wp_handle_upload_prefilter',  array($this, 'validate_svg_upload'));
      // add_filter('upload_mimes', array($this, 'restrict_svg_uploads_to_admin'));
      add_filter('wp_headers',  array($this, 'set_secure_headers'));
      add_action('admin_init', array($this, 'is_lenxel_key_valid'));
   }
   
   function lenxel_reclone_post_data(object $post = NULL){
	
      if($post && in_array($post->post_type, array('courses','lesson','tutor_quiz','topics'))){
         $post_data = array(
            'post_title' => $post->post_title . (!in_array($post->post_type, array('lesson','tutor_quiz','topics')) ? '-clone':''),
            'post_content' => $post->post_content,
            'post_status' => $post->post_status,
            'post_type' => $post->post_type,
            'post_author' => $post->post_author,
            'post_parent' => $post->post_parent,
         );
         $new_course_id = wp_insert_post($post_data);//print_r($new_course_id);die();
   
         if (!is_wp_error($new_course_id)) {
            // Get all post_meta for the original post
            $post_meta = get_post_custom($post->ID);
            // Loop through and add each post_meta to the new post
            foreach ($post_meta as $key => $values) {
               foreach ($values as $value) {
                  if(($value)&&('_tutor_course_product_id'==$key)):
                     //create a new product and assign it to the course on the same meta_key
                     $get_product = get_post($value);
                     $_product = lenxel_create_new_product($get_product);
                     add_post_meta($new_course_id, $key, $_product);
                  else:
                     add_post_meta($new_course_id, $key, $value);
                  endif;
               }
               
            }
            
            return $new_course_id;
         }
         return false;
      }
   }
   function set_secure_headers($headers) {
      if (is_attachment()) {
          $file_type = get_post_mime_type(get_the_ID());
          if ($file_type === 'image/svg+xml') {
              $headers['Content-Type'] = 'image/svg+xml';
              $headers['X-Content-Type-Options'] = 'nosniff';
          }
      }
      return $headers;
   }
   
   function validate_svg_upload($file) {
      // Check if the file is an SVG
      $file_type = wp_check_filetype($file['name']);
      if ($file_type['ext'] === 'svg') {
          // Read the file contents
          $svg_content = file_get_contents($file['tmp_name']);
          
          // Sanitize the SVG
          if (!$this->is_safe_svg($svg_content)) {
              $file['error'] = 'The uploaded SVG contains unsafe content and has been rejected.';
              return $file;
          }
      }
      return $file;
   }
   function is_safe_svg($svg_content) {
      // Disallow specific dangerous tags
      $disallowed_tags = [
          'script', 'iframe', 'object', 'embed', 'form', 'link', 'meta'
      ];
  
      // Check for disallowed tags
      foreach ($disallowed_tags as $tag) {
          if (preg_match('/<\s*' . preg_quote($tag, '/') . '[^>]*>/i', $svg_content)) {
              return false;
          }
      }
  
      // Disallow dangerous attributes (e.g., `onload`, `onclick`)
      $disallowed_attributes = [
          'on[a-z]+(?=\s*=)', '\bstyle\b'
      ];
  
      foreach ($disallowed_attributes as $attr) {
          if (preg_match('/' . $attr . '\s*=/i', $svg_content)) {
              return false;
          }
      }
  
      // Optionally, restrict SVG to specific namespaces (ensure it's a valid SVG file)
      if (!preg_match('/<\s*svg[^>]*xmlns\s*=\s*[\'"]http:\/\/www\.w3\.org\/2000\/svg[\'"]/i', $svg_content)) {
          return false;
      }
  
      return true;
   }
   function lenxel_duplicate_post_link(int $post_id = 0){
      if($post_id > 0):
         $get_escaped = '<a href="?page=tutor&tutor_action=duplicate_course&course_id='.sanitize_text_field($post_id).'" title="Duplicate" class="tutor-dropdown-item" style="padding-left:15px;color:#ffffff">
            <i class="tutor-icon-copy tutor-mr-8" area-hidden="true"></i>
            <span>Duplicate </span>
         </a>';
         echo wp_kses($get_escaped,lenxel_escape_unwanted_tags());
      endif;
   }
   
   function lenxel_duplicate_course($course_id) {
      if (isset($_GET['tutor_action']) && isset($_GET['course_id'])) {
         // Get the post by its ID
         $course_id = (int)$_GET['course_id'];
         $course = get_post($course_id);
         // Create an array with the post data to duplicate
         $new_course_id = $this->lenxel_reclone_post_data($course);
         if($new_course_id){
            $response_child = lenxel_duplicate_lessons_quiz_in_topic_course((int)$course_id, (int)$new_course_id);
         }
         
         if($new_course_id){
            wp_redirect($_SERVER['HTTP_REFERER']);
            die();
         }
      }
   }
   function lenxel_extended_admin_theme_menu($menu){
      $menu['lnx-activation'] = 'Activating Premium';
      return $menu;
   }
   
   function lenxel_create_page_activate() {
      $page_title = 'Sign In';
      $shortcode = '[lenxel_core_login_form_shortcode]';
      if(get_option('lenxel_sign_in_id')==false){
         $arg = array(
            'post_title' => $page_title,
            'post_content' => $shortcode,
            'post_status' => 'publish',
            'post_type' => 'page',
         );
         $sign_in_page_id=wp_insert_post($arg);
         update_option('lenxel_sign_in_id',$sign_in_page_id);
      }
      if( get_page_by_path('certificate', OBJECT, 'page') == NULL ) {
         $arg_post = array(
               'post_title'    => 'Certificate',
               'post_status'   => 'publish',
               'post_type'     => 'page',
               'post_name'     => 'certificate'
            );
         /* Insert the post into the database */
         $certificate_page_id = wp_insert_post( $arg_post );
         update_option('lenxel_certificate_id',$certificate_page_id);
      }
   }
  
   // To generate a link for user to reset password through an email
   function lenxel_generate_reset_pwd_password() {
      if (isset($_POST['_wp_http_referer'])) {
         $nounce = (isset($_POST['_wpnonce'])) ? sanitize_text_field( wp_unslash($_POST['_wpnonce'])) : null ;
         $nounce_value = (isset($_GET['lost_pwd'])) ? sanitize_text_field($_GET['lost_pwd']) : null ;
         if (wp_verify_nonce($nounce, 'lnx_'.$nounce_value)) { 
         global $mailStatus;global $error_email;
      
         $email_forget_pwd = sanitize_email($_POST['email']);
   
         // check if email exist in db
         $user_obj = get_user_by('email', $email_forget_pwd);
         if (!$user_obj) {
            $error_email = 1;
            $mailStatus = wp_kses('
               <small class="error">
               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 11 11" fill="none">
                  <path d="M5.50033 10.0834C2.96895 10.0834 0.916992 8.03146 0.916992 5.50008C0.916992 2.96871 2.96895 0.916748 5.50033 0.916748C8.0317 0.916748 10.0837 2.96871 10.0837 5.50008C10.0837 8.03146 8.0317 10.0834 5.50033 10.0834ZM5.50033 9.16675C6.47279 9.16675 7.40542 8.78044 8.09305 8.09281C8.78068 7.40517 9.16699 6.47254 9.16699 5.50008C9.16699 4.52762 8.78068 3.59499 8.09305 2.90736C7.40542 2.21972 6.47279 1.83341 5.50033 1.83341C4.52786 1.83341 3.59523 2.21972 2.9076 2.90736C2.21997 3.59499 1.83366 4.52762 1.83366 5.50008C1.83366 6.47254 2.21997 7.40517 2.9076 8.09281C3.59523 8.78044 4.52786 9.16675 5.50033 9.16675ZM5.04199 6.87508H5.95866V7.79175H5.04199V6.87508ZM5.04199 3.20841H5.95866V5.95841H5.04199V3.20841Z" fill="#E93C3C"/>
               </svg>Email does not exist
               </small>', get_kses_extended_ruleset());
               

         }else{
            $error_email = 0;
            $reset_pwd_url = wp_nonce_url(get_permalink()."?_id={$user_obj->ID}", 'reset_nonce'.$user_obj->ID);
   
   
            $to = $email_forget_pwd;
            $subject = 'Reset Password';
   
            $message = 
            "<div>
               <p>Click on the link below to reset your password</p>
               <a href='".esc_url(html_entity_decode($reset_pwd_url))."'>Reset password</a>
            </div>";
            // echo $message;
   
            $send_mail = wp_mail($to, $subject, $message,'Content-type: text/html');
            if ($send_mail) {
               $mailStatus = "<img alt='successful reset password' src='".esc_url(LENXEL_THEME_URL . '/images/juicy-closed-email-envelope.png') ."'><h2 class='lfs-36 lfw-700 pb-2 pt-2'>We Sent you a Mail</h2><p class='lfs-20 lfw-500'>Kindly Check your email, we sent you a link to verify your Account with use and reset your Password</p>";
               //load_template(LENXEL_THEME_DIR . '/templates/reset-password-mail.php' );die();

            }
         }
         }
      }
   }

   function lnx_user_registration_hook($user_id,$user_data) {
		if(!empty(lenxel_get_option('enable_registration_notification', '') ) || (lenxel_get_option('enable_registration_notification', '') !== '0')){
			// Your custom code to run after successful user registration
			$super_admin_email = get_site_option('admin_email');
			$user_data = get_userdata($user_id);
			$to = $user_data->user_email; // Recipient's email address
			$instructor_status = tutor_utils()->instructor_status($user_id);
			$instructor_status = is_string($instructor_status) ? strtolower($instructor_status) : '';
			// Email message
			$headers = array('Content-Type: text/html; charset=UTF-8'); 
			if ($user_data) {
				$user_roles = $user_data->roles;
				
				if (!empty($user_roles)) {
					$user_role = $user_roles[0];
					$subject = 'User successfully registered as '. (($instructor_status=='pending') ? 'instructor' : $user_role); // Email subject
					$subject_admin = (($instructor_status=='pending') ? 'New Registration Awaiting Approval' : 'New Registration');
					$message_admin = (($instructor_status=='pending') ? 'The following user successfully register on the platform as instructor waiting your approval.' : "The following user successfully register on the platform as ".$user_role);
					$message_subscriber = (($instructor_status=='pending') ? 'You have successful register on the platform as an instructor waiting admin\'s approval' : "You have successful register on the platform as ".$user_role);
					// For example, you can send a welcome email or perform other tasks
					$approval_role = array('subscriber',tutor()->instructor_role );
					if(in_array($user_role,$approval_role)){
						// Send the email using wp_mail()
						$result = wp_mail($to, $subject, $message_subscriber, $headers);
						$result = wp_mail($super_admin_email, $subject_admin, $message_admin, $headers);
					}

				}
			}
		}
	}

   function add_custom_body_class($classes) {
      $page_id_data = (int) get_option('lenxel_sign_in_id');
      
      if (!empty($page_id_data) && is_page($page_id_data)) {
          $classes[] = 'lnx-login';
      }
  
      return apply_filters('assign_page_id', $classes);
  }
  
   function lenxel_activation_key_actionss_callback(){
      check_ajax_referer('ajax-l-activation-nonce','ajax_l_activation_ajax');
      
      if ((trim($_POST['activation_input']) == '') && (!isset($_POST['activation_hidden']))) {
         $response['error'] = true;
         $response['error_message'] = "You have no course to title?";
         exit(json_encode($response));
         
      }
      $current_user = wp_get_current_user(); // User Login (Username)
      $user_email = $current_user->user_email;
      $custom_value = get_option( 'lenxel_key_validated_status' );
      $custom_value_key =get_option('lenxel_activation_key');
      if((false !== $custom_value) && ($_POST['activation_input'] !== $custom_value_key)){
         update_option( 'lenxel_key_validated_status', false );
         $custom_value = false;
      }
      update_option( 'lenxel_activation_key', sanitize_text_field($_POST['activation_input'] ));
      // Check if the option value exists
      if ( (false !== $custom_value) && !empty($custom_value) ) {
         define( 'LENXEL_PREMIUM_VERSION', $custom_value );
         $data['data'] =  sanitize_text_field($_POST['activation_input']);
         $data['status'] = 'Already Activated';
         $data['msg'] = 'Activation key is already activated.';
         $data['code'] = 202;
         echo json_encode($data); die();
      }
      $route = 'activation_key?activation_key='.sanitize_text_field($_POST['activation_input']).'&email='.sanitize_email($user_email).'&url='.home_url();
      $get_api_response = $this->lenxel_api_request($route, array(), 'GET');
      if($get_api_response['code']==200){
         update_option( 'lenxel_key_validated_status', true );
         if($get_api_response['expires']){
            $expires = ((int)$get_api_response['expires'] > 0) ? $get_api_response['expires'] : 40;
            update_option( 'lenxel_key_expiration', strtotime('+'.$expires.' days') );
         }
         $data['data'] = $get_api_response;
         $data['status'] = 'Successful2';
         $data['msg'] = 'Valid! Activation key is active.';
         $data['code'] = 200;
      }elseif($get_api_response['code']==202){
         update_option( 'lenxel_key_validated_status', true );
         if($get_api_response['expires']){
            $expires = ((int)$get_api_response['expires'] > 0) ? $get_api_response['expires'] : 40;
            update_option( 'lenxel_key_expiration', strtotime('+'.$expires.' days')  );
         }
         $data['data'] = $get_api_response;
         $data['status'] = 'Already Activated2x';
         $data['msg'] = 'Activation key is already activated.';
         $data['code'] = 202;
      }else{
         $data['data'] = $get_api_response;
         $data['status'] = 'Failed';
         $data['msg'] = 'Invalid! activation key is not valid';
         $data['code'] = 404;
      }
      wp_send_json($data); die();

   }

   function lenxel_api_request(
         $endpoint,
         $args = array(),
         $method = 'POST', $token = NULL
         ) {
            $request_url = "https://lenxel.dev/wp-json/verify/v1/";
            
            $uri = "{$request_url}{$endpoint}";
            $response = wp_remote_get( esc_url_raw( $uri ) );
            $api_response = json_decode( wp_remote_retrieve_body( $response ), true );
            
            return $api_response;
   }
   public function include_files()
   {
      require_once('redux/admin-init.php');
      require_once('includes/functions.php');
      require_once('includes/hook.php');
      require_once('includes/metaboxes.php');
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

  
   public function lenxel_core_register_scripts(){
      $js_dir = plugin_dir_url( __FILE__ ).'assets/js';
      wp_register_script('lenxel-core', $js_dir.'/main.js', array('jquery'), null, true);
      wp_enqueue_script('lenxel-core');
   }


   public function lenxel_core_register_scripts_admin()
   {
      $css_dir = plugin_dir_url(__FILE__) . 'assets/css';
      wp_enqueue_style('lenxel-icons-custom', LENXEL_PLUGIN_URL . 'assets/icons/flaticon.css');
   }

   function lenxel_core_handle_deactivate_plugin() {
      // Perform your custom deactivation logic here
      // For example, remove custom database tables, options, or other cleanup tasks
      if(!isset($_POST['skip']) && wp_verify_nonce(sanitize_text_field( wp_unslash ( $_POST['_nonce'])), 'lnx_deactivate_plugin')){
        $message = "Lenxel core deactivated for this purpose\nFeedback: ".sanitize_title($_POST['feedback']) .",\nSite_url: " .home_url().",\nEmail: " . sanitize_email($_POST['email']) .",\nComment: ". sanitize_text_field($_POST['comment']);
         $params = array("text" => $message);
        $response = $this->lenxel_core_api_request(
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
   function is_lenxel_key_valid() {
      $expiration = get_option('lenxel_key_expiration', 0);
      
      // If expiration date is in the past, invalidate the key
      if($expiration > 0){
         if (time() > $expiration) {
            update_option( 'lenxel_key_validated_status', false ); // Expire the key
            return false;
         }
         return get_option('lenxel_key_validated_status', false);
      }
  
      return;
  }
 
  // Usage
    function lenxel_core_api_request(
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
                'headers'     => $this->lenxel_core_get_headers($token),
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
     function lenxel_core_get_headers($token)
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

   function lenxel_core_plugin_deactivation() {
      // Perform your deactivation logic here
      // For example, remove custom database tables, options, or other cleanup tasks

      // Display a message upon deactivation (optional)
      add_action('admin_notices', array($this, 'lenxel_core_plugin_deactivation_notice'));
   }

   function lenxel_core_plugin_deactivation_notice() {
      $html_content = '<div class="notice notice-success is-dismissible">
               <p>plugin has been deactivated.</p>
            </div>';
            echo wp_kses($html_content, array( 'div','p' ));
   }
   
   function lenxel_core_premium_content_div(){
    ob_start();
    ?>
     
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
   
    printf( '%s', wp_kses($premiumContent, array('script'=>array())));
   }
   
   function lenxel_core_login_form() {
      ob_start();
      if (defined('LENXEL_THEME_DIR')) {
         $file_path = LENXEL_THEME_DIR . '/templates/login-template-1.php'; // Adjust the path accordingly

         if (file_exists($file_path)) {
            load_template( $file_path );
         }
      }
      return ob_get_clean();
   }
   function lenxel_core_course_categories(){
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
   
   function lenxel_core_deactivate_plugin_modal(){
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
                           <input id="lenxel-include-email" type="checkbox" name="feedback[email]" value="<?php echo esc_attr( $user_email ); ?>" checked="">
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
         </section>
      </div>

         <div class="overlay hidden"></div>
         <button class="btn btn-open deactivateLenxel hideButton" style="display:none;">Open Modal</button>
         <style>
            .feedbackOther, .betterPlugin{display:none; }
            .choice {padding: 5px 0px;}
            * { margin: 0;padding: 0;box-sizing: border-box;font-family: "Inter", sans-serif;}
               .modal {display: flex;flex-direction: column;justify-content: center;gap: 0.4rem;width: 450px;padding: 1.3rem; min-height: 250px;position: absolute;top: 20%;background-color: white;border: 1px solid #ddd;border-radius: 15px;visibility: visible;height: auto;}

            .modal .flex {display: flex;align-items: center;justify-content: space-between; }
            
            button.hideButton{display:none;}

            .modal input {padding: 0.7rem 1rem;border: 1px solid #ddd; border-radius: 5px;font-size: 0.9em;}

            .modal p {font-size: 0.9rem;color: #777;margin: 0.4rem 0 0.2rem;}

            button {cursor: pointer;border: none;font-weight: 600;}

            .btn {display: inline-block;padding: 0.8rem 1.4rem;font-weight: 700;background-color: black; color: white;border-radius: 5px;text-align: center;font-size: 1em;}

            .btn-open {position: absolute;bottom: 150px;}

            .btn-close {transform: translate(10px, -20px);padding: 0.5rem 0.7rem;background: #eee; border-radius: 50%;}
            .overlay {position: fixed;top: 0;bottom: 0;left: 0;right: 0;width: 100%;height: 100%;background: rgba(0, 0, 0, 0.5);backdrop-filter: blur(3px);z-index: 1;}
            .modal {z-index: 2;}
            .hidden {display: none;}
            .modalContainer{position: fixed;left: 37%;top: 35%;margin: 0 auto;visibility: visible;opacity: 1;z-index: 99;}
         </style>
         
      <?php
      $contentModal = ob_get_clean();
      printf( '%s', wp_kses($contentModal, array('div'=>array('class'=>array(),'id'=>array(),'tabindex'=>array()),'style'=>array(),'p'=>array(),'button'=>array('style'=>array(),'type'=>array(),'class'=>array(),'id'=>array()),'section'=>array('class'=>array()),'h3'=>array('style'=>array()),'input'=>array('class'=>array(),'style'=>array(),'placeholder'=>array(),'checked'=>array(),'id'=>array(),'name'=>array(),'value'=>array(),'type'=>array()),'label'=>array('for'=>array()),'small'=>array(),'form'=>array('action'=>array()))));
      
   }
   
function lenxel_custom_login(){
	if(isset($_POST['_wpnonce'])){
		if (wp_verify_nonce(sanitize_text_field( wp_unslash( $_POST['_wpnonce'])), LENXEL_THEME_URL)) {

			$email = sanitize_email($_POST['email']);
			$password = $_POST['password'];
			$remember = sanitize_text_field($_POST['checkbox']);
			$credentials = array(
				'user_login' => $email,
				'user_password' => $password,
				'remember' => $remember
			);
			$login_user = wp_signon($credentials, false);
			
			if (is_wp_error($login_user)) {

				/* Any page set as login page */
				$page_id= lenxel_get_option('login_on_any_page', false);
				$page_url = get_permalink($page_id);
				$landing_page_id= lenxel_get_option('enable_login_on_landing_page', false);
				if ($page_id != null) {
				
				wp_redirect($page_url .'/?status=invalid_credentials');
				} else {
					if($landing_page_id != null){
						wp_redirect(home_url('/?status=invalid_credentials'));
					}
				}
			}else {
            $tutor_option = get_option('tutor_option',[]);
            if($tutor_option['tutor_dashboard_page_id']){
               $get_postData = get_post($tutor_option['tutor_dashboard_page_id']);
               $dashboard_post_name = $get_postData->post_name;
            }
				wp_set_auth_cookie( $login_user->ID );
				wp_redirect(home_url('/'.$dashboard_post_name));
			}
		}
	}
  }
}

new Lenxel_Theme_Support();

if(!function_exists('lenxel_get_course_first_lesson')){
   function lenxel_get_course_first_lesson($course_id = 0, $post_type = null){
      global $wpdb;

      $course_id = $course_id;
      $user_id   = get_current_user_id();

      $lessons = $wpdb->get_results(
         $wpdb->prepare(
            "SELECT items.ID
            FROM 	{$wpdb->posts} topic
                  INNER JOIN {$wpdb->posts} items
                        ON topic.ID = items.post_parent
            WHERE 	topic.post_parent = %d
                  AND items.post_status = %s
                  " . ($post_type ? " AND items.post_type='{$post_type}' " : '') . '
            ORDER BY topic.menu_order ASC,
                  items.menu_order ASC;
            ',
            $course_id,
            'publish'
         )
      );

      $first_lesson = false;

      if (count($lessons)) {
         if (!empty($lessons[0])) {
            $first_lesson = $lessons[0];
         }

         foreach ($lessons as $lesson) {
            $is_complete = get_user_meta($user_id, "_tutor_completed_lesson_id_{$lesson->ID}", true);
            if (!$is_complete) {
               $first_lesson = $lesson;
               break;
            }
         }

         if (!empty($first_lesson->ID)) {
            
            return home_url() . "/dashboard/my-courses/course-title/course-details/?course_id=" . $course_id . "&lesson_id=" . $first_lesson->ID;
         }
      }

      return '';
   }
}

function get_kses_extended_ruleset() {
   $kses_defaults = wp_kses_allowed_html( 'post' );

   $svg_args = array(
       'svg'   => array(
           'class'           => true,
           'aria-hidden'     => true,
           'aria-labelledby' => true,
           'role'            => true,
           'xmlns'           => true,
           'width'           => true,
           'height'          => true,
           'viewbox'         => true, // <= Must be lower case!
       ),
       'g'     => array( 'fill' => true ),
       'title' => array( 'title' => true ),
       'path'  => array(
           'd'    => true,
           'fill' => true,
       ),
   );
   return array_merge( $kses_defaults, $svg_args );
}



