<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Lenxel_Listing_Comment_BE extends Lenxel_Listing_Comment{
	
	public function __construct(){ 
		add_action( 'add_meta_boxes_comment', array( $this, 'add_meta_box' ) );
		add_action( 'edit_comment', array( $this, 'save_comment' ), 10, 2 );
	}

	public function add_meta_box( $comment ) {
		if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) || 'listing' !== get_post_type( $comment->comment_post_ID ) || 0 !== intval( $comment->comment_parent ) ) {
			return;
		}
		add_meta_box(
			$id         = 'lt_review',
			$title      = esc_html__( 'Review', 'lenxel-core' ),
			$callback   = array( $this, 'output_html' ),
			$screen     = 'comment',
			$context    = 'normal' 
		);
	}

	public function output_html( $comment, $box ) {
		if($comment->comment_parent != 0) return;
		// Get stars.
		$stars = get_comment_meta( $comment->comment_ID, 'lt_review', true );
		$stars = is_array( $stars ) ? $stars : array();

		// Get categories.
		$categories_star = array();
		foreach ( $stars as $category => $review_average ) {
		   $categories_star[] = $category;
		}

		$review_categories = $this->categories_review();
		?>
		<table class="form-table">
			<?php foreach ( $review_categories as $cat_key => $name ) :
				if(isset($stars[$cat_key])){ 
					$current = $stars[ $cat_key ];
				}else{
					$current = '';
				}
			?>
			<tr>
			  <td style="width: 200px;"><strong><?php printf(esc_html__('%s', 'lenxel-core'), esc_html($name )); ?></strong></td>
			  <td>
				 	<select name="lt_review[<?php echo esc_attr($cat_key) ?>]">
						<?php for ( $i = 0; $i <= 5; $i++ ) : ?>
						  <option value="<?php echo esc_attr($i); ?>" <?php selected( $current, $i ); ?>>
							  	<?php echo esc_html($i . ' star'); ?>
						  </option>
						<?php endfor; ?>
				 	</select>
				 (<?php printf(esc_html__('%s star', 'lenxel-core'), esc_html($current )); ?>)
			  </td>
			</tr>
			 <?php endforeach; ?>

			 <!-- Category review was deleted -->
			<tr><td colspan="2"><h3><?php echo esc_html__('Category review was deleted', 'lenxel-core') ?></h3><td></tr>
			<?php foreach ( $categories_star as $cat ) :
				if(!array_key_exists($cat, $review_categories)){ 
					$current = $stars[ $cat ];
				?>
				<tr>
				  	<td style="width: 200px;"><strong><?php printf(esc_html__('%s', 'lenxel-core' ), esc_html($cat)); ?></strong></td>
				  	<td> 
					  	<select name="lt_review[<?php echo esc_attr($cat); ?>]">
							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
							  	<option value="<?php echo esc_attr($i); ?>" <?php selected( $current, $i ); ?>>
								  	<?php echo esc_html($i . ' star'); ?>
							  	</option>
							<?php endfor; ?>
					 		<option value="delete"><?php echo esc_html__('Delete', 'lenxel-core') ?></option>
					 	</select>
					 	(<?php printf(esc_html__('%s star', 'lenxel-core'), esc_html($stars[ $cat ])); ?>)
				  </td>
				</tr>
				<?php } ?>

			 <?php endforeach; ?>

			 <?php wp_nonce_field( 'lenxel_lt_save_data', 'lenxel_lt_meta_nonce' ); ?>
		</table>
	<?php
	}

	public function save_comment( $comment_id, $data ) {
		$post_id = $data['comment_post_ID']; 
		$post = get_post($post_id);
		if ( empty( $_POST['lenxel_lt_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['lenxel_lt_meta_nonce'])), 'lenxel_lt_save_data' ) ) {
			return $comment_id;
		}
		if ( 'listing' !== $post->post_type || 0 !== intval( $data['comment_parent'] ) ) {
			return $comment_id;
		}
		if(isset($_POST['lt_review'])){
			$lt_review = array_map('sanitize_text_field', $_POST['lt_review']);//filter_input(INPUT_POST, 'lt_review', FILTER_DEFAULT , FILTER_REQUIRE_ARRAY); //filter_input_array(INPUT_POST, $_POST['lt_review']);
			foreach ($lt_review as $key => $value) {
				if($value == 'delete'){
					unset($lt_review[$key]);
				}
			}
		  	update_comment_meta( $comment_id, 'lt_review', $lt_review );
	
			$reviews_total = $review_average = $count = 0;

			foreach ($lt_review as $key => $value) {
				if($value){
					$reviews_total += intval($value);
					$count ++;
				}
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
  	}

}

new Lenxel_Listing_Comment_BE();