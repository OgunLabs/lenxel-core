<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;

class LNXElement_Brand extends LNXElement_Base{

    /**
     * Get widget name.
     *
     * Retrieve testimonial widget name.
     *
     * @since  1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'lnx-brand';
    }

    /**
     * Get widget title.
     *
     * Retrieve testimonial widget title.
     *
     * @since  1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('LNX Brand', 'lenxel-core');
    }

    /**
     * Get widget icon.
     *
     * Retrieve testimonial widget icon.
     *
     * @since  1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-posts-carousel';
    }

    public function get_keywords() {
        return [ 'brand', 'content', 'carousel' ];
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
     * Register testimonial widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'lenxel-core'),
            ]
        );
        $repeater = new Repeater();
        
        $repeater->add_control(
            'title',
            [
                'label'       => esc_html__('Title', 'lenxel-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'image',
            [
                'label'      => esc_html__('Choose Image', 'lenxel-core'),
                'default'    => [
                    'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/brand.png',
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label'      => esc_html__('Link', 'lenxel-core'),
                'placeholder' => esc_html__( 'https://your-link.com', 'lenxel-core' ),
                'type'       => Controls_Manager::URL,
            ]
        );
        
        $this->add_control(
            'brands',
            [
                'label'       => esc_html__('Brand Content Item', 'lenxel-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default'     => array(
                    array(
                        'title'  => esc_html__( 'Brand 1', 'lenxel-core' )
                    ),
                    array(
                        'title'  => esc_html__( 'Brand 2', 'lenxel-core' )
                    ),
                    array(
                        'title'  => esc_html__( 'Brand 3', 'lenxel-core' )
                    ),
                    array(
                        'title'  => esc_html__( 'Brand 4', 'lenxel-core' )
                    ),
                    array(
                        'title'  => esc_html__( 'Brand 5', 'lenxel-core' )
                    ),
                    array(
                        'title'  => esc_html__( 'Brand 6', 'lenxel-core' )
                    ),
                ),
            ]
        );
        $this->add_control(
            'style',
            array(
                'label'   => esc_html__( 'Style', 'lenxel-core' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                  'style-1' => esc_html__('Style I', 'lenxel-core'),
                ]
            )
        );
        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image', 
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();

        $this->add_control_carousel(false);


        // Image Styling
        $this->start_controls_section(
            'section_style_image',
            [
                'label'     => esc_html__('Image', 'lenxel-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'image_border',
                'selector'  => '{{WRAPPER}} .lnx-brand-carousel .brand-item-content img',
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
                    '{{WRAPPER}} .lnx-brand-carousel .brand-item-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

     


        

    }

    /**
     * Render testimonial widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
      $settings = $this->get_settings_for_display();
      printf( '<div class="lnx-element-%s lnx-element">', esc_html($this->get_name()) );
        include $this->get_template('brand.php');
      print '</div>';
    }

}

$widgets_manager->register_widget_type(new LNXElement_Brand());
