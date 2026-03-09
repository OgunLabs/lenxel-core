<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }

   use Elementor\Group_Control_Image_Size;
   $style = $settings['style'];
   $this->add_render_attribute(['wrapper'=> ['class'=> ['lnx-brand-carousel' , $style ]], 'carousel'=> ['class'=> 'init-carousel-owl owl-carousel']]);
?>

<?php if($style == 'style-1'): ?>
   <div <?php $this->print_render_attribute_string('wrapper'); ?>>
      <div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
         <?php foreach ($settings['brands'] as $brand): ?>
            <div class="item brand-item">
               <div class="brand-item-content">
                  <?php
                     $image_url = $brand['image']['url']; 
                  ?>
                  <img src="<?php echo esc_url($image_url); ?>" alt="" class="brand-img"/>
                  <?php $link_overlay = $this->lenxel_render_link_overlay($brand['link']);
						 echo wp_kses( $link_overlay, array('a'=>array('class'=>array(), 'id'=>array()), 'div'=>array('class'=>array()),'span'=>array('class'=>array())) ) ?>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   </div>
<?php endif; ?>