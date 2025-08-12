<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$this->add_render_attribute(['carousel' => ['class' => 'init-carousel-owl owl-carousel'], 'wrapper' => ['class' => 'lnx-gallery-carousel']]);
	$_random = lenxel_themesupport_random_id();
	$style = $settings['style'];
?>

	<div <?php $this->print_render_attribute_string('wrapper'); ?>>
		<div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
			<?php
				foreach ($settings['images'] as $image){
					echo '<div class="item">';
						include $this->get_template('gallery/item-' . $style . '.php');
					echo '</div>';	
				}
			?>
		</div>
	</div>
