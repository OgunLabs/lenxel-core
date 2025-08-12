<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }
   use Elementor\Icons_Manager;

   $this->add_render_attribute(['carousel' => ['class' => 'init-carousel-owl owl-carousel'], 'wrapper' => ['class' => ['gsc-icon-box-group layout-carousel', $settings['style']]]]);
?>

<div <?php $this->print_render_attribute_string('wrapper'); ?>>
   <div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
      <?php foreach ($settings['icon_boxs'] as $item): ?>
         <?php include $this->get_template('icon-box-group/item.php'); ?>
      <?php endforeach; ?>
   </div>
</div>
