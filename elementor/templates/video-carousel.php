<?php
   if (!defined('ABSPATH')) {
      exit; // Exit if accessed directly.
   }
   use Elementor\Group_Control_Image_Size;
?>
   
<?php 
   $this->add_render_attribute(['wrapper' => ['class' => 'lnx-video-carousel' ], 'carousel' => ['class' => 'init-carousel-owl owl-carousel']]);
?>

<div <?php $this->print_render_attribute_string('wrapper'); ?>>
   <div <?php $this->print_render_attribute_string('carousel'); ?> <?php $this->lenxel_print_carousel_settings(); ?>>
      <?php
      foreach ($settings['videos_content'] as $video): ?>
         <?php 
            $image = (isset($video['video_image']['url']) && $video['video_image']['url']) ? $video['video_image']['url'] : '';
         ?>
         <div class="item video-item">
            <div class="video-item-inner">
               <div class="video-image">
                  <img src="<?php echo esc_url($image) ?>" alt="<?php echo esc_attr($video['video_title']); ?>" />
               </div>
               <a class="video-link popup-video" href="<?php echo esc_url($video['video_link']); ?>"><i class="fa fa-play"></i></a>
               <?php if($video['video_title']){ ?>
                  <div class="video-title"><?php echo esc_html($video['video_title']); ?></div>
               <?php } ?>   
            </div>   
         </div>
      <?php endforeach; ?>
   </div>
</div>
