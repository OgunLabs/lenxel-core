<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  $query = $this->query_posts();
  $_random = lenxel_themesupport_random_id();
  if ( ! $query->found_posts ) {
    $get_iclude_script = "<script>jQuery( document ).ready(function() {
        jQuery('.elementor-widget-lnx-learning').css('display','none');
    });</script>";
    echo wp_kses( $get_iclude_script, array('script'=>array()) );
	 return;
  }
  $get_iclude_scr = "<script>jQuery( document ).ready(function() {
      jQuery('.elementor-widget-lnx-learning').css('display','block');
  });</script>";
  echo wp_kses( $get_iclude_scr, array('script'=>array()) );
	$this->add_render_attribute(['wrapper' => ['class'=>'lnx-course-grid clearfix lnx-course']]);
  $style = (isset($settings['style'])) ? $settings['style'] : '' ;
	//add_render_attribute grid
	$this->get_grid_settings();
    $username = wp_get_current_user(); 
?>
  
  <div <?php $this->print_render_attribute_string('wrapper'); ?>>
  
  <style>.elementor-widget-lnx-learning{
    padding:100px 0px 80px;
  }</style>
  <div class="gsc-heading"><p class="sub-title-learning">You are almost there! Keep learning</p></div>
  <?php $current_user= wp_get_current_user(); ?>
  <div class=""><div class="gsc-heading"><p class="title-learning" style="">Welcome Back <?php echo esc_html( $current_user->user_login ); ?>, Continue Learning</div></div>
		<div class="lnx-content-items lns-style"> 
		  <div <?php $this->print_render_attribute_string('grid') ?>>
			 <?php
				global $post;
				$count = 0;
        if ($query && $query->have_posts()){
          while ( $query->have_posts() ) {

                $query->the_post();
                $this->lenxel_get_template_part( 'tutor/loop/content/item', 'progress', array(
                    'image_size'   => $settings['image_size']
                ) );
                wp_reset_postdata();
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

  