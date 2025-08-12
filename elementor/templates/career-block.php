<?php
   if ( empty( $settings['title_text'] ) ) {
      return;
   }
   $title_text = $settings['title_text'];
   $image = (isset($settings['image']['url']) && $settings['image']['url']) ? $settings['image']['url'] : '';
   $this->add_render_attribute( ['block'=> ['class'=>  'gsc-career' ]] );
   $header_tag = $settings['header_tag'];

   if ( ! empty( $settings['link']['url'] ) ) {
      $this->add_render_attribute( ['link'=> ['href'=> $settings['link']['url']]] );
      $icon_tag = 'a';
      if ( $settings['link']['is_external'] ) {
         $this->add_render_attribute( ['link'=> ['target'=> '_blank']] );
      }
      if ( $settings['link']['nofollow'] ) {
         $this->add_render_attribute( ['link'=> ['rel'=> 'nofollow']] );
      }
   }
   $title_text_html = $settings['title_text'];
   if ( ! empty( $settings['link']['url'] ) ) {
      $title_text_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $title_text_html . '</a>';
   }

   $this->add_render_attribute( ['title_text'=> ['class'=> 'title'], 'job_type'=> ['class'=> 'job-type'],  'job_address'=> ['class'=> 'job-address'], 'job_company'=> ['class'=> 'job-company']] );

   $this->add_inline_editing_attributes( 'title_text', 'none' );
   $this->add_inline_editing_attributes( 'job_type', 'none' );
   $this->add_inline_editing_attributes( 'job_address', 'none' );
   $this->add_inline_editing_attributes( 'job_company', 'none' );

   ?>
   <div <?php $this->print_render_attribute_string('carousel'); ?>>
      <?php if($image){ ?>
         <div class="image-box"><img src="<?php echo esc_url($image) ?>" alt="<?php echo esc_html($settings['title_text']) ?>"/></div>
      <?php } ?>

      <div class="box-content clearfix">
         <?php if($settings['job_type']){ ?>
            <span <?php $this->print_render_attribute_string( 'job_type' ); ?>><?php echo esc_html( $settings['job_type'] ) ?></span>
         <?php } ?>
         <?php if($title_text){ ?>
            <<?php echo esc_attr($header_tag) ?> <?php $this->print_render_attribute_string( 'title_text' ); ?>>
              <?php echo wp_kses_post($title_text_html); ?>
            </<?php echo esc_attr($header_tag) ?>>
         <?php } ?>
         <div class="box-information clearfix">
            <ul>
               <?php if($settings['company']){ ?>
                  <li <?php $this->print_render_attribute_string( 'job_company' ); ?>>
                     <i class="icon fa fa-suitcase"></i>
                     <?php echo esc_html( $settings['company'] ) ?>
                  </li>
               <?php } ?>
               <?php if($settings['address']){ ?>
                  <li <?php $this->print_render_attribute_string( 'job_address' ); ?>>
                     <i class="icon fas fa-map-marker-alt"></i>
                     <?php echo wp_kses_post( $settings['address'] ) ?>
                  </li>
               <?php } ?>
            </ul>
         </div>
      </div>   
   </div>
