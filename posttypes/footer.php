<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
class Lenxel_Theme_Support_Footer{
  public static $post_type = 'footer';
  
  public static $instance;

  public static function getInstance() {
    if (!isset(self::$instance) && !(self::$instance instanceof Lenxel_Theme_Support_Footer)) {
      self::$instance = new Lenxel_Theme_Support_Footer();
    }
    return self::$instance;
  }

  public function __construct(){ 
    
  }

  public function register_post_type_footer(){
    add_action('init', array($this, 'args_post_type_footer'), 10);
  }

  public function args_post_type_footer(){
    $labels = array(
      'name' => esc_html__( 'Footer Builder', 'lenxel-core' ),
      'singular_name' => esc_html__( 'Footer Builder', 'lenxel-core' ),
      'add_new' => esc_html__( 'Add Footer Builder', 'lenxel-core' ),
      'add_new_item' => esc_html__( 'Add Footer Builder', 'lenxel-core' ),
      'edit_item' => esc_html__( 'Edit Footer', 'lenxel-core' ),
      'new_item' => esc_html__( 'New Footer Builder', 'lenxel-core' ),
      'view_item' => esc_html__( 'View Footer Builder', 'lenxel-core' ),
      'search_items' => esc_html__( 'Search Footer Profiles', 'lenxel-core' ),
      'not_found' => esc_html__( 'No Footer Profiles found', 'lenxel-core' ),
      'not_found_in_trash' => esc_html__( 'No Footer Profiles found in Trash', 'lenxel-core' ),
      'parent_item_colon' => esc_html__( 'Parent Footer:', 'lenxel-core' ),
      'menu_name' => esc_html__( 'Footer Builder', 'lenxel-core' ),
    );

    $args = array(
        'labels'              => $labels,
        'hierarchical'        => true,
        'description'         => esc_html__('List Footer', "lenxel-core"),
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'show_in_nav_menus'   => false,
        'publicly_queryable'  => true,
        'exclude_from_search' => true,
        'has_archive'         => true,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite'             => true,
        'capability_type'     => 'post',
        'map_meta_cap'        => true
    );
    register_post_type( self::$post_type, $args );
  }



  public function get_footers(){
    $args = array(
      'post_type'        => 'footer',
      'posts_per_page'   => 100,
      'numberposts'      => 100,
      'post_status'     => 'publish',
    );
    $footers = array();
    $post_list = get_posts($args);
    foreach ( $post_list as $post ) {
      $footers[$post->post_name] = $post->post_title;
    }
    
    $footers['__default_option_theme'] = esc_html__('Default Option Theme', 'lenxel-core');
    $footers['__disable_footer'] = esc_html__('Disable Footer', 'lenxel-core');
    $footers['__default'] = esc_html__('Default Widget Footer', 'lenxel-core');
    wp_reset_postdata();
    return apply_filters('lenxelthemes_list_footer', $footers );
  }

  public function render_footer_builder($footer_slug) {
    $footer = get_page_by_path($footer_slug, OBJECT, 'footer');
    if ($footer && $footer instanceof WP_Post) {
      return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer->ID );
    }
  }
}

Lenxel_Theme_Support_Footer::getInstance()->register_post_type_footer();