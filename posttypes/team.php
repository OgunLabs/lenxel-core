<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
if(!function_exists('lenxel_post_type_team')){
    function lenxel_post_type_team(){
      $labels = array(
        'name' => esc_html__( 'Team', 'lenxel-core' ),
        'singular_name' => esc_html__( 'Team', 'lenxel-core' ),
        'add_new' => esc_html__( 'Add New Team', 'lenxel-core' ),
        'add_new_item' => esc_html__( 'Add New Team', 'lenxel-core' ),
        'edit_item' => esc_html__( 'Edit Team', 'lenxel-core' ),
        'new_item' => esc_html__( 'New Team', 'lenxel-core' ),
        'view_item' => esc_html__( 'View Team', 'lenxel-core' ),
        'search_items' => esc_html__( 'Search Teams', 'lenxel-core' ),
        'not_found' => esc_html__( 'No Teams found', 'lenxel-core' ),
        'not_found_in_trash' => esc_html__( 'No Teams found in Trash', 'lenxel-core' ),
        'parent_item_colon' => esc_html__( 'Parent Team:', 'lenxel-core' ),
        'menu_name' => esc_html__( 'Teams', 'lenxel-core' ),
      );

      $args = array(
          'labels'              => $labels,
          'hierarchical'        => false,
          'description'         => 'List Team',
          'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comment'),
          'public'              => true,
          'show_ui'             => true,
          'show_in_menu'        => true,
          'menu_position'       => 5,
          'show_in_nav_menus'   => false,
          'publicly_queryable'  => true,
          'exclude_from_search' => false,
          'has_archive'         => true,
          'query_var'           => true,
          'can_export'          => true,
          'rewrite'             => array('slug' => 'team'),
          'capability_type'     => 'post'
      );
      register_post_type( 'lnx_team', $args );
    }
 
  add_action( 'init','lenxel_post_type_team' );

  function lenxel_themesupport_get_teams(){
    $args = array(
      'post_type'     => 'lnx_team',
      'posts_per_page'   => -1,
      'post_status'    => 'publish',
    );
    $posts = new WP_Query($args);
    $teams = array();
    if( $posts->have_posts() ){
      while( $posts->have_posts() ){
        $posts->the_post();
        $teams[get_the_ID()] = get_the_title();
      }
    }
    wp_reset_postdata();
    return apply_filters('lenxelthemes_list_team', $teams );
  }

  function lenxel_themesupport_get_team($id){
    $team = get_post($id);
    return $team;
  }

  // -- Dynamic Social Team Metabox -- 
  add_action( 'add_meta_boxes', 'lenxel_themesupport_team_socials' );
  add_action( 'save_post', 'lenxel_team_socials_save_postdata' );
  function lenxel_themesupport_team_socials() {
      add_meta_box(
          'lenxel_themesupport_team_socials',
          esc_html__( 'Socials', 'lenxel-core' ),
          'lenxel_team_socials_inner_custom_box',
          'lnx_team');
  }
  function lenxel_team_socials_inner_custom_box() {
      global $post;
      wp_nonce_field( plugin_basename( __FILE__ ), 'dynamic_socials_noncename' );
      ?>
      <div id="meta_inner">
      <?php

      $team_socials = get_post_meta($post->ID, 'team_socials', true);

      $c = 0;
      if ( ($team_socials) && count( $team_socials ) > 0 ) {
          foreach( $team_socials as $social ) {
              if ( isset( $social['icon'] ) || isset( $social['link'] ) ) {
                  printf( '<p><input size="20" type="text" placeholder="Class Icon" name="team_socials[%1$s][icon]" value="%2$s" /><input size="100" type="text" placeholder="Link" name="team_socials[%1$s][link]" value="%3$s" /><a class="button remove">%4$s</a></p>', esc_html($c), esc_attr($social['icon']), esc_url($social['link']), esc_html__( 'Remove', 'lenxel-core' ) );
                  $c = $c +1;
              }
          }
      }

      ?>
  <span id="team-social-list"></span>
  <a class="add-social-item"><?php esc_html_e('Add Social','lenxel-core'); ?></a>
  <script>
      jQuery(document).ready(function() {
          var count = <?php echo esc_js($c); ?>;
          jQuery(".add-social-item").click(function() {
              count = count + 1;
              jQuery('#team-social-list').append('<p> <input size="20" type="text" placeholder="Class Icon" name="team_socials['+count+'][icon]" value="" /><input size="100" type="text" placeholder="Link" name="team_socials['+count+'][link]" value="" /> <a class="remove button">Remove</a></p>' );
              return false;
          });
          jQuery(".remove").on('click', function() {
              jQuery(this).parent().remove();
          });
      });
      </script>
  </div><?php
  }

  function lenxel_team_socials_save_postdata( $post_id ) {
     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
          return;
     if ( !isset( $_POST['dynamic_socials_noncename'] ) )
          return;

     if ( !wp_verify_nonce( sanitize_text_field( wp_unslash ($_POST['dynamic_socials_noncename'])), plugin_basename( __FILE__ ) ) )
          return;
     $team_socials = sanitize_text_field($_POST['team_socials']);
     update_post_meta($post_id,'team_socials', $team_socials);
  }

  // -- Dynamic Education Team Metabox -- 
  add_action( 'add_meta_boxes', 'lenxel_themesupport_team_education' );
  add_action( 'save_post', 'lenxel_team_educations_save_postdata' );
  function lenxel_themesupport_team_education() {
    add_meta_box(
        'lenxel_themesupport_team_education',
        esc_html__( 'Education', 'lenxel-core' ),
        'lenxel_team_education_inner_custom_box',
        'lnx_team');
  }

  function lenxel_team_education_inner_custom_box() {
      global $post;
      wp_nonce_field( plugin_basename( __FILE__ ), 'dynamic_educations_noncename' );
      ?>
      <div id="meta_inner">
      <?php

      $team_educations = get_post_meta($post->ID, 'team_educations', true);

      $c = 0;
      if ( ($team_educations) && count( $team_educations ) > 0 ) {
          foreach( $team_educations as $education ) {
              if ( isset( $education['title'] ) ) {
                  printf( '<p><input size="120" type="text" placeholder="Title: MBA, Rotterdam School of Management, Erasmus University" name="team_educations[%1$s][title]" value="%2$s" /><a class="button remove">%3$s</a></p>', esc_attr($c), esc_attr($education['title']), esc_html__( 'Remove', 'lenxel-core') );
                  $c = $c +1;
              }
          }
      }

      ?>
  <span id="team-education-list"></span>
  <a class="add-education-item"><?php esc_html_e('Add Education','lenxel-core'); ?></a>
  <script>
      jQuery(document).ready(function() {
          var count = <?php echo esc_js($c); ?>;
          jQuery(".add-education-item").click(function() {
              count = count + 1;
              jQuery('#team-education-list').append('<p><input size="120" type="text" placeholder="Title: MBA, Rotterdam School of Management, Erasmus University" name="team_educations['+count+'][title]" value="" /> <a class="remove button">Remove</a></p>' );
              return false;
          });
          jQuery(".remove").on('click', function() {
              jQuery(this).parent().remove();
          });
      });
      </script>
  </div><?php
  }

  function lenxel_team_educations_save_postdata( $post_id ) {
     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
          return;
     if ( !isset( $_POST['dynamic_educations_noncename'] ) )
          return;

     if ( !wp_verify_nonce( sanitize_text_field( wp_unslash ($_POST['dynamic_educations_noncename'])), plugin_basename( __FILE__ ) ) )
          return;
     $team_educations = sanitize_text_field($_POST['team_educations']);
     update_post_meta($post_id,'team_educations', $team_educations);
  }

  // -- Dynamic Skills Team Metabox -- 
  add_action( 'add_meta_boxes', 'lenxel_themesupport_team_skills' );
  add_action( 'save_post', 'lenxel_team_skills_save_postdata' );
  function lenxel_themesupport_team_skills() {
    add_meta_box(
        'lenxel_themesupport_team_skills',
        esc_html__( 'Skills', 'lenxel-core' ),
        'lenxel_team_skills_inner_custom_box',
        'lnx_team');
  }

  function lenxel_team_skills_inner_custom_box() {
      global $post;
      wp_nonce_field( plugin_basename( __FILE__ ), 'dynamic_skills_noncename' );
      ?>
      <div id="meta_inner">
      <?php

      $team_skills = get_post_meta($post->ID, 'team_skills', true);

      $c = 0;
      if ( ($team_skills) && count( $team_skills ) > 0 ) {
          foreach( $team_skills as $skill ) {
              if ( isset( $skill['label'] ) || isset( $skill['volume'] ) ) {
                  printf( '<p><input size="80" type="text" placeholder="Label" name="team_skills[%1$s][label]" value="%2$s" /><input size="20" type="text" placeholder=" Volume (max 100)" name="team_skills[%1$s][volume]" value="%3$s" /><a class="button remove">%4$s</a></p>', esc_attr($c), esc_attr($skill['label']), esc_attr($skill['volume']), esc_html__( 'Remove', 'lenxel-core') );
                  $c = $c +1;
              }
          }
      }

      ?>
  <span id="team-skills-list"></span>
  <a class="add-skills-item"><?php esc_html_e('Add Skills','lenxel-core'); ?></a>
  <script>
      jQuery(document).ready(function() {
          var count = <?php echo esc_js($c); ?>;
          jQuery(".add-skills-item").click(function() {
              count = count + 1;
              jQuery('#team-skills-list').append('<p><input size="80" type="text" placeholder="Label" name="team_skills['+count+'][label]" value="" /> <input size="20" type="text" placeholder="Volume (max 100)" name="team_skills['+count+'][volume]" value="" /><a class="remove button">Remove</a></p>' );
              return false;
          });
          jQuery(".remove").on('click', function() {
              jQuery(this).parent().remove();
          });
      });
      </script>
  </div><?php
  }

  function lenxel_team_skills_save_postdata( $post_id ) {
     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
          return;
     if ( !isset( $_POST['dynamic_skills_noncename'] ) )
          return;

     if ( !wp_verify_nonce( sanitize_text_field( wp_unslash ($_POST['dynamic_skills_noncename'])), plugin_basename( __FILE__ ) ) )
          return;
     $team_skills = sanitize_text_field($_POST['team_skills']);
     update_post_meta($post_id,'team_skills', $team_skills);
  }

}
