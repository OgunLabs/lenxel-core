<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

/**
 * Class LNXElement_Posts_Grid
 */
class LNXElement_Posts extends LNXElement_Base{

    public function get_name() {
        return 'lnx-posts';
    }

    public function get_title() {
        return esc_html__('LNX Posts', 'lenxel-core');
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
        return 'eicon-posts-grid';
    }

    public function get_keywords() {
        return [ 'post', 'content', 'carousel', 'grid' ];
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

        $categories['none'] = esc_html__( 'None', 'lenxel-core' );
        $taxonomy = 'category';
        $tax_terms = get_terms( $taxonomy );
        if ( ! empty( $tax_terms ) && ! is_wp_error( $tax_terms ) ){
            foreach( $tax_terms as $item ) {
                $categories[$item->term_id] = $item->name;
            }
        }
        return $categories;
    }

    private function get_posts(){
        $posts = array();

        $loop = new \WP_Query( array(
            'post_type' => array('post'),
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
            'section_query',
            [
                'label' => esc_html__('Query & Layout', 'lenxel-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
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
                'default' => 'carousel',
                'options' => [
                    'grid'          => esc_html__( 'Grid', 'lenxel-core' ),
                    'carousel'      => esc_html__( 'Carousel', 'lenxel-core' ),
                    'small_list'    => esc_html__( 'Small List', 'lenxel-core' ),
                    'sticky'        => esc_html__( 'Post Large & List', 'lenxel-core' )
                ]
            ]
        );
        $this->add_control(
            'style',
            [
                'label'     => esc_html__('Style', 'lenxel-core'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default' => 'post-style-1',
                'options' => [
                    'post-style-1'         => esc_html__( 'Item Post Style I', 'lenxel-core' ),
                    'post-style-2'         => esc_html__( 'Item Post Style II', 'lenxel-core' ),
                ],
                'condition' => [
                    'layout' => array('grid', 'carousel')
                ]
            ]
        );
        $this->add_control(
            'image_size',
            [
               'label'     => esc_html__('Image Style', 'lenxel-core'),
               'type'      => \Elementor\Controls_Manager::SELECT,
               'options'   => $this->get_thumbnail_size(),
               'default'   => 'lenxel_medium'
            ]
        );
        $this->add_control(
         'excerpt_words',
         [
            'label'     => esc_html__('Excerpt Words', 'lenxel-core'),
            'type'      => 'number',
            'default'   => 15
         ]
        );

        $this->add_control( // xx Layout
            'query_heading',
            [
                'label'   => esc_html__( 'Query', 'lenxel-core' ),
                'type'    => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'category_ids',
            [
                'label' => esc_html__( 'Select By Category', 'lenxel-core' ),
                'type' => Controls_Manager::SELECT2,
                'multiple'    => true,
                'default' => '',
                'label_block' => true,
                'options'   => $this->get_categories_list()
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
                ],
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

        // Styling
        $this->start_controls_section(
            'section_styling_blog_sticky',
            [
                'label' => esc_html__( 'Right List Posts Style', 'lenxel-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout' => 'stick'
                ],
            ]
        );

        $this->add_responsive_control(
            'stick_list_padding',
            [
                'label' => esc_html__( 'Padding List Posts', 'lenxel-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .lnx-posts-stick .column-right > .content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'stick_list_background',
            [
                'label' => esc_html__( 'Background List Posts', 'lenxel-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-posts-stick .column-right > .content-inner' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'stick_list_post_title_color',
            [
                'label' => esc_html__( 'List Posts Title Color', 'lenxel-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .post-block-small .post-content .content-inner .entry-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stick_list_post_title_typography',
                'selector' => '{{WRAPPER}} .post-block-small .post-content .content-inner .entry-title a',
            ]
        );

        $this->add_control(
            'stick_list_post_meta_color',
            [
                'label' => esc_html__( 'List Posts Meta Color', 'lenxel-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .post-block-small .post-content .entry-meta .entry-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Styling Posts Grid & Carousel
        $this->start_controls_section(
            'section_styling_post_content',
            [
                'label' => esc_html__( 'Post Content', 'lenxel-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout' => ['grid', 'carousel']
                ],
            ]
        );

        $this->add_responsive_control(
            'post_box_padding',
            [
                'label' => esc_html__( 'Padding Post Content', 'lenxel-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .lnx-posts .entry-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'post_box_background',
            [
                'label' => esc_html__( 'Background Post Content', 'lenxel-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-posts .entry-content' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_section();

        // Styling post title
        $this->start_controls_section(
            'section_styling_post_title',
            [
                'label' => esc_html__( 'Post Title', 'lenxel-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout' => ['grid', 'carousel', 'small_list']
                ],
            ]
        );

        $this->add_control(
            'post_box_title_color',
            [
                'label' => esc_html__( 'Color Title', 'lenxel-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-posts .entry-content .entry-title a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .lnx-posts .post-block-small .post-content a' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'post_box_title_color_hover',
            [
                'label' => esc_html__( 'Color Title Hover', 'lenxel-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-posts .entry-content .entry-title a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .lnx-posts .post-block-small .post-content a' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'post_box_title_typography',
                'selector' => '{{WRAPPER}} .lnx-posts .entry-content .entry-title a',
                '{{WRAPPER}} .lnx-posts .post-block-small .post-content a' => 'color: {{VALUE}};'

            ]
        );

        $this->add_control(
            'post_box_meta_color',
            [
                'label' => esc_html__( 'List Posts Meta Color', 'lenxel-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-posts .post .entry-meta, {{WRAPPER}} .lnx-posts .post .entry-meta a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .lnx-posts .post-block-small .post-content a' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_section();

        // Styling post description
        $this->start_controls_section(
            'section_styling_post_description',
            [
                'label' => esc_html__( 'Post Description', 'lenxel-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout' => ['grid', 'carousel']
                ],
            ]
        );

        $this->add_control(
            'post_box_description_color',
            [
                'label' => esc_html__( 'Color', 'lenxel-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lnx-posts .entry-content .entry-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'post_box_description_typography',
                'selector' => '{{WRAPPER}} .lnx-posts .entry-content .entry-description',
            ]
        );

        $this->end_controls_section();

    }

    public static function get_query_args(  $settings ) {
        $defaults = [
            'post_ids' => '',
            'category_ids' => '',
            'orderby' => 'date',
            'order' => 'desc',
            'posts_per_page' => 3,
            'offset' => 0,
        ];

        $settings = wp_parse_args( $settings, $defaults );
        $cats = $settings['category_ids'];
        $ids = $settings['post_ids'];

        $query_args = [
            'post_type' => 'post',
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
                      'taxonomy' => 'category',
                      'terms' => $cats,
                      'field' => $field_name,
                      'include_children' => false
                    )
                );
            }
        }
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


    protected function render() {
        $settings = $this->get_settings_for_display();
        printf( '<div class="lnx-element-%s lnx-element">', esc_html($this->get_name()) );
        if( !empty($settings['layout']) ){
            include $this->get_template('posts/' . $settings['layout'] . '.php');
        }
        print '</div>'; 

    }

}

$widgets_manager->register_widget_type(new LNXElement_Posts());
