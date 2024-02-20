<?php
if ( ! defined( 'ABSPATH' ) ) {
	 exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Repeater;

/**
 * Class LNXElement_Gallery
 */
class LNXElement_Gallery extends LNXElement_Base{

	public function get_name() {
		return 'lnx-gallery';
	}

	public function get_title() {
		$get_current_name = load_lenxel_widget_content_element('LNX Gallery');
		return __($get_current_name, 'lenxel-theme-support');
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
		return 'eicon-slider-push';
	}

	public function get_keywords() {
		return [ 'gallery', 'images', 'carousel', 'grid' ];
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

	protected function register_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => __('Query & Layout', 'lenxel-theme-support'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();
      $repeater->add_control(
         'image',
         [
            'label'       => __('Image', 'lenxel-theme-support'),
            'type'        => Controls_Manager::MEDIA,
            'show_label' => false,
            'default'    => [
               'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-2.jpg',
            ]
         ]
      );
     	$repeater->add_control(
         'title',
         [
            'label'   => __('Title', 'lenxel-theme-support'),
            'default' => esc_html__('Luxury Interior', 'lenxel-theme-support'),
            'type'    => Controls_Manager::TEXT,
         ]
     	);
		$repeater->add_control(
         'sub_title',
         [
            'label'   => __('Sub-Title', 'lenxel-theme-support'),
            'default' => esc_html__('Charity', 'lenxel-theme-support'),
            'type'    => Controls_Manager::TEXT,
         ]
     	);

		$this->add_control(
         'images',
         [
            'label'       => __('Testimonials Content Item', 'lenxel-theme-support'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => array(
              	array(
                  'image'    => [
                     'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-2.jpg',
                  ],
                  'title' => esc_html__('Lenxel Education', 'lenxel-theme-support'),
                  'sub_title' => esc_html__('Learning', 'lenxel-theme-support'),
              	),
               array(
                  'image'    => [
                     'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-2.jpg',
                  ],
                  'title' => esc_html__('Lenxel Education', 'lenxel-theme-support'),
                  'sub_title' => esc_html__('Learning', 'lenxel-theme-support'),
              	),
               array(
                  'image'    => [
                     'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-2.jpg',
                  ],
                  'title' => esc_html__('Lenxel Education', 'lenxel-theme-support'),
                  'sub_title' => esc_html__('Learning', 'lenxel-theme-support'),
              	),
               array(
                  'image'    => [
                     'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-2.jpg',
                  ],
                  'title' => esc_html__('Lenxel Education', 'lenxel-theme-support'),
                  'sub_title' => esc_html__('Learning', 'lenxel-theme-support'),
              	),
               array(
                  'image'    => [
                     'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-2.jpg',
                  ],
                  'title' => esc_html__('Lenxel Education', 'lenxel-theme-support'),
                  'sub_title' => esc_html__('Learning', 'lenxel-theme-support'),
              	),
            )
         ]
      );

		$this->add_control( // xx Layout
			'layout_heading',
			[
				'label'   => __( 'Layout', 'lenxel-theme-support' ),
				'type'    => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'layout',
			[
				'label'   => __( 'Layout Display', 'lenxel-theme-support' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'      => __( 'Grid', 'lenxel-theme-support' ),
					'carousel'  => __( 'Carousel', 'lenxel-theme-support' ),
				]
			]
	  	);
		$this->add_control(
			'style',
			[
				'label'     => __('Style', 'lenxel-theme-support'),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'gallery-style-1'           => __( 'Gallery Style I', 'lenxel-theme-support' ),
				],
				'default' => 'style-1',
			]
		);
		$this->add_control(
			'image_size',
			[
				'label'     => __('Style', 'lenxel-theme-support'),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $this->get_thumbnail_size(),
				'default'   => 'lenxel_medium'
			]
		);
	  	$this->add_control(
			'pagination',
			[
				'label'     => __('Pagination', 'lenxel-theme-support'),
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

	}

	 protected function render() {
		if ( get_template_restrict()->has_premium){
			$settings = $this->get_settings_for_display();
			printf( '<div class="lnx-element-%s lnx-element">', $this->get_name() );
			if( !empty($settings['layout']) ){
					include $this->get_template('gallery/' . $settings['layout'] . '.php');
			}
			print '</div>'; 
		}else {
			echo $content;
		}
	 }
}

$widgets_manager->register_widget_type(new LNXElement_Gallery());
