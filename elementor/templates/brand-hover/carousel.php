<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }
   use Elementor\Icons_Manager;

   $this->add_render_attribute('wrapper', 'class', ['gsc-brand-hover layout-carousel', $settings['style']]);
   $this->add_render_attribute('carousel', 'class', ['init-carousel-owl owl-carousel']);
?>

<div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('wrapper'))); ?>">
   <div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('carousel'))); ?>" <?php echo esc_attr($this->lenxel_str_replace_action(array('"'), $this->get_carousel_settings())); ?>>
      <?php foreach ($settings['brands'] as $brand): ?>
         <div class="item brand-item">
            <?php include $this->get_template('brand-hover/item.php'); ?>
         </div>
      <?php endforeach; ?>
   </div>
</div>
