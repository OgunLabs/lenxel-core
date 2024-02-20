<?php
if(!function_exists('lenxel_post_type_megamenu')   ){
  function lenxel_post_type_megamenu(){
    $labels = array(
      'name' => __( 'Mega Menus', 'lenxel-plugin' ),
      'singular_name' => __( 'Mega Menu', 'lenxel-plugin' ),
      'add_new' => __( 'Add Profile Mega Menu', 'lenxel-plugin' ),
      'add_new_item' => __( 'Add Profile Mega Menu', 'lenxel-plugin' ),
      'edit_item' => __( 'Edit Mega Menu', 'lenxel-plugin' ),
      'new_item' => __( 'New Profile', 'lenxel-plugin' ),
      'view_item' => __( 'View Mega Menu Profile', 'lenxel-plugin' ),
      'search_items' => __( 'Search Mega Menu Profiles', 'lenxel-plugin' ),
      'not_found' => __( 'No Mega Menu Profiles found', 'lenxel-plugin' ),
      'not_found_in_trash' => __( 'No Mega Menu Profiles found in Trash', 'lenxel-plugin' ),
      'parent_item_colon' => __( 'Parent Mega Menu:', 'lenxel-plugin' ),
      'menu_name' => __( 'Mega Menus', 'lenxel-plugin' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'List Mega Menu',
        'supports' => array( 'title', 'editor' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => false,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => false
    );
    register_post_type( 'lnx_megamenu', $args );
  }
  add_action( 'init','lenxel_post_type_megamenu' ); 

  function lenxelthemesupport_add_custom_css_megamenu(){
      global $post;
      $args = array(
        'post_type'     => 'lnx_megamenu',
        'posts_per_page'   => -1,
        'post_status'    => 'publish',
      );
      $posts = new WP_Query($args);
      if( $posts->have_posts() ){
        $custom_css = '';
        while( $posts->have_posts() ){
          $posts->the_post();
          $custom_css .= get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
        }
        if( !empty($custom_css) ){
          echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
          echo $custom_css;
          echo '</style>';
        }
      }
      wp_reset_postdata();
    }

  function lenxelframework_get_megamenu(){
    $args = array(
      'post_type'     => 'lnx_megamenu',
      'posts_per_page'   => -1,
      'post_status'    => 'publish',
    );
    $posts = new WP_Query($args);
    $menu = array('default' => __('-- None --', 'lenxel-plugin') );
    if( $posts->have_posts() ){
      while( $posts->have_posts() ){
        $posts->the_post();
        $menu[get_the_ID()] = get_the_title();
      }
    }
    wp_reset_postdata();
    return apply_filters('lenxelthemes_list_megamenu', $menu );
  }
}