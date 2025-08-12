<?php
if(!function_exists('lenxel_themesupport_random_id')){
  function lenxel_themesupport_random_id($length=4){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = '';
    for ($i = 0; $i < $length; $i++) {
      $string .= $characters[wp_rand(0, strlen($characters) - 1)];
    }
    return $string;
  }
}  

function lenxel_themesupport_get_select_term( $taxonomy ) {
  global $wpdb;
  $cats = array();
  $categories = $wpdb->get_results( $wpdb->prepare(
      "SELECT a.name, a.slug, a.term_id 
       FROM {$wpdb->terms} a 
       JOIN {$wpdb->term_taxonomy} b ON (a.term_id = b.term_id) 
       WHERE b.count > %d 
       AND b.taxonomy = %s 
       AND b.parent = %d",
      0, // Placeholder for count
      $taxonomy, // Placeholder for taxonomy
      0 // Placeholder for parent
  ));
  $cats['Choose Category'] = '';
  foreach ($categories as $category) {
     $cats[html_entity_decode($category->name, ENT_COMPAT, 'UTF-8')] = $category->slug;
  }
  return $cats;
}

  
function lenxel_theme_support_get_theme_option($key, $default = ''){
  $lenxel_theme_settings = get_option( 'lenxel_theme_settings' );
  if(isset($lenxel_theme_settings[$key]) && $lenxel_theme_settings[$key]){
     return $lenxel_theme_settings[$key];
  }else{
     return $default;
  }
  return false;
}
function lenxel_load_widget_content_element($title=null){
  $get_current_name = $title.((!lenxel_get_template_restrict()->has_premium) ? '<div class="upgrade-action" style="position: absolute;z-index: 9990;left: 0;top: 0;padding: 10px 15px 75px 106px;
        " onmousedown="trigger_dialog_premium_lenxel(event, \''.$title.' Widget\', \'Use '.$title.' widget and dozens more pro features to extend your toolbox and build sites faster and better.\'); event.preventDefault(); event.stopPropagation(); return false;" onclick="trigger_dialog_premium_lenxel(event, \''.$title.' Widget\', \'Use '.$title.' widget and dozens more pro features to extend your toolbox and build sites faster and better.\'); event.preventDefault(); event.stopPropagation(); return false;"><span class="eicon-lock" style="position: absolute;top: 10px;right:20px;"></span></div>' : '');
  return $get_current_name;
}
        