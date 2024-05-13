<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  $query = $this->query_category();
  $_random = lenxel_themesupport_random_id();
  
  if ( ! $query ) {
	 return;
  }

	$this->add_render_attribute(['wrapper', ['class', 'lnx-category-grid clearfix lnx-category']]);
    $style = isset($settings['style']) ? $settings['style'] : '';
	//add_render_attribute grid
	$this->get_grid_settings();
    
?>
  
  <div <?php $this->print_render_attribute_string('wrapper'); ?>>
		<div class="lnx-content-items"> 
		  <div <?php $this->print_render_attribute_string('grid') ?>>
			 <?php
				$count = 0;$category_count=0;
				foreach ( $query as $category ) { 
                    if(((int)$settings['category_per_page'] > 0) && ((int)$settings['category_per_page'] <= $category_count)){
                        break;
                    }
                    $thumbnail = (isset($image_size) && $image_size) ? $image_size : 'post-thumbnail';
                    $column = (isset($settings['column'])) ? $settings['column'] : 4;
                     // get the thumbnail id using the queried category term_id
                    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true ); 
            
                    // get the image URL
                    if((int) $thumbnail_id > 0){
                        $image = wp_get_attachment_url( $thumbnail_id ); 
                    }else{
                        $image = '';
                    }
                ?>
                    <div class="category-grid<?php echo esc_attr($column); ?>">
                        <a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>" class="image-cat-content cat-bg-img" style="background: lightgreen url('<?php echo esc_url($image); ?>') no-repeat center; background-size: cover; display: block;border-radius:5px;">
                            <div class="item-category gsc-heading">
                                <p class="title "> <?php echo esc_html($category->name); ?></p>
                            </div>
                        </a>

                    </div>
                <?php 
                    
                    $category_count++;
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

  