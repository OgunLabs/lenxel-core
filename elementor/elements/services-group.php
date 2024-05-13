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

class LNXElement_Services_Group extends LNXElement_Base{

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
        return 'lnx-services-group';
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
      $get_current_name = lenxel_load_widget_content_element('LNX Services Group');
        $filter_name = 'lenxel/element/'.esc_html($this->get_name());
		return apply_filters( $filter_name, $get_current_name);
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
        return [ 'services', 'content', 'carousel', 'grid' ];
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
        $this->add_control( // xx Layout
            'layout_heading',
            [
                'label'   => esc_html__( 'Layout', 'lenxel-core' ),
                'type'    => Controls_Manager::HEADING,
            ]
        );
         $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Layout Display', 'lenxel-core' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid'      => esc_html__( 'Grid', 'lenxel-core' ),
                    'carousel'  => esc_html__( 'Carousel', 'lenxel-core' )
                ]
            ]
        );
  
        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'lenxel-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style-1' => esc_html__('Style I', 'lenxel-core')
                ],
                'default' => 'style-1',
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control(
          'title',
          [
            'label'       => esc_html__('Title', 'lenxel-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'Add your Title',
            'label_block' => true,
          ]
        );
        $repeater->add_control(
          'desc',
          [
            'label'       => esc_html__('Description', 'lenxel-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => 'Lorem ipsum dolor sit amet, consectetur notted adipisicing elit sed do.',
            'label_block' => true,
          ]
        );
        $repeater->add_control(
            'image',
            [
                'label'      => esc_html__('Choose Image', 'lenxel-core'),
                'default'    => [
                    'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-1.jpg',
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
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
          'services_content',
          [
            'label'       => esc_html__('Service Content Item', 'lenxel-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => array(
              array(
                'title'  => esc_html__( "About Lenxel", 'lenxel-core' ),
              ),
              array(
                'title'       => esc_html__( 'VR Teachnology', 'lenxel-core' ),
              ),
              array(
                'title'  => esc_html__( 'Partner International', 'lenxel-core' ),
              ),
              array(
                'title'  => esc_html__( 'Professional Team', 'lenxel-core' ),
              ),
            )
          ]
        );
        
        $this->end_controls_section();

        $this->add_control_carousel(false, array('layout' => 'carousel'));

        $this->add_control_grid(array('layout' => 'grid'));

        // Icon Styling
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
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content .box-icon i' => 'color: {{VALUE}};',
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content svg' => 'fill: {{VALUE}};'
            ],
          ]
        );

        $this->add_responsive_control(
          'icon_size',
          [
            'label' => esc_html__( 'Size', 'lenxel-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [
              'size' => 60
            ],
            'range' => [
              'px' => [
                'min' => 20,
                'max' => 80,
              ],
            ],
            'selectors' => [
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content .box-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content .box-icon svg' => 'width: {{SIZE}}{{UNIT}};'
            ],
          ]
        );

        $this->add_responsive_control(
          'icon_space',
          [
            'label' => esc_html__( 'Spacing', 'lenxel-core' ),
            'type' => Controls_Manager::SLIDER,
            'default' => [
              'size' => 0,
            ],
            'range' => [
              'px' => [
                'min' => 0,
                'max' => 50,
              ],
            ],
            'selectors' => [
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content .icon-inner' => 'padding-bottom: {{SIZE}}{{UNIT}};',
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
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content .icon-inner .box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
          ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
          'section_style_content',
          [
            'label' => esc_html__( 'Content', 'lenxel-core' ),
            'tab'   => Controls_Manager::TAB_STYLE,
          ]
        );

        $this->add_control(
          'heading_title',
          [
            'label' => esc_html__( 'Title', 'lenxel-core' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
          ]
        );

        $this->add_responsive_control(
          'title_bottom_space',
          [
            'label' => esc_html__( 'Spacing', 'lenxel-core' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
              'px' => [
                'min' => 0,
                'max' => 100,
              ],
            ],
            'default' => [
              'size'  => 5
            ],
            'selectors' => [
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content .title' => 'padding-bottom: {{SIZE}}{{UNIT}};',
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
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content .title' => 'color: {{VALUE}};',
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content .title a' => 'color: {{VALUE}};',
            ],
          ]
        );

        $this->add_group_control(
          Group_Control_Typography::get_type(),
          [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .gsc-services-group .icon-box-item-content .title, {{WRAPPER}} .gsc-services-group .icon-box-item-content .title a',
          ]
        );

        $this->add_control(
          'heading_description',
          [
            'label' => esc_html__( 'Description', 'lenxel-core' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
              'style' => ['style-2'],
            ],
          ]
        );

        $this->add_control(
          'description_color',
          [
            'label' => esc_html__( 'Color', 'lenxel-core' ),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
              '{{WRAPPER}} .gsc-services-group .icon-box-item-content .desc' => 'color: {{VALUE}};',
            ],
            'condition' => [
              'style' => ['style-2'],
            ],
          ]
        );

        $this->add_group_control(
          Group_Control_Typography::get_type(),
          [
            'name' => 'description_typography',
            'selector' => '{{WRAPPER}} .gsc-services-group .icon-box-item-content',
            'condition' => [
              'style' => ['style-2'],
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
      if ( lenxel_get_template_restrict()->has_premium){
        $settings = $this->get_settings_for_display();
        printf( '<div class="lnx-element-%s lnx-element">', esc_html($this->get_name()) );
          if( !empty($settings['layout']) ){
            include $this->get_template('services-group/' . $settings['layout'] . '.php');
          }
        print '</div>';
      }else {
			  $content = '<div></div>';
			wp_kses($content, array( 'div' ));
		  }
    }

}

$widgets_manager->register_widget_type(new LNXElement_Services_Group());
