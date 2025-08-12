<?php
   $image_url = $brand['image']['url']; 
   $image_hover_url = $brand['image_hover']['url']; 
   $active = $brand['active'];
   $classes = !empty($image_hover_url) ? 'brand-hover' : 'band-no-hover';
   $classes .= ($active == 'yes') ? ' active' : '';
?>
<div class="brand-item-content <?php echo esc_attr($classes); ?>">
   <span class="brand-image">
      <img src="<?php echo esc_url($image_url) ?>" alt="<?php echo esc_attr($brand['title']) ?>" class="brand-img"/>
   </span>   
   <?php if(!empty($image_hover_url)){ ?>
      <span class="brand-image-hover">
         <img src="<?php echo esc_url($image_hover_url) ?>" alt="<?php echo esc_attr($brand['title']) ?>" class="brand-img"/>
      </span>   
   <?php } ?>
   <?php $link_overlay = $this->lenxel_render_link_overlay($brand['link']);
						 echo wp_kses( $link_overlay, array('a'=>array('class'=>array(), 'id'=>array()), 'div'=>array('class'=>array()),'span'=>array('class'=>array())) ) ?>
</div>
