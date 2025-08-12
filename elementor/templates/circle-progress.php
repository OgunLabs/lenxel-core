<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  use Elementor\Icons_Manager;
   $settings = $this->get_settings_for_display();
   $this->add_render_attribute( ['block'=> ['class'=> 'gsc-circle-progress clearfix'], 'title_text'=> ['class'=> 'title']] );

   $value = !empty($settings['number']) ? $settings['number']/100 : 0.5;
   $thickness = $settings['thickness'];
   $empty_fill = !empty($settings['empty_fill']) ? $settings['empty_fill'] : '#303030';;
   $color = !empty($settings['color']) ? $settings['color'] : '#CB9D54';
   ?>
   
   <div <?php $this->print_render_attribute_string('carousel'); ?>>
      <div class="circle-progress" data-value="<?php echo esc_attr($value); ?>"  data-thickness="<?php echo esc_attr($thickness); ?>" data-empty-fill="<?php echo esc_attr($empty_fill); ?>" data-lineCap="square" data-size="128" data-fill="{ &quot;color&quot;: &quot;<?php echo esc_attr($color); ?>&quot; }">
         <strong></strong>
      </div> 
      <?php if(!empty($settings['title'])){ ?>
         <div class="title">
            <span><?php echo esc_html( $settings['title'] ); ?></span>
         </div>   
      <?php } ?>
   </div> 

