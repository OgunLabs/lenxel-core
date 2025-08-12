<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
   use Elementor\Icons_Manager;

   if ( empty( $settings['title_text'] ) ) {
      return;
   }
   $title_text = $settings['title_text'];

   $this->add_render_attribute( ['block'=> ['class'=> [ 'widget gsc-pricing', $settings['style'] ]], 'title_text'=> ['class'=> 'title']] );
   
   $header_tag = 'h3';

   $this->add_render_attribute(  );

   $this->add_inline_editing_attributes( 'title_text', 'none' );

   $button_classes = $settings['button_style'] ? $settings['button_style'] : 'btn-white';

?>
   
<div <?php $this->print_render_attribute_string('carousel'); ?>>
   <div class="content-inner">

      <div class="plan-price">
         <div class="plan-price-inner clearfix">
            <span class="currency"><?php echo esc_html( $settings['currency'] ) ?></span>
            <span class="price"><?php echo esc_html( $settings['price'] ) ?></span>
            <?php if(!empty($settings['period'])){ ?>
               <span class="interval">/&nbsp;<?php echo esc_html( $settings['period'] ) ?></span>
            <?php } ?>   
         </div>
      </div>

      <?php if($title_text){ ?>
         <<?php echo esc_attr($header_tag) ?> <?php $this->print_render_attribute_string( 'title_text' ); ?>>
            <span><?php echo esc_html($settings['title_text']); ?></span>
         </<?php echo esc_attr($header_tag) ?>>
      <?php } ?>

      <?php if($settings['pricing_content']){ ?>
         <ul class="plan-list">
            <?php foreach ($settings['pricing_content'] as $key => $item) { ?>
               <li>
                  <span class="text"><?php echo wp_kses_post($item['pricing_features']); ?></span>
               </li>  
            <?php } ?>
         </ul>
      <?php } ?>   


      <?php if($settings['button_url']['url']){ ?>
         <div class="pricing-action">
            <?php $this->lnx_render_button($button_classes); ?>
         </div>
      <?php } ?>
   </div>
</div>

<div class="clearfix"></div>

