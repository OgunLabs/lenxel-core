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

class LNXElement_Testimonial extends LNXElement_Base{

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
        return 'lnx-testimonials';
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
        $get_current_name = load_lenxel_widget_content_element('LNX Testimonials');
        return __($get_current_name, 'lenxel-plugin');
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
        return 'eicon-testimonial-carousel';
    }

    public function get_keywords() {
        return [ 'testimonial', 'content', 'carousel' ];
    }

    public function get_script_depends() {
      return [
          'jquery.owl.carousel',
          'lenxel.elements',
      ];
    }

    public function get_style_depends() {
        return [
            'owl-carousel-css',
        ];
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
            'section_testimonial',
            [
                'label' => __('Testimonials', 'lenxel-plugin'),
            ]
        );
        $this->add_control(
            'style',
            array(
                'label'   => esc_html__( 'Style', 'lenxel-plugin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                  'style-1' => esc_html__('Carousel Style 1', 'lenxel-plugin'),
                  'style-2' => esc_html__('Carousel Style 2', 'lenxel-plugin'),
                  'style-3' => esc_html__('Carousel Style 3', 'lenxel-plugin'),
                  'style-4' => esc_html__('Carousel Style 4', 'lenxel-plugin'),
                ]
            )
         );

        $repeater = new Repeater();
        $repeater->add_control(
            'testimonial_title',
            [
                'label'   => __('Title', 'lenxel-plugin'),
                'default' => 'Amazing Courses',
                'type'    => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'testimonial_content',
            [
                'label'       => __('Content', 'lenxel-plugin'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'I was impresed by the moling services, not lorem ipsum is simply free text of used by refreshing. Neque porro este qui dolorem ipsum quia.',
                'label_block' => true,
                'rows'        => '10',
            ]
        );
        $repeater->add_control(
            'testimonial_image',
            [
                'label'      => __('Choose Image', 'lenxel-plugin'),
                'default'    => [
                    'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/testimonial.png',
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'testimonial_name',
            [
                'label'   => __('Name', 'lenxel-plugin'),
                'default' => 'John Doe',
                'type'    => Controls_Manager::TEXT,
            ]
        );
        
        $repeater->add_control(
            'testimonial_job',
            [
                'label'   => __('Job', 'lenxel-plugin'),
                'default' => 'Designer',
                'type'    => Controls_Manager::TEXT,
            ]
        );     
        
        $repeater->add_control(
            'testimonial_rating',
            [
                'label'   => __('Rating', 'lenxel-plugin'),
                'default' => 2,
                'type'    => Controls_Manager::NUMBER,
            ]
        );  
 
        $this->add_control(
            'testimonials',
            [
                'label'       => __('Testimonials Content Item', 'lenxel-plugin'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ testimonial_title }}}',
                'default'     => array(
                    array(
                        'testimonial_title'    => esc_html__( 'Amazing Courses', 'lenxel-plugin' ),
                        'testimonial_content'  => esc_html__( 'Lorem ipsum is simply free text dolor sit amet, consectetur notted adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'lenxel-plugin' ),
                        'testimonial_name'     => esc_html__( 'Christine Eve', 'lenxel-plugin' ),
                        'testimonial_job'      => esc_html__( 'Founder & CEO', 'lenxel-plugin' ),
                        'testimonial_rating'      => esc_html__( '1', 'lenxel-plugin' ),
                    ),
                    array(
                        'testimonial_title'    => esc_html__( 'Amazing Courses', 'lenxel-plugin' ),
                        'testimonial_content'  => esc_html__( 'Lorem ipsum is simply free text dolor sit amet, consectetur notted adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'lenxel-plugin' ),
                        'testimonial_name'     => esc_html__( 'Kevin Smith', 'lenxel-plugin' ),
                        'testimonial_job'      => esc_html__( 'Customer', 'lenxel-plugin' ),
                        'testimonial_rating'      => esc_html__( '1', 'lenxel-plugin' ),
                    ),
                    array(
                        'testimonial_title'    => esc_html__( 'Amazing Courses', 'lenxel-plugin' ),
                        'testimonial_content'  => esc_html__( 'Lorem ipsum is simply free text dolor sit amet, consectetur notted adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'lenxel-plugin' ),
                        'testimonial_name'     => esc_html__( 'Jessica Brown', 'lenxel-plugin' ),
                        'testimonial_job'      => esc_html__( 'Founder & CEO', 'lenxel-plugin' ),
                        'testimonial_rating'      => esc_html__( '1', 'lenxel-plugin' ),
                    ),
                    array(
                        'testimonial_title'    => esc_html__( 'Amazing Courses', 'lenxel-plugin' ),
                        'testimonial_content'  => esc_html__( 'Lorem ipsum is simply free text dolor sit amet, consectetur notted adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'lenxel-plugin' ),
                        'testimonial_name'     => esc_html__( 'David Anderson', 'lenxel-plugin' ),
                        'testimonial_job'      => esc_html__( 'Customer', 'lenxel-plugin' ),
                        'testimonial_rating'      => esc_html__( '1', 'lenxel-plugin' ),
                    ),
                ),
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'testimonial_image', 
                'default'   => 'full',
                'separator' => 'none',
                'condition' => [
                    'style' => array('style-1', 'style-2', 'style-4')
                ]
            ]
        );

        $this->add_control(
            'view',
            [
                'label'   => __('View', 'lenxel-plugin'),
                'type'    => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );
        $this->end_controls_section();

         $this->add_control_carousel( false,
            array(
               'style' => ['style-1', 'style-2', 'style-3', 'style-4']
            )
         );

        // Style.
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __('Content', 'lenxel-plugin'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
 
        $this->add_control(
            'content_content_color',
            [
                'label'     => __('Text Color', 'lenxel-plugin'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .current-slide .testimonial-content,{{WRAPPER}} .lnx-testimonial-carousel .testimonial-content' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .current-slide .testimonial-content,{{WRAPPER}} .lnx-testimonial-carousel .icon-quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .current-slide .testimonial-content,{{WRAPPER}} .lnx-testimonial-carousel .testimonial-item .testimonial-content',
            ]
        );

        $this->add_responsive_control(
			'content_testimonial_pad',
			[
				'label' => __( 'Content Padding', 'lenxel-plugin' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
				  'top'       => 0,
				  'right'     => 0,
				  'left'      => 0,
				  'bottom'    => 0,
				  'unit'      => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .current-slide .testimonial-content,{{WRAPPER}} .lnx-testimonial-carousel .testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        // Image Styling
        $this->start_controls_section(
            'section_style_image',
            [
                'label'     => __('Image', 'lenxel-plugin'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label'      => __('Image Size', 'lenxel-plugin'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .lnx-testimonial-carousel .testimonial-image img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'image_border',
                'selector'  => '{{WRAPPER}} .lnx-testimonial-carousel .testimonial-image img',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label'      => __('Border Radius', 'lenxel-plugin'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .lnx-testimonial-carousel .testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Name Styling
        $this->start_controls_section(
            'section_style_name',
            [
                'label' => __('Name', 'lenxel-plugin'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_text_color',
            [
                'label'     => __('Text Color', 'lenxel-plugin'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .testimonial-name' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dot' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .testimonial-name',
            ]
        );

        $this->add_responsive_control(
			'name_testimonial_pad',
			[
				'label' => __( 'Name Padding', 'lenxel-plugin' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
				  'top'       => 0,
				  'right'     => 0,
				  'left'      => 0,
				  'bottom'    => 0,
				  'unit'      => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .current-slide .testimonial-information' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        // Job Styling
        $this->start_controls_section(
            'section_style_job',
            [
                'label' => __('Job', 'lenxel-plugin'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'job_text_color',
            [
                'label'     => __('Text Color', 'lenxel-plugin'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .current-slide .testimonial-job,{{WRAPPER}} .current-slide .testimonial-job a,{{WRAPPER}} .testimonial-job, {{WRAPPER}} .testimonial-job a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'job_typography',
                'selector' => '{{WRAPPER}} .current-slide .testimonial-job,{{WRAPPER}} .elementor-testimonial-job',
            ]
        );

        $this->add_responsive_control(
			'job_testimonial_pad',
			[
				'label' => __( 'Job Padding', 'lenxel-plugin' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
				  'top'       => 0,
				  'right'     => 0,
				  'left'      => 0,
				  'bottom'    => 0,
				  'unit'      => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .current-slide .testimonial-information2,{{WRAPPER}} .testimonial-job' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();

        // Grouped Content
        $this->start_controls_section(
            'section_content_group',
            [
                'label' => __('Grouped Content Spacing', 'lenxel-plugin'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'testimonial_group_pad',
			[
				'label' => __( 'Grouped Content Spacing', 'lenxel-plugin' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
				  'top'       => 0,
				  'right'     => 0,
				  'left'      => 0,
				  'bottom'    => 0,
				  'unit'      => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .current-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
            'testimonial_bg_color',
            [
                'label'     => __('Background Color', 'lenxel-plugin'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-element-lnx-testimonials .current-slide' => 'background: {{VALUE}};',
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
        if ( get_template_restrict()->has_premium){
            $settings = $this->get_settings_for_display();
            printf( '<div class="lnx-element-%s lnx-element">', $this->get_name() );
            if(isset($settings['style']) && $settings['style']){
                include $this->get_template('testimonials/lnx-testimonials-' . $settings['style'] . '.php');
            }
            print '</div>';
        }else {
            echo $content;
        }
    }

}

$widgets_manager->register_widget_type(new LNXElement_Testimonial());
