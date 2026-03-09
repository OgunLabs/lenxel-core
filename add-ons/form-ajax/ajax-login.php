<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/*
* https://gist.github.com/cristianstan/10273612
*/

class Lenxel_Addons_Login_Ajax{
	
	private static $instance = null;
   public static function instance() {
      if ( is_null( self::$instance ) ) {
         self::$instance = new self();
      }
      return self::$instance;
   }

	public function __construct(){
		add_action( 'init', array($this, 'ajax_login_init') );
	}

	public function ajax_login_init(){
		if (!is_user_logged_in()) {
			// Enable the user with no privileges to run ajax_login() in AJAX
			add_action( 'wp_ajax_nopriv_lenxel_ajaxlogin', array($this, 'ajax_login') );
		}
	}

	// Execute the action only if the user isn't logged in

	function ajax_login(){
	 	// First check the nonce, if it fails the function will break
	 	check_ajax_referer( 'lenxel-ajax-security-nonce', 'security' );

	 	// Nonce is checked, get the POST data and sign user on
	 	$info = array();
	 	$info['user_login'] = sanitize_user($_POST['username']);
	 	$info['user_password'] = $_POST['password'];
	 	$info['remember'] = true;

	 	$user_signon = wp_signon( $info, false );

	 	if ( !is_wp_error($user_signon) ){
		  	// wp_signon() already handles user authentication and sets auth cookies
		  	// No need to manually set current user - WordPress handles this
		  	
			$message = esc_html__('Login successful, redirecting...', 'lenxel-core');
			if(class_exists('WpFastestCache')){
          	$wpfc = new WpFastestCache();
          	$wpfc->deleteCache();
         }
		  	 wp_send_json(array('logged_in' => true, 'message' => '<div class="alert alert-success">' . $message . '</div>'));
		  	die();

	 	}else{

	 		$message = '';
	 		if(isset($user_signon->errors)){
				foreach ($user_signon->errors as $errors) {
					foreach ($errors as $error) {
						if( empty($message) ){
							$message = $error;
						}else{
							$message .= ' ,' . $error;
						}
					}
				}
				wp_send_json(array('logged_in' => false, 'message' => '<div class="alert alert-warning">' . $message . '</div>'));
		  		die();
			}else{
				$message = esc_html__('Login unsuccessful, plese try again!', 'lenxel-core');
				wp_send_json(array('logged_in' => false, 'message' => '<div class="alert alert-warning">' . $message . '</div>'));
				die();
			}
	 	}
	 	
	 	die();
	}

	public static function html_form(){ 
		$login_form_top = apply_filters( 'login_form_top', '', array() );
		$login_form_middle = apply_filters( 'login_form_middle', '', array() );
		$login_form_bottom = apply_filters( 'login_form_bottom', '', array() );
		$allow_html_miss_svg = [
			'div' => [
				'class' => [],
				'data-display' => [],
			],
			'button' => [
				'class' => [],
				'data-event' => [],
				'data-settings' => [],
				'data-tooltip' => [],
			],
			'i' => [
				'class' => [],
				'aria-hidden' => [],
			],
			'span' => [
				'class' => [],
			],
			'a' => [
				'href' => [],
				'style' => [],
				'target' => [],
			],
			'input' => [
				'type' => [],
				'value' => array(),
				'class' => [],
				'id' => [],
				'value' => [],
				'data-'=>[],
				'name' =>[],
			],
			'select' => [
				'class' => [],
				'id' => [],
				'name' =>[],
			],
			'option'=>[
				'class' => [],
				'value' => [],
				'id' => [],
				'selected' =>[],
			]
		];
		$svg_args = array(
			'svg'   => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true // <= Must be lower case!
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array( 
				'd'               => true, 
				'fill'            => true
			)
		);
		
		$allowed_tags = array_merge( $allow_html_miss_svg, $svg_args );
	?>
		<form id="ajax-login-form" method="post" class="ajax-form-content">
			<?php echo wp_kses(html_entity_decode($login_form_top), $allow_html); ?>
		   <div class="form-status"></div>
		   <div class="form-group">
			   <label for="username"><?php echo esc_html__('Username', 'lenxel-core') ?></label>
			   <input id="username" type="text" placeholder="<?php echo esc_html__('Username', 'lenxel-core') ?>" name="username" autocomplete='off' class="form-control">
			</div>
		   <div class="form-group">
			   <label for="password"><?php echo esc_html__('Password', 'lenxel-core') ?></label>
			   <input id="password" type="password" placeholder="******" name="password" autocomplete='off' class="form-control">
			</div>   
			<?php echo wp_kses(html_entity_decode($login_form_middle), $allow_html); ?>
		   <div class="form-group form-action">
			   <input class="btn-theme btn-fw" type="submit" value="<?php echo esc_html__('Login', 'lenxel-core') ?>" name="submit">
			</div>   
			<div class="lost-password">
		   	<a class="lost-popup" data-toggle="modal" data-target="#form-ajax-lost-password-popup"><?php esc_html_e('Lost your password?', 'lenxel-core') ?></a>
		   </div>
			<?php echo wp_kses(html_entity_decode($login_form_bottom),$allow_html); ?>
		</form>
	<?php
	}
}

new Lenxel_Addons_Login_Ajax();