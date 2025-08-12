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
class LNXElement_Slider_Images extends LNXElement_Base{

	public function get_name() {
		return 'lnx-slider-images';
	}

	public function get_title() {
		$get_current_name = lenxel_load_widget_content_element('LNX Slider Images');
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
		return 'eicon-slider-push';
	}

	public function get_keywords() {
		return [ 'slider', 'images', 'carousel' ];
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
				'label' => esc_html__('Query & Layout', 'lenxel-core'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();
      $repeater->add_control(
         'image',
         [
            'label'       => esc_html__('Image', 'lenxel-core'),
            'type'        => Controls_Manager::MEDIA,
            'show_label' => false,
            'default'    => [
               'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-2.jpg',
            ]
         ]
      );
		$this->add_control(
         'images',
         [
            'label'       => esc_html__('Testimonials Content Item', 'lenxel-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => array(
              	array(
                  'image'    => [
                     'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-1.jpg',
                  ],
                  'title' => esc_html__('The New Future of architecture', 'lenxel-core'),
              	),
               array(
                  'image'    => [
                     'url' => LENXEL_PLUGIN_URL . 'elementor/assets/images/image-2.jpg',
                  ],
                  'title' => esc_html__('The New Future of architecture', 'lenxel-core'),
              	),
            )
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
			'image_size',
			[
				'label'     => esc_html__('Style', 'lenxel-core'),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $this->get_thumbnail_size(),
				'default'   => 'lenxel_medium'
			]
		);
	
		$this->end_controls_section();

	}

	 protected function render() {
		if ( lenxel_get_template_restrict()->has_premium){
		  $settings = $this->get_settings_for_display();
		  printf( '<div class="lnx-element-%s lnx-element">', esc_html($this->get_name()) );
			include $this->get_template('slider-images.php');
		  print '</div>'; 
		}else {
			$content = '<div></div>';
			wp_kses($content, array( 'div' ));
		}
	 }
}

$widgets_manager->register_widget_type(new LNXElement_Slider_Images());
