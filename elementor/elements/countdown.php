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
class LNXElement_Countdown extends LNXElement_Base {

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
		return 'lnx-countdown';
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
		$get_current_name = lenxel_load_widget_content_element('LNX Countdown');
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
		return 'eicon-countdown';
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
		return [ 'countdown', 'time', 'text' ];
	}

	public function get_script_depends() {
      return [
         'countdown'
      ];
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
			'title_text',
			[
				'label' => esc_html__( 'Title', 'lenxel-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your title', 'lenxel-core' ),
				'default' => esc_html__( 'Add Your Heading Text Here', 'lenxel-core' ),
				'label_block' => true
			]
		);
		$this->add_control(
			'year',
			[
				'label' => esc_html__( 'Year', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => gmdate('Y'),
				'placeholder' => esc_html__( 'ex: 2019', 'lenxel-core' ),
				'label_block' => true
			]
		);
		$this->add_control(
			'month',
			[
				'label' => esc_html__( 'Month', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '12',
				'placeholder' => esc_html__( 'ex: 12', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'day',
			[
				'label' => esc_html__( 'Day', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '01',
				'placeholder' => esc_html__( 'ex: 01', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'hour',
			[
				'label' => esc_html__( 'Hour', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '00',
				'placeholder' => esc_html__( 'ex: 00', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'minutes',
			[
				'label' => esc_html__( 'Minutes', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '00',
				'placeholder' => esc_html__( 'ex: 00', 'lenxel-core' ),
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
		$this->end_controls_section();

		//Box Styling
		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Box', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' 		=> 40,
					'right' 		=> 40,
					'left'		=> 40,
					'bottom'		=> 40,
					'unit'		=> 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-countdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'box_background',
			[
				'label' => esc_html__( 'Background Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-countdown' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		//Content Styling
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Title', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'color',
			[
				'label' => esc_html__( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-countdown .content-inner .title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_title',
				'selector' => '{{WRAPPER}} .gsc-countdown .content-inner .title',
			]
		);
		$this->add_responsive_control(
			'space',
			[
				'label' => esc_html__( 'Spacing', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 60,
				], 
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-countdown .content-inner .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
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
			include $this->get_template('countdown.php');
			print '</div>';
		
		}else {
			$content = '<div></div>';
			wp_kses($content, array( 'div' ));
		}
	}

}
$widgets_manager->register_widget_type(new LNXElement_Countdown());

