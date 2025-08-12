<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
   $query = $this->query_posts();
   $_random = lenxel_themesupport_random_id();
   if ( ! $query->found_posts ) {
      return;
   }

   $this->add_render_attribute(['carousel' => ['class' =>'init-carousel-owl owl-carousel'], 'wrapper' => ['class' => 'lnx-portfolio-carousel','data-filter', $_random]]);
  
  ?>

   <div <?php $this->print_render_attribute_string('wrapper'); ?>>
      <div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
          <?php
              global $post;
              $count = 0;
              while ( $query->have_posts() ) { 
              $query->the_post();
                $post->loop = $count++;
                $post->post_count = $query->post_count;
                set_query_var( 'thumbnail_size', $settings['image_size'] );
                get_template_part('templates/content/item', $settings['style'] );
              }
          ?>
      </div>
   </div>
  <?php
  wp_reset_postdata();