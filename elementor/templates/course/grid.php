<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  $query = $this->query_posts();
  $_random = lenxel_themesupport_random_id();
  if ( ! $query->found_posts ) {
	 return;
  }

	$this->add_render_attribute(['wrapper'=> ['class'=> 'lnx-course-grid clearfix lnx-course']]);
   $style = $settings['style'];
	//add_render_attribute grid
	$this->get_grid_settings();
?>
  
  <div <?php $this->print_render_attribute_string('wrapper'); ?>>
		<div class="lnx-content-items"> 
		  <div <?php $this->print_render_attribute_string('grid') ?>>
			 <?php
				global $post;
				$count = 0;
				while ( $query->have_posts() ) { 
               $query->the_post();
               if($style == 'course-1'){
                  do_action('tutor_course/archive/before_loop_course');
               }
               $this->lenxel_get_template_part( 'tutor/loop/content/item', $settings['style'], array(
                  'image_size'   => $settings['image_size']
               ) );
               if($style == 'course-1'){
                  do_action('tutor_course/archive/after_loop_course');
               }
            }
			 ?>
		  </div>
		</div>
		<?php if($settings['pagination'] == 'yes'): ?>
			 <div class="pagination">
				  <?php echo wp_kses( $this->pagination($query), $this->lenxel_get__allowed_html() ); ?>
			 </div>
		<?php endif; ?>
  </div>
  <?php

  wp_reset_postdata();

  