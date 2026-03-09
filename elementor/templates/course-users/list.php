<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$this->add_render_attribute(['wrapper' => ['class' => 'lnx-listing-users clearfix']]);
	//add_render_attribute grid
	$this->get_grid_settings();
?>
  
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
	<div class="lnx-content-items clearfix"> 
		<div class="users-list">
			<?php 
				foreach ($query as $user):
					$data = array(
						'user_id' => $user->ID,
						'settings' => $settings
					)
			?>
			  <div class="column-item">
					<?php  $this->lenxel_get_template_part('templates/content/item', 'user-style-2', $data ); ?>
			  </div>

			<?php endforeach; ?>
		</div>
	</div>
</div>
