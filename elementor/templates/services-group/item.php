<?php if($settings['style'] == 'style-1'): ?>
   <div class="service-item <?php echo esc_attr($settings['style']) ?>">
      <div class="service-item-content">
			
			<?php if (!empty($item['image']['url'])) : ?>
				<div class="image-content">
					<img src="<?php echo esc_url($item['image']['url']) ?>" alt="<?php esc_html($item['title']) ?>" />
				</div>
			<?php endif; ?>

			<div class="service-content">
				<?php if($item['title']){ ?>
					<h3 class="title"><span><?php echo esc_html($item['title']); ?></span></h3>
				<?php } ?>

				<?php if($item['desc']){ ?>
					<div class="desc"><?php echo wp_kses_post($item['desc']); ?></div>
				<?php } ?>

				<?php if($item['link']['url']){ ?>
					<div class="read-more">
					<?php $getLinkData = $this->lenxel_render_link_html(esc_html__('Read more', 'lenxel-core'), $item['link'], 'btn-inline' );
						 echo wp_kses( $getLinkData, array('a'=>array('class'=>array(), 'id'=>array()), 'div'=>array('class'=>array()),'span'=>array('class'=>array())) ) ?>
					</div>
				<?php } ?>
			</div>
				
		</div>
		<?php $link_overlay = $this->lenxel_render_link_overlay($item['link']);
						 echo wp_kses( $link_overlay, array('a'=>array('class'=>array(), 'id'=>array()), 'div'=>array('class'=>array()),'span'=>array('class'=>array())) ) ?>
	</div>		
<?php endif; ?>	

