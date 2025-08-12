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
class LNXElement_Course_Filter extends LNXElement_Base {

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
		return 'lnx-course-filter';
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
		$get_current_name = lenxel_load_widget_content_element('LNX Course With Filter');
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
		return 'eicon-t-letter';
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
		return [ 'courses', 'filter' ];
	}

	public function get_script_depends() {
		return [
			'lenxel.elements',
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
	protected function register_controls(){
		$supported_filters = tutor_utils()->get_option('supported_course_filters', array());
		$supported_filters = array_keys($supported_filters);

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'lenxel-core' ),
			]
		);

		$this->add_control(
			'filter_style',
			[
				'label'  => esc_html__( 'Filter Style', 'lenxel-core' ),
				'type'   => Controls_Manager::SELECT,
				'options' => [
				  'filter-dropdow' => esc_html__('Filter Dropdow', 'lenxel-core'),
				  'filter-list'    => esc_html__('Filter List', 'lenxel-core'),
				],
				'default' => 'filter-dropdow',
			]
		);

		$this->add_control(
			'layout',
			[
				'label'  => esc_html__( 'Style', 'lenxel-core' ),
				'type'   => Controls_Manager::SELECT,
				'options' => [
				  'filter-layout-top'   => esc_html__('Filter Top', 'lenxel-core'),
				  'filter-layout-left' => esc_html__('Filter Left', 'lenxel-core'),
				  'filter-layout-right' => esc_html__('Filter Right', 'lenxel-core'),
				],
				'default' => 'filter-layout-top',
			]
		);

		$this->add_control(
         'per_page',
         [
            'label'     => esc_html__('Per Page', 'lenxel-core'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => '8',
         ]
     	);

		if(in_array('search', $supported_filters)){
			$this->add_control(
				'search_keyword',
				[
					'label' => esc_html__( 'Enable Search Keyword', 'lenxel-core' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);
		}

		if(in_array('category', $supported_filters)){ 
			$this->add_control(
				'search_category',
				[
					'label' => esc_html__( 'Enable Search Category', 'lenxel-core' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);
		}

		if(in_array('tag', $supported_filters)){
			$this->add_control(
				'search_tags',
				[
					'label' => esc_html__( 'Enable Search Tags', 'lenxel-core' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => 'no',
				]
			);
		}

		if(in_array('difficulty_level', $supported_filters)){
			$this->add_control(
				'search_level',
				[
					'label' => esc_html__( 'Enable Search Level', 'lenxel-core' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);
		}

		$this->add_control(
			'search_price',
			[
				'label' => esc_html__( 'Enable Search Price', 'lenxel-core' ),
				'type' => Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		if(in_array('search', $supported_filters)){
			$this->add_control(
				'placeholder_keyword',
				[
					'label' => esc_html__( 'Placeholder keyword input', 'lenxel-core' ),
					'type' => Elementor\Controls_Manager::TEXT,
					'default' => esc_html__('Search...', 'lenxel-core'),
					'label_block' => true,
					'condition' => [
					  'search_keyword' => array('yes')
					]
				]
			);
		}

		if(in_array('category', $supported_filters)){ 
			$this->add_control(
				'placeholder_category',
				[
					'label' => esc_html__( 'Placeholder Category', 'lenxel-core' ),
					'type' => Elementor\Controls_Manager::TEXT,
					'default' => esc_html__('All Categories', 'lenxel-core'),
					'label_block' => true,
					'condition' => [
					  'search_category' => array('yes')
					]
				]
			);
		}

		if(in_array('tag', $supported_filters)){
			$this->add_control(
				'placeholder_tags',
				[
					'label' => esc_html__( 'Placeholder Category', 'lenxel-core' ),
					'type' => Elementor\Controls_Manager::TEXT,
					'default' => esc_html__('All Tags', 'lenxel-core'),
					'label_block' => true,
					'condition' => [
					  'search_tags' => ('yes')
					]
				]
			);
		}
			
		if(in_array('difficulty_level', $supported_filters)){
			$this->add_control(
				'placeholder_level',
				[
					'label' => esc_html__( 'Placeholder Level', 'lenxel-core' ),
					'type' => Elementor\Controls_Manager::TEXT,
					'default' => esc_html__('Level', 'lenxel-core'),
					'label_block' => true,
					'condition' => [
					  'search_level' => ('yes')
					]
				]
			);
		}

		$this->add_control(
			'placeholder_price',
			[
				'label' => esc_html__( 'Placeholder Price', 'lenxel-core' ),
				'type' => Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Price', 'lenxel-core'),
				'label_block' => true,
				'condition' => [
				  'search_price' => ('yes')
				]
			]
		);

		$this->add_control(
			'label_input',
			[
				'label' => esc_html__( 'Enable Label Input', 'lenxel-core' ),
				'type' => Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'filter_order',
			[
				'label' => esc_html__( 'Disable Order', 'lenxel-core' ),
				'type' => Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
         'pagination',
         [
            'label'     => esc_html__('Pagination', 'lenxel-core'),
            'type'      => Controls_Manager::SWITCHER,
            'default'   => 'no',
         ]
     	);

		$this->end_controls_section();

       $this->add_control_grid();

		$this->start_controls_section(
			'section_video_style',
			[
				'label' => esc_html__( 'Video Button', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'video' => 'yes',
				],
			]
		);
		$this->add_control(
			'video_background_primary',
			[
				'label' => esc_html__( 'Primary Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .heading-video .video-link' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'video_background_second',
			[
				'label' => esc_html__( 'Primary Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .heading-video .video-link:after' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'video_color',
			[
				'label' => esc_html__( 'Text Button Video Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading  .heading-video .video-link' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'video_size',
			[
				'label' => esc_html__( 'Video Button Size', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 92,
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-heading  .heading-video .video-link' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height:{{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'lenxel-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gsc-heading  .heading-video .video-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Box', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'box_space',
			[
				'label' => esc_html__( 'Heading Element Space Bottom', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 26,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .gsc-heading .title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .gsc-heading .title',
			]
		);
		$this->add_responsive_control(
			'title_space',
			[
				'label' => esc_html__( 'Title Spacing', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_sub_title_style',
			[
				'label' => esc_html__( 'Sub Title', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
 
		$this->add_control(
			'sub_title_color',
			[
				'label' => esc_html__( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .sub-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_title_line_color',
			[
				'label' => esc_html__( 'Line Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .sub-title:after' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_title_space',
			[
				'label' => esc_html__( 'Sub Title Spacing', 'lenxel-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_sub_title',
				'selector' => '{{WRAPPER}} .gsc-heading .sub-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_desc_style',
			[
				'label' => esc_html__( 'Description', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'desc_color',
			[
				'label' => esc_html__( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .title-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_desc',
				'selector' => '{{WRAPPER}} .gsc-heading .title-desc',
			]
		);

		$this->add_responsive_control(
			'description_space',
			[
				'label' => esc_html__( 'Description Spacing', 'lenxel-core' ),
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
					'{{WRAPPER}} .gsc-heading .title-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				include $this->get_template('course-filter/course.php');
			print '</div>';
		}else {
			$content = '<div></div>';
			wp_kses($content, array( 'div' ));
		}
	}
}

$widgets_manager->register_widget_type(new LNXElement_Course_Filter());
