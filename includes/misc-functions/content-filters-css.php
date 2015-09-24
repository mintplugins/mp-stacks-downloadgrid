<?php 
/**
 * This file contains the function which hooks to a brick's content output
 *
 * @since 1.0.0
 *
 * @package    MP Stacks DownloadGrid
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

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
function mp_stacks_brick_content_output_css_downloadgrid( $css_output, $post_id, $first_content_type, $second_content_type ){
	
	if ( $first_content_type != 'downloadgrid' && $second_content_type != 'downloadgrid' ){
		return $css_output;	
	}
	
	//Enqueue all css stylesheets used by grids.
	mp_stacks_grids_enqueue_frontend_css( 'downloadgrid' );
	
	//Download per row
	$downloadgrid_per_row = mp_core_get_post_meta($post_id, 'downloadgrid_per_row', '3');
	
	//Post Spacing (padding)
	$downloadgrid_post_spacing = mp_core_get_post_meta($post_id, 'downloadgrid_post_spacing', '20');
	
	//Post Inner Margin (padding)
	$downloadgrid_post_inner_margin = mp_core_get_post_meta($post_id, 'downloadgrid_post_inner_margin', '0');
		
	//Padding inside the featured images
	$downloadgrid_featured_images_inner_margin = mp_core_get_post_meta($post_id, 'downloadgrid_featured_images_inner_margin', '10' );
	
	//Image Overlay Color and Opacity
	$downloadgrid_images_overlay_color = mp_core_get_post_meta($post_id, 'downloadgrid_images_overlay_color', '#FFF' );
	$downloadgrid_images_overlay_opacity = mp_core_get_post_meta($post_id, 'downloadgrid_images_overlay_opacity', '0' );
	
	//Max Image Width
	$downloadgrid_feat_img_max_width = mp_core_get_post_meta($post_id, 'downloadgrid_feat_img_max_width', '0' );
	$img_max_width_css = empty( $downloadgrid_feat_img_max_width ) ? NULL : '#mp-brick-' . $post_id . ' .mp-stacks-grid-item-image-holder{ max-width: ' . $downloadgrid_feat_img_max_width . 'px;}';
	
	//Padding for items directly under the image
	$downloadgrid_post_below_image_area_inner_margin = mp_core_get_post_meta( $post_id, 'downloadgrid_post_below_image_area_inner_margin', '0' );
	
	//Use the Excerpt's Color as the default fallback for all text in the grid
	$default_text_color = mp_core_get_post_meta( $post_id, 'downloadgrid_excerpt_color' );
	
	//Get CSS Output
	
	$css_output .= '
	#mp-brick-' . $post_id . ' .mp-stacks-grid-item{' . 
			mp_core_css_line( 'color', $default_text_color ) . 
			mp_core_css_line( 'width', mp_stacks_grid_posts_per_row_percentage( $downloadgrid_per_row ), '%' ) . 
			mp_core_css_line( 'padding', $downloadgrid_post_spacing, 'px' ) . 
	'}
	#mp-brick-' . $post_id . ' .mp-stacks-grid-item-inner{' . 
			mp_core_css_line( 'padding', $downloadgrid_post_inner_margin, 'px' ) . '
	}' . 
	$img_max_width_css . '
	#mp-brick-' . $post_id . ' .mp-stacks-grid-item-inner .mp-stacks-grid-item-below-image-holder{' . 
			mp_core_css_line( 'padding', $downloadgrid_post_below_image_area_inner_margin, 'px' ) . '
	}
	/*Below image, remove the padding-top (spacing) from the first text item*/
	#mp-brick-' . $post_id . ' .mp-stacks-grid-item-inner .mp-stacks-grid-item-below-image-holder [class*="link"]:first-child [class*="holder"]{
		' . ( $downloadgrid_post_below_image_area_inner_margin != '0' ? 'padding-top:0px!important;' : NULL ) . '	
	}
	/*Over image, remove the padding-top (spacing) from the first text item*/
	#mp-brick-' . $post_id . ' .mp-stacks-grid .mp-stacks-grid-item .mp-stacks-grid-item-inner .mp-stacks-grid-over-image-text-container-table-cell [class*="holder"]:first-child{
		padding-top:0px;
	}';
	
	$css_output .= apply_filters( 'mp_stacks_downloadgrid_css', $css_output, $post_id );
	
	$css_output .= '
	#mp-brick-' . $post_id . ' .mp-stacks-grid-over-image-text-container,
	#mp-brick-' . $post_id . ' .mp-stacks-grid-over-image-text-container-top,
	#mp-brick-' . $post_id . ' .mp-stacks-grid-over-image-text-container-middle,
	#mp-brick-' . $post_id . ' .mp-stacks-grid-over-image-text-container-bottom{' . 
		mp_core_css_line( 'padding', $downloadgrid_featured_images_inner_margin, 'px' ) . 
	'}';
	
	//Get the css output the the isotope button navigation
	$css_output .= mp_stacks_grid_isotope_nav_btns_css( $post_id, 'downloadgrid' );
	
	//Get the css output for the image overlay for mobile
	$css_output .= mp_stacks_grid_overlay_mobile_css( $post_id, 'downloadgrid_image_overlay_animation_keyframes', 'downloadgrid' );
	
	//Get the bg color for each post
	$css_output .= mp_stacks_grid_bg_color_css( $post_id, mp_core_get_post_meta( $post_id, 'downloadgrid_taxonomy_terms', array() ), 'inner_bg_color' );
	
	return $css_output;
	
}
add_filter('mp_brick_additional_css', 'mp_stacks_brick_content_output_css_downloadgrid', 10, 4);