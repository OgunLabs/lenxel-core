<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $query = $this->query_category();
    $_random = lenxel_themesupport_random_id();
    if ( ! $query ) {
       return;
    }
   $this->add_render_attribute(['wrapper'=> ['class'=> 'lnx-course-list clearfix lnx-course']]);

?>
  
  <div <?php $this->print_render_attribute_string('wrapper'); ?>>
      <div class="lnx-content-items"> 
        <div class="list-course-content">
          <?php
                $count = 0;
				foreach ( $query as $category ) { 

                    $thumbnail = (isset($image_size) && $image_size) ? $image_size : 'post-thumbnail';
                    $column = (isset($settings['column'])) ? $settings['column'] : 3;
                ?>
                   <div class="item">
                        <a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>" class="image-cat-content cat-list-img" style="">
                            <div class="item-category gsc-heading">
                                <p class="title"> <?php echo esc_html($category->name); ?></p>
                            </div>
                        </a>

                    </div>
                <?php    
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

  