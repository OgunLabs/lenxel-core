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
class LNXElement_Counter extends LNXElement_Base {  

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
		return 'lnx-counter';
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
		$get_current_name = lenxel_load_widget_content_element('LNX Counter');
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
		return [ 'counter', 'icon' ];
	}

	public function get_script_depends() {
      return [
         'jquery.count_to',
         'jquery.appear',
      ];
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
			'section_content',
			[
				'label' => esc_html__( 'Content', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon Class', 'lenxel-core' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-home',
					'library' => 'fa-solid',
				],
			]
		);
		$this->add_control(
			'number',
			[
				'label' => esc_html__( 'Number', 'lenxel-core' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 110
			]
		);
		$this->add_control(
			'text_before',
			[
				'label' => esc_html__( 'Text Before Number', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'text_after',
			[
				'label' => esc_html__( 'Text After Number', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'title_text',
			[
				'label' => esc_html__( 'Title & Description', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'This is the heading', 'lenxel-core' ),
				'placeholder' => esc_html__( 'Enter your title', 'lenxel-core' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'lenxel-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'title_size',
			[
				'label' => esc_html__( 'Title HTML Tag', 'lenxel-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'div',
			]
		);
		$this->add_control(
			'style',
			[
				'label' => esc_html__( 'Style', 'lenxel-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' 		=> esc_html__( 'Style I', 'lenxel-core' ),
					'style-2' 		=> esc_html__( 'Style II', 'lenxel-core' ),
					'style-3' 		=> esc_html__( 'Style III', 'lenxel-core' ),
				],
				'default' => 'style-1',
			]
		);
		$this->end_controls_section();


		// Style Icon
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'lenxel-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .milestone-block .milestone-icon .icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .milestone-block .milestone-icon .icon svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Hover | Icon Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .milestone-block:hover .milestone-icon .icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .milestone-block:hover .milestone-icon .icon svg' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 60
				],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .milestone-block .milestone-icon .icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .milestone-block .milestone-icon .icon svg' => 'width: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label' => esc_html__( 'Spacing', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .milestone-block.style-1 .box-content .milestone-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .milestone-block.style-2 .box-content .milestone-icon' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'lenxel-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'title_top_space',
			[
				'label' => esc_html__( 'Spacing', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .milestone-block .box-content .milestone-content .milestone-text' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .milestone-block .box-content .milestone-content .milestone-text' => 'color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .milestone-block .box-content .milestone-content .milestone-text',
			]
		);

		$this->end_controls_section();

		// Number Text
		$this->start_controls_section(
			'sectionn_number_style',
			[
				'label' => esc_html__( 'Number Text', 'lenxel-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'number_bottom_space',
			[
				'label' => esc_html__( 'Spacing', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .milestone-block .milestone-number-inner' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'number_text_color',
			[
				'label' => esc_html__( 'Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .milestone-block .milestone-number-inner' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_text_typography',
				'selector' => '{{WRAPPER}} .box-content .milestone-content .milestone-number-inner',
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
			include $this->get_template('counter.php');
		print '</div>';
		}else {
			$content = '<div></div>';
			wp_kses($content, array( 'div' ));
		}
	}
}
$widgets_manager->register_widget_type(new LNXElement_Counter());
