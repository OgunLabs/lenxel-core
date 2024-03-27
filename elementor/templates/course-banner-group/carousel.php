<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }
   use Elementor\Icons_Manager;

   extract( $settings );

   $this->add_render_attribute('wrapper', 'class', ['gsc-course-banner-group layout-carousel', $settings['style']]);
   
   $this->add_render_attribute('carousel', 'class', ['init-carousel-owl owl-carousel']);

   $style = $settings['style'] ? $settings['style'] : 'style-1';
?>

<div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('wrapper'))); ?>">
   <div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('carousel'))); ?>" <?php echo esc_attr($this->lenxel_str_replace_action(array('"'), $this->get_carousel_settings())); ?>>
      <?php
         foreach ($settings['content_banners'] as $banner): 
            include $this->get_template('course-banner-group/item-' . $style . '.php');
         endforeach; 
      ?>
   </div>
</div>
