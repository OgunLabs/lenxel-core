<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;

/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class LNXElement_User extends LNXElement_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve heading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'lnx-user';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve heading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
      $get_current_name = lenxel_load_widget_content_element('LNX User');
		$filter_name = 'lenxel/element/'.esc_html($this->get_name());
		return apply_filters( $filter_name, $get_current_name);
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve heading widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-lock-user';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'menu', 'user', 'block' ];
	}

	public function get_all_menus(){
	   $menus = get_terms( array('nav_menu', 'hide_empty' => true ) ); 
	   $results = array();
	   foreach ($menus as $key => $menu) {
	   	$results[$menu->slug] = $menu->name;
	   }
	   return $results;
	}

	/**
	 * Register heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'lenxel-core' ),
			]
		);
      $this->add_control(
         'align',
         [
            'label' => esc_html__( 'Alignment', 'lenxel-core' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
               'left' => [
                  'title' => esc_html__( 'Left', 'lenxel-core' ),
                  'icon' => 'fa fa-align-left',
               ],
               'center' => [
                  'title' => esc_html__( 'Center', 'lenxel-core' ),
                  'icon' => 'fa fa-align-center',
               ],
               'right' => [
                  'title' => esc_html__( 'Right', 'lenxel-core' ),
                  'icon' => 'fa fa-align-right',
               ],
            ],
            'default' => 'center',
         ]
      );

      $this->add_control(
         'text_login',
         [
            'label'        => esc_html__('Login text', 'lenxel-core'),
            'type'         => Controls_Manager::TEXT,
            'default'      => esc_html__('Login', 'lenxel-core'),
            'label_block'  => true
         ]
      );

      $this->add_control(
         'link_login',
         [
            'label'        => esc_html__('Custom Login Link Page', 'lenxel-core'),
            'type'         => Controls_Manager::TEXT,
            'label_block'  => true,
            'description'  => esc_html__('Empty = Popup Login Form', 'lenxel'),
            'condition' => [
               'enable_register' => 'yes'
            ]
         ]
      );

      $this->add_control(
         'enable_register',
         [
            'label' => esc_html__( 'Enable Register Link', 'lenxel-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes'
         ]
      );

      $this->add_control(
         'text_register',
         [
            'label'        => esc_html__('Register Text', 'lenxel-core'),
            'type'         => Controls_Manager::TEXT,
            'default'      => esc_html__('Register', 'lenxel-core'),
            'label_block'  => true,
            'condition' => [
               'enable_register' => 'yes'
            ]
         ]
      );

      $this->add_control(
         'link_register',
         [
            'label'        => esc_html__('Link Register', 'lenxel-core'),
            'type'         => Controls_Manager::TEXT,
            'label_block'  => true,
            'description'  => esc_html__('Empty = default link', 'lenxel'),
            'condition' => [
               'enable_register' => 'yes'
            ]
         ]
      );

      $this->add_control(
         'selected_icon',
         [
            'label' => esc_html__( 'Icon', 'lenxel-core' ),
            'type' => Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'default' => [
               'value' => 'fas fa-user',
               'library' => 'fa-solid',
            ],
         ]
      );

		$this->add_control(
			'menu_width',
			[
				'label' => esc_html__( 'Menu Width (px)', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 250,
				],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .lnx-user ul.lnx-nav-menu' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

      $this->start_controls_section(
         'section_content_style',
         [
            'label' => esc_html__( 'Text & Icon', 'lenxel-core' ),
            'tab' => Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_control(
         'icon_style',
         [
            'label' => esc_html__( 'Icon Style', 'lenxel-core' ),
            'type'      => Controls_Manager::HEADING,
         ]
      );

      $this->add_responsive_control(
         'icon_size',
         [
            'label' => esc_html__( 'Icon Size', 'lenxel-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [
               'size' => 13,
            ],
            'range' => [
               'px' => [
                  'min' => 0,
                  'max' => 500,
               ],
            ],
            'selectors' => [
               '{{WRAPPER}} .lnx-user .login-register i' => 'font-size: {{SIZE}}{{UNIT}};',
               '{{WRAPPER}} .lnx-user .login-register svg' => 'width: {{SIZE}}{{UNIT}};',
            ],
         ]
      );

      $this->add_control(
         'icon_color',
         [
            'label' => esc_html__( 'Color', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .lnx-user .login-register i' => 'color: {{VALUE}}', 
               '{{WRAPPER}} .lnx-user .login-register svg' => 'fill: {{VALUE}}', 
            ],
         ]
      );

      $this->add_control(
         'icon_color_hover',
         [
            'label' => esc_html__( 'Color Hover', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .lnx-user:hover .login-register .box-icon i' => 'color: {{VALUE}}', 
               '{{WRAPPER}} .lnx-user:hover .login-register .box-icon svg' => 'fill: {{VALUE}}', 
            ],
         ]
      );

      $this->add_control(
         'text_style',
         [
            'label' => esc_html__( 'Text Style', 'lenxel-core' ),
            'type'      => Controls_Manager::HEADING,
         ]
      );

      $this->add_control(
         'text_color',
         [
            'label' => esc_html__( 'Text Color', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .lnx-user .user-text' => 'color: {{VALUE}}', 
               '{{WRAPPER}} .lnx-user .sign-in-link' => 'color: {{VALUE}}',
               '{{WRAPPER}} .lnx-user .register-link' => 'color: {{VALUE}}',
               '{{WRAPPER}} .lnx-user .login-link' => 'color: {{VALUE}}'
            ],
         ]
      );

      $this->add_control(
         'text_color_hover',
         [
            'label' => esc_html__( 'Text Color Hover', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .lnx-user:hover .user-text' => 'color: {{VALUE}}', 
               '{{WRAPPER}} .lnx-user:hover .login-account .profile .name .icon' => 'color: {{VALUE}}', 
               '{{WRAPPER}} .lnx-user:hover .sign-in-link' => 'color: {{VALUE}}',
               '{{WRAPPER}} .lnx-user .register-link:hover' => 'color: {{VALUE}}',
               '{{WRAPPER}} .lnx-user .login-link:hover' => 'color: {{VALUE}}'
            ],
         ]
      );

      $this->add_group_control(
         Group_Control_Typography::get_type(),
         [
            'name' => 'text_typography',
            'selector' => '{{WRAPPER}} .lnx-user .user-text',
         ]
      );

  

      $this->end_controls_section();

		$this->start_controls_section(
			'section_account_menu_style',
			[
				'label' => esc_html__( 'Account Menu', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
      $this->add_control(
         'account_menu_color',
         [
            'label'     => esc_html__('Color', 'lenxel-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lnx-user .login-account .user-account .lnx-nav-menu > li > a' => 'color: {{VALUE}}',
            ],
         ]
      );
      $this->add_control(
         'account_menu_color_hover',
         [
            'label'     => esc_html__('Color Hover', 'lenxel-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lnx-user .login-account .user-account .lnx-nav-menu > li > a:hover' => 'color: {{VALUE}}',
            ],
         ]
      );

      $this->add_group_control(
         Group_Control_Typography::get_type(),
         [
            'name' => 'typography',
            'selector' => '{{WRAPPER}} .lnx-user .login-account .user-account .lnx-nav-menu > li > a',
         ]
      );

      $this->add_responsive_control(
         'main_menu_padding',
         [
            'label' => esc_html__( 'Menu Item Padding', 'lenxel-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors' => [
               '{{WRAPPER}} .lnx-user .login-account .user-account .lnx-nav-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );
  
      $this->end_controls_tab();

      $this->end_controls_tabs();

	}

	/**
	 * Render heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
      if ( lenxel_get_template_restrict()->has_premium){
         $settings = $this->get_settings_for_display();

         printf( '<div class="lnx-element-%s lnx-element">', esc_html($this->get_name()) );
         include $this->get_template('user.php');
         print '</div>';
      }else {
			$content = '<div></div>';
			wp_kses($content, array( 'div' ));
      }
	}
}

$widgets_manager->register_widget_type(new LNXElement_User());
