<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$this->add_render_attribute('wrapper', 'class', ['lnx-gallery-carousel']);
	$this->add_render_attribute('carousel', 'class', 'init-carousel-owl owl-carousel');
	$_random = lenxelthemesupport_random_id();
	$style = $settings['style'];
?>

	<div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('wrapper'))); ?>">
		<div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('carousel'))); ?>" <?php echo esc_attr($this->lenxel_str_replace_action(array('"'), $this->get_carousel_settings())); ?>>
			<?php
				foreach ($settings['images'] as $image){
					echo '<div class="item">';
						include $this->get_template('gallery/item-' . $style . '.php');
					echo '</div>';	
				}
			?>
		</div>
	</div>
