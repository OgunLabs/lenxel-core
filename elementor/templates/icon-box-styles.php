<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  use Elementor\Icons_Manager;

   $style = $settings['style'];
   $description_text = $settings['description_text'];
   $header_tag = 'h2';
   if(!empty($settings['header_tag'])) $header_tag = $settings['header_tag'];

   $has_icon = ! empty( $settings['selected_icon']['value']);

   $title_html = $settings['title_text'];

   $this->add_render_attribute( ['block' => ['class' => [ 'widget gsc-icon-box-styles', $settings['style'], $settings['active'] == 'yes' ? 'active' : '' ]], 'description_text'=> ['class'=> 'desc'], 'title_text'=> ['class'=> 'title' ]] );

   $this->add_inline_editing_attributes( 'title_text', 'none' );
   $this->add_inline_editing_attributes( 'description_text' );

   ?>

   <?php if($style == 'style-1'){ ?>
      <div <?php $this->print_render_attribute_string('carousel'); ?>">
         <div class="icon-box-content">
            <?php if ( $has_icon ){ ?>
               <div class="box-icon">
                  <span class="box-icon-inner">
                     <?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                  </span>
                  <?php if(!empty($settings['number_text'])) echo '<span class="number">' . esc_html($settings['number_text']) . '</span>'; ?>
               </div>
            <?php } ?>

            <div class="box-content">
               <?php if(!empty($settings['title_text'])){ ?>
                  <<?php echo esc_attr($header_tag) ?> <?php $this->print_render_attribute_string( 'title_text' ); ?>>
                     <?php echo wp_kses($title_html, $this->lenxel_get__allowed_html()); ?>
                  </<?php echo esc_attr($header_tag) ?>>
               <?php } ?>
            </div>
         </div> 
         <?php $this->lenxel_render_link_html('', $settings['button_url'], 'link-overlay'); ?>
      </div>   
   <?php } ?>


   <?php if($style == 'style-2'){ ?>
      <div <?php $this->print_render_attribute_string('carousel'); ?>>
         
         <div class="content-inner">
            <?php if(!empty($settings['title_text'])){ ?>
               <<?php echo esc_attr($header_tag) ?> <?php $this->print_render_attribute_string( 'title_text' ); ?>>
                  <?php echo wp_kses($title_html, $this->lenxel_get__allowed_html()); ?>
               </<?php echo esc_attr($header_tag) ?>>
            <?php } ?>
            <?php if(!empty($settings['description_text'])){ ?>
               <div <?php $this->print_render_attribute_string( 'description_text' ); ?>><?php echo wp_kses($description_text, true); ?></div>
            <?php } ?>
         </div>

         <?php if ( $has_icon ){ ?>
            <div class="icon-inner">
               <?php if ( $has_icon ){ ?>
                  <span class="box-icon">
                     <span class="box-icon-inner">
                        <?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                     </span>
                  </span>
               <?php } ?>
            </div>
         <?php } ?>

         <?php $image_url = $settings['image']['url']; ?>
         <?php if($image_url){ ?>
            <div class="bg-image">
               <img src="<?php echo esc_url($image_url) ?>" alt="<?php echo esc_attr( $settings['title_text'] ) ?>" />
            </div>
         <?php } ?>
         
         <?php $this->lenxel_render_link_html('', $settings['button_url'], 'link-overlay'); ?>

      </div> 
   <?php } ?>   

   <?php if( $style == 'style-3'){ ?>
      <div <?php $this->print_render_attribute_string('carousel'); ?>>
         <?php if ( $has_icon ){ ?>
            <div class="icon-inner">
               <?php if ( $has_icon ){ ?>
                  <span class="box-icon">
                     <span class="box-icon-inner">
                        <?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                     </span>
                     <?php if(!empty($settings['number_text'])) echo '<span class="number">' . esc_html($settings['number_text']) . '</span>'; ?>
                  </span>
               <?php } ?>
               <?php $this->lenxel_render_link_html('', $settings['button_url'], 'link-overlay'); ?>
            </div>
         <?php } ?>

         <div class="content-inner">
            <?php if(!empty($settings['title_text'])){ ?>
               <<?php echo esc_attr($header_tag) ?> <?php $this->print_render_attribute_string( 'title_text' ); ?>>
                  <?php $this->lenxel_render_link_html($title_html, $settings['button_url']); ?>
               </<?php echo esc_attr($header_tag) ?>>
            <?php } ?>

            <?php if(!empty($settings['description_text'])){ ?>
               <div <?php $this->print_render_attribute_string( 'description_text' ); ?>><?php echo wp_kses($description_text, true); ?></div>
            <?php } ?>
         </div>

      </div> 
   <?php } ?>   

   <?php if( $style == 'style-4'){ ?>
      <div <?php $this->print_render_attribute_string('carousel'); ?>">
         <?php if ( $has_icon ){ ?>
            <div class="icon-inner">
               <?php if ( $has_icon ){ ?>
                  <span class="box-icon">
                     <span class="box-icon-inner">
                        <?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                     </span>
                     <?php if(!empty($settings['number_text'])) echo '<span class="number">' . esc_html($settings['number_text']) . '</span>'; ?>
                  </span>
               <?php } ?>
               <?php $this->lenxel_render_link_html('', $settings['button_url'], 'link-overlay'); ?>
            </div>
         <?php } ?>

         <div class="content-inner">
            <?php if(!empty($settings['title_text'])){ ?>
               <<?php echo esc_attr($header_tag) ?> <?php $this->print_render_attribute_string( 'title_text' ); ?>>
                  <?php $this->lenxel_render_link_html($title_html, $settings['button_url']); ?>
               </<?php echo esc_attr($header_tag) ?>>
            <?php } ?>

            <?php if(!empty($settings['description_text'])){ ?>
               <div <?php $this->print_render_attribute_string( 'description_text' ); ?>><?php echo wp_kses($description_text, true); ?></div>
            <?php } ?>
         </div>

      </div> 
   <?php } ?> 


