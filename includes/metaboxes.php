<?php
function lenxel_register_meta_boxes(){
	$prefix = 'lenxel_';
	global $meta_boxes, $wp_registered_sidebars;;
	$sidebar = array();
	$sidebar[""] = ' --Default-- ';
	foreach($wp_registered_sidebars as $key => $value){
		$sidebar[$value['id']] = $value['name'];
	}
	$default_options = get_option('lenxel_options');
	
	$meta_boxes = array();

	/* ====== Metabox Post Thumbnail ====== */
	$meta_boxes[] = array(
		'id' 			=> 'lenxel_metaboxes_post_thumbnail',
		'title' 		=> esc_html__('Thumbnail', 'lenxel-core'),
		'pages' 		=> array( 'post' ),
		'context' 	=> 'normal',
		'fields' 	=> array(
			
			// THUMBNAIL IMAGE
			array(
				'name'  => esc_html__('Thumbnail image', 'lenxel-core'),
				'desc'  => esc_html__('The image that will be used as the thumbnail image.', 'lenxel-core'),
				'id'    => "{$prefix}thumbnail_image",
				'type'  => 'image_advanced',
				'max_file_uploads' => 1
			),

			// THUMBNAIL VIDEO
			array(
				'name' => esc_html__('Thumbnail video URL', 'lenxel-core'),
				'id' => $prefix . 'thumbnail_video_url',
				'desc' => esc_html__('Enter the video url for the thumbnail. Only links from Vimeo & YouTube are supported.', 'lenxel-core'),
				'clone' => false,
				'type'  => 'oembed',
				'std' => '',
			),

			// THUMBNAIL AUDIO
			array(
				'name' 	=> esc_html__('Thumbnail audio URL', 'lenxel-core'),
				'id' 		=> "{$prefix}thumbnail_audio_url",
				'desc' 	=> esc_html__('Enter the audio url for the thumbnail.', 'lenxel-core'),
				'clone' 	=> false,
				'type'  	=> 'oembed',
				'std' 	=> '',
			),

			// THUMBNAIL GALLERY
			array(
				'name'             => esc_html__('Thumbnail gallery', 'lenxel-core'),
				'desc'             => esc_html__('The images that will be used in the thumbnail gallery.', 'lenxel-core'),
				'id'               => "{$prefix}thumbnail_gallery",
				'type'             => 'image_advanced',
				'max_file_uploads' => 50,
			),

			// THUMBNAIL LINK TYPE
			array(
				'name' 		=> esc_html__('Thumbnail link type', 'lenxel-core'),
				'id'   		=> "{$prefix}thumbnail_link_type",
				'type' 		=> 'select',
				'options' 	=> array(
					'link_to_post'    => esc_html__('Link to item', 'lenxel-core'),
					'link_to_url'     => esc_html__('Link to URL', 'lenxel-core'),
					'link_to_url_nw'  => esc_html__('Link to URL (New Window)', 'lenxel-core'),
					'lightbox_thumb'  => esc_html__('Lightbox to the thumbnail image', 'lenxel-core'),
					'lightbox_image'  => esc_html__('Lightbox to image (select below)', 'lenxel-core'),
					'lightbox_video'  => esc_html__('Fullscreen Video Overlay (input below)', 'lenxel-core')
				),
				'multiple' => false,
				'std'  => 'link-to-post',
				'desc' => esc_html__('Choose what link will be used for the image(s) and title of the item.', 'lenxel-core')
			),

			// THUMBNAIL LINK URL
			array(
				'name' 	=> esc_html__('Thumbnail link URL', 'lenxel-core'),
				'id' 		=> $prefix . 'thumbnail_link_url',
				'desc' 	=> esc_html__('Enter the url for the thumbnail link.', 'lenxel-core'),
				'clone' 	=> false,
				'type'  	=> 'text',
				'std' 	=> '',
			),

			// THUMBNAIL LINK LIGHTBOX IMAGE
			array(
				'name'  => esc_html__('Thumbnail link lightbox image', 'lenxel-core'),
				'desc'  => esc_html__('The image that will be used as the lightbox image.', 'lenxel-core'),
				'id'    => "{$prefix}thumbnail_link_image",
				'type'  => 'thickbox_image'
			),
		)
	);

	/* ====== Metabox Page ====== */
	$meta_boxes[] = array(
		'id'    		=> 'lenxel_metaboxes_page',
		'title' 		=> esc_html__('Page Meta', 'lenxel-core'),
		'pages' 		=> array( 'page', 'portfolio', 'product', 'post', 'courses' ),
		'priority'  => 'high',
		'fields' 	=> array(
			array(
				'name' => esc_html__('Full Width( 100% Main Width )', 'lenxel-core'),
				'id'   => "{$prefix}page_full_width",
				'type' => 'switch',
				'desc' => esc_html__('Turn on to have the main area display at 100% width according to the window size. Turn off to follow site width.', 'lenxel-core'),
				'std'  => 0,
			),
			array(
				'name' 		=> esc_html__('Header', 'lenxel-core'),
				'id' 			=> $prefix . 'page_header',
				'desc' 		=> esc_html__("You can change header for page if you like's.", 'lenxel-core'),
				'type'  		=> 'select',
				'options'   => lenxel_get_headers(),
				'std' 		=> '__default_option_theme',
			),
			array(
				'name'      => esc_html__('Footer', 'lenxel-core'),
				'id'        => $prefix . 'page_footer',
				'desc'      => esc_html__("You can change footer for page if you like's",'lenxel-core'),
				'type'      => 'select',
				'options'   =>  lenxel_get_footer(),
				'std'       => '__default_option_theme'
			),
			array(
				'name' 	=> esc_html__('Extra page class', 'lenxel-core'),
				'id' 		=> $prefix . 'extra_page_class',
				'desc' 	=> esc_html__("If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.", 'lenxel-core'),
				'clone' 	=> false,
				'type'  	=> 'text',
				'std' 	=> '',
			),
		)
	);

	/* ====== Metabox Page Title ====== */
	$meta_boxes[] = array(
		'id' 			=> 'lenxel_metaboxes_page_heading',
		'title' 		=> esc_html__('Page Title & Breadcrumb', 'lenxel-core'),
		'pages' 		=> array( 'post', 'page', 'product', 'portfolio', 'lnx_team', 'courses'),
		'context' 	=> 'normal',
		'priority'  => 'high',
		'fields' => array(
		  array(
			 'name' => esc_html__('Remove Title of Page', 'lenxel-core'),   
			 'id'   => "{$prefix}disable_page_title",
			 'type' => 'switch',
			 'std'  => 0,
		  ),
		  array(
			 'name' => esc_html__('Disable Breadcrumbs', 'lenxel-core'),
			 'id'   => "{$prefix}no_breadcrumbs",
			 'type' => 'switch',
			 'desc' => esc_html__('Disable the breadcrumbs from under the page title on this page.', 'lenxel-core'),
			 'std'  => 0,
		  ),
		  array(
			 'name' 		=> esc_html__('Breadcrumb Layout', 'lenxel-core'),
			 'id'   		=> "{$prefix}breadcrumb_layout",
			 'type' 		=> 'select',
			 'options' 	=> array(
				 'theme_options'     => esc_html__('Default Options in Theme-Settings', 'lenxel-core'),
				 'page_options'      => esc_html__('Individuals Options This Page', 'lenxel-core')
			 ),
			 'multiple' => false,
			 'std'  => 'theme_options',
			 'desc' => esc_html__('You can use breadcrumb settings default in Theme-Settings or individuals this page', 'lenxel-core')
		  ),
		  array(
			 'name'    => esc_html__('Show page title in the breadcrumbs', 'lenxel-core'),   
			 'id'      => "{$prefix}page_title",
			 'type'    => 'switch',
			 'std'     => 1,
			 'class'   => 'breadcrumb_setting'
		  ),
		  array(
			 'name' 		=> esc_html__('Page Title Override', 'lenxel-core'),
			 'id' 		=> $prefix . 'page_title_one',
			 'desc' 		=> esc_html__("Enter a custom page title if you'd like.", 'lenxel-core'),
			 'type'  	=> 'text',
			 'std' 		=> '',
			 'class'   	=> 'breadcrumb_setting'
		  ),
		  array(
			 'name'        => esc_html__( 'Breadcrumb Padding Top (px)', 'lenxel-core' ),
			 'id'          => "{$prefix}breadcrumb_padding_top",
			 'type'        => 'number',
			 'prefix'      => 'px',
			 'class'       => 'breadcrumb_setting',
			 'desc'        => esc_html__('Option Padding Top of Breacrumb, set empty = padding default of theme', 'lenxel-core'),
			 'std'         => lenxel_get_option('breadcrumb_padding_top', '135'),
		  ),
		  array(
			 'name'       => esc_html__( 'Breadcrumb Padding Bottom (px)', 'lenxel-core' ),
			 'id'         => "{$prefix}breadcrumb_padding_bottom",
			 'type'       => 'number',
			 'prefix'     => 'px',
			 'class'      => 'breadcrumb_setting',
			 'desc'       => esc_html__('Option Padding Bottom of Breacrumb, set empty = padding default of theme', 'lenxel-core'),
			 'std'        => lenxel_get_option('breadcrumb_padding_bottom', '135'),
		  ),
		  array(
			 'name' 	=> esc_html__( 'Background Overlay Color', 'lenxel-core' ),
			 'id'   	=> "{$prefix}bg_color_title",
			 'desc' 	=> esc_html__( "Set an overlay color for hero heading image.", 'lenxel-core' ),
			 'type' 	=> 'color',
			 'class' => 'breadcrumb_setting',
			 'std'  	=> '',
		  ),
		  array(
			 'name'       => esc_html__( 'Overlay Opacity', 'lenxel-core' ),
			 'id'         => "{$prefix}bg_opacity_title",
			 'desc'       => esc_html__( 'Set the opacity level of the overlay. This will lighten or darken the image depening on the color selected.', 'lenxel-core' ),
			 'clone'      => false,
			 'type'       => 'slider',
			 'prefix'     => '%',
			 'class'   	  => 'breadcrumb_setting',
			 'js_options' => array(
				  'min'  => 0,
				  'max'  => 100,
				  'step' => 1,
			 ),
			 'std'   => '50'
		  ),
		  array(
			 'name'    => esc_html__('Enable Breadcrumb Image', 'lenxel-core'),
			 'id'      => "{$prefix}image_breadcrumbs",
			 'type'    => 'switch',
			 'class'   => 'breadcrumb_setting',
			 'std'     => 1,
		  ),
		  array(
			 'name'  	=> esc_html__('Breadcrumb Background Image', 'lenxel-core'),
			 'id'    	=> "{$prefix}page_title_image",
			 'type'  	=> 'image_advanced',
			 'class'   	=> 'breadcrumb_setting',
			 'max_file_uploads' => 1
		  ),
		  array(
			 'name' 		=> esc_html__('Heading Text Style', 'lenxel-core'),
			 'id'   		=> '{$prefix}page_title_text_style',
			 'type' 		=> 'select',
			 'class'    => 'breadcrumb_setting',
			 'options'  => array(
				 'text-light'     => esc_html__('Light', 'lenxel-core'),
				 'text-dark'      => esc_html__('Dark', 'lenxel-core')
			 ),
			 'multiple' => false,
			 'std'  		=> lenxel_get_option('breadcrumb_text_style', 'text-dark'),
			 'desc' 		=> esc_html__('If you uploaded an image in the option above, choose light/dark styling for the text heading text here.', 'lenxel-core')
		  ),
		  array(
			 'name' 	=> esc_html__('Heading Text Align', 'lenxel-core'),
			 'id'   	=> "{$prefix}page_title_text_align",
			 'type' 	=> 'select',
			 'class'   => 'breadcrumb_setting',
			 'options' => array(
				 'text-left'      => esc_html__('Left', 'lenxel-core'),
				 'text-center'    => esc_html__('Center', 'lenxel-core'),
				 'text-right'     => esc_html__('Right', 'lenxel-core')
			 ),
			 'multiple' => false,
			 'std'  => lenxel_get_option('breadcrumb_text_align', 'text-center'),
			 'desc' => esc_html__('Choose the text alignment for the hero heading.', 'lenxel-core')
		  ),
		)
	);

	/* ====== Metabox Page ====== */
	$meta_boxes[] = array(
		'id'    => 'lenxel_metaboxes_sidebar_page',
		'title' => esc_html__('Sidebar Options', 'lenxel-core'),
		'pages' => array( 'post', 'page', 'portfolio' ),
		'priority'   => 'high',
		'fields' => array(
			// SIDEBAR CONFIG
			array(
				'name' => esc_html__('Sidebar configuration', 'lenxel-core'),
				'id'   => "{$prefix}sidebar_config",
				'type' => 'select',
				'options' => array(
				  ''                   => esc_html__('--Default--', 'lenxel-core'),
				  'no-sidebars'        => esc_html__('No Sidebars', 'lenxel-core'),
				  'left-sidebar'       => esc_html__('Left Sidebar', 'lenxel-core'),
				  'right-sidebar'      => esc_html__('Right Sidebar', 'lenxel-core'),
				),
				'multiple' => false,
				'std'  => '',
				'desc' => esc_html__('Choose the sidebar configuration for the detail page of this page.', 'lenxel-core'),
			),
			// LEFT SIDEBAR
			array (
				'name'   	=> esc_html__('Left Sidebar', 'lenxel-core'),
				 'id'    	=> "{$prefix}left_sidebar",
				 'type' 		=> 'select',
				 'options'  => $sidebar,
				 'std'   	=> ''
			),
			// RIGHT SIDEBAR
			array (
				'name'   	=> esc_html__('Right Sidebar', 'lenxel-core'),
				'id'    		=> "{$prefix}right_sidebar",
				'type' 		=> 'select',
				'options'  	=> $sidebar,
				'std'   	=> ''
			),
		)
	);

	/* ====== Metabox Team ====== */
  	$meta_boxes[] = array(
	 	'id'    		=> 'metaboxes_team_page',
	 	'title' 		=> esc_html__('Team Settings', 'lenxel-core'),
	 	'pages' 		=> array( 'lnx_team' ),
	 	'priority'  => 'high',
	 	'fields' 	=> array(
			array (
			  	'name'   => esc_html__('Position', 'lenxel-core'),
			  	'id'    	=> "{$prefix}team_position",
			  	'type' 	=> 'text',
			  	'std'   	=> ''
			),
			array (
			  	'name'   => esc_html__('Quote', 'lenxel-core'),
			  	'id'    	=> "{$prefix}team_quote",
			  	'type' 	=> 'textarea',
			  	'std'   	=> ''
			),
			array(
			  	'name'   => esc_html__('Email', 'lenxel-core'),
			  	'id'    	=> "{$prefix}team_email",
			  	'type' 	=> 'text',
			  	'std'   	=> ''
			),
			array(
			  	'name'   => esc_html__('Phone', 'lenxel-core'),
			  	'id'    	=> "{$prefix}team_phone",
			  	'type' 	=> 'text',
			  	'std'   	=> ''
			),
			array(
			  	'name'   => esc_html__('Address', 'lenxel-core'),
			  	'id'    	=> "{$prefix}team_address",
			  	'type' 	=> 'text',
			  	'std'   	=> ''
			)
		)
  );

  	/* ====== Metabox Header Builder ====== */
  	$meta_boxes[] = array(
	 	'id'    		=> 'metaboxes_header_builder',
	 	'title' 		=> esc_html__('Header Builder', 'lenxel-core'),
	 	'pages' 		=> array( 'lnx_header' ),
	 	'priority' 	=> 'high',
	 	'fields' 	=> array(
			array(
		  		'name' => esc_html__('Enable Background Black', 'lenxel-core'),
		  		'id'   => "{$prefix}header_bg_black",
		  		'type' => 'switch',
		  		'desc' => esc_html__('It will display background black when builder header.', 'lenxel-core'),
		  		'std'  => 0,
			),
			array(
			  	'name' => esc_html__('Full Width( 100% Main Width )', 'lenxel-core'),
			  	'id'   => "{$prefix}header_full_width",
			  	'type' => 'switch',
			  	'desc' => esc_html__('Turn on to have the main area display at 100% width according to the window size. Turn off to follow site width.', 'lenxel-core'),
			  	'std'  => 0,
	  		),
			array(
			  	'name' => esc_html__('Position Styling', 'lenxel-core'),
			  	'id'   => "{$prefix}header_position",
			  	'type' => 'select',
			  	'options' => array(
				 	'relative'      => esc_html__('Relative', 'lenxel-core'),
				 	'absolute'      => esc_html__('Absolute', 'lenxel-core'),
			  	),
		  		'std' 	  => 'relative',
		  		'multiple' => false,
			)
	 	)
  	);
  	if(current_user_can('administrator')){
	  	$meta_boxes[] = array(
		 	'id'    		=> 'administrator_course_settings',
		 	'title' 		=> esc_html__('Administrator Settings for Course', 'lenxel-core'),
		 	'pages' 		=> array( 'courses' ),
		 	'priority'   => 'high',
		 	'fields' 	=> array(
				array(
				  	'name' => esc_html__('Featured', 'lenxel-core'),
				  	'id'   => "{$prefix}course_featured",
				  	'type'    => 'switch',
			  		'std' 	  => '0',
				),
				array(
					'name' => esc_html__('Trending Course', 'lenxel-core'),
					'id'   => "{$prefix}course_trendy",
					'type'    => 'switch',
					'std' 	  => '0',
			  	)
		 	)
	  	);
	}

	if(current_user_can('administrator')){
		$meta_boxes[] = array(
		   'id'    		=> 'administrator_course_conclusion_settings',
		   'title' 		=> esc_html__('Conclusion', 'lenxel-core'),
		   'pages' 		=> array( 'courses' ),
		   'priority'   => 'high',
		   'fields' 	=> array(
			  array(
					'name' => esc_html__('', 'lenxel-core'),
					'id'   => "{$prefix}course_conclusion",
					'type'    => 'textarea',
			  )
			  ),
		);
  }

	return $meta_boxes;
 }  
  /********************* META BOX REGISTERING ***********************/
  add_filter( 'rwmb_meta_boxes', 'lenxel_register_meta_boxes' , 99 );