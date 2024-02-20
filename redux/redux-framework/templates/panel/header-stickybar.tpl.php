<?php
/**
 * The template for the header sticky bar.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author        Redux Framework
 * @package       ReduxFramework/Templates
 * @version:      4.0.0
 */

?>
<div id="redux-sticky">
	<div id="info_bar">
		<a href="javascript:void(0);"
			class="expand_options<?php echo esc_attr( ( $this->parent->args['open_expanded'] ) ? ' expanded' : '' ); ?>"<?php echo( true === $this->parent->args['hide_expand'] ? ' style="display: none;"' : '' ); ?>>
			<?php esc_attr_e( 'Expand', 'lenxel-plugin' ); ?>
		</a>
		<div class="redux-action_bar">
			<span class="spinner"></span>
			<?php
			if ( false === $this->parent->args['hide_save'] ) {
				submit_button( esc_attr__( 'Save Changes', 'lenxel-plugin' ), 'primary', 'redux_save', false, array( 'id' => 'redux_top_save' ) );
			}

			if ( false === $this->parent->args['hide_reset'] ) {
				submit_button( esc_attr__( 'Reset Section', 'lenxel-plugin' ), 'secondary', $this->parent->args['opt_name_triger'] . '[defaults-section]', false, array( 'id' => 'redux-defaults-section-top' ) );
				submit_button( esc_attr__( 'Reset All', 'lenxel-plugin' ), 'secondary', $this->parent->args['opt_name_triger'] . '[defaults]', false, array( 'id' => 'redux-defaults-top' ) );
			}
			?>
		</div>
		<div class="redux-ajax-loading" alt="<?php esc_attr_e( 'Working...', 'lenxel-plugin' ); ?>">&nbsp;</div>
		<div class="clear"></div>
	</div>

	<!-- Notification bar -->
	<div id="redux_notification_bar">
		<?php $this->notification_bar(); ?>
	</div>
</div>
