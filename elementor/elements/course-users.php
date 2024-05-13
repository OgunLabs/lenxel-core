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
class LNXElement_Users extends LNXElement_Base {

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
		return 'lnx-users-instructor';
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
		$get_current_name = lenxel_load_widget_content_element('LNX Instructor/Students');
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
		return [ 'instructor', 'students', 'users' ];
	}

	public function get_script_depends() {
      return [
          'jquery.owl.carousel',
          'lenxel.elements',
      ];
    }

    public function get_style_depends() {
      return array('owl-carousel-css');
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
			'user_role',
			[
				'label'   => esc_html__('Filter by role', 'lenxel-core'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''       				=> esc_html__('All', 'lenxel-core'),
					'tutor_instructor' 	=> esc_html__('Instructor', 'lenxel-core'),
					'user'   				=> esc_html__('User', 'lenxel-core'),
					'customer'   			=> esc_html__('Customer', 'lenxel-core'),
				],
				'default' => 'tutor_instructor',
			]
		);
		$this->add_control(
         'user_ids',
         [
            'label' 				=> esc_html__( 'Select Individually (IDs)', 'lenxel-core' ),
            'type' 				=> Controls_Manager::TEXT,
            'default' 			=> '',
            'label_block' 		=> true,
         	'description' 		=> 'e.g: 1,2,3,4,5',
         ]  
      );
		$this->add_control(
			'per_page',
			[
				'label'   => esc_html__('Per page', 'lenxel-core'),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '6',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__('Order By', 'lenxel-core'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'registered',
				'options' => [
				  'registered'   => esc_html__('Registered date', 'lenxel-core'),
				  'nicename'     => esc_html__('Nickname', 'lenxel-core'),
				  'ID'           => esc_html__('ID', 'lenxel-core'),
				  'display_name' => esc_html__('Display name', 'lenxel-core'),
				  'post_count'   => esc_html__('Post count', 'lenxel-core'),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__('Order', 'lenxel-core'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc'  => esc_html__('ASC', 'lenxel-core'),
					'desc' => esc_html__('DESC', 'lenxel-core'),
				],
			]
		);

		$this->add_control(
			'show_paginate',
			[
				'label'   => esc_html__('Show Paginate', 'lenxel-core'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		 );  

		$this->end_controls_section();

		$this->start_controls_section(
			'section_team_layout',
			[
				'label' => esc_html__('Layout', 'lenxel-core'),
				'type'  => Controls_Manager::SECTION,
			]
		);
		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout Display', 'lenxel-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'carousel',
				'options' => [
					'grid'      => esc_html__( 'Grid', 'lenxel-core' ),
					'carousel'  => esc_html__( 'Carousel', 'lenxel-core' ),
					'list'  		=> esc_html__( 'List', 'lenxel-core' ),
				]
			]
		);

		$this->end_controls_section();

		$this->add_control_carousel(false, array('layout' => 'carousel'));

		$this->add_control_grid(array('layout' => 'grid'));

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

			$layout    = $settings['layout'];
			$user_role = $settings['user_role'];
			$per_page  = $settings['per_page'];

			if (is_front_page()) {
				$paged = (get_query_var('page')) ? get_query_var('page') : 1;
			} else {
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			}
			$offset = ($paged - 1) * $per_page;

			$args = [
				'offset'  => $offset,
				'number'  => $per_page,
				'role'    => $user_role,
				'orderby' => $settings['orderby'],
				'order'   => $settings['order']
			];

			if($settings['user_ids']){
				$ids = explode(',', $settings['user_ids']);
				if( is_array($ids) && count($ids) > 0 ){
					$args['include'] = $ids;
				}
			}

			$users       = count_users();
			$query       = get_users($args);
			$total_query = count($query);

			$total_pages = 1;
			if (isset($users['avail_roles'][$user_role]) && !empty($users['avail_roles'][$user_role])) {
				$total_pages = intval($users['avail_roles'][$user_role] / $per_page) + 1;
			}

			printf( '<div class="lnx-element-%s lnx-element">', esc_html($this->get_name()) );
			if( !empty($layout) ){
				include $this->get_template('course-users/' . $layout . '.php');
			}
			print '</div>';
		}else {
			$content = '<div></div>';
			wp_kses($content, array( 'div' ));
		}
	}
}

$widgets_manager->register_widget_type(new LNXElement_Users());
