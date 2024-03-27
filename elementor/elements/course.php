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
class LNXElement_Course extends LNXElement_Base {

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
		return 'lnx-course';
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
		$get_current_name = load_lenxel_widget_content_element('LNX Course');
		$filter_name = 'lenxel/element/'.$this->get_name();
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
		return [ 'course', 'filter' ];
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

	private function get_categories_list(){
      $categories = array();

      $categories['none'] = __( 'None', 'lenxel-core' );
      $taxonomy = 'course-category';
      $tax_terms = get_terms( $taxonomy );
      if ( ! empty( $tax_terms ) && ! is_wp_error( $tax_terms ) ){
         foreach( $tax_terms as $item ) {
            $categories[$item->term_id] = $item->name;
         }
      }
      return $categories;
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
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Layout & Content', 'lenxel-core' ),
			]
		);

		$this->add_control(
         'layout',
         [
             'label'   => __( 'Layout Display', 'lenxel-core' ),
             'type'    => Controls_Manager::SELECT,
             'default' => 'carousel',
             'options' => [
                 'grid'          => __( 'Grid', 'lenxel-core' ),
                 'carousel'      => __( 'Carousel', 'lenxel-core' ),
                 'list'          => __( 'List', 'lenxel-core' )
             ]
         ]
      );

      $this->add_control(
         'style',
         [
             'label'     => __('Style', 'lenxel-core'),
             'type'      => \Elementor\Controls_Manager::SELECT,
             'default' => 'course-1',
             'options' => [
                 'course-1'         => __( 'Item Course Style I', 'lenxel-core' ),
                 'course-2'         => __( 'Item Course Style II', 'lenxel-core' ),
                 'course-3'   	 	=> __( 'Item Course Style III', 'lenxel-core' ),
             ],
             'condition' => [
                 'layout' => array('grid', 'carousel')
             ]
         ]
     );

      $this->add_control(
         'image_size',
         [
            'label'     => __('Image Style', 'lenxel-core'),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'options'   => $this->get_thumbnail_size(),
            'default'   => 'post-thumbnail'
         ]
     );

		$this->add_control( // xx Layout
         'query_heading',
         [
            'label'   => __( 'Query', 'lenxel-core' ),
            'type'    => Controls_Manager::HEADING,
         ]
      );
      $this->add_control(
         'category_ids',
         [
            'label' 			=> __( 'Select By Category', 'lenxel-core' ),
            'type' 			=> Controls_Manager::SELECT2,
            'multiple'    	=> true,
            'default' 		=> '',
            'label_block' 	=> true,
            'options'   	=> $this->get_categories_list()
         ]
      );
      $this->add_control(
         'post_ids',
         [
            'label' 				=> __( 'Select Individually (IDs)', 'lenxel-core' ),
            'type' 				=> Controls_Manager::TEXT,
            'default' 			=> '',
            'label_block' 		=> true,
         	'description' 		=> 'e.g: 1,2,3,4,5',
         ]  
      );
      $this->add_control(
         'price',
         [
            'label' 			=> __( 'Price Type', 'lenxel-core' ),
            'type' 			=> Controls_Manager::SELECT2,
            'multiple'    	=> true,
            'label_block' 	=> true,
            'options'   	=> array(
            	'free'=> __('Free', 'lenxel-core'),
					'paid'=> __('Paid', 'lenxel-core')
            )
         ]
      );
      $this->add_control(
         'level',
         [
            'label' 			=> __( 'Level', 'lenxel-core' ),
            'type' 			=> Controls_Manager::SELECT2,
            'multiple'    	=> true,
            'label_block' 	=> true,
            'options'   	=> array(
            	'beginner'		=> __('Beginner', 'lenxel-core'),
					'intermediate'	=> __('Intermediate', 'lenxel-core'),
					'expert'			=> __('Expert', 'lenxel-core')
            )
         ]
      );
      $this->add_control(
         'featured',
         [
            'label' => __( 'Only show Course Featured', 'lenxel-core' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no'
         ]
      );
      $this->add_control(
         'posts_per_page',
         [
            'label' => __( 'Posts Per Page', 'lenxel-core' ),
            'type' => Controls_Manager::NUMBER,
            'default' => 6,
         ]
      );
     	$this->add_control(
         'orderby',
         [
            'label'   => __( 'Order By', 'lenxel-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'post_date',
            'options' => [
              	'post_date'  => __( 'Date', 'lenxel-core' ),
              	'post_title' => __( 'Title', 'lenxel-core' ),
              	'menu_order' => __( 'Menu Order', 'lenxel-core' ),
              	'rand'       => __( 'Random', 'lenxel-core' ),
            ]
         ]
      );

      $this->add_control(
         'order',
         [
            'label'   => __( 'Order', 'lenxel-core' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'desc',
            'options' => [
             	'asc'  => __( 'ASC', 'lenxel-core' ),
             	'desc' => __( 'DESC', 'lenxel-core' )
            ]
         ]
     	);

      $this->add_control(
         'pagination',
         [
            'label'     => __('Pagination', 'lenxel-core'),
            'type'      => Controls_Manager::SWITCHER,
            'default'   => 'no',
            'condition' => [
               'layout' => 'grid'
            ]
         ]
      );

      $this->end_controls_section();

      $this->add_control_carousel(false, array('layout' => 'carousel'));

      $this->add_control_grid(array('layout' => 'grid'));

		$this->start_controls_section(
			'section_video_style',
			[
				'label' => __( 'Video Button', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'video' => 'yes',
				],
			]
		);
		$this->add_control(
			'video_background_primary',
			[
				'label' => __( 'Primary Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .heading-video .video-link' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'video_background_second',
			[
				'label' => __( 'Primary Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .heading-video .video-link:after' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'video_color',
			[
				'label' => __( 'Text Button Video Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading  .heading-video .video-link' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'video_size',
			[
				'label' => __( 'Video Button Size', 'lenxel-core' ),
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
				'label' => __( 'Border Radius', 'lenxel-core' ),
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
				'label' => __( 'Box', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'box_space',
			[
				'label' => __( 'Heading Element Space Bottom', 'lenxel-core' ),
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
				'label' => __( 'Title', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .title a,.gsc-heading .title' => 'color: {{VALUE}};',
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
				'label' => __( 'Title Spacing', 'lenxel-core' ),
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
				'label' => __( 'Sub Title', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
 
		$this->add_control(
			'sub_title_color',
			[
				'label' => __( 'Text Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .sub-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_title_line_color',
			[
				'label' => __( 'Line Color', 'lenxel-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsc-heading .sub-title:after' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_title_space',
			[
				'label' => __( 'Sub Title Spacing', 'lenxel-core' ),
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
				'label' => __( 'Description', 'lenxel-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'desc_color',
			[
				'label' => __( 'Text Color', 'lenxel-core' ),
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
				'label' => __( 'Description Spacing', 'lenxel-core' ),
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

	public static function get_query_args(  $settings ) {
      $defaults = [
         'post_ids'        => '',
         'category_ids'    => '',
         'price'           => '',
         'level'           => '',
         'orderby'         => 'date',
         'order'           => 'desc',
         'posts_per_page'  => 3,
         'offset'          => 0,
      ];

      $settings = wp_parse_args( $settings, $defaults );
      $cats    = $settings['category_ids'];
      $ids     = $settings['post_ids'];
      $level   = $settings['level'];
      $price   = $settings['price'];
      $query_args = [
         'post_type' => 'courses',
         'posts_per_page' => $settings['posts_per_page'],
         'orderby' => $settings['orderby'],
         'order' => $settings['order'],
         'ignore_sticky_posts' => 1,
         'post_status' => 'publish', // Hide drafts/private posts for admins
      ];

      if($cats){
         if( is_array($cats) && count($cats) > 0 ){
            $field_name = is_numeric($cats[0]) ? 'term_id':'slug';
            $query_args['tax_query'] = array(
               array(
                  'taxonomy' => 'course-category',
                  'terms' => $cats,
                  'field' => $field_name,
                  'include_children' => false
               )
            );
         }
      }

      $is_membership = get_tutor_option('monetize_by')=='pmpro' && tutils()->has_pmpro();
      $meta_query = array();
      if(is_array($price) && count($price) > 0){
         $meta_query[] = array(
            'key'      => '_tutor_course_price_type',
            'value'    => $price,
            'compare'  => 'IN'
         );
      }

      if(is_array($level) && count($level) > 0){
         $meta_query[] = array(
            'key'      => '_tutor_course_level',
            'value'    => $level,
            'compare'  => 'IN'
         );
      }
      if($settings['featured'] == 'yes'){
         $meta_query[] = array(
            'key'      => 'lenxel_course_featured',
            'value'    => '1',
            'compare'  => '='
         );
      }
      count($meta_query) ? $query_args['meta_query'] = $meta_query : 0;

      if( $ids ){
         if( is_array($ids) && count($ids) > 0 ){
            $query_args['post__in'] = $ids;
            $query_args['orderby'] = 'post__in';
         }
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
	 * Render heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		if ( get_template_restrict()->has_premium){
			$settings = $this->get_settings_for_display();
			printf( '<div class="lnx-element-%s lnx-element">', $this->get_name() );
				if( !empty($settings['layout']) ){
				include $this->get_template('course/' . $settings['layout'] . '.php');
				}
			print '</div>';
		}else {
			$content='';
			printf('%s', $content);
		}
	}
}

$widgets_manager->register_widget_type(new LNXElement_Course());
