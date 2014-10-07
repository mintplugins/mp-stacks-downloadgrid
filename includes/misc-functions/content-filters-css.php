<?php 
/**
 * This file contains the function which hooks to a brick's content output
 *
 * @since 1.0.0
 *
 * @package    MP Stacks DownloadGrid
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
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
	
	//Download per row
	$downloadgrid_per_row = mp_core_get_post_meta($post_id, 'downloadgrid_per_row', '3');
	
	//Post Spacing (padding)
	$downloadgrid_post_spacing = mp_core_get_post_meta($post_id, 'downloadgrid_post_spacing', '20');
	
	//Padding inside the featured images
	$downloadgrid_featured_images_inner_margin = mp_core_get_post_meta($post_id, 'downloadgrid_featured_images_inner_margin', '10' );
	
	//Image Overlay Color and Opacity
	$downloadgrid_images_overlay_color = mp_core_get_post_meta($post_id, 'downloadgrid_images_overlay_color', '#FFF' );
	$downloadgrid_images_overlay_opacity = mp_core_get_post_meta($post_id, 'downloadgrid_images_overlay_opacity', '0' );
	
	//Titles placement
	$downloadgrid_titles_placement = mp_core_get_post_meta($post_id, 'downloadgrid_titles_placement', 'below_image_left');
	
	//Title Color and size
	$downloadgrid_title_color = mp_core_get_post_meta($post_id, 'downloadgrid_title_color', 'NULL');
	$downloadgrid_title_size = mp_core_get_post_meta($post_id, 'downloadgrid_title_size', '20');
	$downloadgrid_title_lineheight = mp_core_get_post_meta($post_id, 'downloadgrid_title_lineheight', '20');
	
	//Show Post Title Backgrounds?
	$downloadgrid_show_title_backgrounds = mp_core_get_post_meta($post_id, 'downloadgrid_show_title_backgrounds');
	
	//If we should show the title backgrounds
	if ( $downloadgrid_show_title_backgrounds ){
		
		//Title background spacing (padding)
		$downloadgrid_title_background_padding = mp_core_get_post_meta($post_id, 'downloadgrid_title_background_padding', '5');	
		
			//Calculate Minimum Line Height with Padding
			$min_line_height_with_padding = ( $downloadgrid_title_background_padding * 3 ) + $downloadgrid_title_size;
			//If the line height with padding is greater than the lineheight, we need to make the lineheight match or the layout gets thrown off
			$downloadgrid_title_lineheight = $min_line_height_with_padding  > $downloadgrid_title_lineheight ? $min_line_height_with_padding : $downloadgrid_title_lineheight;
		
		//Title background color 
		$downloadgrid_title_background_color = mp_core_get_post_meta($post_id, 'downloadgrid_title_background_color', '#fff' );	
		
		//Title background opacity 
		$downloadgrid_title_background_opacity = mp_core_get_post_meta($post_id, 'downloadgrid_title_background_opacity', '100');	
	}
	else{
		//Title background spacing (padding)
		$downloadgrid_title_background_padding = '0';	
		//Title background color - defaults to white
		$downloadgrid_title_background_color = '#FFFFFF';	
		//Title background opacity 
		$downloadgrid_title_background_opacity = '0';	
	}
	
	//Excerpts Placement
	$downloadgrid_excerpt_placement = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_placement', 'below_image_left');
	
	//Excerpt Color and Size
	$downloadgrid_excerpt_color = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_color', 'NULL');
	$downloadgrid_excerpt_size = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_size', '15');
	$downloadgrid_excerpt_lineheight = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_lineheight', '18');
	
	//Show Excerpt Backgrounds?
	$downloadgrid_show_excerpt_backgrounds = mp_core_get_post_meta($post_id, 'downloadgrid_show_excerpt_backgrounds');
	
	//If we should show the excerpt backgrounds
	if ( $downloadgrid_show_excerpt_backgrounds ){
		
		//Excerpt background spacing (padding)
		$downloadgrid_excerpt_background_padding = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_background_padding', '5');	
		
			//Calculate Minimum Line Height with Padding
			$min_line_height_with_padding = ( $downloadgrid_excerpt_background_padding * 3 ) + $downloadgrid_excerpt_size;
			//If the line height with padding is greater than the lineheight, we need to make the lineheight match or the layout gets thrown off
			$downloadgrid_excerpt_lineheight = $min_line_height_with_padding  > $downloadgrid_excerpt_lineheight ? $min_line_height_with_padding : $downloadgrid_excerpt_lineheight;
		
		//Excerpt background color 
		$downloadgrid_excerpt_background_color = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_background_color', '#fff' );	
		//Excerpt background opacity 
		$downloadgrid_excerpt_background_opacity = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_background_opacity', '100');	
	}
	else{
		//Excerpt background spacing (padding)
		$downloadgrid_excerpt_background_padding = '0';	
		//Excerpt background color - defaults to white
		$downloadgrid_excerpt_background_color = '#FFFFFF';	
		//Excerpt background opacity 
		$downloadgrid_excerpt_background_opacity = '0';	
	}
	
	//Price Placement
	$downloadgrid_price_placement = mp_core_get_post_meta($post_id, 'downloadgrid_price_placement', 'over_image_top_left');
	
	//Price Color and Size
	$downloadgrid_price_color = mp_core_get_post_meta($post_id, 'downloadgrid_price_color', 'NULL');
	$downloadgrid_price_size = mp_core_get_post_meta($post_id, 'downloadgrid_price_size', '15');
	
	//Show Price Backgrounds?
	$downloadgrid_show_price_backgrounds = mp_core_get_post_meta($post_id, 'downloadgrid_show_price_backgrounds');
	
	//If we should show the price backgrounds
	if ( $downloadgrid_show_price_backgrounds ){
		//Price background spacing (padding)
		$downloadgrid_price_background_padding = mp_core_get_post_meta($post_id, 'downloadgrid_price_background_padding', '5');	
		
			//Calculate Minimum Line Height with Padding
			$downloadgrid_price_lineheight = ( $downloadgrid_price_background_padding * 3 ) + $downloadgrid_price_size;
			
		//Price background color 
		$downloadgrid_price_background_color = mp_core_get_post_meta($post_id, 'downloadgrid_price_background_color', '#FFFFFF' );	
		//Price background opacity 
		$downloadgrid_price_background_opacity = mp_core_get_post_meta($post_id, 'downloadgrid_price_background_opacity', '100');	
	}
	else{
		//Price background spacing (padding)
		$downloadgrid_price_background_padding = '1';	
		//Price background color - defaults to white
		$downloadgrid_price_background_color = '#FFFFFF';	
		//Price background opacity 
		$downloadgrid_price_background_opacity = '0';	
	}
	
	//Load More Buttons Colors
	$downloadgrid_load_more_button_color = mp_core_get_post_meta($post_id, 'downloadgrid_load_more_button_color', 'NULL');
	$downloadgrid_load_more_button_text_color = mp_core_get_post_meta($post_id, 'downloadgrid_load_more_button_text_color', 'NULL');
	$downloadgrid_mouse_over_load_more_button_color = mp_core_get_post_meta($post_id, 'downloadgrid_mouse_over_load_more_button_color', 'NULL');
	$downloadgrid_mouse_over_load_more_button_text_color = mp_core_get_post_meta($post_id, 'downloadgrid_mouse_over_load_more_button_text_color', 'NULL');
	
	//Get CSS Output
	$css_output .= '
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item{' . 
			mp_core_css_line( 'color', $downloadgrid_excerpt_color ) . 
			mp_core_css_line( 'width', (100/$downloadgrid_per_row), '%' ) . 
			mp_core_css_line( 'padding', $downloadgrid_post_spacing, 'px' ) . 
		'}
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item-title-holder{
			' . mp_stacks_grid_get_text_placement_css( $downloadgrid_titles_placement, array( 
					'line_height' => ( $downloadgrid_title_size ),
				) ) . ';' . 
			mp_core_css_line( 'color', $downloadgrid_title_color ) . 
			mp_core_css_line( 'font-size', $downloadgrid_title_size, 'px' ) . 
			mp_core_css_line( 'line-height', $downloadgrid_title_lineheight, 'px' ) . 
		'}' . 
		mp_stacks_grid_highlight_text_css( array( 
				'brick_id' => $post_id,
				'class_name' => 'mp-stacks-downloadgrid-item-title',
				'highlight_padding' => $downloadgrid_title_background_padding, 
				'highlight_color' => $downloadgrid_title_background_color, 
				'highlight_opacity' => $downloadgrid_title_background_opacity
		) ) . '
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item-excerpt-holder, 
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item-excerpt-holder a{
			' . mp_stacks_grid_get_text_placement_css( $downloadgrid_excerpt_placement, array( 
					'line_height' => ($downloadgrid_excerpt_size),
				) ) . '; ' .
			mp_core_css_line( 'color', $downloadgrid_excerpt_color ) . 
			mp_core_css_line( 'font-size', $downloadgrid_excerpt_size, 'px' ) . 
			mp_core_css_line( 'line-height', $downloadgrid_excerpt_lineheight, 'px' ) . 
		'}' . 
		mp_stacks_grid_highlight_text_css( array( 
				'brick_id' => $post_id,
				'class_name' => 'mp-stacks-downloadgrid-item-excerpt',
				'highlight_padding' => $downloadgrid_excerpt_background_padding, 
				'highlight_color' => $downloadgrid_excerpt_background_color, 
				'highlight_opacity' => $downloadgrid_excerpt_background_opacity
		) ) . '
		
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item-price-holder, 
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item-price-holder a{
			' . mp_stacks_grid_get_text_placement_css( $downloadgrid_price_placement, array( 
					'line_height' => ($downloadgrid_price_size),
				) ) . '; ' .
			mp_core_css_line( 'color', $downloadgrid_price_color ) . 
			mp_core_css_line( 'font-size', $downloadgrid_price_size, 'px' ) .
			mp_core_css_line( 'line-height', $downloadgrid_price_lineheight, 'px' ) . 
		'}' . 
		mp_stacks_grid_highlight_text_css( array( 
				'brick_id' => $post_id,
				'class_name' => 'mp-stacks-downloadgrid-item-price',
				'highlight_padding' => $downloadgrid_price_background_padding, 
				'highlight_color' => $downloadgrid_price_background_color, 
				'highlight_opacity' => $downloadgrid_price_background_opacity
		) ) . '
		
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-load-more-button{' . 
			mp_core_css_line( 'color', $downloadgrid_load_more_button_text_color ) . 
			mp_core_css_line( 'background-color', $downloadgrid_load_more_button_color ) . 
		'}
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-load-more-button:hover{' . 
			mp_core_css_line( 'color', $downloadgrid_mouse_over_load_more_button_text_color ) . 
			mp_core_css_line( 'background-color', $downloadgrid_mouse_over_load_more_button_color ) . 
		'}
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-over-image-text-container,
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-over-image-text-container-top,
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-over-image-text-container-middle,
		#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-over-image-text-container-bottom{' . 
			mp_core_css_line( 'padding', $downloadgrid_featured_images_inner_margin, 'px' ) . 
		'}';
		
		return $css_output;
	
}
add_filter('mp_brick_additional_css', 'mp_stacks_brick_content_output_css_downloadgrid', 10, 4);