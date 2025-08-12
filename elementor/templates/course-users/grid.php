<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$this->add_render_attribute(['wrapper' => ['class' => 'lnx-listing-users clearfix']]);
	//add_render_attribute grid
	$this->get_grid_settings();
?>
  
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
	<div class="lnx-content-items clearfix"> 
		<div <?php $this->print_render_attribute_string('grid') ?>>
			<?php 
				foreach ($query as $user):
					$data = array(
						'user_id' => $user->ID,
						'user_name' => $user->user_login,
						'settings' => $settings
					)
			?>
			  <div class="column-item">
					<?php  $this->lenxel_get_template_part('templates/content/item', 'user-style-1', $data ); ?>
			  </div>

			<?php endforeach; ?>
		</div>

		<?php
			$users_total = isset($users['avail_roles'][$user_role]) ? $users['avail_roles'][$user_role] : 0;
			if($users_total > $per_page){
			  	$pl_args = array(
			     'base'     => add_query_arg('paged','%#%'),
			     'format'   => '',
			     'total'    => ceil($users_total / $per_page),
			     'current'  => max(1, $paged),
			  	);

			  if($GLOBALS['wp_rewrite']->using_permalinks())
			    	$pl_args['base'] = user_trailingslashit(trailingslashit(get_pagenum_link(1)).'page/%#%/', 'paged');

			   echo '<div class="pager text-center"><div class="paginations">';
			  		echo paginate_links($pl_args);
			  	echo '</div></div>';	
			}
		?>

	</div>
</div>
