<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  	$query = $this->query_posts();
  	$_random = lenxel_themesupport_random_id();
  	if ( ! $query->found_posts ) {
	 	return;
  	}

	$this->add_render_attribute(['wrapper' => ['class' => ['lnx-give-form-grid clearfix', 'grid-' . $_random]]]);
	
	$this->get_grid_settings();
?>
  
  	<div <?php $this->print_render_attribute_string('wrapper'); ?>>
		<div class="lnx-content-items"> 
		  	<div>
				<?php
					global $post;
					$count = 0;
					while ( $query->have_posts() ) { 
					  	$query->the_post();
					  	$post->loop = $count++;
					  	$post->post_count = $query->post_count;
					  	echo '<div class="item-columns">';
                     set_query_var( 'image_size', $settings['image_size'] );
                     get_template_part('tribe-events/list/single', $settings['style'] );
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