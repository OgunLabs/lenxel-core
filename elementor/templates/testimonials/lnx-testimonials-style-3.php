<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }
   use Elementor\Group_Control_Image_Size;
?>
   
<?php if( $settings['style'] == 'style-3' ){ 

   $this->add_render_attribute( [
      'carousel' => [
         'class' => 'init-carousel-owl owl-carousel',
      ],
      'wrapper' => [
         'class' => ['lnx-testimonial-carousel' , $settings['style']]
      ],
   ] );

   ?>
   <div <?php $this->print_render_attribute_string('wrapper'); ?>>
      <div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
         <?php
         foreach ($settings['testimonials'] as $testimonial): ?>
            <?php 
               $avatar = (isset($testimonial['testimonial_image']['url']) && $testimonial['testimonial_image']['url']) ? esc_url($testimonial['testimonial_image']['url']) : '';
            ?>
            <div class="item">
               <div class="testimonial-item">
                  <div class="testimonial-item-content">
                    
                     <div class="testimonial-content">
                        <?php echo wp_kses_post($testimonial['testimonial_content']); ?>
                     </div>
                     <div class="testimonial-meta clearfix">
                        <div class="testimonial-image">
                           <img src="<?php echo esc_url($avatar) ?>" alt="<?php echo esc_attr($testimonial['testimonial_name']); ?>" />
                           <span class="icon-quote">“</span>
                        </div>
                        <div class="testimonial-information">
                           <span class="testimonial-name"><?php echo esc_html($testimonial['testimonial_name']); ?></span>
                           <span class="testimonial-job"><?php echo wp_kses_post($testimonial['testimonial_job']); ?></span>
                        </div>
                     </div>

                  </div>   
               </div>
            </div>   
         <?php endforeach; ?>
      </div>
   </div>
   <?php
}