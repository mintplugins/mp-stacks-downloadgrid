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
 * This function hooks to the brick output. If it is supposed to be a 'downloadgrid', then it will output the downloadgrid
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_downloadgrid( $default_content_output, $mp_stacks_content_type, $post_id ){
	
	global $wp_query;
	
	//If this stack content type is NOT set to be a downloadgrid	
	if ($mp_stacks_content_type != 'downloadgrid'){
		
		return $default_content_output;
		
	}
	
	//Because we run the same function for this and for "Load More" ajax, we call a re-usable function which returns the output
	$downloadgrid_output = mp_stacks_downloadgrid_output( $post_id, 0 );
	
	//Return
	return $downloadgrid_output['downloadgrid_output'] . $downloadgrid_output['load_more_button'];

}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_downloadgrid', 10, 3);

/**
 * Output more posts using ajax
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_downloadgrid_ajax_load_more(){
			
	if (!isset( $_POST['mp_stacks_downloadgrid_post_id'] ) || !isset( $_POST['mp_stacks_downloadgrid_offset'] ) || !isset( $_POST['mp_stacks_downloadgrid_counter'] ) ){
		return;	
	}
	
	$post_id = $_POST['mp_stacks_downloadgrid_post_id'];
	$post_offset = $_POST['mp_stacks_downloadgrid_offset'];

	//Because we run the same function for this and for "Load More" ajax, we call a re-usable function which returns the output
	$downloadgrid_output = mp_stacks_downloadgrid_output( $post_id, $post_offset );
	
	echo json_encode( array(
		'items' => $downloadgrid_output['downloadgrid_output'],
		'button' => $downloadgrid_output['load_more_button'],
		'animation_trigger' => $downloadgrid_output['animation_trigger']
	) );
	
	die();
			
}
add_action( 'wp_ajax_mp_stacks_downloadgrid_load_more', 'mp_downloadgrid_ajax_load_more' );
add_action( 'wp_ajax_nopriv_mp_stacks_downloadgrid_load_more', 'mp_downloadgrid_ajax_load_more' );

/**
 * Run the Grid Loop and Return the HTML Output, Load More Button, and Animation Trigger for the Grid
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $post_id Int - The ID of the Brick
 * @param    $post_offset Int - The number of posts deep we are into the loop (if doing ajax). If not doing ajax, set this to 0;
 * @return   Array - HTML output from the Grid Loop, The Load More Button, and the Animation Trigger in an array for usage in either ajax or not.
 */
function mp_stacks_downloadgrid_output( $post_id, $post_offset ){
	
	$downloadgrid_output = NULL;
	
	//Get Download Taxonomy Term to Loop through
	$downloadgrid_taxonomy_term = mp_core_get_post_meta($post_id, 'downloadgrid_taxonomy_term', '');
	
	//Download per row
	$downloadgrid_per_row = mp_core_get_post_meta($post_id, 'downloadgrid_per_row', '3');
	
	//Download per page
	$downloadgrid_per_page = mp_core_get_post_meta($post_id, 'downloadgrid_per_page', '9');
	
	//If we should show related downloads
	if ( $downloadgrid_taxonomy_term == 'related_downloads' ){
		
		$tags = wp_get_post_terms( $wp_query->queried_object_id, 'download_tag' );
		
		if ( is_object( $tags ) ){
			$tags_array = $tags;
		}
		elseif (is_array( $tags ) ){
			$tags_array = $tags[0];
		}
		
		$tag_slugs = wp_get_post_terms( $wp_query->queried_object_id, 'download_tag', array("fields" => "slugs") );
		
		$downloadgrid_args = array(
			'order' => 'DESC',
			'offset' => $post_offset,
			'posts_per_page' => $downloadgrid_per_page,
			'post_type' => 'download',
			'post__not_in' => array($wp_query->queried_object_id),
			'tax_query' => array(
				array(
					'taxonomy' => 'download_tag',
					'field'    => 'slug',
					'terms'    => $tag_slugs,
					
				)
			)
		);
					
	}
	//If we should show a download category of the users choosing
	else{
		
		//Set the args for the new query
		$downloadgrid_args = array(
			'order' => 'DESC',
			'offset' => $post_offset,
			'post_status' => 'publish',
			'posts_per_page' => $downloadgrid_per_page,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'download_category',
					'field'    => 'id',
					'terms'    => $downloadgrid_taxonomy_term,
					'operator' => 'IN'
				)
			)
		);		
	}
	
	//Show Download Images?
	$downloadgrid_featured_images_show = mp_core_get_post_meta($post_id, 'downloadgrid_featured_images_show');
	
	//Download Image width and height
	$downloadgrid_featured_images_width = mp_core_get_post_meta( $post_id, 'downloadgrid_featured_images_width', '300' );
	$downloadgrid_featured_images_height = mp_core_get_post_meta( $post_id, 'downloadgrid_featured_images_height', '200' );
	
	//Get the options for the grid placement - we pass this to the action filters for text placement
	$grid_placement_options = apply_filters( 'mp_stacks_downloadgrid_placement_options', NULL, $post_id );
	
	//Show Load More Button?
	$downloadgrid_load_more_button_show = mp_core_get_post_meta($post_id, 'downloadgrid_load_more_button_show');

	//Load More Button Text
	$downloadgrid_load_more_button_text = mp_core_get_post_meta($post_id, 'downloadgrid_load_more_button_text', __( 'Load More', 'mp_stacks_downloadgrid' ) );
	
	//Get the JS for animating items - only needed the first time we run this - not on subsequent Ajax requests.
	if ( !defined('DOING_AJAX') ){
		
		//Filter Hook which can be used to apply javascript output for items in this grid
		$downloadgrid_output .= apply_filters( 'mp_stacks_downloadgrid_animation_js', $downloadgrid_output, $post_id );
		
		//Get JS output to animate the images on mouse over and out
		$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item', '.mp-stacks-downloadgrid-item-image', mp_core_get_post_meta( $post_id, 'downloadgrid_image_animation_keyframes', array() ) ); 
		
		//Get JS output to animate the images overlays on mouse over and out
		$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item', '.mp-stacks-downloadgrid-item-image-overlay',mp_core_get_post_meta( $post_id, 'downloadgrid_image_overlay_animation_keyframes', array() ) ); 
	}
	
	//Get Download Output
	$downloadgrid_output .= !defined('DOING_AJAX') ? '<div class="mp-stacks-downloadgrid">' : NULL;
	
	//Set counter
	$counter = isset( $_POST['mp_stacks_postgrid_counter'] ) ? $_POST['mp_stacks_postgrid_counter'] : 1;
		
	//Create new query for stacks
	$downloadgrid_query = new WP_Query( apply_filters( 'downloadgrid_args', $downloadgrid_args ) );
	
	$total_posts = $downloadgrid_query->found_posts;
	
	$css_output = NULL;
	
	//Loop through the stack group		
	if ( $downloadgrid_query->have_posts() ) { 
		
		while( $downloadgrid_query->have_posts() ) : $downloadgrid_query->the_post(); 
		
				$grid_post_id = get_the_ID();
		
				$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item">';
					
					//Microformats
					$downloadgrid_output .= '
					<article class="microformats hentry" style="display:none;">
						<h2 class="entry-title">' . get_the_title() . '</h2>
						<span class="author vcard"><span class="fn">' . get_the_author() . '</span></span>
						<time class="published" datetime="' . get_the_time('Y-m-d H:i:s') . '">' . get_the_date() . '</time>
						<time class="updated" datetime="' . get_the_modified_date('Y-m-d H:i:s') . '">' . get_the_modified_date() .'</time>
						<div class="entry-summary">' . mp_core_get_excerpt_by_id($grid_post_id) . '</div>
					</article>';
					
					//If we should show the featured images
					if ($downloadgrid_featured_images_show){
						
						$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item-image-holder">';
						
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item-image-overlay"></div>';
							
							$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-downloadgrid-image-link">';
							
							$downloadgrid_output .= '<img src="' . mp_core_the_featured_image($grid_post_id, $downloadgrid_featured_images_width, $downloadgrid_featured_images_height) . '" class="mp-stacks-downloadgrid-item-image" title="' . the_title_attribute( 'echo=0' ) . '" />';
							
							//Top Over
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-top">';
							
								$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table-cell">';
										
										//Filter Hook to output HTML into the "Top" and "Over" position on the featured Image
										$downloadgrid_output .= apply_filters( 'mp_stacks_downloadgrid_top_over', NULL, $grid_post_id, $grid_placement_options );
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							//Middle Over
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-middle">';
							
								$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table-cell">';
									
										//Filter Hook to output HTML into the "Middle" and "Over" position on the featured Image
										$downloadgrid_output .= apply_filters( 'mp_stacks_downloadgrid_middle_over', NULL, $grid_post_id, $grid_placement_options );
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							//Bottom Over
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-bottom">';
							
								$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table-cell">';
										
										//Filter Hook to output HTML into the "Bottom" and "Over" position on the featured Image
										$downloadgrid_output .= apply_filters( 'mp_stacks_downloadgrid_bottom_over', NULL, $grid_post_id, $grid_placement_options );
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</a>';
							
						$downloadgrid_output .= '</div>';
						
					}
					
					//Filter Hook to output HTML into the "Below" position on the featured Image
					$downloadgrid_output .= apply_filters( 'mp_stacks_downloadgrid_below', NULL, $grid_post_id, $grid_placement_options );
				
				$downloadgrid_output .= '</div>';
				
				if ( $downloadgrid_per_row == $counter ){
					
					//Add clear div to bump a new row
					$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item-clearedfix"></div>';
					
					//Reset counter
					$counter = 1;
				}
				else{
					
					//Increment Counter
					$counter = $counter + 1;
					
				}
				
				//Increment Offset
				$post_offset = $post_offset + 1;
				
		endwhile;
	}
	
	$downloadgrid_output .= !defined('DOING_AJAX') ? '</div>' : NULL;
	
	//If there are still more posts in this taxonomy
	if ( $total_posts > $post_offset && $downloadgrid_load_more_button_show ){
		$load_more_button = '<div class="mp-stacks-downloadgrid-load-more-container"><a mp_post_id="' . $post_id . '" mp_brick_offset="' . $post_offset . '" mp_stacks_downloadgrid_counter="' . $counter . '" class="button mp-stacks-downloadgrid-load-more-button">' . $downloadgrid_load_more_button_text . '</a></div>';	
	}
	else{
		$load_more_button = NULL;
	}
	
	//jQuery Trigger to reset all downloadgrid animations to their first frames
	$animation_trigger = '<script type="text/javascript">jQuery(document).ready(function($){ $(document).trigger("mp_core_animation_set_first_keyframe_trigger"); });</script>';
	
	return array(
		'downloadgrid_output' => $downloadgrid_output,
		'load_more_button' => $load_more_button,
		'animation_trigger' => $animation_trigger
	);
		
}