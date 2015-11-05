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
	
	if ( !isset( $_POST['mp_stacks_grid_post_id'] ) || !isset( $_POST['mp_stacks_grid_offset'] ) ){
		return;	
	}
	
	$post_id = $_POST['mp_stacks_grid_post_id'];
	$post_offset = $_POST['mp_stacks_grid_offset'];

	//Because we run the same function for this and for "Load More" ajax, we call a re-usable function which returns the output
	$downloadgrid_output = mp_stacks_downloadgrid_output( $post_id, true, $post_offset );
	
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
 * @param    $loading_more string - If we are loading more through ajax, this will be true, Defaults to false.
 * @param    $post_offset Int - The number of posts deep we are into the loop (if doing ajax). If not doing ajax, set this to 0;
 * @return   Array - HTML output from the Grid Loop, The Load More Button, and the Animation Trigger in an array for usage in either ajax or not.
 */
function mp_stacks_downloadgrid_output( $post_id, $loading_more = false, $post_offset = NULL ){
	
	global $wp_query;
	
	//Enqueue all js scripts used by grids.
	mp_stacks_grids_enqueue_frontend_scripts( 'downloadgrid' );
	
	//If we are NOT doing ajax get the parent's post id from the wp_query.
	if ( !defined( 'DOING_AJAX' ) ){
		$queried_object_id = $wp_query->queried_object_id;
	}
	//If we are doing ajax, get the parent's post id from the AJAX-passed $_POST['mp_stacks_queried_object_id']
	else{
		$queried_object_id = isset( $_POST['mp_stacks_queried_object_id'] ) ? $_POST['mp_stacks_queried_object_id'] : NULL;
	}
	
	//Get this Brick Info
	$post = get_post($post_id);
	
	$downloadgrid_output = NULL;
	
	//Get Download Taxonomy Term to Loop through
	$downloadgrid_taxonomy_term = mp_core_get_post_meta($post_id, 'downloadgrid_taxonomy_term', '');
	
	//Get taxonomy term repeater (new way)
	$downloadgrid_taxonomy_terms = mp_core_get_post_meta($post_id, 'downloadgrid_taxonomy_terms', '');
	
	//Download per row
	$downloadgrid_per_row = mp_core_get_post_meta($post_id, 'downloadgrid_per_row', '3');
	
	//Download per page
	$downloadgrid_per_page = mp_core_get_post_meta($post_id, 'downloadgrid_per_page', '9');
	
	//Setup the WP_Query args
	$downloadgrid_args = array(
		'order' => 'DESC',
		'paged' => 0,
		'post_status' => 'publish',
		'posts_per_page' => $downloadgrid_per_page,
		'post_type' => 'download',
		'post__not_in' => array($queried_object_id),
		'tax_query' => array(
			'relation' => 'OR',
		)
	);
	
	$orderby = mp_stacks_grid_order_by( $post_id, 'downloadgrid' );
	
	//Set the order by options for the wp query
	switch ( $orderby ) {
		case 'popular':
			$downloadgrid_args['orderby'] = 'meta_value_num date';
			$downloadgrid_args['meta_key'] = '_edd_download_sales';
			break;
		case 'date_newest_to_oldest':
			$downloadgrid_args['orderby'] = 'date';
			$downloadgrid_args['order'] = 'DESC';
			break;
		case 'date_oldest_to_newest':
			$downloadgrid_args['orderby'] = 'date';
			$downloadgrid_args['order'] = 'ASC';
			break;
		case 'price_highest_to_lowest':
			$downloadgrid_args['orderby'] = 'meta_value_num date';
			$downloadgrid_args['meta_key'] = 'edd_price';
			$downloadgrid_args['order'] = 'DESC';
			break;
		case 'price_lowest_to_highest':
			$downloadgrid_args['orderby'] = 'meta_value_num date';
			$downloadgrid_args['meta_key'] = 'edd_price';
			$downloadgrid_args['order'] = 'ASC';
			break;
		case 'most_comments':
			$downloadgrid_args['orderby'] = 'comment_count';
			break;
		case 'random':
			$downloadgrid_args['orderby'] = 'rand';
			break;
	}
	
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
	
	//Check the load more behavior to make sure it ins't pagination
	$load_more_behaviour = mp_core_get_post_meta($post_id, 'downloadgrid' . '_load_more_behaviour', 'ajax_load_more' );
	
	//If we are loading from scratch based on a user's selection AND we are not using pagination as the "Load More" style (which won't work with this type of filtering)
	if ( isset( $_POST['mp_stacks_grid_filter_tax'] ) && !empty( $_POST['mp_stacks_grid_filter_tax'] ) && isset( $_POST['mp_stacks_grid_filter_term'] ) && !empty( $_POST['mp_stacks_grid_filter_term'] ) && $load_more_behaviour != 'pagination' ){
		
		$user_chosen_tax = $_POST['mp_stacks_grid_filter_tax'];
		$user_chosen_term = $_POST['mp_stacks_grid_filter_term'];
		
		if ( !empty( $user_chosen_tax ) && !empty( $user_chosen_term ) ){
		
			//Add the user chosen tax and term as a tax_query to the WP_Query
			$downloadgrid_args['tax_query'][] = array(
				'taxonomy' => $user_chosen_tax,
				'field'    => 'slug',
				'terms'    => $user_chosen_term,
			);
		
		}
					
	}	
	else{	
		//If there are tax terms selected to show (the "new" way with multiple terms)
		if ( is_array( $downloadgrid_taxonomy_terms ) && !empty( $downloadgrid_taxonomy_terms[0]['taxonomy_term'] ) ){
			
			//If the selection for category is "all", we don't need to add anything extra to the qeury
			if ( $downloadgrid_taxonomy_terms[0]['taxonomy_term'] != 'all' ){
			
				//Loop through each term the user added to this downloadgrid
				foreach( $downloadgrid_taxonomy_terms as $downloadgrid_taxonomy_term ){
				
					//If we should show related downloads
					if ( $downloadgrid_taxonomy_term['taxonomy_term'] == 'related_downloads' ){
						
						$tags = wp_get_post_terms( $queried_object_id, 'download_tag' );
						
						if ( is_object( $tags ) ){
							$tags_array = $tags;
						}
						elseif (is_array( $tags ) ){
							$tags_array = isset( $tags[0] ) ? $tags[0] : NULL;
						}
						
						$tag_slugs = wp_get_post_terms( $queried_object_id, 'download_tag', array("fields" => "slugs") );
						
						//Add the related tags as a tax_query to the WP_Query
						$downloadgrid_args['tax_query'][] = array(
							'taxonomy' => 'download_tag',
							'field'    => 'slug',
							'terms'    => $tag_slugs,
						);
									
					}
					//If we should show a download category of the users choosing
					else{
						
						//Add the category we want to show to the WP_Query
						$downloadgrid_args['tax_query'][] = array(
							'taxonomy' => 'download_category',
							'field'    => 'id',
							'terms'    => $downloadgrid_taxonomy_term['taxonomy_term'],
							'operator' => 'IN'
						);		
					}
				}
			}
		}
		//if there is a single tax term to show (this is backward compatibility for before the terms selector was repeatable.
		else if( !empty( $downloadgrid_taxonomy_term ) ){
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
				$downloadgrid_args['tax_query'][] = array(
					'taxonomy' => 'download_tag',
					'field'    => 'slug',
					'terms'    => $tag_slugs,
				);
							
			}
			//If we should show a download category of the users choosing
			else{
				
				//Add the category we want to show to the WP_Query
				$downloadgrid_args['tax_query'][] = array(
					'taxonomy' => 'download_category',
					'field'    => 'id',
					'terms'    => $downloadgrid_taxonomy_term,
					'operator' => 'IN'
				);		
			}
		}
		else{
			return false;	
		}
	}
	
	//Show Download Images?
	$downloadgrid_featured_images_show = mp_core_get_post_meta_checkbox($post_id, 'downloadgrid_featured_images_show', true);
	
	//Download Image width and height
	$downloadgrid_featured_images_width = mp_core_get_post_meta( $post_id, 'downloadgrid_featured_images_width', '500' );
	$downloadgrid_featured_images_height = mp_core_get_post_meta( $post_id, 'downloadgrid_featured_images_height', 0 );
	
	//Get the options for the grid placement - we pass this to the action filters for text placement
	$grid_placement_options = apply_filters( 'mp_stacks_downloadgrid_placement_options', NULL, $post_id );
	
	//Get the JS for animating items - only needed the first time we run this - not on subsequent Ajax requests.
	if ( !$loading_more ){
		
		//Here we set javascript for this grid
		$downloadgrid_output .= apply_filters( 'mp_stacks_grid_js', NULL, $post_id, 'downloadgrid' );
		
	}
	
	//Add HTML that sits before the "grid" div
	$downloadgrid_output .= !$loading_more ? apply_filters( 'mp_stacks_grid_before', NULL, $post_id, 'downloadgrid', $downloadgrid_taxonomy_terms ) : NULL; 
	
	//Get Download Output
	$downloadgrid_output .= !$loading_more ? '<div class="mp-stacks-grid ' . apply_filters( 'mp_stacks_grid_classes', NULL, $post_id, 'downloadgrid' ) . '" ' . apply_filters( 'mp_stacks_grid_attributes', NULL, $post_id, 'downloadgrid' ) . '>' : NULL;
			
	//Create new query for stacks
	$downloadgrid_query = new WP_Query( apply_filters( 'downloadgrid_args', $downloadgrid_args ) );
	
	$total_posts = $downloadgrid_query->found_posts;
	
	$css_output = NULL;
	
	//Loop through the stack group		
	if ( $downloadgrid_query->have_posts() ) { 
		
		while( $downloadgrid_query->have_posts() ) : $downloadgrid_query->the_post(); 
		
				$grid_post_id = get_the_ID();
										
				//Reset Grid Classes String
				$source_counter = 0;
				$post_source_num = NULL;
				$grid_item_inner_bg_color = NULL;
				
				//If there are multiple tax terms selected to show
				if ( is_array( $downloadgrid_taxonomy_terms ) && !empty( $downloadgrid_taxonomy_terms[0]['taxonomy_term'] ) ){					
					
					//Loop through each term the user added to this downloadgrid
					foreach( $downloadgrid_taxonomy_terms as $downloadgrid_taxonomy_term ){
																		
						//If the current post has this term, make that term one of the classes for the grid item
						if ( has_term( $downloadgrid_taxonomy_term['taxonomy_term'], 'download_category', $grid_post_id ) ){
							
							//Store the source this post belongs to
							$post_source_num = $source_counter;
														
							if ( !empty( $downloadgrid_taxonomy_term['taxonomy_bg_color'] ) ){
								$grid_item_inner_bg_color = $downloadgrid_taxonomy_term['taxonomy_bg_color'];
							}
							
						}
						
						$source_counter = $source_counter + 1;
						
					}
				}
				
				//Add our custom classes to the grid-item 
				$class_string = 'mp-stacks-grid-source-' . $post_source_num . ' mp-stacks-grid-item mp-stacks-grid-item-' . $grid_post_id . ' ';
				//Add all posts that would be added from the post_class wp function as well
				$class_string = join( ' ', get_post_class( $class_string, $grid_post_id ) );
				$class_string = apply_filters( 'mp_stacks_grid_item_classes', $class_string, $post_id, 'downloadgrid' ); 
				
				//Get the Grid Item Attributes
				$grid_item_attribute_string = apply_filters( 'mp_stacks_grid_attribute_string', NULL, $downloadgrid_taxonomy_terms, $grid_post_id, $post_id, 'downloadgrid', $post_source_num );
				
				$downloadgrid_output .= '<div class="' . $class_string . '" ' . $grid_item_attribute_string . '>';
					$downloadgrid_output .= '<div class="mp-stacks-grid-item-inner" ' . (!empty( $grid_item_inner_bg_color ) ? 'mp-default-bg-color="' . $grid_item_inner_bg_color . '"' : NULL) . '>';
					
					//Add htmloutput directly inside this grid item
					$downloadgrid_output .= apply_filters( 'mp_stacks_grid_inside_grid_item_top', NULL, $downloadgrid_taxonomy_terms, $post_id, 'downloadgrid', $grid_post_id, $post_source_num );
										
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
												
							$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-grid-image-link" title="' . the_title_attribute( 'echo=0' ) . '" alt="' . the_title_attribute( 'echo=0' ) . '">';
							
							$downloadgrid_output .= '<div class="mp-stacks-grid-item-image-overlay"></div>';
							
							//Get the featured image and crop according to the user's specs
							if ( $downloadgrid_featured_images_height > 0 && !empty( $downloadgrid_featured_images_height ) ){
								$featured_image = mp_core_the_featured_image($grid_post_id, $downloadgrid_featured_images_width, $downloadgrid_featured_images_height);
							}
							else{
								$featured_image = mp_core_the_featured_image( $grid_post_id, $downloadgrid_featured_images_width );	
							}
							 
							$downloadgrid_output .= '<img src="' . $featured_image . '" class="mp-stacks-grid-item-image" title="' . the_title_attribute( 'echo=0' ) . '" alt="' . the_title_attribute( 'echo=0' ) . '" />';
							
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
					
					//Below Image Area Container:
					$downloadgrid_output .= '<div class="mp-stacks-grid-item-below-image-holder">';
					
						//Filter Hook to output HTML into the "Below" position on the featured Image
						$downloadgrid_output .= apply_filters( 'mp_stacks_downloadgrid_below', NULL, $grid_post_id, $grid_placement_options );
				
					$downloadgrid_output .= '</div>';
				
				$downloadgrid_output .= '</div></div>';
								
				//Increment Offset
				$post_offset = $post_offset + 1;
				
		endwhile;
	}
	
	//If we're not doing ajax, add the stuff to close the downloadgrid container and items needed after
	if ( !$loading_more ){
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