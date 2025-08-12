<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
   $filter_object = new \TUTOR\Course_Filter();
   $filter_levels = array(
      'beginner'=> esc_html__('Beginner', 'lenxel-core'),
      'intermediate'=> esc_html__('Intermediate', 'lenxel-core'),
      'expert'=> esc_html__('Expert', 'lenxel-core')
   );
   $filter_prices=array(
      'free'=> esc_html__('Free', 'lenxel-core'),
      'paid'=> esc_html__('Paid', 'lenxel-core')
   );

   $supported_filters = tutor_utils()->get_option('supported_course_filters', array());
   $supported_filters = array_keys($supported_filters);
   $is_membership = get_tutor_option('monetize_by')=='pmpro' && tutils()->has_pmpro();

   $number = 0;
   $number = (in_array('search', $supported_filters) && $settings['search_keyword'] == 'yes') ? $number + 1 : $number;
   $number = (in_array('category', $supported_filters) && $settings['search_category'] == 'yes') ? $number + 1 : $number;
   $number = (in_array('difficulty_level', $supported_filters) && $settings['search_level'] == 'yes') ? $number + 1 : $number;
   $number = (!$is_membership && in_array('price_type', $supported_filters) && $settings['search_price'] == 'yes') ? $number + 1 : $number;

   $this->add_render_attribute( ['block'=> ['class'=> ['widget gsc-course-filter-form', $settings['style'] ]]]  );
   $link = isset($settings['link']['url']) ? $settings['link']['url'] : '';
?>

<div <?php $this->print_render_attribute_string('carousel'); ?>>
   <form class="course-filter-form" action="<?php echo esc_url($link) ?>"> 
      <div class="search-form-content">

         <div class="search-form-fields clearfix cols-<?php echo esc_attr($number) ?>">
            <?php if(in_array('search', $supported_filters) && $settings['search_keyword'] == 'yes'){ ?>
               <div class="course-filter_search">
                  <div class="content-inner">
                     <input type="text" name="keyword" placeholder="<?php echo esc_html__('Search...', 'lenxel-core'); ?>"/>
                     <i class="tutor-icon-magnifying-glass-1"></i>
                  </div>   
               </div>
            <?php } ?>

            <?php if(in_array('category', $supported_filters) && $settings['search_category'] == 'yes'){ ?>
               <div class="course-filter_category course-checkbox-filter">
                  <?php
                     ob_start();
                        wp_dropdown_categories( 
                           array(
                              'taxonomy'           => 'course-category',
                              'hierarchical'       => 1,
                              'show_option_all'    => false,
                              'show_option_none'   => 'All Categories',
                              'option_none_value'  => '',
                              'name'               => 'cat',
                              'orderby'            => 'name',
                              'hide_empty'         => false,
                              'class'              => 'option-select2-filter',

                           )
                        );
                     $html = str_replace('<select', '<select data-placeholder="All Categories"', ob_get_clean()); 
                     echo wp_kses(
                        $html,
                        array(
                            'div'=>array(
                              'class' => array(),
                            ),
                            'select'      => array(
                              'class' => array(),
                              'name' =>array(),
                              'data-placeholder' => array(),
                              'id' => array(),
                              'tabindex' => array(),
                              'aria-hidden'
                            ),
                            'option' => array(
                              'class' => array(),
                              'value'=>array(),
                            ),
                            'span' => array(
                              'aria-expanded'=>array(),
                              'aria-haspopup'=>array(),
                              'role'=>array(),
                              'expanded'=>array(),
                              'tabindex'=>array(),
                              'aria-labelledby'=>array(),
                              'class'=>array(),
                              'dir'=>array(),
                              'style' =>array(),
                              'aria-hidden'=>array(),
                            ),
                            'b' => array(
                              'role'=>array(),
                            ),
                            
                        )
                    );
                  ?>    
               </div>
            <?php } ?>

            <?php if(in_array('difficulty_level', $supported_filters) && $settings['search_level'] == 'yes'){ ?>
               <div class="course-filter_level course-checkbox-filter">
                  <select name="level" class="option-select2-filter" data-placeholder="All Level">
                     <option value="">All Level</option>
                     <?php foreach($filter_levels as $value=>$title){ ?>
                           <option value="<?php echo esc_attr($value); ?>"/> <?php echo esc_html($title); ?></option>
                     <?php } ?>
                  </select>
               </div>
            <?php } ?>

            <?php if(!$is_membership && in_array('price_type', $supported_filters) && $settings['search_price'] == 'yes'){ ?>
               <div class="course-filter-price_type course-checkbox-filter">
                  <select name="price" class="option-select2-filter" data-placeholder="All Price Type">
                     <option value="">All Price Type</option>
                     <?php foreach($filter_prices as $value=>$title){ ?>
                        <option value="<?php echo esc_attr($value); ?>"/> <?php echo esc_html($title); ?></option>
                     <?php } ?>
                  </select>
               </div>
            <?php } ?>
         </div>
         <?php wp_nonce_field('ajax-l-course-filter-nonce', 'ajax_l_course_filter_ajax'); ?>
         <div class="form-action">
            <button class="btn-theme btn-action" type="submit">
               <i class="fi flaticon-magnifying-glass"></i>
               <?php echo esc_html__('Search', 'lenxel-core') ?> 
            </button>
         </div>   
      </div>

   </form>
</div>      