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
 * Elementor icon box widget.
 *
 * Elementor widget that displays an icon, a headline and a text.
 *
 * @since 1.0.0
 */
class LNXElement_Search_Box extends LNXElement_Base {  

	/**
	 * Get widget name.
	 *
	 * Retrieve icon box widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'lnx-search-box';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve icon box widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		$get_current_name = lenxel_load_widget_content_element('LNX Search Box');
		$filter_name = 'lenxel/element/'.esc_html($this->get_name());
		return apply_filters( $filter_name, $get_current_name);
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve icon box widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-icon-box';
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
		return [ 'search' ];
	}

	/**
	 * Register icon box widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'lenxel-core' ),
			]
		);

		$this->add_control(
			'style',
			[
				'label' => esc_html__( 'Style', 'lenxel-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' 		=> esc_html__( 'Style I', 'lenxel-core' ),
				],
				'default' => 'style-1',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'lenxel-core' ),
				'style' => ['style-1']
			]
		);
		
		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'lenxel-core' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fa fa-search',
					'library' => 'fa-solid',
				],
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
				'selectors' => [
					'{{WRAPPER}} .gsc-search-box' => 'text-align: {{VALUE}};',
				],
				'default' => 'center',
				'condition' => [
					'style' => 'style-1'
				]
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 24,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-search-box .control-search i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gsc-search-box .control-search svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .gsc-search-box .control-search i' => 'color: {{VALUE}}', 
               '{{WRAPPER}} .gsc-search-box .control-search svg' => 'fill: {{VALUE}}', 
            ],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Color Hover', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .gsc-search-box .control-search:hover i' => 'color: {{VALUE}}', 
               '{{WRAPPER}} .gsc-search-box .control-search:hover svg' => 'fill: {{VALUE}}', 
            ],
			]
		);

		$this->add_control(
			'icon_background',
			[
				'label' => esc_html__( 'Background', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .gsc-search-box .control-search' => 'background: {{VALUE}}', 
            ],
			]
		);

		$this->add_control(
			'icon_background_hover',
			[
				'label' => esc_html__( 'Background Hover', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .gsc-search-box .control-search:hover' => 'background: {{VALUE}}', 
            ],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Padding', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gsc-search-box .control-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gsc-icon-box .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'lenxel-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' 		=> 15,
					'right' 		=> 15,
					'left'		=> 15,
					'bottom'		=> 15,
					'unit'		=> 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-search-box .lnx-search .lnx-search-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'content_size',
			[
				'label' => esc_html__( 'Content Width', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 200,
				],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-search-box .lnx-search-content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render icon box widget output on the frontend.
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
			include $this->get_template('search-box.php');
		print '</div>';
		}else {
			$content = '<div></div>';
			wp_kses($content, array( 'div' ));
		}
	}
}

$widgets_manager->register_widget_type(new LNXElement_Search_Box());
