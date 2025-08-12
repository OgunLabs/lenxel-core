<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }
   use Elementor\Icons_Manager;

   $this->add_render_attribute();
   $this->add_render_attribute(['wrapper'=> ['class'=> ['gsc-brand-hover layout-carousel', $settings['style']]], 'carousel'=> ['class'=> 'init-carousel-owl owl-carousel']]);
?>

<div <?php $this->print_render_attribute_string('wrapper'); ?>>
   <div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
      <?php foreach ($settings['brands'] as $brand): ?>
         <div class="item brand-item">
            <?php include $this->get_template('brand-hover/item.php'); ?>
         </div>
      <?php endforeach; ?>
   </div>
</div>
