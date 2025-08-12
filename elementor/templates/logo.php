<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  use Elementor\Group_Control_Image_Size;

  $settings = $this->get_settings_for_display();
  $title_text = $settings['title_text'];
  $this->add_render_attribute( ['block'=> ['class'=> [ 'gsc-logo', 'text-' . $settings['align'] ]], 'link' => ['class'=> 'site-branding-logo']] );
  $html_tags = 'span';

  if ( ! empty( $settings['link']['url'] ) ) {
    $html_tags = 'a';
    $this->add_render_attribute( ['link'=> ['href'=> $settings['link']['url']]] );
    if ( $settings['link']['is_external'] ) {
      $this->add_render_attribute( ['link'=> ['target'=> '_blank']] );
    }
    if ( $settings['link']['nofollow'] ) {
      $this->add_render_attribute( ['link' => ['rel'=> 'nofollow']] );
    }
  }else{
    $html_tags = 'a';
    $this->add_render_attribute( ['link'=> ['href'=> get_home_url()]] );
  }


  if($title_text){
    $this->add_render_attribute(['link'=>['title'=> $title_text, 'rel'=> $title_text]]);
  }

?>
      
  <div <?php $this->print_render_attribute_string('carousel'); ?>>
    <?php if (!empty($settings['image']['url'])) : ?>

      <<?php printf('%1$s', esc_attr($html_tags)); ?> <?php  $this->print_render_attribute_string( 'link' ) ?>>
        <img src="<?php echo esc_url($settings['image']['url'])?>" alt="<?php echo esc_attr($settings['title_text']) ?>" />
      </<?php printf('%1$s', esc_attr($html_tags)); ?>>
    <?php endif; ?>
  </div>
