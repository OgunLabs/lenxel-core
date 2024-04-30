<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;

class LNXElement_Teams extends  LNXElement_Base {

    public function get_name() {
        return 'lnx-teams';
    }

    public function get_title() {
        $get_current_name = lenxel_load_widget_content_element('LNX Teams');
        $filter_name = 'lenxel/element/'.esc_html($this->get_name());
		return apply_filters( $filter_name, $get_current_name);
    }

    public function get_keywords() {
        return [ 'team', 'content', 'carousel', 'grid' ];
    }

    public function get_icon() {
        return 'eicon-person';
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

    private function get_posts() {
        $posts = array();

        $loop = new \WP_Query( array(
            'post_type' => array('lnx_team'),
            'posts_per_page' => -1,
            'post_status'=>array('publish'),
        ) );

        $posts['none'] = esc_html__('None', 'lenxel-core');

        while ( $loop->have_posts() ) : $loop->the_post();
            $id = get_the_ID();
            $title = get_the_title();
            $posts[$id] = $title;
        endwhile;

        wp_reset_postdata();

        return $posts;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_team_query',
            [
                'label' => esc_html__('Teams Query', 'lenxel-core'),
            ]
        );
     
        $this->add_control(
            'post_ids',
            [
                'label' => esc_html__( 'Select Individually', 'lenxel-core' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple'    => true,
                'label_block' => true,
                'options'   => $this->get_posts()
            ]  
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'lenxel-core' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__( 'Order By', 'lenxel-core' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post_date',
                'options' => [
                    'post_date'  => esc_html__( 'Date', 'lenxel-core' ),
                    'post_title' => esc_html__( 'Title', 'lenxel-core' ),
                    'menu_order' => esc_html__( 'Menu Order', 'lenxel-core' ),
                    'rand'       => esc_html__( 'Random', 'lenxel-core' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__( 'Order', 'lenxel-core' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc'  => esc_html__( 'ASC', 'lenxel-core' ),
                    'desc' => esc_html__( 'DESC', 'lenxel-core' ),
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_team_layout',
            [
                'label' => esc_html__('Layout', 'lenxel-core'),
                'type'  => Controls_Manager::SECTION,
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
                    'carousel'  => esc_html__( 'Carousel', 'lenxel-core' ),
                ]
            ]
        );
        $this->add_control(
            'style',
            [
                'label'     => esc_html__('Style', 'lenxel-core'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'team-style-1'           => esc_html__( 'LNX Team Style', 'lenxel-core' ),
                ],
                 'default' => 'team-style-1',
            ]
        );
        $this->add_control(
            'image_size',
            [
               'label'     => esc_html__('Style', 'lenxel-core'),
               'type'      => \Elementor\Controls_Manager::SELECT,
               'options'   => $this->get_thumbnail_size(),
               'default'   => 'lenxel_medium'
            ]
        );
  
        $this->add_control(
            'show_skills',
            [
                'label'     => esc_html__('Show Skills', 'lenxel-core'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'no',
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label'     => esc_html__('Pagination', 'lenxel-core'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'no',
                'condition' => [
                    'layout' => 'grid'
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_control_carousel(false, array('layout' => 'carousel'));

        $this->add_control_grid(array('layout' => 'grid'));

        // Name Styling
        $this->start_controls_section(
            'section_style_team_name',
            [
                'label' => esc_html__('Name', 'lenxel-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_text_color',
            [
                'label'     => esc_html__('Text Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-block.team-v1 .team-name a,.team-block.team-v2 .team-name, {{WRAPPER}} .team-block.team-v2 .team-name a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'name_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .team-block .team.text-align' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .team-block.team-v1 .team-name,.team-block.team-v2 .team-name, {{WRAPPER}} .team-block.team-v2 .team-name a',
            ]
        );

        $this->end_controls_section();

        // Job Styling
        $this->start_controls_section(
            'section_style_team_job',
            [
                'label' => esc_html__('Job', 'lenxel-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'job_text_color',
            [
                'label'     => esc_html__('Text Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-block.team-v1 .team-job,.team-block.team-v2 .team-job' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'job_typography',
                'selector' => '{{WRAPPER}} .team-block.team-v1 .team-job,.team-block.team-v2 .team-job, {{WRAPPER}} .team-block.team-v2 .team-name a',
            ]
        );

        $this->end_controls_section();

        // Information.
        $this->start_controls_section(
            'section_style_team_social',
            [
                'label' => esc_html__('Social', 'lenxel-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'social_size',
            [
                'label' => esc_html__( 'Social Size', 'lenxel-core' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 16
                ],
                'range' => [
                    'px' => [
                        'min' => 12,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .team-block.team-v2 .team-image .socials-team a' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label'     => esc_html__('Social Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-block.team-v2 .team-image .socials-team a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_hover_color',
            [
                'label'     => esc_html__('Social Hover Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-block.team-v2 .team-image .socials-team a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        // Padding Styling
        $this->start_controls_section(
            'section_style_team_padding',
            [
                'label' => esc_html__('Team Styling', 'lenxel-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
			'team_box_space',
			[
				'label' => esc_html__( 'Team Padding Spacing', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
				  'top'       => 20,
				  'right'     => 0,
				  'left'      => 0,
				  'bottom'    => 0,
				  'unit'      => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .item-columns .team-block .team-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
            'team_image_border_radius',
            [
                'label'      => esc_html__( 'Team Image Border Radius', 'lenxel-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                '{{WRAPPER}} .team-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                'separator' => 'before',
            ]
		);
        $this->add_control(
            'team_bg_text_color',
            [
                'label'     => esc_html__('Background Name/Job Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-block.team-v1 .team-content,{{WRAPPER}} .team-block.team-v1,{{WRAPPER}} .team-block.team-v2 .team-content,{{WRAPPER}} .team-block.team-v2' => 'background: {{VALUE}};'
                ],
            ]
        );
        $this->add_control(
            'bg_team_color',
            [
                'label'     => esc_html__('General Background Color', 'lenxel-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .team-block.team-v1' => 'background: {{VALUE}};'
                ],
            ]
        );
        $this->end_controls_section();

    }

    public static function get_query_args( $settings ) {
        $defaults = [
            'category' => '',
            'orderby' => 'date',
            'order' => 'desc',
            'posts_per_page' => 3,
            'offset' => 0,
        ];

        $settings = wp_parse_args( $settings, $defaults );
        $ids = $settings['post_ids'];

        $query_args = [
            'post_type' => 'lnx_team',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish', 
        ];

        if( is_array($ids) && count($ids) > 0 ){
            $query_args['post__in'] = $ids;
            $query_args['orderby'] = 'post__in';
        }

        if(is_front_page()){
            $query_args['paged'] = (get_query_var('page')) ? get_query_var('page') : 1;
        }else{
            $query_args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }
 
        return $query_args;
    }


    public function query_posts() {
        $query_args = $this->get_query_args( $this->get_settings() );
        return new WP_Query( $query_args );
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
                include $this->get_template('teams/' . $settings['layout'] . '.php');
            }
            print '</div>'; 
        }else {
            $content = '<div></div>';
			wp_kses($content, array( 'div' ));
        }
    }

}

$widgets_manager->register_widget_type(new LNXElement_Teams());
