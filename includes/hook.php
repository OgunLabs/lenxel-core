<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
function lenxel_theme_support_mime_types($mimes) {
   $mimes['svg'] = 'image/svg+xml';
   return $mimes;
}
add_filter('upload_mimes', 'lenxel_theme_support_mime_types');

function lenxel_theme_support_share(){
   ob_start();
      require_once(LENXEL_PLUGIN_DIR . 'templates/sharebox-post.php');
   echo ob_get_clean();
}
add_action('lenxel_share', 'lenxel_theme_support_share', 1);

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action( 'init', 'lenxel_init_options', 1 );
function lenxel_init_options(){
   //this update the the events calendar plugin pre define tribeEventsTemplate
   if( empty(get_option( 'tribeEventsTemplate', '' )) ){
      update_option('tribeEventsTemplate', 'default');
   }
   if( empty(get_option( 'lenxel_views_v2_enabled', '' )) ){
      update_option('lenxel_views_v2_enabled', '0');
   }

   if(function_exists('tutor')){
      //this update the tutor plugin pre define add_option tutor_option
      $options    = (array) maybe_unserialize(get_option('tutor_option'));
      $update = false;
      
      if(empty($options)) return;

      if(!isset($options['course_archive_filter'])){
         $options['course_archive_filter'] = 1;
         $update = true;
      }
      if(!isset($options['course_archive_filter'])){
         $options['course_archive_filter'] = 1;
         $update = true;
      }
      if(!isset($options['supported_course_filters']['search'])){
         $options['supported_course_filters']['search'] = 1;
         $update = true;
      }
      if(!isset($options['supported_course_filters']['category'])){
         $options['supported_course_filters']['category'] = 1;
         $update = true;
      }
      if(!isset($options['supported_course_filters']['difficulty_level'])){
         $options['supported_course_filters']['difficulty_level'] = 1;
         $update = true;
      }
      if(!isset($options['supported_course_filters']['price_type'])){
         $options['supported_course_filters']['price_type'] = 1;
         $update = true;
      }
      if(!isset($options['load_tutor_js'])){
         $options['load_tutor_js'] = 1;
         $update = true;
      }
      if(!isset($options['load_tutor_css']) || $options['load_tutor_css']){
         $options['load_tutor_css'] = 0;
         $update = true;
      }
      if(!isset($options['enable_public_profile'])){
         $options['enable_public_profile'] = 1;
         $update = true;
      }
      if(!isset($options['tutor_dashboard_page_id'])){
         $options['tutor_dashboard_page_id'] = 864;
         $update = true;
      }
      if(!isset($options['course_content_access_for_ia'])){
         $options['course_content_access_for_ia'] = 1;
         $update = true;
      }
      if(!isset($options['course_archive_page'])){
         $options['course_archive_page'] = 963;
         $update = true;
      }
      if(!isset($options['enable_course_marketplace'])){
         $options['enable_course_marketplace'] = 1;
         $update = true;
      }
      if(!isset($options['instructor_register_page'])){
         $options['instructor_register_page'] = 866;
         $update = true;
      }
      if(!isset($options['enable_become_instructor_btn'])){
         $options['enable_become_instructor_btn'] = 1;
         $update = true;
      }
      if(!isset($options['student_register_page'])){
         $options['student_register_page'] = 865;
         $update = true;
      }
      if(!isset($options['students_own_review_show_at_profile'])){
         $options['students_own_review_show_at_profile'] = 1;
         $update = true;
      }
      if(!isset($options['show_courses_completed_by_student'])){
         $options['show_courses_completed_by_student'] = 1;
         $update = true;
      }
      if(!isset($options['enable_tutor_earning'])){
         $options['enable_tutor_earning'] = 1;
         $update = true;
      }
      if(!isset($options['monetize_by'])){
         $options['monetize_by'] = 'wc';
         $update = true;
      }
      if(!isset($options['enable_guest_course_cart'])){
         $options['enable_guest_course_cart'] = 1;
         $update = true;
      }
      if(!isset($options['earning_admin_commission'])){
         $options['earning_admin_commission'] = 20;
         $update = true;
      }
      if(!isset($options['earning_instructor_commission'])){
         $options['earning_instructor_commission'] = 80;
         $update = true;
      }
      if($update){
         update_option('tutor_option', $options);
      }
   }
}

add_shortcode( 'lenxel_gv-page-content', 'lenxel_theme_support_page_content_shortcode' );
function lenxel_theme_support_page_content_shortcode($atts) {
   $thispage = get_page($atts["id"]);
   return do_shortcode( $thispage->post_content );
}
add_shortcode( 'lenxel_gv-post-content', 'lenxel_theme_support_post_content_shortcode' );
function lenxel_theme_support_post_content_shortcode($atts) {
   $thispost = get_post($atts["id"]);
   return do_shortcode( $thispost->post_content );
}

add_action( 'admin_menu', 'lenxel_theme_support_remove_menu_pages', 999);
function lenxel_theme_support_remove_menu_pages() {
    if( 
      current_user_can('tutor_instructor') 
      && !current_user_can('administrator') 
      && !current_user_can('editor')
      && !current_user_can('author')
      && !current_user_can('shop_manager')
   ){
      remove_menu_page( 'index.php' );
      remove_menu_page( 'edit.php' );
      remove_menu_page( 'edit.php?post_type=footer' );
      remove_menu_page( 'edit.php?post_type=lnx_header' );
      remove_menu_page( 'edit.php?post_type=lnx_team' );
      remove_menu_page( 'edit.php?post_type=portfolio' );
      remove_menu_page( 'edit.php?post_type=elementor_library' );
      remove_menu_page( 'upload.php' );
      remove_menu_page( 'profile.php' );
      remove_menu_page( 'tools.php' );
      remove_menu_page( 'wpcf7' );
      remove_menu_page( 'edit-elementor_library' );
   }
}
//add_filter( 'tec_events_views_v1_should_display_deprecated_notice', '__return_false' );

// function lenxel_theme_support_elementor_capability_type($args){
//    $args['capability_type'] = 'post_elementor';
//    return $args;
// }
// add_filter('elementor/template_library/sources/local/register_post_type_args', 'lenxel_theme_support_elementor_capability_type');


add_action('wp_logout','lenxel_theme_support_redirect_after_logout');
function lenxel_theme_support_redirect_after_logout(){
   wp_safe_redirect( home_url() );
   exit;
}