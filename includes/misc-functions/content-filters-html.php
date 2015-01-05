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
	
	//If this stack content type is NOT set to be a downloadgrid	
	if ($mp_stacks_content_type != 'downloadgrid'){
		
		return $default_content_output;
		
	}
	
	//Because we run the same function for this and for "Load More" ajax, we call a re-usable function which returns the output
	$downloadgrid_output = mp_stacks_downloadgrid_output( $post_id );
	
	//Return
	return $downloadgrid_output['downloadgrid_output'] . $downloadgrid_output['load_more_button'] . $downloadgrid_output['downloadgrid_after'];

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
	
	if ( !isset( $_POST['mp_stacks_grid_post_id'] ) || !isset( $_POST['mp_stacks_grid_offset'] ) || !isset( $_POST['mp_stacks_grid_post_counter'] ) ){
		return;	
	}
	
	$post_id = $_POST['mp_stacks_grid_post_id'];
	$post_offset = $_POST['mp_stacks_grid_offset'];
	$post_counter = $_POST['mp_stacks_grid_post_counter'];

	//Because we run the same function for this and for "Load More" ajax, we call a re-usable function which returns the output
	$downloadgrid_output = mp_stacks_downloadgrid_output( $post_id, $post_offset, $post_counter );
	
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
function mp_stacks_downloadgrid_output( $post_id, $post_offset = NULL, $post_counter = 1 ){
	
	global $wp_query;
	
	//Start up the PHP session if there isn't one already
	if( !session_id() ){
		session_start();
	}
	
	//If we are NOT doing ajax get the parent's post id from the wp_query.
	if ( !defined( 'DOING_AJAX' ) ){
		$queried_object_id = $wp_query->queried_object_id;
		$_SESSION['mp_stacks_downloadgrid_queryobjid_' . $post_id] = $queried_object_id;
	}
	//If we are doing ajax, get the parent's post id from the PHP session where it was stored on initial the page load.
	else{
		$queried_object_id = $_SESSION['mp_stacks_downloadgrid_queryobjid_' . $post_id];
	}
	
	//Get this Brick Info
	$post = get_post($post_id);
	
	$downloadgrid_output = NULL;
	
	//Get Download Taxonomy Term to Loop through
	$downloadgrid_taxonomy_term = mp_core_get_post_meta($post_id, 'downloadgrid_taxonomy_term', '');
	
	//Download per row
	$downloadgrid_per_row = mp_core_get_post_meta($post_id, 'downloadgrid_per_row', '3');
	
	//Download per page
	$downloadgrid_per_page = mp_core_get_post_meta($post_id, 'downloadgrid_per_page', '9');
	
	//Setup the WP_Query args
	$downloadgrid_args = array(
		'order' => 'DESC',
		'paged' => 0,
		'posts_per_page' => $downloadgrid_per_page,
		'post_type' => 'download',
		'post__not_in' => array($queried_object_id),
	);
	
	//If we are using Offset
	if ( !empty( $post_offset ) ){
		//Add offset args to the WP_Query
		$downloadgrid_args['offset'] = $post_offset;
	}
	//Alternatively, if we are using brick pagination
	else if ( isset( $wp_query->query['mp_brick_pagination_slugs'] ) ){
		
		//Get the brick slug
		$pagination_brick_slugs = explode( '|||', $wp_query->query['mp_brick_pagination_slugs'] );
		
		$pagination_brick_page_numbers = explode( '|||', $wp_query->query['mp_brick_pagination_page_numbers'] );
		
		$brick_pagination_counter = 0;
	
		//Loop through each brick in the url which has pagination
		foreach( $pagination_brick_slugs as $brick_slug ){
			//If this brick is the one we want to paginate
			if ( $brick_slug == $post->post_name ){
				//Add page number to the WP_Query
				$downloadgrid_args['paged'] = $pagination_brick_page_numbers[$brick_pagination_counter];
				//Set the post offset variable to start at the end of the current page
				$post_offset = isset( $downloadgrid_args['paged'] ) ? ($downloadgrid_args['paged'] * $downloadgrid_per_page) - $downloadgrid_per_page : 0;
			}
			
			//Increment the counter which aligns $pagination_brick_page_numbers to $pagination_brick_slugs
			$brick_pagination_counter = $brick_pagination_counter + 1;
		}
		
	}
		
	//If we should show related downloads
	if ( $downloadgrid_taxonomy_term == 'related_downloads' ){
		
		$tags = wp_get_post_terms( $queried_object_id, 'download_tag' );
		
		if ( is_object( $tags ) ){
			$tags_array = $tags;
		}
		elseif (is_array( $tags ) ){
			$tags_array = isset( $tags[0] ) ? $tags[0] : NULL;
		}
		
		$tag_slugs = wp_get_post_terms( $queried_object_id, 'download_tag', array("fields" => "slugs") );
		
		//Add the related tags as a tax_query to the WP_Query
		$downloadgrid_args['tax_query'] = array(
			array(
				'taxonomy' => 'download_tag',
				'field'    => 'slug',
				'terms'    => $tag_slugs,
				
			)
		);
					
	}
	//If we should show a download category of the users choosing
	else{
		
		//Add the category we want to show to the WP_Query
		$downloadgrid_args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'download_category',
				'field'    => 'id',
				'terms'    => $downloadgrid_taxonomy_term,
				'operator' => 'IN'
			)
		);		
	}
	
	//Show Download Images?
	$downloadgrid_featured_images_show = mp_core_get_post_meta($post_id, 'downloadgrid_featured_images_show');
	
	//Download Image width and height
	$downloadgrid_featured_images_width = mp_core_get_post_meta( $post_id, 'downloadgrid_featured_images_width', '500' );
	$downloadgrid_featured_images_height = mp_core_get_post_meta( $post_id, 'downloadgrid_featured_images_height', '0' );
	
	//Get the options for the grid placement - we pass this to the action filters for text placement
	$grid_placement_options = apply_filters( 'mp_stacks_downloadgrid_placement_options', NULL, $post_id );
	
	//Get the JS for animating items - only needed the first time we run this - not on subsequent Ajax requests.
	if ( !defined('DOING_AJAX') ){
					
		//Check if we should apply Masonry to this grid
		$downloadgrid_masonry = mp_core_get_post_meta( $post_id, 'downloadgrid_masonry' );
		
		//If we should apply Masonry to this grid
		if ( $downloadgrid_masonry ){
			 
			//Add Masonry JS 
			$downloadgrid_output .= '<script type="text/javascript">
				jQuery(document).ready(function($){ 
					//Activate Masonry for Grid Items
					$( "#mp-brick-' . $post_id . ' .mp-stacks-grid" ).imagesLoaded(function(){
						$( "#mp-brick-' . $post_id . ' .mp-stacks-grid" ).masonry();
					});
				});
				var masonry_grid_' . $post_id . ' = true;
				</script>';
		}
		else{
			
			//Set Masonry Variable to False so we know not to refresh masonry upon ajax
			$downloadgrid_output .= '<script type="text/javascript">
				var masonry_grid_' . $post_id . ' = false;
			</script>';	
		}
		
		//Filter Hook which can be used to apply javascript output for items in this grid
		$downloadgrid_output .= apply_filters( 'mp_stacks_downloadgrid_animation_js', $downloadgrid_output, $post_id );
		
		//Get JS output to animate the images on mouse over and out
		$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-grid-item', '.mp-stacks-grid-item-image', mp_core_get_post_meta( $post_id, 'downloadgrid_image_animation_keyframes', array() ) ); 
		
		//Get JS output to animate the images overlays on mouse over and out
		$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-grid-item', '.mp-stacks-grid-item-image-overlay',mp_core_get_post_meta( $post_id, 'downloadgrid_image_overlay_animation_keyframes', array() ) ); 
	}
	
	//Get Download Output
	$downloadgrid_output .= !defined('DOING_AJAX') ? '<div class="mp-stacks-grid">' : NULL;
		
	//Create new query for stacks
	$downloadgrid_query = new WP_Query( apply_filters( 'downloadgrid_args', $downloadgrid_args ) );
	
	$total_posts = $downloadgrid_query->found_posts;
	
	$css_output = NULL;
	
	//Loop through the stack group		
	if ( $downloadgrid_query->have_posts() ) { 
		
		while( $downloadgrid_query->have_posts() ) : $downloadgrid_query->the_post(); 
		
				$grid_post_id = get_the_ID();
		
				$downloadgrid_output .= '<div class="mp-stacks-grid-item">';
					
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
						
						$downloadgrid_output .= '<div class="mp-stacks-grid-item-image-holder">';
						
							$downloadgrid_output .= '<div class="mp-stacks-grid-item-image-overlay"></div>';
							
							$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-grid-image-link">';
							
							//Get the featured image and crop according to the user's specs
							if ( $downloadgrid_featured_images_height > 0 && !empty( $downloadgrid_featured_images_height ) ){
								$featured_image = mp_core_the_featured_image($grid_post_id, $downloadgrid_featured_images_width, $downloadgrid_featured_images_height);
							}
							else{
								$featured_image = mp_core_the_featured_image( $grid_post_id, $downloadgrid_featured_images_width );	
							}
							 
							$downloadgrid_output .= '<img src="' . $featured_image . '" class="mp-stacks-grid-item-image" title="' . the_title_attribute( 'echo=0' ) . '" />';
							
							//Top Over
							$downloadgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-top">';
							
								$downloadgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table-cell">';
										
										//Filter Hook to output HTML into the "Top" and "Over" position on the featured Image
										$downloadgrid_output .= apply_filters( 'mp_stacks_downloadgrid_top_over', NULL, $grid_post_id, $grid_placement_options );
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							//Middle Over
							$downloadgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-middle">';
							
								$downloadgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table-cell">';
									
										//Filter Hook to output HTML into the "Middle" and "Over" position on the featured Image
										$downloadgrid_output .= apply_filters( 'mp_stacks_downloadgrid_middle_over', NULL, $grid_post_id, $grid_placement_options );
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							//Bottom Over
							$downloadgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-bottom">';
							
								$downloadgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table-cell">';
										
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
				
				if ( $downloadgrid_per_row == $post_counter ){
					
					//Add clear div to bump a new row
					$downloadgrid_output .= '<div class="mp-stacks-grid-item-clearedfix"></div>';
					
					//Reset counter
					$post_counter = 1;
				}
				else{
					
					//Increment Counter
					$post_counter = $post_counter + 1;
					
				}
				
				//Increment Offset
				$post_offset = $post_offset + 1;
				
		endwhile;
	}
	
	//If we're not doing ajax, add the stuff to close the downloadgrid container and items needed after
	if ( !defined('DOING_AJAX') ){
		$downloadgrid_output .= '</div>';
	}
	
	
	//jQuery Trigger to reset all downloadgrid animations to their first frames
	$animation_trigger = '<script type="text/javascript">jQuery(document).ready(function($){ $(document).trigger("mp_core_animation_set_first_keyframe_trigger"); });</script>';
	
	//Assemble args for the load more output
	$load_more_args = array(
		 'meta_prefix' => 'downloadgrid',
		 'total_posts' => $total_posts, 
		 'posts_per_page' => $downloadgrid_per_page, 
		 'paged' => $downloadgrid_args['paged'], 
		 'post_counter' => $post_counter, 
		 'post_offset' => $post_offset,
		 'brick_slug' => $post->post_name
	);
	
	return array(
		'downloadgrid_output' => $downloadgrid_output,
		'load_more_button' => apply_filters( 'mp_stacks_downloadgrid_load_more_html_output', $load_more_html = NULL, $post_id, $load_more_args ),
		'animation_trigger' => $animation_trigger,
		'downloadgrid_after' => '<div class="mp-stacks-grid-item-clearedfix"></div><div class="mp-stacks-grid-after"></div>'
	);
		
}