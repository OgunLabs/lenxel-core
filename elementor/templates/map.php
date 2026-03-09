<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
   $title_text = $settings['title_text'];

   $this->add_render_attribute( ['block'=>['class'=> 'widget gsc-map' ],  'title_text'=> ['class'=> 'title']] );

   $this->add_inline_editing_attributes( 'title_text', 'none' );
   $zoom = 14;
   $bubble = true;
   $style = '[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]';
   // Map initialization script is now properly enqueued via widget's get_script_depends() method
   $_id = lenxel_themesupport_random_id();
   
   // Prepare map configuration as JSON for data attribute
   $map_config = array(
      'scrollwheel' => false,
      'zoom' => absint($zoom),
      'center' => sanitize_text_field($settings['link']),
      'mapTypeId' => sanitize_text_field($settings['map_type']),
      'styles' => $style,
      'panControl' => true,
      'marker' => array(
         'position' => sanitize_text_field($settings['link']),
         'title' => sanitize_text_field($settings['title_text'])
      )
   );
   ?>
   <div <?php $this->print_render_attribute_string('carousel'); ?>>
      <div class="content-inner">
         <div id="map_canvas_<?php echo esc_attr($_id); ?>" 
              class="map_canvas lnx-gmap-init" 
              data-map-config="<?php echo esc_attr(wp_json_encode($map_config)); ?>"
              style="width:100%; height:<?php echo esc_attr($settings['height']); ?>;"></div>
      </div>
   </div>
   <div class="clearfix"></div>