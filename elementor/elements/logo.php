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
class LNXElement_Logo extends LNXElement_Base {

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
		return 'lnx-logo';
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
		return esc_html__( 'LNX Logo', 'lenxel-core' );
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
		return 'eicon-site-logo';
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
		return [ 'image', 'logo', 'branding' ];
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
			'section_title',
			[
				'label' => esc_html__( 'Content', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'title_text',
			[
				'label' => esc_html__( 'Title', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'lenxel-core' ),
				'default' => esc_html__( 'Home', 'lenxel-core' ),
				'label_block' => true
			]
		);
		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Logo Image', 'lenxel-core' ),
				'type' => Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/logo.png',
				],
			]
		);
		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'lenxel-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'lenxel-core' ),
				'label_block' => true
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
			'max_width',
			[
				'label' => esc_html__( 'Max Width', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 120,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-logo .site-branding-logo' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Logo Styling', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'logo_padding',
			[
				'label' => esc_html__( 'Padding', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gsc-logo .site-branding-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'logo_margin',
			[
				'label' => esc_html__( 'Margin', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gsc-logo .site-branding-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
         Group_Control_Border::get_type(),
         [
            'name'      => 'logo_border',
            'selector'  => '{{WRAPPER}} .gsc-logo .site-branding-logo',
            'separator' => 'before',
         ]
       );

       $this->add_control(
         'image_border_radius',
         [
            'label'      => esc_html__('Border Radius', 'lenxel-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .gsc-logo .site-branding-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		
      printf( '<div class="lnx-element-%s lnx-element">', esc_html($this->get_name()) );
         include $this->get_template('logo.php');
      print '</div>';
	}
}

$widgets_manager->register_widget_type( new LNXElement_Logo );
