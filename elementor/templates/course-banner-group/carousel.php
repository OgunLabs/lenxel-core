<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }
   use Elementor\Icons_Manager;

   extract( $settings );

   $this->add_render_attribute('wrapper', 'class', ['gsc-course-banner-group layout-carousel', $settings['style']]);
   
   $this->add_render_attribute(['carousel' => ['class' => 'init-carousel-owl owl-carousel']]);

   $style = $settings['style'] ? $settings['style'] : 'style-1';
?>

<div <?php $this->print_render_attribute_string('wrapper'); ?>>
   <div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
      <?php
         foreach ($settings['content_banners'] as $banner): 
            include $this->get_template('course-banner-group/item-' . $style . '.php');
         endforeach; 
      ?>
   </div>
</div>
