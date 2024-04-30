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
use Elementor\Repeater;

/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class LNXElement_Pricing_Block extends LNXElement_Base {

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
		return 'lnx-pricing-block';
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
		$get_current_name = lenxel_load_widget_content_element('LNX Pricing Block');
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
		return 'eicon-price-list';
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
		return [ 'pricing', 'block' ];
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
			'style',
			[
				'label' => esc_html__( 'Style', 'lenxel-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' 		=> esc_html__( 'Style I: Default', 'lenxel-core' ),
					'style-2' 		=> esc_html__( 'Style II: Scale 0.95', 'lenxel-core' )
				],
				'default' => 'style-1',
			]
		);
		$this->add_control(
			'title_text',
			[
				'label' => esc_html__( 'Title', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'lenxel-core' ),
				'default' => esc_html__( 'Standard', 'lenxel-core' ),
				'label_block' => true
			]
		);
		$this->add_control(
			'subtitle_text',
			[
				'label' => esc_html__( 'Sub Title', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Private amenities', 'lenxel-core' ),
				'default' => esc_html__( 'Private amenities', 'lenxel-core' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'desc_text',
			[
				'label' => esc_html__( 'Description', 'lenxel-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Your Description', 'lenxel-core' ),
				'default' => esc_html__( 'There are many new variatns of pasages of available text.', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '60.00', 'lenxel-core' ),
				'default' => esc_html__( '60.00', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'currency',
			[
				'label' => esc_html__( 'Currency', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Currency', 'lenxel-core' ),
				'default' => esc_html__( '$', 'lenxel-core' ),
			]
		);
		$this->add_control(
			'period',
			[
				'label' => esc_html__( 'Period', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Monthly', 'lenxel-core' ),
				'default' => esc_html__( 'Monthly', 'lenxel-core' ),
			]
		);

		$repeater = new Repeater();
      $repeater->add_control(
         'pricing_features',
			[
	         'label'       => esc_html__('Pricing Features', 'lenxel-core'),
	         'type'        => Controls_Manager::TEXT,
	         'default'     => 'Free text goes here',
	         'label_block' => true,
	         'rows'        => '10',
	     	]
	   );
		$this->add_control(
         'pricing_content',
         [
            'label'       => esc_html__('Pricing Features', 'lenxel-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ pricing_features }}}',
            'default'     => array(
               array(
                  'pricing_features'  => esc_html__( '3D Visualization', 'lenxel-core' )
               ),
               array(
                  'pricing_features'  => esc_html__( 'Planning Solution', 'lenxel-core' )
               ),
               array(
                  'pricing_features'  => esc_html__( 'Selection of Materials', 'lenxel-core' )
               ),
               array(
                  'pricing_features'  => esc_html__( '10 Construction Drawings', 'lenxel-core' )
               )
            ),
         ]
      );

		$this->end_controls_section();

		$this->start_controls_section( //** Section Icon
			'section_Button',
			[
				'label' => esc_html__( 'Button', 'lenxel-core' ),
			]
		);

		$this->add_control(
			'button_url',
			[
				'label' => esc_html__( 'Button URL', 'lenxel-core' ),
				'type' => Controls_Manager::URL,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'lenxel-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Read More'
			]
		);

		$this->add_control(
			'button_style',
			[
				'label' => esc_html__( 'Button Style', 'lenxel-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'btn-theme' 		=> esc_html__('Button Theme', 'lenxel-core'),
					'btn-theme-2' 		=> esc_html__('Button Theme Second', 'lenxel-core'),
					'btn-white' 		=> esc_html__('Button White', 'lenxel-core'),
					'btn-black' 		=> esc_html__('Button Black', 'lenxel-core')
				],
				'default' => 'btn-white',
			]
		);

		$this->end_controls_section();

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
					'{{WRAPPER}} .gsc-pricing .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .gsc-pricing .title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_price_style',
			[
				'label' => esc_html__( 'Price Text', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
 
		$this->add_control(
			'sub_title_color',
			[
				'label' => esc_html__( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-pricing .content-inner .plan-price .price-value' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_price_text',
				'selector' => '{{WRAPPER}} .gsc-pricing .content-inner .plan-price .price-value',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
 
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-pricing .content-inner .plan-list li .text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_content',
				'selector' => '{{WRAPPER}} .gsc-pricing .content-inner .plan-list li .text',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-pricing .content-inner .plan-list li .icon' => 'color: {{VALUE}};',
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
			include $this->get_template('pricing-block.php');
		print '</div>';
		}else {
			$content = '<div></div>';
			wp_kses($content, array( 'div' ));
		}
	}

}

$widgets_manager->register_widget_type(new LNXElement_Pricing_Block());
