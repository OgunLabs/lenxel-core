<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  $query = $this->query_posts();
  $_random = lenxel_themesupport_random_id();
  if ( ! $query->found_posts ) {
    return;
  }

   $this->add_render_attribute(['wrapper' => ['class'=> 'lnx-course-list clearfix lnx-course']]);

?>
  
  <div <?php $this->print_render_attribute_string('wrapper'); ?>>
      <div class="lnx-content-items"> 
        <div class="list-course-content">
          <?php
            global $post;
            while ( $query->have_posts() ) { 
               $query->the_post();
               echo '<div class="item">';
                  $this->lenxel_get_template_part('tutor/loop/content/item', 'course-list');
               echo '</div>';     
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

  