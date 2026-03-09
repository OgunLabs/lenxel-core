<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$query = $this->query_posts();
	$_random = lenxel_themesupport_random_id();
	if ( ! $query->found_posts ) {
		return;
	}

	$this->add_render_attribute('wrapper', 'class', ['lnx-course-carousel lnx-course']);

	$this->add_render_attribute('wrapper', 'data-filter', $_random);

	$this->add_render_attribute(['carousel'=> ['class'=> 'init-carousel-owl owl-carousel'], 'wrapper'=> ['class'=> 'lnx-course-carousel lnx-course', 'data-filter'=> $_random] ]);
  	$style = $settings['style'];
  ?>

	<div <?php $this->print_render_attribute_string('wrapper'); ?>>
		<div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
			<?php
			  	global $post;
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
  <?php
  wp_reset_postdata();