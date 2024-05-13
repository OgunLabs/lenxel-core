<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$query = $this->query_category();
    $_random = lenxel_themesupport_random_id();
    if ( ! $query ) {
       return;
    }

	$this->add_render_attribute();

	$this->add_render_attribute('wrapper', 'data-filter', $_random);

	$this->add_render_attribute(['carousel'=> ['class'=> 'init-carousel-owl owl-carousel stag'.$_random], 'wrapper'=> ['class'=> 'lnx-category-carousel lnx-category',  'data-filter'=> $_random]]);
    $style = (isset($settings['style'])) ? $settings['style'] : '' ;
  ?>
	<div <?php $this->print_render_attribute_string('wrapper'); ?>>
		<div <?php $this->print_render_attribute_string('carousel'); ?> <?php echo esc_attr('stag'.$_random); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
                
                <?php
                    foreach ( $query as $category ): ?>
                        <?php
                        $category_count = 0;
                            if(((int)$settings['category_per_page'] > 0) && ((int)$settings['category_per_page'] <= $category_count)){
                                break;
                            }
                            $thumbnail = (isset($image_size) && $image_size) ? $image_size : 'post-thumbnail';
                            $column = (isset($settings['column'])) ? $settings['column'] : 4;
                             // get the thumbnail id using the queried category term_id
                            $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true ); 
                    
                            // get the image URL
                            if((int) $thumbnail_id > 0){
                                $image = wp_get_attachment_url( $thumbnail_id ); 
                            }else{
                                $image = '';
                            }
                        ?>
                        <div class="category-grid<?php echo esc_attr( $column); ?>">
                            <a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>" class="image-cat-content cat-bg-img" style="background: lightgreen url('<?php echo esc_url($image); ?>') no-repeat center; background-size: cover; display: block;border-radius:5px;">
                                <div class="item-category gsc-heading">
                                    <p class="title"> <?php echo esc_html($category->name); ?></p>
                                </div>
                            </a>
    
                        </div>
                    
                       
                <?php $category_count++; endforeach; ?>
        </div>
	</div>
    <script>
    getCorouselCat = setInterval(function(){
        var owl = jQuery('.owl-carousel.stag<?php echo esc_js($_random); ?>');
        if(owl !== undefined){
                    owl.owlCarousel({
                        items:3,
                        loop:true,
                        margin:10,
                        autoplay:true,
                        navigation:true,
                        mouseDrag:true,
                        touchDrag:true,
                        autoplayTimeout:5000,
                        autoplayHoverPause:true,
                        responsive:{
                            0:{
                                items:1,
                                nav:true,
                                loop:true,
                                navRewind:true,
                                navigation:true,
                                autoplayHoverPause:true,
                            },
                            600:{
                                items:2,
                                nav:true,
                                loop:true,
                                navRewind:true,
                                navigation:true,
                                autoplayHoverPause:true,
                            },
                            1000:{
                                items:<?php echo esc_js($settings['category_per_page']); ?>,
                                nav:true,
                                loop:true,
                                navRewind:true,
                                navigation:true,
                                autoplayHoverPause:true,
                            }
                        },
                        autoplaySpeed:500
                    }); jQuery('.owl-carousel.stag<?php echo esc_js($_random ); ?> .owl-nav .owl-prev').html('<i class=\"las la-arrow-left\"></i>'); 
                    jQuery('.owl-carousel.stag<?php echo esc_js($_random); ?> .owl-nav .owl-next').html('<i class=\"las la-arrow-right\"></i>')
                    clearInterval(getCorouselCat);}},3000); </script> <style>
                    .owl-carousel.stag<?php echo esc_js($_random); ?> .owl-nav{display: block !important;}</style>

            
  <?php
  wp_reset_postdata();

?>
  