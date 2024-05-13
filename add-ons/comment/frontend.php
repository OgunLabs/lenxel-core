<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Lenxel_Listing_Comment_FE extends Lenxel_Listing_Comment{

  public static $instance;

	public static function getInstance() {
		if (!isset(self::$instance) && !(self::$instance instanceof Lenxel_Listing_Comment_FE)) {
		self::$instance = new Lenxel_Listing_Comment_FE();
		}
		return self::$instance;
	}

	public function __construct(){ 
	 	add_filter( 'comments_template', array($this, 'lenxel_comments_template_loader') );
	 	add_filter( 'pre_comment_approved', array( $this, 'lenxel_pre_save_review' ), 10, 2 );
	 	add_action( 'comment_post', array( $this, 'lenxel_save_comment_review' ), 10, 3 );
	 	add_filter( 'comment_form_logged_in', '__return_empty_string' );
		add_action( 'lt_review_field_init', array( $this, 'lenxel_reviews_field_for_listing_owner' ), 10, 3 );
	}
  
	function lenxel_comments_template_loader($template) {
		global $post; 	
		if ( $post->post_type !== 'listing' ) {
			return $template;
		}
		return get_template_directory() . '/ulisting/comment/lenxel-comments.php';
	}

	public function add_review_field() {
		if ( ! is_singular( 'listing' ) ) { return; }

		$post         = get_post();
		$current_user = wp_get_current_user();
		$is_author    = $current_user->ID && absint( $current_user->ID ) === absint( $post->post_author );

		do_action( 'lt_review_field_init', $post, $current_user, $is_author );

		echo apply_filters( 'lt_review_field', $this->review_field() );
	}


  	public function review_field() {
  		$review_categories = $this->categories_review();
	 	ob_start();
  	?>

	 	<div id="lt-comment-reviews" class="lt-comment-reviews">
			<div class="comment-reviews-content">
            <div class="comment-reviews-inner clearfix">
   			  	<?php foreach ( $review_categories as $key => $name) : ?>
      				<div class='review-item'>
      					<label><?php printf(esc_html__('%s','lenxel-core'), esc_html($name)); ?></label>
      					<div class='stars select-review' data-review-key='<?php echo esc_attr($key); ?>'>
      						<span data-star='5' class="star dashicons dashicons-star-filled"></span>
      						<span data-star='4' class="star dashicons dashicons-star-filled"></span>
      						<span data-star='3' class="star dashicons dashicons-star-filled"></span>
      						<span data-star='2' class="star dashicons dashicons-star-filled"></span>
      						<span data-star='1' class="star dashicons dashicons-star-filled"></span>
      					  <input type="hidden" class="lt-review-val" id="lt-review-<?php echo esc_attr($key); ?>-val"  required="required" name="lt_review[<?php echo esc_attr($key); ?>]" value="3">
      					</div>
      				</div>
   			  <?php endforeach; wp_nonce_field( 'lenxel_lt_save_data', 'lenxel_lt_meta_nonce' ); ?>
   			</div>
            <div class="avg-total-tmp">
               <span class="value">3</span>
               <span class="avg-title"><?php echo esc_html__('Average Ratting', 'lenxel-core') ?></span>
            </div>
         </div>   
	 	</div>

<?php
	 return ob_get_clean();
  }

  	public function lenxel_pre_save_review( $approved, $data ) {
	 	$post = get_post( $data['comment_post_ID'] );

	 	if ( 'listing' !== $post->post_type ) {
		return $approved;
	 	}

	 	if ( 0 !== intval( $data['comment_parent'] ) ) {
		return $approved;
	 	}

	 	return $approved;
  	}

  public function lenxel_save_comment_review( $comment_id, $comment_approved, $data ) {
	 	$post_id = $data['comment_post_ID']; 
	 	$post = get_post($post_id);
		if ( empty( $_POST['lenxel_lt_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ($_POST['lenxel_lt_meta_nonce'])), 'lenxel_lt_save_data' ) ) {
			return $comment_id;
		}
	 	if ( 'listing' !== $post->post_type || 0 !== intval( $data['comment_parent'] ) ) {
			return;
	 	}
	 
	 	if(isset($_POST['lt_review'])){
			$lt_review = array_map('sanitize_text_field', $_POST['lt_review']);//filter_input(INPUT_POST, 'lt_review', FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);
        	update_comment_meta( $comment_id, 'lt_review', $lt_review ); //update_comment_meta( $comment_id, 'lt_review', $_POST['lt_review'] );
	 	}

	 	$reviews_total = $review_average = $count = 0;
		 $lt_review = array_map('sanitize_text_field', $_POST['lt_review']);
     	foreach ($lt_review as $key => $value) {
         $reviews_total += intval($value);
         $count ++;
     	}
     	
     	$review_average = $reviews_total/$count;
     	update_comment_meta( $comment_id, 'lt_review_average', $review_average );

     	// Update average, count for all reviews categoires of post.
     	$results_reviews = $this->results_reviews_by_post($post_id);
     	update_post_meta( $post_id, 'lt_results_reviews', $results_reviews );

     	// Update average all reviews of post.
     	$reviews_post_average = $this->average_reviews_by_post($post_id);
     	update_post_meta( $post_id, 'lt_reviews_average', $reviews_post_average );

  }

  public function lenxel_reviews_field_for_listing_owner( $post, $current_user, $is_author ) {
		if ( $is_author && lenxel_themer_get_theme_option( 'lt_review_allow_owner', 'enable' ) != 'enable' ) {
			
			echo sprintf( '<div class="alert alert-warning">%s</div>', wpautop( esc_html__( "You can't add a star rating to your own product.", 'lenxel-core' ) ) );
			add_filter( 'lt_review_field', '__return_false' ); 
			
		}
	}

  public function comment_rate($rate) {
	 $alt = '';
	 switch ($rate) {
		case '0':
		  $alt = 'Zero - 0 stars';
		  break;
		case '1':
		  $alt = 'Really bad - 1 star';
		  break;
		case '2':
		  $alt = 'Bad - 2 stars';
		  break;
		case '3':
		  $alt = 'Good - 3 stars';
		  break;
		case '4':
		  $alt = 'Very good - 4 stars';
		  break;
		case '5':
		  $alt = 'Excellent - 5 stars';
		  break;
		default:
		  $alt = 'No grade';
		  break;
	 }

	 for ($i = 0; $i < 5; $i++) {
		if ($rate > $i){
		  echo '<i class="on fas fa-star"></i>';
		}else{
		  echo '<i class="off far fa-star"></i>';
		}
	 }
  }

}

new Lenxel_Listing_Comment_FE();