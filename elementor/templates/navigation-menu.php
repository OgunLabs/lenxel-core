<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
   $_random = lenxelthemesupport_random_id();

   $this->add_render_attribute( ['block' => ['class'=> [ 'lnx-navigation-menu', ' menu-align-' . $settings['align'] ]]] );
   $args = [
      'echo'        => false,
      'menu'        => $settings['menu'],
      'menu_class'  => 'lnx-nav-menu lnx-main-menu',
      'menu_id'     => 'menu-' . $_random,
      'container'   => 'div'
   ];

   if(class_exists('Lenxel_Walker')){
      $args['walker' ]     = new Lenxel_Walker();
   }
   
   $menu_html = wp_nav_menu($args);
   if (empty($menu_html)) {
      return;
   }
?>
   <div <?php $this->print_render_attribute_string('carousel'); ?>>
      <?php echo wp_kses(
    $menu_html,
    array(
        'div'      => array(
            'class'  => array(),
            'id' => array(),
        ),
        'ul'     => array(
         'class'=>array()
        ),
        'li'     => array(
         'class'=>array(),
         'id'=>array(),
        ),
        'a' => array(
         'href'=>array(),
         'onclick'=>array(),
        ),
        'span'=>array(
         'class'=>array(),
        ),
    )
); ?>
   </div>