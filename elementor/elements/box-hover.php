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
use Elementor\Controls_Stack;
use Elementor\Repeater;

class LNXElement_Box_Hover extends LNXElement_Base{

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
        return 'lnx-box-hover';
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
        return esc_html__('LNX Box Hover', 'lenxel-core');
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
        return [ 'hover', 'content', 'box' ];
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
        $this->add_control(
            'style',
            array(
                'label'   => esc_html__( 'Style', 'lenxel-core' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                  'style-1' => esc_html__('Style I - Carousel', 'lenxel-core'),
                ]
            )
         );

        $repeater = new Repeater();
        $repeater->add_control(
            'box_title',
            [
                'label'   => esc_html__('Name', 'lenxel-core'),
                'default' => 'Box Title',
                'type'    => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'box_image',
            [
                'label'      => esc_html__('Background Image', 'lenxel-core'),
                'dynamic' => [
                  'active' => true,
                ],
                'default'    => [
                    'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/testimonial.png',
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => true,
            ]
        );
        $repeater->add_control(
            'selected_icon',
            [
                'label'      => esc_html__('Choose Icon', 'lenxel-core'),
                'type'       => Controls_Manager::ICONS,
                'default' => [
                  'value' => 'fas fa-home',
                  'library' => 'fa-solid',
                ]
            ]
        );
        $repeater->add_control(
            'box_content',
            [
                'label'       => esc_html__('Content', 'lenxel-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'I was impresed by the lenxel services, lorem ipsum is simply free text used by copytyping refreshing. Neque porro est qui dolorem ipsum quia.',
                'label_block' => true,
                'rows'        => '10',
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label'     => esc_html__( 'Link', 'lenxel-core' ),
                'type'      => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'lenxel-core' ),
                'label_block' => true
              ]
          );

        $this->add_control(
            'content_items',
            [
                'label'       => esc_html__('Content Item', 'lenxel-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ box_title }}}',
                'default'     => array(
                    array(
                        'box_title'  => esc_html__('Women Rights', 'lenxel-core'),
                        'box_image'    => [
                            'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-4.jpg',
                        ],
                        'box_content'  => esc_html__( 'There are many variations of passages of available but the majority have suffered alter randomised words.', 'lenxel-core' ),
                    ),
                    array(
                        'box_title'  => esc_html__('Civil Rights', 'lenxel-core'),
                        'box_image'    => [
                            'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-5.jpg',
                        ],
                        'box_content'  => esc_html__( 'There are many variations of passages of available but the majority have suffered alter randomised words.', 'lenxel-core' ),
                    ),
                    array(
                        'box_title'  => esc_html__('Human Rights','lenxel-core'),
                        'box_image'    => [
                            'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-6.jpg',
                        ],
                        'box_content'  => esc_html__( 'There are many variations of passages of available but the majority have suffered alter randomised words.', 'lenxel-core' ),
                    ),
                    array(
                        'box_title'  => esc_html__('Civil Rights','lenxel-core'),
                        'box_image'    => [
                            'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-4.jpg',
                        ],
                        'box_content'  => esc_html__( 'There are many variations of passages of available but the majority have suffered alter randomised words.', 'lenxel-core' ),
                    ),
                ),
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'box_image', 
                'default'   => 'full',
                'separator' => 'none',
            ]
        );
        $this->add_control(
            'box_height',
            [
                'label'      => esc_html__('Box Height', 'lenxel-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default'    => [
                    'size' => 510
                ],
                'range'      => [
                    'px' => [
                        'min' => 320,
                        'max' => 800,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'view',
            [
                'label'   => esc_html__('View', 'lenxel-core'),
                'type'    => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );
        $this->end_controls_section();

        $this->add_control_carousel( false,
            array(
               'style' => ['style-1']
            )
        );

        // Icon Styling
        $this->start_controls_section(
            'section_style_icon',
            [
                'label'     => esc_html__('Icon', 'lenxel-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'icon_background',
            [
                'label'     => esc_html__('Icon Background Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Icon Background Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
          'icon_size',
          [
            'label' => esc_html__( 'Size', 'lenxel-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [
              'size' => 48
            ],
            'range' => [
              'px' => [
                'min' => 20,
                'max' => 80,
              ],
            ],
            'selectors' => [
              '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
              '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-icon svg' => 'width: {{SIZE}}{{UNIT}};'
            ],
          ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'icon_border',
                'selector'  => '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-icon',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label'      => esc_html__('Icon Border Radius', 'lenxel-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_icon',
            [
                'label' => esc_html__( 'Padding', 'lenxel-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top'       => 10,
                    'right'     => 0,
                    'left'      => 0,
                    'bottom'    => 0,
                    'unit'      => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Title Styling
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Name', 'lenxel-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_text_color',
            [
                'label'     => esc_html__('Text Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-title a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-title','{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-title a',
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'lenxel-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top'       => 0,
                    'right'     => 0,
                    'left'      => 0,
                    'bottom'    => 20,
                    'unit'      => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Content Styling
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__('Content', 'lenxel-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'content_text_color',
            [
                'label'     => esc_html__('Text Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-desc' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-desc',
            ]
        );
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__( 'Padding', 'lenxel-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top'       => 0,
                    'right'     => 15,
                    'left'      => 15,
                    'bottom'    => 00,
                    'unit'      => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .lnx-hover-box-carousel .hover-box-item .box-content .box-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
      if(isset($settings['style']) && $settings['style']){
         include $this->get_template('hover-box/' . $settings['style'] . '.php');
      }
      print '</div>';
    }

}
$widgets_manager->register_widget_type(new LNXElement_Box_Hover());
