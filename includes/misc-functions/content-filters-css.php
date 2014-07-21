<?php 
/**
 * This file contains the function which hooks to a brick's content output
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Download Grid
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Get the CSS for a text div based on the placement string the user has chosen
 *
 * @access   public
 * @since    1.0.0
 * @param    $placement_string String - A string chosen by the user to specify the position of the title
 * @param    $args Array - An associative array with additional options like image width and height, etc
 * @return   $css_output String - A string containing the CSS for the titles in this grid
 */
function mp_stacks_get_text_placement_css( $placement_string, $args ){
	
	$css_output = NULL;
	
	$title_line_height = $args['download_grid_line_height'] / 2 . 'px';
	
	if( $placement_string == 'below_image_left' ){
		
		$css_output = 'text-align:left; padding-top:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'below_image_right' ){
		$css_output = 'text-align:right; padding-top:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'below_image_centered' ){
		$css_output = 'text-align:center; padding-top:' . $title_line_height . ';;';
	}
	else if(  $placement_string == 'over_image_top_left' ){
		$css_output = 'text-align:left; padding:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'over_image_top_right' ){
		$css_output = 'text-align:right; padding:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'over_image_top_centered' ){
		$css_output = 'text-align:center; padding:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'over_image_middle_left' ){
		$css_output = 'text-align:left; padding:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'over_image_middle_right' ){
		$css_output = 'text-align:right; padding:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'over_image_middle_centered' ){
		$css_output = 'text-align:center; padding:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'over_image_bottom_left' ){
		$css_output = 'text-align:left; padding:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'over_image_bottom_right' ){
		$css_output = 'text-align:right; padding:' . $title_line_height . ';';
	}
	else if(  $placement_string == 'over_image_bottom_centered' ){
		$css_output = 'text-align:center; padding:' . $title_line_height . ';';
	}
	
	return $css_output;
		
}

/**
 * Process the CSS needed for the grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $css_output          String - The incoming CSS output coming from other things using this filter
 * @param    $post_id             Int - The post ID of the brick
 * @param    $first_content_type  String - The first content type chosen for this brick
 * @param    $second_content_type String - The second content type chosen for this brick
 * @return   $html_output         String - A string holding the css the brick
 */
function mp_stacks_brick_content_output_css_download_grid( $css_output, $post_id, $first_content_type, $second_content_type ){
	
	if ( $first_content_type != 'download_grid' && $second_content_type != 'download_grid' ){
		return $css_output;	
	}
	
	//Download per row
	$download_grid_per_row = mp_core_get_post_meta($post_id, 'download_grid_per_row', '3');
	
	//Post Spacing (padding)
	$download_grid_post_spacing = mp_core_get_post_meta($post_id, 'download_grid_post_spacing', '20');
	
	//Download Image width and height
	$download_grid_featured_images_width = mp_core_get_post_meta($post_id, 'download_grid_featured_images_width', '300px', array( 'after' => 'px' ) );
	$download_grid_featured_images_height = mp_core_get_post_meta($post_id, 'download_grid_featured_images_height', '200px', array( 'after' => 'px' ));
	
	//Image Overlay Color and Opacity
	$download_grid_images_overlay_color = mp_core_get_post_meta($post_id, 'download_grid_images_overlay_color', '#FFF' );
	$download_grid_images_overlay_opacity = mp_core_get_post_meta($post_id, 'download_grid_images_overlay_opacity', '0' );
	
	//Titles placement
	$download_grid_titles_placement = mp_core_get_post_meta($post_id, 'download_grid_titles_placement', 'below_image_left');
	
	//Title Color and size
	$download_grid_title_color = mp_core_get_post_meta($post_id, 'download_grid_title_color', 'inherit');
	$download_grid_title_size = mp_core_get_post_meta($post_id, 'download_grid_title_size', '20');
	
	//Excerpts Placement
	$download_grid_excerpt_placement = mp_core_get_post_meta($post_id, 'download_grid_excerpt_placement', 'below_image_left');
	
	//Excerpt Color and Size
	$download_grid_excerpt_color = mp_core_get_post_meta($post_id, 'download_grid_excerpt_color', 'inherit');
	$download_grid_excerpt_size = mp_core_get_post_meta($post_id, 'download_grid_excerpt_size', '15');
	
	//Load More Buttons Colors
	$download_grid_load_more_button_color = mp_core_get_post_meta($post_id, 'download_grid_load_more_button_color', 'inherit');
	$download_grid_load_more_button_text_color = mp_core_get_post_meta($post_id, 'download_grid_load_more_button_text_color', 'inherit');
	$download_grid_mouse_over_load_more_button_color = mp_core_get_post_meta($post_id, 'download_grid_mouse_over_load_more_button_color', 'inherit');
	$download_grid_mouse_over_load_more_button_text_color = mp_core_get_post_meta($post_id, 'download_grid_mouse_over_load_more_button_text_color', 'inherit');
	
	//Get CSS Output
	$css_output .= '
		#mp-brick-' . $post_id . ' .mp-stacks-download-grid-item{ 
			color:' . $download_grid_excerpt_color . ';
			width:' . (100/$download_grid_per_row) .'%;
			padding: ' . $download_grid_post_spacing . 'px;
		}
		#mp-brick-' . $post_id . ' .mp-stacks-download-grid-item-title-holder{
			' . mp_stacks_get_text_placement_css( $download_grid_titles_placement, array( 
					'download_grid_line_height' => $download_grid_title_size,
				) ) . ';
	
			color:' . $download_grid_title_color . ';
			font-size:' . $download_grid_title_size . 'px;
			line-height:' . $download_grid_title_size . 'px;
		}
		#mp-brick-' . $post_id . ' .mp-stacks-download-grid-item-excerpt-holder, 
		#mp-brick-' . $post_id . ' .mp-stacks-download-grid-item-excerpt-holder a{
			' . mp_stacks_get_text_placement_css( $download_grid_excerpt_placement, array( 
					'download_grid_line_height' => $download_grid_title_size,
				) ) . ';
			
			color:' . $download_grid_excerpt_color . ';
			font-size:' . $download_grid_excerpt_size . 'px;
			line-height:' . $download_grid_excerpt_size . 'px;
		}
		#mp-brick-' . $post_id . ' .mp-stacks-download-grid-load-more-button{
			color:' . $download_grid_load_more_button_text_color  . ';
			background-color:' . $download_grid_load_more_button_color  . ';
		}
		#mp-brick-' . $post_id . ' .mp-stacks-download-grid-load-more-button:hover{
			color:' . $download_grid_mouse_over_load_more_button_text_color  . ';
			background-color:' . $download_grid_mouse_over_load_more_button_color  . ';
		}';
		
		return $css_output;
	
}
add_filter('mp_brick_additional_css', 'mp_stacks_brick_content_output_css_download_grid', 10, 4);