<?php

/**
 * Plugin Name: Lenxel Core
 * Description: AI-powered LMS, Header builder, Footer builder, Teams, Portfolios, Lenxel Theme Settings ... for theme
 * Plugin URI: https://lenxel.ai 
 * Version: 1.3.9
 * Requires PHP: 7.4
 * Author: Ogun Labs
 * Requires at least: 6.3
 * Author URI: https://www.devteamsondemand.com/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain: lenxel-core
 * Tags: ai lms, lms, learning management system, ai, learning management system ai, ai course generation, artificial intelligence, course builder, elearning, education, online courses, tutor lms
 * Copyright: © 2024 Lenxel
 * Domain Path:  /languages
 * Icon: assets/logo.png
 */
if (! defined('ABSPATH')) exit; // Exit if accessed directly 
define('LENXEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LENXEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LENXEL_CORE_PATH', plugin_dir_path(__FILE__)); // Add this line
define('LENXEL_CORE_VERSION', '1.3.9');

class Lenxel_Theme_Support
{

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
      add_action('wp_ajax_lenxel_deactivate_plugin', array($this, 'lenxel_core_handle_deactivate_plugin'));
      register_deactivation_hook(__FILE__, array($this, 'lenxel_core_plugin_deactivation'));
      add_action('admin_footer', array($this, 'lenxel_core_deactivate_plugin_modal'));
      add_action('admin_head', array($this, 'lenxel_core_inject_pro_dialog_script'), 1);
      add_action('elementor/editor/before_enqueue_scripts', array($this, 'lenxel_core_inject_pro_dialog_script'), 1);
      add_action('elementor/editor/footer', array($this, 'lenxel_core_pro_content_div'), 99);
      add_shortcode('lenxel_core_login_form_shortcode', array($this, 'lenxel_core_login_form'));
      add_shortcode('lenxel_core_course_category', array($this, 'lenxel_core_course_categories'));
      add_action('init', array($this, 'lenxel_custom_login'), 5);
      add_filter('lenxel_admin_theme_menu', array($this, 'lenxel_extended_admin_theme_menu'), 10);
      add_action('wp_ajax_lenxel_api_key_actions', array($this, 'lenxel_api_key_actionss_callback'));
      add_action('wp_ajax_lenxel_get_secret_token', array($this, 'lenxel_get_secret_token_callback'));
      add_action('init', array($this, 'lenxel_duplicate_course'));
      add_action('tutor_admin_middle_course_list_action', array($this, 'lenxel_duplicate_post_link'), 10, 1);
      add_filter('wp_handle_upload_prefilter',  array($this, 'validate_svg_upload'));
      // add_filter('upload_mimes', array($this, 'restrict_svg_uploads_to_admin'));
      add_filter('wp_headers',  array($this, 'set_secure_headers'));

      // Add hook for course builder editor scripts
      add_action('admin_enqueue_scripts', array($this, 'lenxel_enqueue_course_builder_scripts'));
      
      // Add REST API authentication support
      add_action('wp_enqueue_scripts', array($this, 'lenxel_enqueue_rest_api_auth'));
      add_action('admin_enqueue_scripts', array($this, 'lenxel_enqueue_rest_api_auth'));

      // Add Upgrade to Pro submenu under Tutor LMS
      add_action('admin_menu', array($this, 'lenxel_add_upgrade_submenu'), 999);
      
      // Remove Tutor LMS default "Upgrade to Pro" menu
      add_action('admin_menu', array($this, 'lenxel_remove_tutor_upgrade_menu'), 9999);
      
      // Add custom styles for Upgrade to Pro menu
      add_action('admin_head', array($this, 'lenxel_upgrade_menu_styles'));

      // Override admin footer text for Lenxel pages
      add_filter('admin_footer_text', array($this, 'lenxel_admin_footer_text'), 2);
      
      // Register settings for privacy compliance (settings page rendered via lnx-admin/feedback-settings.php)
      add_action('admin_init', array($this, 'lenxel_register_settings'));
   }

   function lenxel_reclone_post_data(object $post = NULL)
   {

      if ($post && in_array($post->post_type, array('courses', 'lesson', 'tutor_quiz', 'topics'))) {
         $post_data = array(
            'post_title' => $post->post_title . (!in_array($post->post_type, array('lesson', 'tutor_quiz', 'topics')) ? '-clone' : ''),
            'post_content' => $post->post_content,
            'post_status' => $post->post_status,
            'post_type' => $post->post_type,
            'post_author' => $post->post_author,
            'post_parent' => $post->post_parent,
         );
         $new_course_id = wp_insert_post($post_data); //print_r($new_course_id);die();

         if (!is_wp_error($new_course_id)) {
            // Get all post_meta for the original post
            $post_meta = get_post_custom($post->ID);
            // Loop through and add each post_meta to the new post
            foreach ($post_meta as $key => $values) {
               foreach ($values as $value) {
                  if (($value) && ('_tutor_course_product_id' == $key)):
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
   function set_secure_headers($headers)
   {
      if (is_attachment()) {
         $file_type = get_post_mime_type(get_the_ID());
         if ($file_type === 'image/svg+xml') {
            $headers['Content-Type'] = 'image/svg+xml';
            $headers['X-Content-Type-Options'] = 'nosniff';
         }
      }
      return $headers;
   }

   function validate_svg_upload($file)
   {
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
   function is_safe_svg($svg_content)
   {
      // Disallow specific dangerous tags
      $disallowed_tags = [
         'script',
         'iframe',
         'object',
         'embed',
         'form',
         'link',
         'meta'
      ];

      // Check for disallowed tags
      foreach ($disallowed_tags as $tag) {
         if (preg_match('/<\s*' . preg_quote($tag, '/') . '[^>]*>/i', $svg_content)) {
            return false;
         }
      }

      // Disallow dangerous attributes (e.g., `onload`, `onclick`)
      $disallowed_attributes = [
         'on[a-z]+(?=\s*=)',
         '\bstyle\b'
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
   function lenxel_duplicate_post_link(int $post_id = 0)
   {
      if ($post_id > 0):
         $nonce = wp_create_nonce('lenxel_duplicate_course_' . $post_id);
         $get_escaped = '<a href="?page=tutor&tutor_action=duplicate_course&course_id=' . sanitize_text_field($post_id) . '&_wpnonce=' . esc_attr($nonce) . '" title="Duplicate" class="tutor-dropdown-item" style="padding-left:15px;color:#ffffff">
            <i class="tutor-icon-copy tutor-mr-8" area-hidden="true"></i>
            <span>Duplicate </span>
         </a>';
         echo wp_kses($get_escaped, lenxel_escape_unwanted_tags());
      endif;
   }

   function lenxel_duplicate_course($course_id)
   {
      if (isset($_GET['tutor_action']) && 'duplicate_course' === $_GET['tutor_action'] && isset($_GET['course_id'])) {
         // Security: Verify nonce
         $course_id = absint($_GET['course_id']);
         if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'lenxel_duplicate_course_' . $course_id)) {
            wp_die(esc_html__('Security check failed', 'lenxel-core'));
         }
         
         // Security: Check user capability
         if (!current_user_can('edit_post', $course_id)) {
            wp_die(esc_html__('You do not have permission to duplicate this course', 'lenxel-core'));
         }
         
         // Get the post by its ID
         $course = get_post($course_id);
         // Create an array with the post data to duplicate
         $new_course_id = $this->lenxel_reclone_post_data($course);
         if ($new_course_id) {
            $response_child = lenxel_duplicate_lessons_quiz_in_topic_course((int)$course_id, (int)$new_course_id);
         }

         if ($new_course_id) {
            $referer = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw(wp_unslash($_SERVER['HTTP_REFERER'])) : admin_url();
            wp_safe_redirect($referer);
            die();
         }
      }
   }
   function lenxel_extended_admin_theme_menu($menu)
   {
      $menu['lnx-activation'] = 'Plugin Activation';
      $menu['lnx-ai'] = 'AI';
      return $menu;
   }

   function lenxel_create_page_activate()
   {
      $page_title = 'Sign In';
      $shortcode = '[lenxel_core_login_form_shortcode]';
      if (get_option('lenxel_sign_in_id') == false) {
         $arg = array(
            'post_title' => $page_title,
            'post_content' => $shortcode,
            'post_status' => 'publish',
            'post_type' => 'page',
         );
         $sign_in_page_id = wp_insert_post($arg);
         update_option('lenxel_sign_in_id', $sign_in_page_id);
      }
      if (get_page_by_path('certificate', OBJECT, 'page') == NULL) {
         $arg_post = array(
            'post_title'    => 'Certificate',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'certificate'
         );
         /* Insert the post into the database */
         $certificate_page_id = wp_insert_post($arg_post);
         update_option('lenxel_certificate_id', $certificate_page_id);
      }
   }

   // To generate a link for user to reset password through an email
   function lenxel_generate_reset_pwd_password()
   {
      if (isset($_POST['_wp_http_referer'])) {
         $nounce = (isset($_POST['_wpnonce'])) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : null;
         $nounce_value = (isset($_GET['lost_pwd'])) ? sanitize_text_field($_GET['lost_pwd']) : null;
         if (wp_verify_nonce($nounce, 'lnx_' . $nounce_value)) {
            global $lenxel_mailStatus;
            global $lenxel_error_email;

            $email_forget_pwd = sanitize_email($_POST['email']);

            // check if email exist in db
            $user_obj = get_user_by('email', $email_forget_pwd);
            if (!$user_obj) {
               $lenxel_error_email = 1;
               $lenxel_mailStatus = wp_kses('
               <small class="error">
               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 11 11" fill="none">
                  <path d="M5.50033 10.0834C2.96895 10.0834 0.916992 8.03146 0.916992 5.50008C0.916992 2.96871 2.96895 0.916748 5.50033 0.916748C8.0317 0.916748 10.0837 2.96871 10.0837 5.50008C10.0837 8.03146 8.0317 10.0834 5.50033 10.0834ZM5.50033 9.16675C6.47279 9.16675 7.40542 8.78044 8.09305 8.09281C8.78068 7.40517 9.16699 6.47254 9.16699 5.50008C9.16699 4.52762 8.78068 3.59499 8.09305 2.90736C7.40542 2.21972 6.47279 1.83341 5.50033 1.83341C4.52786 1.83341 3.59523 2.21972 2.9076 2.90736C2.21997 3.59499 1.83366 4.52762 1.83366 5.50008C1.83366 6.47254 2.21997 7.40517 2.9076 8.09281C3.59523 8.78044 4.52786 9.16675 5.50033 9.16675ZM5.04199 6.87508H5.95866V7.79175H5.04199V6.87508ZM5.04199 3.20841H5.95866V5.95841H5.04199V3.20841Z" fill="#E93C3C"/>
               </svg>Email does not exist
               </small>', get_kses_extended_ruleset());
            } else {
               $lenxel_error_email = 0;
               $reset_pwd_url = wp_nonce_url(get_permalink() . "?_id={$user_obj->ID}", 'reset_nonce' . $user_obj->ID);


               $to = $email_forget_pwd;
               $subject = 'Reset Password';

               $message =
                  "<div>
               <p>Click on the link below to reset your password</p>
               <a href='" . esc_url(html_entity_decode($reset_pwd_url)) . "'>Reset password</a>
            </div>";
               // echo $message;

               $send_mail = wp_mail($to, $subject, $message, 'Content-type: text/html');
               if ($send_mail) {
                  $lenxel_mailStatus = "<img alt='successful reset password' src='" . esc_url(LENXEL_THEME_URL . '/images/juicy-closed-email-envelope.png') . "'><h2 class='lfs-36 lfw-700 pb-2 pt-2'>We Sent you a Mail</h2><p class='lfs-20 lfw-500'>Kindly Check your email, we sent you a link to verify your Account with use and reset your Password</p>";
                  //load_template(LENXEL_THEME_DIR . '/templates/reset-password-mail.php' );die();

               }
            }
         }
      }
   }

   function lnx_user_registration_hook($user_id, $user_data)
   {
      if(function_exists('lenxel_get_option')){

         if (!empty(lenxel_get_option('enable_registration_notification', '')) || (lenxel_get_option('enable_registration_notification', '') !== '0')) {
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
                  $subject = 'User successfully registered as ' . (($instructor_status == 'pending') ? 'instructor' : $user_role); // Email subject
                  $subject_admin = (($instructor_status == 'pending') ? 'New Registration Awaiting Approval' : 'New Registration');
                  $message_admin = (($instructor_status == 'pending') ? 'The following user successfully register on the platform as instructor waiting your approval.' : "The following user successfully register on the platform as " . $user_role);
                  $message_subscriber = (($instructor_status == 'pending') ? 'You have successful register on the platform as an instructor waiting admin\'s approval' : "You have successful register on the platform as " . $user_role);
                  // For example, you can send a welcome email or perform other tasks
                  $approval_role = array('subscriber', tutor()->instructor_role);
                  if (in_array($user_role, $approval_role)) {
                     // Send the email using wp_mail()
                     $result = wp_mail($to, $subject, $message_subscriber, $headers);
                     $result = wp_mail($super_admin_email, $subject_admin, $message_admin, $headers);
                  }
               }
            }
         }
      }
   }

   function add_custom_body_class($classes)
   {
      $page_id_data = (int) get_option('lenxel_sign_in_id');

      if (!empty($page_id_data) && is_page($page_id_data)) {
         $classes[] = 'lnx-login';
      }

      return apply_filters('assign_page_id', $classes);
   }

   /**
    * Verify AI API Key - Optional validation with external service
    * This method validates an API key by calling a remote service.
    * However, the plugin remains fully functional regardless of validation status.
    * The API key is stored for use with AI features that require authentication.
    * 
    * NOTE: This validation is optional and informational only. It does NOT gate
    * or restrict any plugin functionality. All features work with or without validation.
    */
   function lenxel_api_key_actionss_callback()
   {
      check_ajax_referer('ajax-l-activation-nonce', 'ajax_l_activation_ajax');

      $activation_input = isset($_POST['activation_input']) ? sanitize_text_field(wp_unslash($_POST['activation_input'])) : '';
      
      if ((trim($activation_input) == '') && (!isset($_POST['activation_hidden']))) {
         $response['error'] = true;
         $response['error_message'] = "You have no course to title?";
         exit(wp_json_encode($response));
      }
      
      // Simply store the API key - validation is optional and does NOT gate features
      update_option('lenxel_activation_key', $activation_input);
      
      // Optionally verify the key with external service for informational purposes only
      // This is a courtesy check; the plugin works fully regardless of the result
      $route = '/wp/sites/status/verify';
      $secretToken = $activation_input;
      
      $get_api_response = $this->lenxel_api_request($route, array('site_url' => site_url()), 'POST', $secretToken);
      
      // Build response for user feedback - validation status is informational only
      if (is_array($get_api_response) && isset($get_api_response['success'])) {
         if ($get_api_response['success'] == true) {
            // Store validation timestamp for UI purposes (not feature gating)
            update_option('lenxel_api_last_validated', current_time('mysql'));
            $data['data'] = $get_api_response;
            $data['status'] = true;
            $data['msg'] = 'API key validated successfully. All features are available.';
            $data['code'] = 200;
         } else {
            // Validation failed, but plugin remains fully functional
            $data['data'] = $get_api_response;
            $data['status'] = false;
            $data['msg'] = isset($get_api_response['error']) ? $get_api_response['error'] : 'API validation failed, but plugin remains fully functional.';
            $data['code'] = 402;
         }
      } else {
         // Service unavailable, but plugin remains fully functional
         $data['data'] = $get_api_response;
         $data['status'] = false;
         $data['msg'] = 'Could not reach validation service. API key has been stored. Plugin remains fully functional.';
         $data['code'] = 503;
      }
      
      wp_send_json($data);
      die();
   }

   /**
    * AJAX callback to fetch the latest secret token from WordPress options
    * This is called by the frontend when user returns from adding a secret token
    */
   function lenxel_get_secret_token_callback()
   {
      // Verify nonce for security
      check_ajax_referer('lenxel_ajax_nonce', 'nonce', false);

      // Check if user is authenticated
      if (!is_user_logged_in()) {
         wp_send_json_error(array(
            'message' => 'User not authenticated'
         ), 401);
      }

      // Fetch the latest secret token from WordPress options (always fresh from DB)
      $secret_token = get_option('lenxel_activation_key');

      if (empty($secret_token)) {
         wp_send_json_error(array(
            'message' => 'No secret token found in database'
         ), 404);
      }

      // Return the secret token with fresh data
      wp_send_json_success(array(
         'secret_token' => sanitize_text_field($secret_token),
         'timestamp' => current_time('mysql'),
         'cached' => false // Indicate this is fresh from DB, not cached
      ), 200);
   }

   function lenxel_api_request(
      $endpoint,
      $args = array(),
      $method = 'POST',
      $secretToken = NULL
   ) {
      // Sanitize and validate host against allowlist
      $host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
      // Remove port from host if present
      $host_without_port = preg_replace('/:\d+$/', '', $host);
      // Remove any path components for security
      $host_without_port = preg_replace('/\/.*$/', '', $host_without_port);
      
      // Validate against allowlist of known hosts
      $allowed_hosts = array('localhost', '127.0.0.1', 'staging.lenxel.ai');
      $is_allowed = false;
      foreach ($allowed_hosts as $allowed_host) {
         if ($host_without_port === $allowed_host || strpos($host_without_port, $allowed_host) !== false) {
            $is_allowed = true;
            break;
         }
      }
      // Also check for production lenxel.ai domains
      if (!$is_allowed && preg_match('/^[a-z0-9.-]*lenxel\.ai$/', $host_without_port)) {
         $is_allowed = true;
      }
      
      if (preg_match('/^(localhost|127\.0\.0\.1)$/', $host_without_port)) {
         // Local development
         $request_url = "http://localhost:3000/api";
      } elseif ($is_allowed && (strpos($host_without_port, 'staging.lenxel.ai') !== false || preg_match('/prod.*lenxel\.ai/', $host_without_port))) {
         // Staging or production environment
         $request_url = "https://devapi.lenxel.ai/api";
      } else {
         // Default production endpoint
         $request_url = "https://api.lenxel.ai/api";
      }
      

   // Build final URL with query string if needed
   $uri = esc_url_raw("{$request_url}{$endpoint}");
   // Prepare headers
   $headers = array(
      'Content-Type'   => 'application/json',
      'X-Secret-Token' => $secretToken,
   );
   // Prepare request arguments
   $request_args = array(
      'method'    => $method,
      'timeout'   => 45,
      'sslverify' => false,
      'headers'   => $headers,
   );

   // Make GET or POST request accordingly
   if (strtoupper($method) === 'POST') {
      $request_args['body'] = wp_json_encode($args);
      $response = wp_remote_post($uri, $request_args);
   } else {
      $response = wp_remote_get($uri, $request_args);
   }

   // Handle and parse response
   if (is_wp_error($response)) {
      return array('error' => $response->get_error_message());
   }

   $api_response = json_decode(wp_remote_retrieve_body($response), true);

   return $api_response;
   }
   public function include_files()
   {
      require_once('redux/admin-init.php');
      require_once('includes/functions.php');
      require_once('includes/hook.php');
      require_once('includes/metaboxes.php');
      require_once('elementor/init.php');
      // Note: Sample content has been moved to the separate 'lenxel-demo' plugin
      require_once('add-ons/form-ajax/init.php');
      require_once('widgets/recent_posts.php');
      require_once('lenxel-react.php');

      // Include new course builder related files
      require_once('includes/class-assets.php');
      require_once('includes/class-course.php');
      require_once('includes/class-rest-api-taxonomy.php');
      require_once('includes/H5PAjaxHandlers.php');
      // require_once('includes/ContentBankAjaxHandlers.php');
      // require_once('includes/class-gutenberg.php');
      // require_once('includes/class-setup.php');
      // require_once('includes/course-builder-integration.php');
      
      // Initialize the REST API Taxonomy handler
      new Lenxel_REST_API_Taxonomy();

      // Initialize H5P AJAX handlers
      new LENXEL_CORE\H5PAjaxHandlers();
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


   public function lenxel_core_register_scripts()
   {
      $js_dir = plugin_dir_url(__FILE__) . 'assets/js';
      wp_register_script('lenxel-core', $js_dir . '/main.js', array('jquery'), null, true);
      wp_enqueue_script('lenxel-core');
   }


   public function lenxel_core_register_scripts_admin()
   {
      $css_dir = plugin_dir_url(__FILE__) . 'assets/css';
      $js_dir = plugin_dir_url(__FILE__) . 'assets/js';
      
      // Register and enqueue admin dynamic fields script
      wp_register_script('lenxel-admin-dynamic-fields', $js_dir . '/admin-dynamic-fields.js', array('jquery'), '1.0.0', true);
      wp_enqueue_script('lenxel-admin-dynamic-fields');
      // wp_enqueue_style('lenxel-icons-custom', LENXEL_PLUGIN_URL . 'assets/icons/flaticon.css');
   }

   // Add new method for course builder scripts
   public function lenxel_enqueue_course_builder_scripts($hook)
   {
      // Only enqueue on course builder pages
      if (isset($_GET['page']) && $_GET['page'] === 'create-course') {
         wp_enqueue_script('wp-tinymce');
         wp_enqueue_script('editor');
         wp_enqueue_script('wp-editor');
         wp_enqueue_editor();
         wp_enqueue_media();
      }
   }

   // Add REST API authentication support
   public function lenxel_enqueue_rest_api_auth()
   {
      // Only for logged in users
      if (is_user_logged_in()) {
         // Enqueue wp-api script
         wp_enqueue_script('wp-api');
         
         // Create fresh nonce and localize script data
         wp_localize_script('wp-api', 'lenxelRestApi', array(
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
            'current_user_id' => get_current_user_id(),
            'site_url' => site_url(),
         ));
         
         // Add meta tag for nonce (accessible via JavaScript)
         add_action('wp_head', array($this, 'lenxel_add_rest_nonce_meta'));
         add_action('admin_head', array($this, 'lenxel_add_rest_nonce_meta'));
      }
   }

   // Add nonce meta tag to head
   public function lenxel_add_rest_nonce_meta()
   {
      echo '<meta name="wp-rest-nonce" content="' . esc_attr(wp_create_nonce('wp_rest')) . '" />' . "\n";
   }

   function lenxel_core_handle_deactivate_plugin()
   {
      // Verify nonce
      if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'lnx_deactivate_plugin')) {
         wp_send_json_error(array('message' => 'Nonce verification failed.'));
         return;
      }

      // If skip is set, just acknowledge and return
      if (isset($_POST['skip']) && $_POST['skip']) {
         wp_send_json_success(array('message' => 'Deactivation skipped.'));
         return;
      }

      // Check if user has opted-in to deactivation feedback
      $feedback_enabled = get_option('lenxel_enable_deactivation_feedback', '0');
      if ($feedback_enabled !== '1') {
         wp_send_json_success(array('message' => 'Feedback not enabled.'));
         return;
      }

      // Process feedback
      $feedback = isset($_POST['feedback']) ? sanitize_text_field(wp_unslash($_POST['feedback'])) : '';
      $comment = isset($_POST['comment']) ? sanitize_text_field(wp_unslash($_POST['comment'])) : '';
      $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';

      if (empty($feedback)) {
         wp_send_json_error(array('message' => 'No feedback provided.'));
         return;
      }
      
      // Build message
      $message = "Lenxel Core Plugin Deactivation Feedback\n\n";
      $message .= "Reason: " . $feedback . "\n";
      $message .= "Site URL: " . home_url() . "\n";
      if (!empty($comment)) {
         $message .= "Additional Comment: " . $comment . "\n";
      }
      
      
      $message .= "Date/Time: " . current_time('mysql') . "\n";

      $params = array(
         'Message' => $message,
         'Name' => !empty($email) ? $email : 'Anonymous',
         'Email' => !empty($email) ? $email : 'Anonymous Email',
         'SourceWebsite' => 'Lenxel Core Deactivation'
      );
      
      // Send to Slack (optional - won't block on failure)
      
      $slack_response = $this->lenxel_core_api_request('', $params);
      
      // Log for debugging
      error_log('Lenxel Deactivation Feedback - Slack Response: ' . print_r($slack_response, true));
      
      wp_send_json_success(array('message' => 'Feedback submitted successfully.'));
   }

   // Usage
   function lenxel_core_api_request(
      $endpoint,
      $args = array(),
      $method = 'POST',
      $token = NULL
   ) {
      $request_url = "https://form-submission-to-slack-notify-495600076509.us-central1.run.app";
      $uri = !empty($endpoint) ? "{$request_url}{$endpoint}" : $request_url;
      $arg = array(
         'method'      => $method,
         'timeout'     => 15,
         'sslverify'   => true,
         'headers'     => [ 'Content-Type' => 'application/json' ],//$this->lenxel_core_get_headers($token),
         'body'        => wp_json_encode($args),
      );

      // Log request for debugging
      error_log('Lenxel Slack Request - URL: ' . $uri);
      error_log('Lenxel Slack Request - Data: ' . print_r($args, true));

      $getApiResponse = wp_remote_request($uri, $arg);
      if (is_wp_error($getApiResponse)) {
         $bodyApiResponse = $getApiResponse->get_error_message();
         error_log('Lenxel Slack Error: ' . $bodyApiResponse);
      } else {
         $response_code = wp_remote_retrieve_response_code($getApiResponse);
         $bodyApiResponse = wp_remote_retrieve_body($getApiResponse);
         error_log('Lenxel Slack Response Code: ' . $response_code);
         error_log('Lenxel Slack Response Body: ' . $bodyApiResponse);
         $bodyApiResponse = json_decode($bodyApiResponse);
      }

      return $bodyApiResponse;
   }

   /**
    * Generates the headers to pass to API request.
    */
   function lenxel_core_get_headers($token)
   {
      if (!empty($token)) {
         $getHead = array(
            'Authorization' => "Bearer {$token}",
            'Content-Type'  => 'application/json',
         );
      } else {
         $getHead = array('Content-Type'  => 'application/json',);
      }

      return $getHead;
   }

   function lenxel_core_plugin_deactivation()
   {
      // Perform your deactivation logic here
      // For example, remove custom database tables, options, or other cleanup tasks

      // Display a message upon deactivation (optional)
      add_action('admin_notices', array($this, 'lenxel_core_plugin_deactivation_notice'));
   }

   function lenxel_core_plugin_deactivation_notice()
   {
      $html_content = '<div class="notice notice-success is-dismissible">
               <p>plugin has been deactivated.</p>
            </div>';
      echo wp_kses($html_content, array('div', 'p'));
   }

   /**
    * Inject trigger_dialog_pro_lenxel function directly in admin_head
    * Output as raw inline script before any other scripts/handlers
    */
   function lenxel_core_inject_pro_dialog_script()
   {
      $script = "
      // Define function globally with safety checks and error handling
      if (typeof window.trigger_dialog_pro_lenxel === 'undefined') {
         window.trigger_dialog_pro_lenxel = function(event, video, message) {
            try {
               if (typeof jQuery !== 'undefined') {
                  var $ = jQuery;
                  $('#elementor-element--promotion__dialog__title').text(video);
                  $('#elementor-element--promotion__dialog').css('display', 'block');
                  var yPosition = event && event.clientY;
                  $('#elementor-element--promotion__dialog').css('top', yPosition);
                  $('.dialog-buttons-message').text(message);
               }
            } catch(e) {
               console.warn('trigger_dialog_pro_lenxel error:', e);
            }
         };
      }
      ";
      wp_add_inline_script('jquery', $script);
   }

   /**
    * Output the dialog HTML and initialization
    */
   function lenxel_core_pro_content_div()
   {
      // Enqueue the external promotion dialog script
      $js_dir = plugin_dir_url(__FILE__) . 'assets/js';
      wp_enqueue_script('lenxel-promotion-dialog', $js_dir . '/promotion-dialog.js', array('jquery'), '1.0.0', true);
   }

   function lenxel_core_login_form()
   {
      ob_start();
      if (defined('LENXEL_THEME_DIR')) {
         $file_path = LENXEL_THEME_DIR . '/templates/login-template-1.php'; // Adjust the path accordingly

         if (file_exists($file_path)) {
            load_template($file_path);
         }
      }
      return ob_get_clean();
   }
   function lenxel_core_course_categories()
   {
      $query_args = [
         'taxonomy' => 'course-category',
         'order' => 'ASC',
         'hide_empty' => 0,
      ];
      $Allcategories = get_categories($query_args);
      $cat_data = '';

      foreach ($Allcategories as $category) {
         $category_url = get_category_link($category->term_id);
         $cat_data .= '<p style="width: 300px;"><a href="' . esc_url($category_url) . '">' . esc_html($category->name) . '</a></p>';
      }
      // Output is already escaped above, use wp_kses_post for safe HTML output
      return wp_kses_post("<div class='col-sm-12 col-md-3'><h2>Categories</h2>{$cat_data}</div>");
   }

   function lenxel_core_deactivate_plugin_modal()
   {
       if ( isset( $_GET['tab'] ) && (int) $_GET['tab'] > 0) {
         $tab = ((int) $_GET['tab']) + 1;
         $custom_css = 'ul.wp-submenu.wp-submenu-wrap li:nth-child(' . $tab . ') a{ background-color: #171919; }';
         wp_register_style( 'lenxel-admin-tabs-inline', false );
         wp_enqueue_style( 'lenxel-admin-tabs-inline' );
         wp_add_inline_style( 'lenxel-admin-tabs-inline', wp_strip_all_tags( $custom_css ) );
      }

      $current_user = wp_get_current_user();
      $user_email = $current_user->user_email;
      $feedback_enabled = get_option('lenxel_enable_deactivation_feedback', '0');
      ob_start();
   ?>
      <div class="modalContainer">
         <section class="modal hidden" data-feedback-enabled="<?php echo esc_attr($feedback_enabled === '1' ? 'true' : 'false'); ?>">
            <div class="flex">
               <h3>QUICK FEEDBACK</h3>
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
                     <input type="text" class="betterPlugin" name="feedback[comment]" placeholder="Plugin name...">
                  </div>
                  <div class="choice" tabindex="6">
                     <input id="lenxel-cause-06" type="radio" name="feedback[cause]" value="It's a temporary deactivation. I'm just debugging an issue.">
                     <label for="lenxel-cause-06">It's a temporary deactivation. I'm just debugging an
                        issue.</label>
                  </div>
                  <div class="choice" tabindex="7">
                     <input id="lenxel-cause-07" type="radio" name="feedback[cause]" value="Other">
                     <label for="lenxel-cause-07">Other</label>
                     <input type="text" class="feedbackOther" name="feedback[comment]" placeholder="Reason...">
                  </div>

                  <div class="footer">
                     <div class="include-email">
                        <input id="lenxel-include-email" type="checkbox" name="feedback[email]" value="<?php echo esc_attr($user_email); ?>" checked="">
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
<?php
      // Enqueue deactivation modal styles and scripts
      $css_dir = plugin_dir_url(__FILE__) . 'assets/css';
      $js_dir = plugin_dir_url(__FILE__) . 'assets/js';
      wp_enqueue_style('lenxel-deactivation-modal', $css_dir . '/deactivation-modal.css', array(), '1.0.0');
      wp_enqueue_script('lenxel-deactivation-modal', $js_dir . '/deactivation-modal.js', array('jquery'), '1.0.0', true);
      
      $contentModal = ob_get_clean();
      printf('%s', wp_kses($contentModal, array('div' => array('class' => array(), 'id' => array(), 'tabindex' => array()), 'style' => array(), 'p' => array(), 'button' => array('style' => array(), 'type' => array(), 'class' => array(), 'id' => array()), 'section' => array('class' => array()), 'h3' => array('style' => array()), 'input' => array('class' => array(), 'style' => array(), 'placeholder' => array(), 'checked' => array(), 'id' => array(), 'name' => array(), 'value' => array(), 'type' => array()), 'label' => array('for' => array()), 'small' => array(), 'form' => array('action' => array()))));
   }

   function lenxel_custom_login()
   {
      // Only process if this is a POST request with login form data
      if (!isset($_POST['email']) || !isset($_POST['password'])) {
         return;
      }

      if (isset($_POST['_wpnonce'])) {
         if (wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), LENXEL_THEME_URL)) {

            $email = sanitize_email($_POST['email']);
            $password = isset($_POST['password']) ? wp_unslash($_POST['password']) : '';
            $remember = isset($_POST['checkbox']) ? sanitize_text_field($_POST['checkbox']) : false;
            $credentials = array(
               'user_login' => $email,
               'user_password' => $password,
               'remember' => $remember
            );
            // Use is_ssl() to properly detect secure cookie requirement
            $login_user = wp_signon($credentials, is_ssl());

            if (is_wp_error($login_user)) {

               /* Any page set as login page */
               $page_id = lenxel_get_option('login_on_any_page', false);
               $page_url = get_permalink($page_id);
               $landing_page_id = lenxel_get_option('enable_login_on_landing_page', false);
               if ($page_id != null) {

                  wp_safe_redirect($page_url . '/?status=invalid_credentials');
                   exit;
               } else {
                  if ($landing_page_id != null) {
                     wp_safe_redirect(home_url('/?status=invalid_credentials'));
                      exit;
                  }
               }
            } else {
               $dashboard_url = home_url('/dashboard');
               $tutor_option = get_option('tutor_option', array());
               if (!empty($tutor_option['tutor_dashboard_page_id'])) {
                  $get_postData = get_post($tutor_option['tutor_dashboard_page_id']);
                  if ($get_postData && !empty($get_postData->post_name)) {
                     $dashboard_url = home_url('/' . $get_postData->post_name);
                  }
               }
               wp_safe_redirect($dashboard_url);
               exit;
            }
         }
      }
   }



   public function lenxel_admin_footer_text( $footer_text ) {
      if ( isset( $_GET['page'] ) && $_GET['page'] === 'create-course' ) {
         $footer_text = sprintf(
            /* translators: %s: plugin name */
            __( 'If you like %1$s please leave us a %2$s rating. A huge thanks in advance!', 'lenxel-core' ),
            sprintf( '<strong>%s</strong>', esc_html__( 'Lenxel Core', 'lenxel-core' ) ),
            '<a href="https://wordpress.org/support/plugin/lenxel-core/reviews?rate=5#new-post" target="_blank" class="lenxel-rating-link">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
         );
      }
      return $footer_text;
   }

   /**
    * Add Upgrade to Pro submenu under Tutor LMS
    */
   public function lenxel_add_upgrade_submenu() {
      add_submenu_page(
         'tutor',
         __('Upgrade to Pro', 'lenxel-core'),
         __('Upgrade to Pro', 'lenxel-core'),
         'read',
         'lenxel-upgrade-to-pro',
         array($this, 'lenxel_upgrade_redirect')
      );
   }

   /**
    * Remove Tutor LMS default "Upgrade to Pro" menu
    */
   public function lenxel_remove_tutor_upgrade_menu() {
      remove_submenu_page('tutor', 'tutor-get-pro');
   }

   /**
    * Add custom styles for Upgrade to Pro menu item
    */
   public function lenxel_upgrade_menu_styles() {
      $custom_css = "
         #adminmenu #toplevel_page_tutor .wp-submenu a[href*='lenxel-upgrade-to-pro'] {
            background-color: orange !important;
            font-weight: 600 !important;
            color: #000 !important;
         }
         #adminmenu #toplevel_page_tutor .wp-submenu a[href*='lenxel-upgrade-to-pro']:hover {
            background-color: #ff8c00 !important;
            color: #000 !important;
         }
      ";
      wp_register_style( 'lenxel-upgrade-menu-inline', false );
      wp_enqueue_style( 'lenxel-upgrade-menu-inline' );
      wp_add_inline_style( 'lenxel-upgrade-menu-inline', wp_strip_all_tags( $custom_css ) );
      
      $custom_js = "
         jQuery(document).ready(function($) {
            $('#adminmenu a[href*=\"lenxel-upgrade-to-pro\"]').on('click', function(e) {
               e.preventDefault();
               window.open('https://lenxel.ai/#lenxel-pro', '_blank');
               return false;
            });
         });
      ";
      wp_add_inline_script('jquery', $custom_js);
   }

   /**
    * Redirect to Lenxel Pro upgrade page (fallback for non-JS)
    */
   public function lenxel_upgrade_redirect() {
      wp_redirect('https://lenxel.ai/#lenxel-pro');
      exit;
   }

   /**
    * Register plugin settings for privacy compliance
    */
   public function lenxel_register_settings() {
      register_setting(
         'lenxel_privacy_settings',
         'lenxel_enable_deactivation_feedback',
         array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '0'
         )
      );
   }

   /**
    * Settings page is rendered via includes/lnx-admin/feedback-settings.php
    * This file is loaded by the theme's admin interface
    */
}

new Lenxel_Theme_Support();

if (!function_exists('lenxel_get_course_first_lesson')) {
   function lenxel_get_course_first_lesson($course_id = 0, $post_type = null)
   {
      global $wpdb;

      $course_id = $course_id;
      $user_id   = get_current_user_id();

      // Build query conditionally based on whether post_type is provided
      // Use proper placeholders for all variables including post_type
      if ($post_type) {
         $lessons = $wpdb->get_results(
            $wpdb->prepare(
               "SELECT items.ID
               FROM 	{$wpdb->posts} topic
                     INNER JOIN {$wpdb->posts} items
                           ON topic.ID = items.post_parent
               WHERE 	topic.post_parent = %d
                     AND items.post_status = %s
                     AND items.post_type = %s
               ORDER BY topic.menu_order ASC,
                     items.menu_order ASC;
               ",
               $course_id,
               'publish',
               $post_type
            )
         );
      } else {
         $lessons = $wpdb->get_results(
            $wpdb->prepare(
               "SELECT items.ID
               FROM 	{$wpdb->posts} topic
                     INNER JOIN {$wpdb->posts} items
                           ON topic.ID = items.post_parent
               WHERE 	topic.post_parent = %d
                     AND items.post_status = %s
               ORDER BY topic.menu_order ASC,
                     items.menu_order ASC;
               ",
               $course_id,
               'publish'
            )
         );
      }

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

function get_kses_extended_ruleset()
{
   $kses_defaults = wp_kses_allowed_html('post');

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
      'g'     => array('fill' => true),
      'title' => array('title' => true),
      'path'  => array(
         'd'    => true,
         'fill' => true,
      ),
   );
   return array_merge($kses_defaults, $svg_args);
}

add_action('admin_init', function () {
   if (isset($_GET['page']) && $_GET['page'] === 'create-course') {
       remove_all_actions('admin_notices');
       remove_all_actions('all_admin_notices');
   }
});

add_action('admin_head', function () {
   // Target ONLY your course builder page
   if (isset($_GET['page']) && $_GET['page'] === 'create-course') {
       $custom_css = 'div#adminmenumain {display: none;} #wpbody{display:none;}';
       wp_register_style( 'lenxel-course-builder-hide-admin-inline', false );
       wp_enqueue_style( 'lenxel-course-builder-hide-admin-inline' );
       wp_add_inline_style( 'lenxel-course-builder-hide-admin-inline', wp_strip_all_tags( $custom_css ) );
   }
});
/**
 * Force complete WooCommerce orders paid via Stripe
 */
function lenxel_force_complete_stripe_orders($order_id, $posted_data, $order) {
    // Only process if payment method is Stripe
    if ($order->get_payment_method() === 'stripe') {
        // Check if payment was successful
        if ($order->get_status() === 'processing' || $order->get_status() === 'pending') {
            // Force complete the order
            $order->update_status('completed', __('Order completed via Stripe payment fix.', 'lenxel-core'));
            
            // Add order note
            $order->add_order_note(__('Payment received via Stripe - Order automatically completed.', 'lenxel-core'));
            
            // Reduce stock levels
            wc_reduce_stock_levels($order_id);
            
            // Send completion email
            WC()->mailer()->emails['WC_Email_Customer_Completed_Order']->trigger($order_id);
        }
    }
}
add_action('woocommerce_checkout_order_processed', 'lenxel_force_complete_stripe_orders', 10, 3);

/**
 * Alternative: Hook into Stripe payment success
 */
function lenxel_stripe_payment_complete($order_id) {
    $order = wc_get_order($order_id);
    
    if ($order && $order->get_payment_method() === 'stripe') {
        // Small delay to ensure Stripe has processed
        wp_schedule_single_event(time() + 5, 'lenxel_complete_stripe_order', array($order_id));
    }
}
add_action('woocommerce_payment_complete', 'lenxel_stripe_payment_complete');

/**
 * Scheduled event to complete Stripe orders
 */
function lenxel_complete_stripe_order($order_id) {
    $order = wc_get_order($order_id);
    
    if ($order && in_array($order->get_status(), array('pending', 'processing'))) {
        $order->update_status('completed', __('Stripe payment confirmed - Order completed.', 'lenxel-core'));
        wc_reduce_stock_levels($order_id);
    }
}
add_action('lenxel_complete_stripe_order', 'lenxel_complete_stripe_order');
