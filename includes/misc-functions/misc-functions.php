<?php
/**
 * This file contains the enqueue scripts function for the download_grid plugin
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Features
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Return the array of text pacement options a user can choose from
 *
 * @access   public
 * @since    1.0.0
 * @return   array
 */
function mp_stacks_download_grid_get_text_position_options(){
	
	return array( 
		'below_image_left' => __( 'Below Image, Left', 'mp_stacks_download_grid' ),
		'below_image_right' => __( 'Below Image, Right', 'mp_stacks_download_grid' ),
		'below_image_centered' => __( 'Below Image, Centered', 'mp_stacks_download_grid' ),
		
		'over_image_top_left' => __( 'Over Image, Top-Left', 'mp_stacks_download_grid' ),
		'over_image_top_right' => __( 'Over Image, Top-Right', 'mp_stacks_download_grid' ),
		'over_image_top_centered' => __( 'Over Image, Top-Centered', 'mp_stacks_download_grid' ),
		
		'over_image_middle_left' => __( 'Over Image, Middle-Left', 'mp_stacks_download_grid' ),
		'over_image_middle_right' => __( 'Over Image, Middle-Right', 'mp_stacks_download_grid' ),
		'over_image_middle_centered' => __( 'Over Image, Middle-Centered', 'mp_stacks_download_grid' ),
		
		'over_image_bottom_left' => __( 'Over Image, Bottom-Left', 'mp_stacks_download_grid' ),
		'over_image_bottom_right' => __( 'Over Image, Bottom-Right', 'mp_stacks_download_grid' ),
		'over_image_bottom_centered' => __( 'Over Image, Bottom-Centered', 'mp_stacks_download_grid' ),
	);
	
}