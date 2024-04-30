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
class LNXElement_Cart_Box extends LNXElement_Base {  

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
		return 'lnx-cart-box';
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
		$get_current_name = lenxel_load_widget_content_element('LNX Cart Box');
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
		return [ 'cart' ];
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
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .gsc-cart-box .mini-cart-header .mini-cart .title-cart' => 'color: {{VALUE}}', 
            ],
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Number Color', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .gsc-cart-box .mini-cart-header .mini-cart .mini-cart-items' => 'color: {{VALUE}}', 
            ],
			]
		);
		$this->add_control(
			'number_background',
			[
				'label' => esc_html__( 'Number Background', 'lenxel-core' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .gsc-cart-box .mini-cart-header .mini-cart .mini-cart-items' => 'background-color: {{VALUE}}', 
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
			include $this->get_template('cart.php');
			print '</div>';
		}else {
			$content = '<div></div>';
			wp_kses($content, array( 'div' ));
		}
	}
}

 $widgets_manager->register_widget_type(new LNXElement_Cart_Box());
