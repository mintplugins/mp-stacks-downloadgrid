<?php
/**
 * This page contains functions for modifying the metabox for downloadgrid as a media type
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks DownloadGrid
 * @subpackage Functions
 *
 * @copyright   Copyright (c) 2015, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Add DownloadGrid as a Media Type to the dropdown
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_downloadgrid_create_meta_box(){
		
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_downloadgrid_add_meta_box = array(
		'metabox_id' => 'mp_stacks_downloadgrid_metabox', 
		'metabox_title' => __( '"DownloadGrid" Content-Type', 'mp_stacks_downloadgrid'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$download_categories = mp_core_get_all_terms_by_tax('download_category'); 
	$download_categories['related_downloads'] = __('Show Related Downloads based on Tag (only use this if the stack is sitting on a "Download" page).');
	 
	$mp_stacks_downloadgrid_items_array = array(
	
		//Use this to add new options at this point with the filter hook
		'downloadgrid_meta_hook_anchor_0' => array( 'field_type' => 'hook_anchor'),
		
		'downloadgrid_taxonomy_showhider' => array(
			'field_id'			=> 'downloadgrid_taxonomy_showhider',
			'field_title' 	=> __( 'Download Categories', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( '', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
			'downloadgrid_taxonomy_terms' => array(
				'field_id'			=> 'taxonomy_term',
				'field_title' 	=> __( 'Select a Category or Tag you want to show', 'mp_stacks_downloadgrid'),
				'field_description' 	=> __( 'What posts should be shown in the Download Grid?', 'mp_stacks_downloadgrid' ),
				'field_type' 	=> 'select',
				'field_value' => '',
				'field_select_values' => $download_categories,
				'field_repeater' => 'downloadgrid_taxonomy_terms',
				'field_showhider' => 'downloadgrid_taxonomy_showhider'
			),
			'downloadgrid_taxonomy_bg_color' => array(
				'field_id'			=> 'taxonomy_bg_color',
				'field_title' 	=> __( 'Background Color for these posts (Optional).', 'mp_stacks_downloadgrid'),
				'field_description' 	=> __( 'If you want these posts to have a background color, select it here. If not, leave this clear.', 'mp_stacks_downloadgrid' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_repeater' => 'downloadgrid_taxonomy_terms',
				'field_showhider' => 'downloadgrid_taxonomy_showhider'
			),
			
		
		'downloadgrid_layout_showhider' => array(
			'field_id'			=> 'downloadgrid_layout_settings',
			'field_title' 	=> __( 'Grid Layout Settings', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( '', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		'downloadgrid_posts_per_row' => array(
			'field_id'			=> 'downloadgrid_per_row',
			'field_title' 	=> __( 'Downloads Per Row', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'How many posts do you want from left to right before a new row starts? Default 3', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '3',
			'field_showhider' => 'downloadgrid_layout_settings',
		),
		'downloadgrid_posts_per_page' => array(
			'field_id'			=> 'downloadgrid_per_page',
			'field_title' 	=> __( 'Total Downloads', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'How many posts do you want to show entirely? Default: 9', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '9',
			'field_showhider' => 'downloadgrid_layout_settings',
		),
		'downloadgrid_post_spacing' => array(
			'field_id'			=> 'downloadgrid_post_spacing',
			'field_title' 	=> __( 'Download Spacing', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'How much space would you like to have in between each post in pixels? Default 20', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '20',
			'field_showhider' => 'downloadgrid_layout_settings',
		),
		'downloadgrid_post_inner_margin' => array(
			'field_id'			=> 'downloadgrid_post_inner_margin',
			'field_title' 	=> __( 'Download Inner Margin', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'How much space would you like to have between the outer edge of each download and the download\'s inner content? Default 0', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_showhider' => 'downloadgrid_layout_settings',
		),
		'downloadgrid_post_below_image_area_inner_margin' => array(
			'field_id'			=> 'downloadgrid_post_below_image_area_inner_margin',
			'field_title' 	=> __( 'Inner Margin for the area below in image.', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'In the area "below" the image, how much space would you like to have between the outer edge and the text content? Default 0', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_showhider' => 'downloadgrid_layout_settings',
		),
		//Bg animation stuff
		'downloadgrid_bg_settings' => array(
			'field_id'			=> 'downloadgrid_bg_settings',
			'field_title' 	=> __( 'Animate the Background', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Control the animations of the background when the user\'s mouse is over it by adding keyframes here:', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
			'field_showhider' => 'downloadgrid_layout_settings',
		),
			//Background color animation
			'downloadgrid_bg_animation_repeater_title' => array(
				'field_id'			=> 'downloadgrid_bg_animation_repeater_title',
				'field_title' 	=> __( 'KeyFrame', 'mp_stacks_downloadgrid'),
				'field_description' 	=> NULL,
				'field_type' 	=> 'repeatertitle',
				'field_repeater' => 'downloadgrid_bg_animation_keyframes',
				'field_showhider' => 'downloadgrid_bg_settings',
			),
			'downloadgrid_bg_animation_length' => array(
				'field_id'			=> 'animation_length',
				'field_title' 	=> __( 'Animation Length', 'mp_stacks_downloadgrid'),
				'field_description' 	=> __( 'Set the length between this keyframe and the previous one in milliseconds. Default: 500', 'mp_stacks_downloadgrid' ),
				'field_type' 	=> 'number',
				'field_value' => '500',
				'field_repeater' => 'downloadgrid_bg_animation_keyframes',
				'field_showhider' => 'downloadgrid_bg_settings',
				'field_container_class' => 'mp_animation_length',
			),
			'downloadgrid_bg_animation_color' => array(
				'field_id'			=> 'backgroundColor',
				'field_title' 	=> __( 'Color', 'mp_stacks_downloadgrid'),
				'field_description' 	=> __( 'Set the color for the background at this keyframe. Default: 100', 'mp_stacks_downloadgrid' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_repeater' => 'downloadgrid_bg_animation_keyframes',
				'field_showhider' => 'downloadgrid_bg_settings',
			),
			'downloadgrid_bg_animation_backgroundColorAlpha' => array(
				'field_id'			=> 'backgroundColorAlpha',
				'field_title' 	=> __( 'Background Opacity (Requires Background Color)', 'mp_stacks_downloadgrid'),
				'field_description' 	=> __( 'Set the opacity percentage for the background color at this keyframe. Default: 100', 'mp_stacks_downloadgrid' ),
				'field_type' 	=> 'input_range',
				'field_value' => '100',
				'field_repeater' => 'downloadgrid_bg_animation_keyframes',
				'field_showhider' => 'downloadgrid_bg_settings',
			),
			'downloadgrid_bg_animation_rotation' => array(
				'field_id'			=> 'rotateZ',
				'field_title' 	=> __( 'Rotation', 'mp_stacks_downloadgrid'),
				'field_description' 	=> __( 'Set the rotation degree angle at this keyframe. Default: 0', 'mp_stacks_downloadgrid' ),
				'field_type' 	=> 'number',
				'field_value' => '0',
				'field_repeater' => 'downloadgrid_bg_animation_keyframes',
				'field_showhider' => 'downloadgrid_bg_settings',
			),
			'downloadgrid_bg_animation_x' => array(
				'field_id'			=> 'translateX',
				'field_title' 	=> __( 'X Position', 'mp_stacks_downloadgrid'),
				'field_description' 	=> __( 'Set the X position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_downloadgrid' ),
				'field_type' 	=> 'number',
				'field_value' => '0',
				'field_repeater' => 'downloadgrid_bg_animation_keyframes',
				'field_showhider' => 'downloadgrid_bg_settings',
			),
			'downloadgrid_bg_animation_y' => array(
				'field_id'			=> 'translateY',
				'field_title' 	=> __( 'Y Position', 'mp_stacks_downloadgrid'),
				'field_description' 	=> __( 'Set the Y position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_downloadgrid' ),
				'field_type' 	=> 'number',
				'field_value' => '0',
				'field_repeater' => 'downloadgrid_bg_animation_keyframes',
				'field_showhider' => 'downloadgrid_bg_settings',
			),
			'downloadgrid_bg_animation_scale' => array(
				'field_id'			=> 'scale',
				'field_title' 	=> __( 'Scale', 'mp_stacks_downloadgrid'),
				'field_description' 	=> __( 'Set the Scale % of this Download, in relation to its starting position, at this keyframe. The unit is pixels. Default: 100', 'mp_stacks_downloadgrid' ),
				'field_type' 	=> 'number',
				'field_value' => '100',
				'field_repeater' => 'downloadgrid_bg_animation_keyframes',
				'field_showhider' => 'downloadgrid_bg_settings',
			),
		
		'downloadgrid_masonry' => array(
			'field_id'			=> 'downloadgrid_masonry',
			'field_title' 	=> __( 'Use Masonry?', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Would you like to use Masonry for the layout? Masonry is similar to how Pinterest posts are laid out.', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'checkbox',
			'field_value' => 'downloadgrid_masonry',
			'field_showhider' => 'downloadgrid_layout_settings',
		),
		
		//Use this to add new options at this point with the filter hook
		'downloadgrid_meta_hook_anchor_1' => array( 'field_type' => 'hook_anchor'),
		
		'downloadgrid_feat_img_showhider' => array(
			'field_id'			=> 'downloadgrid_featured_images_settings',
			'field_title' 	=> __( 'Featured Images Settings', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( '', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		'downloadgrid_feat_img_show' => array(
			'field_id'			=> 'downloadgrid_featured_images_show',
			'field_title' 	=> __( 'Show Featured Images?', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Do you want to show the featured images for these posts?', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'checkbox',
			'field_value' => 'downloadgrid_show_featured_images',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_note' => array(
			'field_id'			=> 'downloadgrid_feat_img_note',
			'field_title' 	=> __( 'Featured Image Size Note:', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'The following settings won\'t control the size of how the image displays. To change the actual display size of the images, change the "Downloads Per Row" option or change the "Maximum Content Width" under "Brick Size Settings".', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'basictext',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_width' => array(
			'field_id'			=> 'downloadgrid_featured_images_width',
			'field_title' 	=> __( 'Featured Image Crop Width', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'How wide should the images crop-to in pixels? Note: If your images show pixelated, increase this value.', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '500',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_height' => array(
			'field_id'			=> 'downloadgrid_featured_images_height',
			'field_title' 	=> __( 'Featured Image Crop Height', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'How high should the images crop-to in pixels? Set this to 0 for no cropping. Note: If your images show pixelated, increase this value.', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_max_width' => array(
			'field_id'			=> 'downloadgrid_feat_img_max_width',
			'field_title' 	=> __( 'Featured Image Max Width', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'In most scenarios you\'ll want to leave this blank. But if you want the images to display smaller than the width of each grid item, enter the max-width in pixels here.',  'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_inner_margin' => array(
			'field_id'			=> 'downloadgrid_featured_images_inner_margin',
			'field_title' 	=> __( 'Featured Image Inner Margin', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'How many pixels should the inner margin be for things placed over the featured image? Default 20', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '20',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		//Image animation stuff
		'downloadgrid_feat_img_animation_desc' => array(
			'field_id'			=> 'downloadgrid_featured_images_animation_description',
			'field_title' 	=> __( 'Animate the Featured Images', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Control the animations of the featured images when the user\'s mouse is over the featured images by adding keyframes here:', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'basictext',
			'field_value' => '',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_animation_repeater_title' => array(
			'field_id'			=> 'downloadgrid_image_animation_repeater_title',
			'field_title' 	=> __( 'KeyFrame', 'mp_stacks_downloadgrid'),
			'field_description' 	=> NULL,
			'field_type' 	=> 'repeatertitle',
			'field_repeater' => 'downloadgrid_image_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_animation_length' => array(
			'field_id'			=> 'animation_length',
			'field_title' 	=> __( 'Animation Length', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Set the length between this keyframe and the previous one in milliseconds. Default: 500', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '500',
			'field_repeater' => 'downloadgrid_image_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_settings',
			'field_container_class' => 'mp_animation_length',
		),
		'downloadgrid_feat_img_animation_opacity' => array(
			'field_id'			=> 'opacity',
			'field_title' 	=> __( 'Opacity', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Set the opacity percentage at this keyframe. Default: 100', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'input_range',
			'field_value' => '100',
			'field_repeater' => 'downloadgrid_image_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_animation_rotation' => array(
			'field_id'			=> 'rotateZ',
			'field_title' 	=> __( 'Rotation', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Set the rotation degree angle at this keyframe. Default: 0', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'downloadgrid_image_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_animation_x' => array(
			'field_id'			=> 'translateX',
			'field_title' 	=> __( 'X Position', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Set the X position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'downloadgrid_image_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_animation_y' => array(
			'field_id'			=> 'translateY',
			'field_title' 	=> __( 'Y Position', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Set the Y position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'downloadgrid_image_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		'downloadgrid_feat_img_animation_scale' => array(
			'field_id'			=> 'scale',
			'field_title' 	=> __( 'Scale', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Set the Scale % of this Image, in relation to its starting position, at this keyframe. The unit is pixels. Default: 100', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '100',
			'field_repeater' => 'downloadgrid_image_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_settings',
		),
		
		//Image Overlay
		'downloadgrid_feat_img_overlay_showhider' => array(
			'field_id'			=> 'downloadgrid_featured_images_overlay_settings',
			'field_title' 	=> __( 'Featured Images Overlay Settings', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( '', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		'downloadgrid_feat_img_overlay_desc' => array(
			'field_id'			=> 'dl_grid_feat_overlay_img_desc',
			'field_title' 	=> __( 'What is the Featured Images Overlay?', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'It\'s a animate-able solid color which can sit on top of the image when the user\'s mouse hovers over the image. The keyframes to animate the overlay are managed here:', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'basictext',
			'field_value' => '',
			'field_showhider' => 'downloadgrid_featured_images_overlay_settings',
		),
		
		//Image Overlay animation stuff
		'downloadgrid_feat_img_overlay_animation_repeater_title' => array(
			'field_id'			=> 'downloadgrid_image_animation_repeater_title',
			'field_title' 	=> __( 'KeyFrame', 'mp_stacks_downloadgrid'),
			'field_description' 	=> NULL,
			'field_type' 	=> 'repeatertitle',
			'field_repeater' => 'downloadgrid_image_overlay_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_overlay_settings',
		),
		'downloadgrid_feat_img_overlay_animation_length' => array(
			'field_id'			=> 'animation_length',
			'field_title' 	=> __( 'Animation Length', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Set the length between this keyframe and the previous one in milliseconds. Default: 500', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '500',
			'field_repeater' => 'downloadgrid_image_overlay_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_overlay_settings',
			'field_container_class' => 'mp_animation_length',
		),
		'downloadgrid_feat_img_overlay_animation_opacity' => array(
			'field_id'			=> 'opacity',
			'field_title' 	=> __( 'Opacity', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Set the opacity percentage at this keyframe. Default: 100', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'input_range',
			'field_value' => '0',
			'field_repeater' => 'downloadgrid_image_overlay_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_overlay_settings',
		),
		'downloadgrid_feat_img_overlay_animation_background_color' => array(
			'field_id'			=> 'backgroundColor',
			'field_title' 	=> __( 'Color', 'mp_stacks_downloadgrid'),
			'field_description' 	=> __( 'Set the Color of the Image Overlay at this keyframe. Default: #FFF (white)', 'mp_stacks_downloadgrid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#FFF',
			'field_repeater' => 'downloadgrid_image_overlay_animation_keyframes',
			'field_showhider' => 'downloadgrid_featured_images_overlay_settings',
		),
		
		//Use this to add new options at this point with the filter hook
		'downloadgrid_meta_hook_anchor_2' => array( 'field_type' => 'hook_anchor'),
		
		//Use this to add new options at this point with the filter hook
		'downloadgrid_meta_hook_anchor_3' => array( 'field_type' => 'hook_anchor'),
		
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_downloadgrid_add_meta_box = has_filter('mp_stacks_downloadgrid_meta_box_array') ? apply_filters( 'mp_stacks_downloadgrid_meta_box_array', $mp_stacks_downloadgrid_add_meta_box) : $mp_stacks_downloadgrid_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_downloadgrid_items_array = has_filter('mp_stacks_downloadgrid_items_array') ? apply_filters( 'mp_stacks_downloadgrid_items_array', $mp_stacks_downloadgrid_items_array) : $mp_stacks_downloadgrid_items_array;
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_downloadgrid_meta_box;
	$mp_stacks_downloadgrid_meta_box = new MP_CORE_Metabox($mp_stacks_downloadgrid_add_meta_box, $mp_stacks_downloadgrid_items_array);
}
add_action('mp_brick_metabox', 'mp_stacks_downloadgrid_create_meta_box');