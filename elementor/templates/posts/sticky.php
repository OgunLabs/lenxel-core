<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  $query = $this->query_posts();
  $_random = lenxel_themesupport_random_id();
  if ( ! $query->found_posts ) {
	 return;
  }

	$this->add_render_attribute( [
		'wrapper' => [
		   'class' => 'lnx-posts-sticky clearfix lnx-posts'
		],
	 ] );
	//add_render_attribute grid
	$this->get_grid_settings();
?>
  
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
		
	<div class="lnx-content-items cleafix"> 
	  	<div class="row">
		  	<?php
			 	global $post;
			 	$i = 0;
			 	while ( $query->have_posts() ) { 
					$i ++;
					$query->the_post();
					$post->post_count = $query->post_count;
					set_query_var( 'thumbnail_size', $settings['image_size'] );
					set_query_var('index', $i);
					if( $i == 1 ){ 
				  		echo '<div class="first-post col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">';
					 		get_template_part('templates/content/item', 'post-style-sticky' );
				  		echo '</div>';
					}else{
				  		if( $i == 2 ){ echo '<div class="list-post col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">'; }
					 		get_template_part('templates/content/item', 'post-style-sticky' );
				  		if( $i == $query->found_posts || $i == $settings['posts_per_page']) { echo '</div>'; }
					}
			 	}
		  	?>
		</div>

		<?php if($settings['pagination'] == 'yes'): ?>
		 	<div class="pagination">
			  	<?php echo wp_kses( $this->pagination($query), $this->lenxel_get__allowed_html() ); ?>
		 	</div>
		<?php endif; ?>
	</div>

</div>	
  <?php

  wp_reset_postdata();