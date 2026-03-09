<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }
   use Elementor\Icons_Manager;

   $this->add_render_attribute(['wrapper' => ['class' => ['gsc-icon-box-group layout-grid', $settings['style']]]]);

   //add_render_attribute grid
   $this->get_grid_settings();
?>

<div <?php $this->print_render_attribute_string('wrapper'); ?>>
   <div <?php $this->print_render_attribute_string('grid') ?>>
      <?php
      foreach ($settings['icon_boxs'] as $item): 
         $has_icon = ! empty( $item['selected_icon']['value']);
      ?>
         <div class="icon-box-item item-columns">
            <div class="icon-box-item-content <?php echo esc_attr($item['active'] == 'yes' ? 'active' : ''); ?>">
               <div class="icon-box-item-inner">
                  <?php include $this->get_template('icon-box-group/item.php'); ?>
               </div>
               <?php $this->lenxel_render_link_html('', $item['link'], 'link-overlay'); ?>
            </div>
         </div>
      <?php endforeach; ?>
   </div>   
</div>
