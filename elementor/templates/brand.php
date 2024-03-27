<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }

   use Elementor\Group_Control_Image_Size;
   $style = $settings['style'];
   $this->add_render_attribute('wrapper', 'class', ['lnx-brand-carousel' , $style ]);
   $this->add_render_attribute('carousel', 'class', ['init-carousel-owl owl-carousel']);
?>

<?php if($style == 'style-1'): ?>
   <div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('wrapper'))); ?>">
      <div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('carousel'))); ?>" <?php echo esc_attr($this->lenxel_str_replace_action(array('"'), $this->get_carousel_settings())); ?>>
         <?php foreach ($settings['brands'] as $brand): ?>
            <div class="item brand-item">
               <div class="brand-item-content">
                  <?php
                     $image_url = $brand['image']['url']; 
                  ?>
                  <img src="<?php echo esc_url($image_url); ?>" alt="" class="brand-img"/>
                  <?php echo $this->lnx_render_link_overlay($brand['link']) ?>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   </div>
<?php endif; ?>