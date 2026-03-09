<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
   use Elementor\Icons_Manager;

   $this->add_render_attribute(['icon'=> ['class'=> 'icon icon-font'], 'icon_image'=> ['class'=> 'icon icon-image' ], 'block'=> ['class'=> [ $settings['style'], 'widget gsc-search-box' ] ]]);
   $has_icon = ! empty( $settings['selected_icon']['value']);

   ?>
   <div <?php $this->print_render_attribute_string('carousel'); ?>>
      <div class="content-inner">
         
         <div class="main-search lnx-search">
            <?php if($has_icon){ ?>
               <a class="control-search">
                  <?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
               </a>
            <?php } ?>   

            <div class="lnx-search-content search-content">
              <div class="search-content-inner">
                <div class="content-inner"><?php get_search_form(); ?></div>  
              </div>  
            </div>
         </div>
         
      </div>
   </div>
