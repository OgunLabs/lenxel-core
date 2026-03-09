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
class LNXElement_Career_Block extends LNXElement_Base {

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
		return 'lnx-career-block';
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
		return esc_html__( 'LNX Career Block', 'lenxel-core' );
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
		return 'eicon-table';
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
		return [ 'career', 'block', 'text' ];
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
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'lenxel-core' ),
				'default' => esc_html__( 'Add Your Heading Text Here', 'lenxel-core' ),
				'label_block' => true
			]
		);
		$this->add_control(
			'header_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'lenxel-core' ),
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
				'default' => 'h2',
			]
		);
		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'lenxel-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'lenxel-core' ),
				'separator' => 'before',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section( //** Section Icon
			'image_section',
			[
				'label' => esc_html__( 'Image', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Icon Image', 'lenxel-core' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/icon.png',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section( 
			'content',
			[
				'label' => esc_html__( 'Content', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'job_type',
			[
				'label' => esc_html__( 'Job Type', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Full Time'
			]
		);
		$this->add_control(
			'company',
			[
				'label' => esc_html__( 'Company', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Lenxelthemes'
			]
		);
		$this->add_control(
			'address',
			[
				'label' => esc_html__( 'Address', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'New South Wales, Australia'
			]
		);
		$this->end_controls_section();

		// Title Styling
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-career .box-content .title, {{WRAPPER}} .gsc-career .box-content .title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .gsc-career .box-content .title',
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'	=> [
					'top' 		=> 0,
					'bottom'		=> 0,
					'right' 		=> 0,
					'left'  		=> 0,
					'unit'		=> 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-career .box-content .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Job Type Styling
		$this->start_controls_section(
			'section_job_type_style',
			[
				'label' => esc_html__( 'Job Type', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'job_type_color',
			[
				'label' => esc_html__( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-career .box-content .job-type' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'job_type_background',
			[
				'label' => esc_html__( 'Background Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-career .box-content .job-type' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'job_type_typography',
				'selector' => '{{WRAPPER}} .gsc-career .box-content .job-type',
			]
		);
		$this->add_responsive_control(
			'job_type_padding',
			[
				'label' => esc_html__( 'Padding', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'	=> [
					'top' 		=> 2,
					'bottom'		=> 2,
					'right' 		=> 12,
					'left'  		=> 12,
					'unit'		=> 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-career .box-content .job-type' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Address & Company Styling
		$this->start_controls_section(
			'info_type_style',
			[
				'label' => esc_html__( 'Information (Address & Company)', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'info_color',
			[
				'label' => esc_html__( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-career .box-content .box-information' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'info_typography',
				'selector' => '{{WRAPPER}} .gsc-career .box-content .box-information',
			]
		);
		$this->add_responsive_control(
			'info_padding',
			[
				'label' => esc_html__( 'Padding', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'	=> [
					'top' 		=> 10,
					'bottom'		=> 0,
					'right' 		=> 0,
					'left'  		=> 0,
					'unit'		=> 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-career .box-content .box-information' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$settings = $this->get_settings_for_display();
		printf( '<div class="lnx-element-%s lnx-element">', esc_html($this->get_name()) );
         include $this->get_template('career-block.php');
      print '</div>';
	}

}
      $widgets_manager->register_widget_type(new LNXElement_Career_Block());
