<?php
	$this->add_render_attribute('wrapper', 'class', ['lnx-gallery-grid clearfix']);
	$this->get_grid_settings();
	$_random = lenxelthemesupport_random_id();
	$style = $settings['style'];
?>
  
  	<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
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
