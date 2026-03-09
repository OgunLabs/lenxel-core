<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$this->add_render_attribute([ 'wrapper' => ['class' => 'lnx-listing-users'], 'carousel' => ['class' => 'init-carousel-owl owl-carousel']]);
  ?>

<div <?php $this->print_render_attribute_string('wrapper'); ?>>
	<div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
		<?php 
			foreach ($query as $user):
				$data = array(
					'user_id' => $user->ID,
					'user_name' => $user->user_login,
					'settings' => $settings
				)
		?>
		  <div class="item">
				<?php  $this->lenxel_get_template_part('templates/content/item', 'user-style-1', $data ); ?>
		  </div>

		<?php endforeach; ?>

	</div>
</div>

<?php wp_reset_postdata(); ?>