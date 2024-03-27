<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }
   use Elementor\Group_Control_Image_Size;
   use Elementor\Icons_Manager;

?>
<?php 
   $this->add_render_attribute('wrapper', 'class', ['lnx-hover-box-carousel' , $settings['style'] ]);
   $this->add_render_attribute('carousel', 'class', ['init-carousel-owl owl-carousel']);
?>

<div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('wrapper'))); ?>">
   <div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('carousel'))); ?>" <?php echo esc_attr($this->lenxel_str_replace_action(array('"'), $this->get_carousel_settings())); ?>>
      <?php
      foreach ($settings['content_items'] as $box):
         $has_icon = ! empty( $box['selected_icon']['value']);
         $box_image = (isset($box['box_image']['url']) && $box['box_image']['url']) ? $box['box_image']['url'] : '';
      ?>
         <div class="item hover-box-item">
            
            <div class="box-background" style="background-image:url('<?php echo ($box_image ? $box_image : '' ); ?>')"></div>
            
            <div class="box-content">
               <div class="content-inner">
                  <?php if ( $has_icon ){ ?>
                     <div class="icon-inner">
                        <?php if ( $has_icon ){ ?>
                           <?php $this->lnx_render_link_begin($box['link']); ?>
                              <span class="box-icon">
                                 <?php Icons_Manager::render_icon( $box['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                              </span>
                           <?php $this->lnx_render_link_end($box['link']); ?>
                        <?php } ?>
                     </div>
                  <?php } ?>
                  
                  <div class="box-title">
                     <?php echo esc_html($box['box_title']); ?>
                  </div>

                  <div class="box-desc">
                     <?php echo esc_html($box['box_content']); ?>
                  </div>
               </div>
            </div>   
           
         </div>
      <?php endforeach; ?>
   </div>
</div>
