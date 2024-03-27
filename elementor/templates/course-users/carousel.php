<?php
	$this->add_render_attribute( 'wrapper', 'class', ['lnx-listing-users']);
	$this->add_render_attribute('carousel', 'class', 'init-carousel-owl owl-carousel');
  ?>

<div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('wrapper'))); ?>">
	<div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('carousel'))); ?>" <?php echo esc_attr($this->lenxel_str_replace_action(array('"'), $this->get_carousel_settings())); ?>>
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