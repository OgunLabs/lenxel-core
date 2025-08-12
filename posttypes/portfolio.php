<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
if(!function_exists('lenxel_post_type_portfolio')  ){
    function lenxel_post_type_portfolio(){
      $labels = array(
          'name'               => esc_html__( 'Portfolios', "lenxel-core" ),
          'singular_name'      => esc_html__( 'Portfolio', "lenxel-core" ),
          'add_new'            => esc_html__( 'Add New Portfolio', "lenxel-core" ),
          'add_new_item'       => esc_html__( 'Add New Portfolio', "lenxel-core" ),
          'edit_item'          => esc_html__( 'Edit Portfolio', "lenxel-core" ),
          'new_item'           => esc_html__( 'New Portfolio', "lenxel-core" ),
          'view_item'          => esc_html__( 'View Portfolio', "lenxel-core" ),
          'search_items'       => esc_html__( 'Search Portfolios', "lenxel-core" ),
          'not_found'          => esc_html__( 'No Portfolios found', "lenxel-core" ),
          'not_found_in_trash' => esc_html__( 'No Portfolios found in Trash', "lenxel-core" ),
          'parent_item_colon'  => esc_html__( 'Parent Portfolio:', "lenxel-core" ),
          'menu_name'          => esc_html__( 'Portfolios', "lenxel-core" ),
      );

      $args = array(
          'labels'              => $labels,
          'hierarchical'        => true,
          'description'         => 'List Portfolio',
          'supports'            => array( 'title', 'editor', 'author', 'thumbnail','excerpt', 'post-formats'  ), 
          'taxonomies'          => array( 'portfolio_category','post_tag' ),
          'post-formats'        => false,
          'public'              => true,
          'show_ui'             => true,
          'show_in_menu'        => true,
          'menu_position'       => 5,
          'show_in_nav_menus'   => true,
          'publicly_queryable'  => true,
          'exclude_from_search' => false,
          'has_archive'         => true,
          'query_var'           => true,
          'can_export'          => true,
          'rewrite'             => array(
            'slug'  => 'portfolio'
          ),
          'capability_type'     => 'post'
      );
      $slug = apply_filters('lenxel-post-type/slug-portfolio', '');
      if($slug){
        $args['rewrite']['slug'] = $slug;
      }
      register_post_type( 'portfolio', $args );

      $labels = array(
        'name'              => esc_html__( 'Categories', "lenxel-core" ),
        'singular_name'     => esc_html__( 'Category', "lenxel-core" ),
        'search_items'      => esc_html__( 'Search Category', "lenxel-core" ),
        'all_items'         => esc_html__( 'All Categories', "lenxel-core" ),
        'parent_item'       => esc_html__( 'Parent Category', "lenxel-core" ),
        'parent_item_colon' => esc_html__( 'Parent Category:', "lenxel-core" ),
        'edit_item'         => esc_html__( 'Edit Category', "lenxel-core" ),
        'update_item'       => esc_html__( 'Update Category', "lenxel-core" ),
        'add_new_item'      => esc_html__( 'Add New Category', "lenxel-core" ),
        'new_item_name'     => esc_html__( 'New Category Name', "lenxel-core" ),
        'menu_name'         => esc_html__( 'Categories', "lenxel-core" ),
      );
      // Now register the taxonomy
      register_taxonomy('category_portfolio',array('portfolio'),
          array(
              'hierarchical'      => true,
              'labels'            => $labels,
              'show_ui'           => true,
              'show_admin_column' => true,
              'query_var'         => true,
              'show_in_nav_menus' =>false,
              'rewrite'           => array( 'slug' => 'category-portfolio'
          ),
      ));
  }
  add_action( 'init','lenxel_post_type_portfolio' );
  add_action( 'init', 'lenxel_portfolio_remove_post_type_support', 10 );
  function lenxel_portfolio_remove_post_type_support() {
    remove_post_type_support( 'portfolio', 'post-formats' );
  }
}

  function lenxel_themesupport_portfolio_query( $args ){
    $ds = array(
      'post_type'   => 'portfolio',
      'posts_per_page'  =>  12
    );

    $args = array_merge( $ds , $args );
    $loop = new WP_Query($args);

    return $loop;
  }

 
  function lenxel_themesupport_profolio_terms(){
    return get_terms('category_portfolio');
  }


  function lenxel_themesupport_portfolio_terms_related( $postId ){
    $output = array();
    
    $item_cats = get_the_terms( $postId, 'category_portfolio' );

    foreach((array)$item_cats as $item_cat){
      if( !empty($item_cats) && !is_wp_error($item_cats) ){
        $output[] = $item_cat->slug;
      }
    }
      
    return $output;
  }

  if(!function_exists('moniathemer_related_portfolio')){
    function lenxel_themesupport_related_portfolio($per_page){
      $terms = get_the_terms( get_the_ID(),  'category_portfolio' );
      $termids =array();
     
      if(!empty($terms) && !is_wp_error($terms)){
          foreach($terms as $term){
              if( is_object($term) ){
                 $termids[] = $term->term_id;
              }
          }
      }

      $args = array(
          'post_type' => 'portfolio',
          'posts_per_page' => $per_page,
          'post__not_in' => array( get_the_ID() ),
          'tax_query' => array(
              'relation' => 'AND',
              array(
                  'taxonomy' => 'category_portfolio',
                  'field' => 'id',
                  'terms' => $termids,
                  'operator' => 'IN'
              )
          )
      );
      $results = new WP_Query( $args );
      return $results;
    }
  }

  function lenxelframeworkPortfolioAutocompleteSuggester( $query ) {
    global $wpdb;
    $id = (int) $query;

    $post_meta_infos = $wpdb->get_results(
      $wpdb->prepare(
          "
          SELECT ID, post_title, post_type 
          FROM {$wpdb->posts}   
          WHERE post_type = 'portfolio' AND (ID = %d OR post_title LIKE %s )
          ",
          $id > 0 ? $id : -1,
          '%' . $wpdb->esc_like( stripslashes( $query ) ) . '%' // Escape and add wildcards around the query
      ),
      ARRAY_A
    );

    if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
      foreach ( $post_meta_infos as $value ) {
        $data = array();
        $data['value'] = $value['ID'];
        $data['label'] = esc_html__( 'Id', 'lenxelframework' ) . ': ' . $value['ID']  . esc_html__( ' - Title', 'lenxel-core' ) . ': ' . $value['post_title'];
        $result[] = $data;
      }
    }
    return $result;
  }

   function lenxelframeworkPortfolioAutocompleteRender( $query ) {
    $query = trim( $query['value'] ); 
    if ( ! empty( $query ) ) {
      $post_object = get_post( (int) $query );
      if ( is_object( $post_object ) ) {
        $post_title = $post_object->post_title();
        $post_id = $post_object->ID();
        $post_title_display = '';
        if ( ! empty( $post_title ) ) {
          $post_title_display = ' - ' . esc_html__( 'Title', 'lenxel-core' ) . ': ' . $post_title;
        }
        $post_id_display = esc_html__( 'Id', 'lenxel-core' ) . ': ' . $post_id;
        $data = array();
        $data['value'] = $post_id;
        $data['label'] = $post_id_display . $post_title_display;
        return ! empty( $data ) ? $data : false;
      }
      return false;
    }
    return false;
  }

// -- Dynamic Social Teacher Metabox -- 
  add_action( 'add_meta_boxes', 'lenxel_themesupport_portfolio_information' );
  add_action( 'save_post', 'lenxel_themesupport_portfolio_save_postdata' );
  function lenxel_themesupport_portfolio_information() {
      add_meta_box(
          'lenxel_themesupport_portfolio_information',
          esc_html__( 'Information', 'lenxel-core' ),
          'lenxel_themesupport_portfolio_inner_custom_box',
          'portfolio');
  }
  function lenxel_themesupport_portfolio_inner_custom_box() {
      global $post;
      wp_nonce_field( plugin_basename( __FILE__ ), 'dynamic_lenxel_themesupport_portfolio_noncename' );
      ?>
      <div id="meta_inner">
      <?php

      $pinformations = get_post_meta($post->ID, 'pinformations', true);

      $c = 0;
      if ( ($pinformations) && count( $pinformations ) > 0 ) {
          foreach( $pinformations as $information ) {
              if ( isset( $information['label'] ) || isset( $information['value'] ) ) {
                  printf( '<p><input size="40" type="text" placeholder="Label" name="pinformations[%1$s][label]" value="%2$s" /><input size="80" type="text" placeholder="Value" name="pinformations[%1$s][value]" value="%3$s" /><a class="button remove">%4$s</a></p>', esc_attr($c), esc_attr($information['label']), esc_html($information['value']), esc_html__( 'Remove', 'lenxel-core' ) );
                  $c = $c +1;
              }
          }
      }

      ?>
  <span id="pinformations-list"></span>
  <a class="add-information-item"><?php esc_html_e('Add Information', 'lenxel-core'); ?></a>
  <script>
      var $ =jQuery.noConflict();
      $(document).ready(function() {
          var count = <?php echo esc_js($c); ?>;
          $(".add-information-item").click(function() {
              count = count + 1;
              $('#pinformations-list').append('<p> <input size="40" type="text" placeholder="Label" name="pinformations['+count+'][label]" value="" /><input size="80" type="text" placeholder="Value" name="pinformations['+count+'][value]" value="" /> <a class="remove button">Remove</a></p>' );
              return false;
          });
          $(".remove").on('click', function() {
              $(this).parent().remove();
          });
      });
      </script>
  </div><?php
  }

  function lenxel_themesupport_portfolio_save_postdata( $post_id ) {
     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
          return;
     if ( !isset( $_POST['dynamic_lenxel_themesupport_portfolio_noncename'] ) )
          return;

     if ( !wp_verify_nonce( sanitize_text_field( wp_unslash ($_POST['dynamic_lenxel_themesupport_portfolio_noncename'])), plugin_basename( __FILE__ ) ) )
          return;
     $pinformations = sanitize_text_field($_POST['pinformations']);
     update_post_meta($post_id,'pinformations', $pinformations);
  }