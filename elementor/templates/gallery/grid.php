<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$this->add_render_attribute('wrapper', 'class', ['lnx-gallery-grid clearfix']);
	$this->get_grid_settings();
	$_random = lenxel_themesupport_random_id();
	$style = $settings['style'];
?>
  
  	<div class="<?php echo esc_attr($this->lenxel_str_replace_action(array('class="', '"'), $this->get_render_attribute_string('wrapper'))); ?>">
		<div class="lnx-content-items"> 
		  	<div <?php echo $this->get_render_attribute_string('grid') ?>>
			 	<?php
				  	foreach ($settings['images'] as $image){
					 	echo '<div class="item">';
							include $this->get_template('gallery/item-' . $style . '.php');
					 	echo '</div>';  
				  	}
				?>
		  	</div>
		</div>
  	</div>
