<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
   $title_text = $settings['title_text'];
   $sub_title = $settings['sub_title'];
   $description_text = $settings['description_text'];
   $button_style = $settings['button_style'] ? $settings['button_style'] : 'btn-line-white';
   $button_size = $settings['button_size'] ? $settings['button_size'] : '';
   $icon_image = '';
   if($settings['icon'] == 'yes'){
      $icon_image = (isset($settings['icon_image']['url']) && $settings['icon_image']['url']) ? $settings['icon_image']['url'] : '';
      if(empty($icon_image)) $icon_image = LENXEL_PLUGIN_URL . 'elementor/assets/images/icon-heading.png';
   }
   $auto_responisve = $settings['auto_responsive'] == 'yes' ? 'auto-responsive' : '';
   $this->add_render_attribute( ['block'=> ['class'=> [ 'align-' . $settings['align'], $settings['style'], 'widget gsc-heading', 'box-align-' . $settings['box_align'], $auto_responisve ]], 'title_text'=> ['class'=> 'title'], 'description_text'=> ['class'=> 'title-desc'], 'sub_title'=> ['class'=> 'sub-title']]);
   $header_tag = 'h2';

   $this->add_inline_editing_attributes( ['title_text'=> 'none', 'sub_title'=> 'none', 'description_text'] );
   $btn_classes = "btn-cta {$button_style} {$button_size}";
   ?>
   <div <?php $this->print_render_attribute_string('carousel'); ?>>
      <div class="content-inner">
         
         <?php if($icon_image){ ?>
            <div class="heading-icon"><img src="<?php echo esc_url($icon_image) ?>" alt="<?php echo esc_attr($settings['title_text']) ?>"/></div>
         <?php } ?>
         <?php if($settings['video'] == 'yes' && $settings['video_url']){ ?>
            <div class="heading-video">
            <a class="video-link popup-video" href="<?php echo esc_url($settings['video_url']) ?>"><i class="fa fa-play"></i></a>
            </div>
         <?php } ?>

         <?php if($sub_title){ ?>
            <div <?php $this->print_render_attribute_string( 'sub_title' ); ?>><span><?php echo wp_kses_post($sub_title); ?></span></div>
         <?php } ?>  
         
         <?php if($title_text){ ?>
            <<?php echo esc_attr($header_tag) ?> <?php $this->print_render_attribute_string( 'title_text' ); ?>>
               <span><?php echo esc_html($settings['title_text']); ?></span>
            </<?php echo esc_attr($header_tag) ?>>
         <?php } ?>
         <?php if( $description_text && !empty(trim($description_text)) ){ ?>
            <div <?php $this->print_render_attribute_string( 'description_text' ); ?>><?php echo wp_kses_post($description_text); ?></div>
         <?php } ?>

         <?php if($settings['button_url']['url']){ ?>
            <div class="heading-action">
               <?php $this->lnx_render_button($btn_classes); ?>
            </div>
         <?php } ?>

      </div>
   </div>
