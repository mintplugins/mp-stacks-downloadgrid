<?php
/**
 * This page contains functions for modifying the metabox for download_grid as a media type
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks Download Grid
 * @subpackage Functions
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Add Download Grid as a Media Type to the dropdown
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_download_grid_create_meta_box(){
	
	//Get current page
	$current_page = get_current_screen();
	
	//Only load if we are on an mp_brick page
	if ( $current_page->id != 'mp_brick' ){
		return;	
	}
		
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_download_grid_add_meta_box = array(
		'metabox_id' => 'mp_stacks_download_grid_metabox', 
		'metabox_title' => __( '"Download Grid" Content-Type', 'mp_stacks_download_grid'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_download_grid_items_array = array(
		array(
			'field_id'			=> 'download_grid_taxonomy_term',
			'field_title' 	=> __( 'Select the Category or Tag you want to show', 'mp_stacks_download_grid'),
			'field_description' 	=> '<br />' . __( 'Open up the following areas to add/remove new download_grid.', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'select',
			'field_value' => '',
			'field_select_values' => mp_core_get_all_terms_by_tax('download_category')
		),
		array(
			'field_id'			=> 'download_grid_layout_settings',
			'field_title' 	=> __( 'Grid Layout Settings', 'mp_stacks_download_grid'),
			'field_description' 	=> __( '', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'download_grid_per_row',
			'field_title' 	=> __( 'Downloads Per Row', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'How many posts do you want from left to right before a new row starts? Default 3', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '',
			'field_showhider' => 'download_grid_layout_settings',
		),
		array(
			'field_id'			=> 'download_grid_per_page',
			'field_title' 	=> __( 'Total Downloads', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'How many posts do you want to show entirely? Default: 9', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '',
			'field_showhider' => 'download_grid_layout_settings',
		),
		array(
			'field_id'			=> 'download_grid_post_spacing',
			'field_title' 	=> __( 'Download Spacing', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'How much space would you like to have in between each post in pixels? Default 20', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '',
			'field_showhider' => 'download_grid_layout_settings',
		),
		array(
			'field_id'			=> 'download_grid_featured_images_settings',
			'field_title' 	=> __( 'Featured Images Settings', 'mp_stacks_download_grid'),
			'field_description' 	=> __( '', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'download_grid_show_featured_images',
			'field_title' 	=> __( 'Show Featured Images?', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Do you want to show the featured images for these posts?', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'checkbox',
			'field_value' => 'download_grid_show_featured_images',
			'field_showhider' => 'download_grid_featured_images_settings',
		),
		array(
			'field_id'			=> 'download_grid_featured_images_width',
			'field_title' 	=> __( 'Featured Image Width', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'How wide should the images be in pixels? Default 300', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '',
			'field_showhider' => 'download_grid_featured_images_settings',
		),
		array(
			'field_id'			=> 'download_grid_featured_images_height',
			'field_title' 	=> __( 'Featured Image Height', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'How high should the images be in pixels? Default 200', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '',
			'field_showhider' => 'download_grid_featured_images_settings',
		),
		//Image animation stuff
		array(
			'field_id'			=> 'download_grid_image_animation_repeater_title',
			'field_title' 	=> __( 'KeyFrame', 'mp_stacks_download_grid'),
			'field_description' 	=> NULL,
			'field_type' 	=> 'repeatertitle',
			'field_repeater' => 'download_grid_image_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_settings',
		),
		array(
			'field_id'			=> 'animation_length',
			'field_title' 	=> __( 'Animation Length', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the length between this keyframe and the previous one in milliseconds. Default: 500', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '500',
			'field_repeater' => 'download_grid_image_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_settings',
			'field_container_class' => 'mp_animation_length',
		),
		array(
			'field_id'			=> 'opacity',
			'field_title' 	=> __( 'Opacity', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the opacity percentage at this keyframe. Default: 100', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'input_range',
			'field_value' => '100',
			'field_repeater' => 'download_grid_image_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_settings',
		),
		array(
			'field_id'			=> 'rotateZ',
			'field_title' 	=> __( 'Rotation', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the rotation degree angle at this keyframe. Default: 0', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'download_grid_image_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_settings',
		),
		array(
			'field_id'			=> 'translateX',
			'field_title' 	=> __( 'X Position', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the X position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'download_grid_image_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_settings',
		),
		array(
			'field_id'			=> 'translateY',
			'field_title' 	=> __( 'Y Position', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the Y position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'download_grid_image_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_settings',
		),
		
		//Image Overlay
		array(
			'field_id'			=> 'download_grid_featured_images_overlay_settings',
			'field_title' 	=> __( 'Featured Images Overlay Settings', 'mp_stacks_download_grid'),
			'field_description' 	=> __( '', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		
		//Image Overlay animation stuff
		array(
			'field_id'			=> 'download_grid_image_animation_repeater_title',
			'field_title' 	=> __( 'KeyFrame', 'mp_stacks_download_grid'),
			'field_description' 	=> NULL,
			'field_type' 	=> 'repeatertitle',
			'field_repeater' => 'download_grid_image_overlay_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_overlay_settings',
		),
		array(
			'field_id'			=> 'animation_length',
			'field_title' 	=> __( 'Animation Length', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the length between this keyframe and the previous one in milliseconds. Default: 500', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '500',
			'field_repeater' => 'download_grid_image_overlay_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_overlay_settings',
			'field_container_class' => 'mp_animation_length',
		),
		array(
			'field_id'			=> 'opacity',
			'field_title' 	=> __( 'Opacity', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the opacity percentage at this keyframe. Default: 100', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'input_range',
			'field_value' => '0',
			'field_repeater' => 'download_grid_image_overlay_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_overlay_settings',
		),
		array(
			'field_id'			=> 'backgroundColor',
			'field_title' 	=> __( 'Color', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the Color of the Image Overlay at this keyframe. Default: #FFF (white)', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#FFF',
			'field_repeater' => 'download_grid_image_overlay_animation_keyframes',
			'field_showhider' => 'download_grid_featured_images_overlay_settings',
		),
		
		//Title Settings
		array(
			'field_id'			=> 'download_grid_title_settings',
			'field_title' 	=> __( 'Title Settings', 'mp_stacks_download_grid'),
			'field_description' 	=> __( '', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'download_grid_show_titles',
			'field_title' 	=> __( 'Show Titles?', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Do you want to show the Titles for these posts?', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'checkbox',
			'field_value' => 'true',
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'download_grid_titles_placement',
			'field_title' 	=> __( 'Titles\' Placement', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Where would you like to place the title? Default: Below Image, Left', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'select',
			'field_value' => '',
			'field_select_values' => mp_stacks_download_grid_get_text_position_options(),
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'download_grid_title_color',
			'field_title' 	=> __( 'Titles\' Color', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Select the color the titles will be (leave blank for theme default)', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '',
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'download_grid_title_size',
			'field_title' 	=> __( 'Titles\' Size', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Enter the text size the titles will be. Default: 20', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '',
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'download_grid_title_animation_on',
			'field_title' 	=> __( 'Animate Trigger', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'When would you like the animation to begin?', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'select',
			'field_value' => '',
			'field_select_values' => array(
				'none' => 'None',
				'load' => 'Page-Load',
				'comes_into_view' => 'In-View',
				'mouseover' => 'Mouse-Over',	
			),
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'download_grid_title_animation_repeater_title',
			'field_title' 	=> __( 'KeyFrame', 'mp_stacks_download_grid'),
			'field_description' 	=> NULL,
			'field_type' 	=> 'repeatertitle',
			'field_repeater' => 'download_grid_title_animation_keyframes',
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'animation_length',
			'field_title' 	=> __( 'Animation Length', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the length between this keyframe and the previous one in milliseconds. Default: 500', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '500',
			'field_repeater' => 'download_grid_title_animation_keyframes',
			'field_showhider' => 'download_grid_title_settings',
			'field_container_class' => 'mp_animation_length',
		),
		array(
			'field_id'			=> 'opacity',
			'field_title' 	=> __( 'Opacity', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the opacity percentage at this keyframe. Default: 100', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'input_range',
			'field_value' => '100',
			'field_repeater' => 'download_grid_title_animation_keyframes',
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'rotateZ',
			'field_title' 	=> __( 'Rotation', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the rotation degree angle at this keyframe. Default: 0', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'download_grid_title_animation_keyframes',
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'translateX',
			'field_title' 	=> __( 'X Position', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the X position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'download_grid_title_animation_keyframes',
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'translateY',
			'field_title' 	=> __( 'Y Position', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the Y position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'download_grid_title_animation_keyframes',
			'field_showhider' => 'download_grid_title_settings',
		),
		array(
			'field_id'			=> 'download_grid_excerpt_settings',
			'field_title' 	=> __( 'Excerpt Settings', 'mp_stacks_download_grid'),
			'field_description' 	=> __( '', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'download_grid_show_excerpts',
			'field_title' 	=> __( 'Show Excerpts?', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Do you want to show the Excerpts for these posts?', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'checkbox',
			'field_value' => 'true',
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		array(
			'field_id'			=> 'download_grid_excerpt_placement',
			'field_title' 	=> __( 'Titles\' Placement', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Where would you like to place the excerpt? Default: Below Image, Left', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'select',
			'field_value' => '',
			'field_select_values' => mp_stacks_download_grid_get_text_position_options(),
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		array(
			'field_id'			=> 'download_grid_excerpt_color',
			'field_title' 	=> __( 'Excerpt\' Color', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Select the color the excerpts will be (leave blank for theme default)', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '',
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		array(
			'field_id'			=> 'download_grid_excerpt_size',
			'field_title' 	=> __( 'Excerpt\' Size', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Enter the text size the excerpts will be. Default: 15', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '',
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		array(
			'field_id'			=> 'download_grid_excerpt_word_limit',
			'field_title' 	=> __( 'Word Limit for Excerpt\'s', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'How many words should be displayed before the "Read More" link is shown. Default: All words are shown.', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '',
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		//Excerpt animation stuff
		array(
			'field_id'			=> 'download_grid_excerpt_animation_repeater_title',
			'field_title' 	=> __( 'KeyFrame', 'mp_stacks_download_grid'),
			'field_description' 	=> NULL,
			'field_type' 	=> 'repeatertitle',
			'field_repeater' => 'download_grid_excerpt_animation_keyframes',
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		array(
			'field_id'			=> 'animation_length',
			'field_title' 	=> __( 'Animation Length', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the length between this keyframe and the previous one in milliseconds. Default: 500', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '500',
			'field_repeater' => 'download_grid_excerpt_animation_keyframes',
			'field_showhider' => 'download_grid_excerpt_settings',
			'field_container_class' => 'mp_animation_length',
		),
		array(
			'field_id'			=> 'opacity',
			'field_title' 	=> __( 'Opacity', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the opacity percentage at this keyframe. Default: 100', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'input_range',
			'field_value' => '100',
			'field_repeater' => 'download_grid_excerpt_animation_keyframes',
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		array(
			'field_id'			=> 'rotateZ',
			'field_title' 	=> __( 'Rotation', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the rotation degree angle at this keyframe. Default: 0', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'download_grid_excerpt_animation_keyframes',
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		array(
			'field_id'			=> 'translateX',
			'field_title' 	=> __( 'X Position', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the X position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'download_grid_excerpt_animation_keyframes',
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		array(
			'field_id'			=> 'translateY',
			'field_title' 	=> __( 'Y Position', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Set the Y position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'download_grid_excerpt_animation_keyframes',
			'field_showhider' => 'download_grid_excerpt_settings',
		),
		
		
		array(
			'field_id'			=> 'download_grid_ajax_load_more_settings',
			'field_title' 	=> __( 'Ajax "Load More" Settings', 'mp_stacks_download_grid'),
			'field_description' 	=> __( '', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'download_grid_show_load_more_button',
			'field_title' 	=> __( 'Show "Load More" Button?', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'Should we show a "Load More" button?', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'checkbox',
			'field_value' => 'download_grid_show_load_more_button',
			'field_showhider' => 'download_grid_ajax_load_more_settings',
		),
		array(
			'field_id'			=> 'download_grid_load_more_button_color',
			'field_title' 	=> __( '"Load More" Button Color', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'What color should the "Load More" button be? (Leave blank for theme default)', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '',
			'field_showhider' => 'download_grid_ajax_load_more_settings',
		),
		array(
			'field_id'			=> 'download_grid_load_more_button_text_color',
			'field_title' 	=> __( '"Load More" Button Text Color', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'What color should the "Load More" button\'s text be? (Leave blank for theme defaults)', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '',
			'field_showhider' => 'download_grid_ajax_load_more_settings',
		),
		array(
			'field_id'			=> 'download_grid_mouse_over_load_more_button_color',
			'field_title' 	=> __( '"Load More" Button Text Color', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'What color should the "Load More" button be when the Mouse is over it? (Leave blank for theme defaults)', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '',
			'field_showhider' => 'download_grid_ajax_load_more_settings',
		),
		array(
			'field_id'			=> 'download_grid_mouse_over_load_more_button_text_color',
			'field_title' 	=> __( '"Load More" Button Text Color', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'What color should the "Load More" button\'s text be when the Mouse is over it? (Leave blank for theme defaults)', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '',
			'field_showhider' => 'download_grid_ajax_load_more_settings',
		),
		array(
			'field_id'			=> 'download_grid_load_more_text',
			'field_title' 	=> __( '"Load More" Text', 'mp_stacks_download_grid'),
			'field_description' 	=> __( 'What should the "Load More" button say? Default: "Load More"', 'mp_stacks_download_grid' ),
			'field_type' 	=> 'textbox',
			'field_value' => '',
			'field_showhider' => 'download_grid_ajax_load_more_settings',
		)
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_download_grid_add_meta_box = has_filter('mp_stacks_download_grid_meta_box_array') ? apply_filters( 'mp_stacks_download_grid_meta_box_array', $mp_stacks_download_grid_add_meta_box) : $mp_stacks_download_grid_add_meta_box;
	
	//Globalize the and populate mp_stacks_features_items_array (do this before filter hooks are run)
	global $global_mp_stacks_download_grid_items_array;
	$global_mp_stacks_download_grid_items_array = $mp_stacks_download_grid_items_array;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_download_grid_items_array = has_filter('mp_stacks_download_grid_items_array') ? apply_filters( 'mp_stacks_download_grid_items_array', $mp_stacks_download_grid_items_array) : $mp_stacks_download_grid_items_array;
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_download_grid_meta_box;
	$mp_stacks_download_grid_meta_box = new MP_CORE_Metabox($mp_stacks_download_grid_add_meta_box, $mp_stacks_download_grid_items_array);
}
add_action('current_screen', 'mp_stacks_download_grid_create_meta_box');