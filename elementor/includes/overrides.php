<?php
use Elementor\Controls_Manager;
use Elementor\Repeater;
class LNX_Elementor_Override{
   public function __construct() {
      $this->add_actions();
      $this->elementor_init_setup();
      add_action( 'elementor/editor/after_save', array($this, 'clear_cache_updating_elementor') );
   }
   public function clear_cache_updating_elementor() {
      \Elementor\Plugin::$instance->files_manager->clear_cache();
   }

   function elementor_init_setup(){
      if(!get_option('elementor_allow_svg', '')) update_option( 'elementor_allow_svg', 1 );
      if(!get_option('elementor_load_fa4_shim', '')) update_option( 'elementor_load_fa4_shim', 'yes' );
      if(!get_option('elementor_disable_color_schemes', '')) update_option( 'elementor_disable_color_schemes', 'yes' );
      if(!get_option('elementor_disable_typography_schemes', '')) update_option( 'elementor_disable_typography_schemes', 'yes' );
      if(!get_option('elementor_container_width', '')) update_option( 'elementor_container_width', '1200' );
      $cpt_support = get_option( 'elementor_cpt_support' );
      if( empty($cpt_support) ){
         $cpt_support[] = 'page';
         $cpt_support[] = 'footer';
         $cpt_support[] = 'lnx_header';
         update_option('elementor_cpt_support', $cpt_support);
      }else{
         if( !in_array('footer', $cpt_support) || !in_array('lnx_header', $cpt_support) ){
            $cpt_support[] = 'footer';
            $cpt_support[] = 'lnx_header';
            update_option('elementor_cpt_support', $cpt_support);
         }
      }
   }

   public function add_actions() {
      add_action( 'elementor/element/column/layout/after_section_end', [ $this, 'column_style' ], 10, 2 );
      add_action( 'elementor/element/section/section_structure/after_section_end', [ $this, 'row_style' ], 10, 2 );
      add_action( 'elementor/element/section/section_layout/after_section_end', [ $this, 'after_row_end' ], 10, 2 );
      add_action( 'elementor/element/icon-box/section_icon/after_section_end', array($this,'icon_box'), 10, 2 );

   }

   public function column_style( $obj, $args ) {
      $obj->start_controls_section(
         'lnx_column_style',
         array(
            'label' => esc_html__( 'Lenxel Style Settings', 'lenxel-theme-support' ),
            'tab'   => Controls_Manager::TAB_STYLE,
         )
      );

      $obj->add_control(
         '_lnx_extra_classes',
         [
            'label' => __( 'Style Available', 'lenxel-theme-support' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
               '' => __( '-- None --', 'lenxel-theme-support' ),
               'bg-overflow-left' => __( 'Background Overflow Left', 'lenxel-theme-support' ),
               'bg-overflow-right' => __( 'Background Overflow Right', 'lenxel-theme-support' ),
            ],
            'default' => 'top',
            'prefix_class' => 'column-style-',
         ]
      );
 
      $obj->end_controls_section();    
   }

   public function after_row_end( $obj, $args ) {
      
      $obj->start_controls_section(
         'lnx_section_row',
         array(
            'label' => esc_html__( 'Lenxel Extra Settings Row for Header Builder', 'lenxel-theme-support' ),
            'tab'   => Controls_Manager::TAB_LAYOUT,
         )
      );

      // Header Sticky
      $obj->add_control(
         'row_header_sticky',
         [
            'label'  => esc_html__( 'Sticky Row Settings (Use only for row in header)', 'lenxel-theme-support' ),
            'type'      => Controls_Manager::HEADING
         ]
      );

      $obj->add_control(
         '_lnx_sticky_menu',
         [
            'label'     => __( 'Sticky Menu Row', 'lenxel-theme-support' ),
            'type'      => Controls_Manager::SELECT,
            'options'   => [
               '' => __( '-- None --', 'lenxel-theme-support' ),
               'gv-sticky-menu' => __( 'Sticky Menu', 'lenxel-theme-support' ),
            ],
            'default'         => '',
            'prefix_class'    => '',
            'description'     => __('You can only enable sticky menu for one row, please make sure display all sticky menu for other rows', 'lenxel-theme-support')
         ]
      );

      $obj->add_control(
         '_lnx_sticky_background',
         [
            'label'     => __('Sticky Background Color', 'lenxel-theme-support'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [ 
               '.gv-sticky-wrapper.is-fixed > .elementor-section' => 'background: {{VALUE}}!important;', 
            ],
            'condition' => [
               '_lnx_sticky_menu!' => ''
            ]
         ]
      );
      $obj->add_control(
         '_lnx_sticky_menu_text_color',
         [
            'label'     => __('Sticky Text Color', 'lenxel-theme-support'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '.gv-sticky-wrapper.is-fixed > .elementor-section' => 'color: {{VALUE}}', 
            ],
            'condition' => [
               '_lnx_sticky_menu!' => ''
            ]
         ]
      );
      $obj->add_control(
         '_lnx_sticky_menu_link_color',
         [
            'label'     => __('Sticky Link Menu Color', 'lenxel-theme-support'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '.gv-sticky-wrapper.is-fixed > .elementor-section .lnx-navigation-menu ul.lnx-nav-menu > li > a' => 'color: {{VALUE}}',
            ],
            'condition' => [
               '_lnx_sticky_menu!' => ''
            ]
         ]
      );
      $obj->add_control(
         '_lnx_sticky_menu_link_hover_color',
         [
            'label'     => __('Sticky Link Menu Hover Color', 'lenxel-theme-support'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '.gv-sticky-wrapper.is-fixed > .elementor-section .lnx-navigation-menu ul.lnx-nav-menu > li > a:hover' => 'color: {{VALUE}}',
            ],
            'condition' => [
               '_lnx_sticky_menu!' => ''
            ]
         ]
      );
      $obj->end_controls_section();
   }

   public function row_style($obj, $args){

      // Settings for row
      $obj->start_controls_section(
         'lnx_extra_settings_row',
         array(
            'label' => esc_html__( 'Lenxel Style Settings', 'lenxel-theme-support' ),
            'tab'   => Controls_Manager::TAB_STYLE,
         )
      );
      $obj->add_control(
         '_lnx_extra_row_style',
         [
            'label'     => __( 'Style Available', 'lenxel-theme-support' ),
            'type'      => Controls_Manager::SELECT,
            'options'   => [
               '' => __( '-- None --', 'lenxel-theme-support' ),
               'style-1' => __( 'Background White Full To Left and Border Theme', 'lenxel-theme-support' ),
               'style-2' => __( 'Background Theme Full To Right', 'lenxel-theme-support' ),
            ],
            'label_block'  => true,
            'default'      => 'top',
            'prefix_class' => 'row-',
         ]
      );
      $obj->add_control(
         'lnx_row_color',
         [
            'label' => __( 'Background Color', 'lenxel-theme-support' ),
            'type' => Controls_Manager::SELECT,
            'label_block'  => true,
            'options' => [
               '' => __( '-- Default --', 'lenxel-theme-support' ),
               'theme'         => __( 'Background Color Theme', 'lenxel-theme-support' ),
               'theme-second'  => __( 'Background Color Theme Second', 'lenxel-theme-support' ),
            ],
            'default' => '',
            'prefix_class' => 'bg-row-',
         ]
      );
      $obj->add_control(
         'lnx_bg_row_effect',
         [
            'label' => __( 'Background Effect', 'lenxel-theme-support' ),
            'type' => Controls_Manager::SELECT,
            'label_block'  => true,
            'options' => [
               '' => __( '-- Default --', 'lenxel-theme-support' ),
               'particles-js'  => __( 'Particles', 'lenxel-theme-support' )
            ],
            'default' => '',
            'prefix_class' => 'row-background-',
         ]
      );

      $obj->end_controls_section();
   }

   public function icon_box( $obj, $args ) {
      $obj->start_controls_section(
         'lnx_section_icon_box',
         array(
            'label' => esc_html__( 'LNX Theme Settings', 'lenxel-theme-support' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
         )
      );

      $obj->add_control(
         'lnx_icon_color',
         [
            'label'     => __( 'Style', 'lenxel-theme-support' ),
            'type'      => Controls_Manager::SELECT,
            'options'   => [
               ''             => esc_html__( '-- Default --', 'lenxel-theme-support' ),
               'style-1'      => esc_html__( 'Style I', 'lenxel-theme-support' ),
               'style-2'      => esc_html__( 'Style II', 'lenxel-theme-support' )
            ],
            'default'      => '',
            'prefix_class' => 'elementor-icon-box-',
         ]
      );
      $obj->end_controls_section(); 
   }

}

new LNX_Elementor_Override();

