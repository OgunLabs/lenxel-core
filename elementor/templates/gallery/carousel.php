<?php
	$this->add_render_attribute('wrapper', 'class', ['lnx-gallery-carousel']);
	$this->add_render_attribute('carousel', 'class', 'init-carousel-owl owl-carousel');
	$_random = lenxelthemesupport_random_id();
	$style = $settings['style'];
?>

	<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
		<div <?php echo $this->get_render_attribute_string('carousel') ?> <?php echo $this->get_carousel_settings() ?>>
			<?php
				foreach ($settings['images'] as $image){
					echo '<div class="item">';
						include $this->get_template('gallery/item-' . $style . '.php');
					echo '</div>';	
				}
			?>
		</div>
	</div>
