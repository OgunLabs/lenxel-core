<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
class Lenxel_Theme_Support_Header{
  public static $post_type = 'lnx_header';
  
  public static $instance;

  public static function getInstance() {
    if (!isset(self::$instance) && !(self::$instance instanceof Lenxel_Theme_Support_Header)) {
      self::$instance = new Lenxel_Theme_Support_Header();
    }
    return self::$instance;
  }

  public function __construct(){ 
    
  }

  public function register_post_type_header(){
    add_action('init', array($this, 'args_post_type_header'), 10);
  }

  public function args_post_type_header(){
    $labels = array(
      'name' => esc_html__( 'Header Builder', 'lenxel-core' ),
      'singular_name' => esc_html__( 'Header Builder', 'lenxel-core' ),
      'add_new' => esc_html__( 'Add Header Builder', 'lenxel-core' ),
      'add_new_item' => esc_html__( 'Add Header Builder', 'lenxel-core' ),
      'edit_item' => esc_html__( 'Edit Header', 'lenxel-core' ),
      'new_item' => esc_html__( 'New Header Builder', 'lenxel-core' ),
      'view_item' => esc_html__( 'View Header Builder', 'lenxel-core' ),
      'search_items' => esc_html__( 'Search Header Profiles', 'lenxel-core' ),
      'not_found' => esc_html__( 'No Header Profiles found', 'lenxel-core' ),
      'not_found_in_trash' => esc_html__( 'No Header Profiles found in Trash', 'lenxel-core' ),
      'parent_item_colon' => esc_html__( 'Parent Header:', 'lenxel-core' ),
      'menu_name' => esc_html__( 'Header Builder', 'lenxel-core' ),
    );

    $args = array(
        'labels'              => $labels,
        'hierarchical'        => true,
        'description'         => esc_html__('List Header', "lenxel-core"),
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
        'capability_type'     => 'post'
    );
    register_post_type( self::$post_type, $args );
  }


  public function get_headers( $default = true ){
    $args = array(
      'post_type'         => 'lnx_header',
      'posts_per_page'   => 100,
      'numberposts'      => 100,
      'post_status'       => 'publish',
      'orderby'           => 'title',
      'order'             => 'asc'
    );
    $post_list = get_posts($args);
    $headers = array();
    if($default){
      $headers['__default_option_theme'] = esc_html__('Default Option Theme', 'lenxel-core');
    }
    foreach ( $post_list as $post ) {
      $headers[$post->post_name] = $post->post_title;
    }
    wp_reset_postdata();
    return apply_filters('lenxelthemes_list_header', $headers );
  }

  public function render_header_builder($header_slug) {
    $header = get_page_by_path($header_slug, OBJECT, 'lnx_header');
    if ($header && $header instanceof WP_Post) {
      return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $header->ID );
    }
  }
}

Lenxel_Theme_Support_Header::getInstance()->register_post_type_header();