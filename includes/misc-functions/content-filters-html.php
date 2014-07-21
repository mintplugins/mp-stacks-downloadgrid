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
 * This function hooks to the brick output. If it is supposed to be a 'download_grid', then it will output the download_grid
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_download_grid( $default_content_output, $mp_stacks_content_type, $post_id ){
	
	//If this stack content type is set to be a download grid	
	if ($mp_stacks_content_type != 'download_grid'){
		
		return $default_content_output;
		
	}
	
	//Set default value for $content_output to NULL
	$content_output = NULL;	
	
	//Get Download Taxonomy Term to Loop through
	$download_grid_taxonomy_term = mp_core_get_post_meta($post_id, 'download_grid_taxonomy_term', '');
		
	//Download per row
	$download_grid_per_row = mp_core_get_post_meta($post_id, 'download_grid_per_row', '3');
	
	//Download per page
	$download_grid_per_page = mp_core_get_post_meta($post_id, 'download_grid_per_page', '9');
	
	//Show Download Images?
	$download_grid_show_featured_images = mp_core_get_post_meta($post_id, 'download_grid_show_featured_images');
	
	//Download Image width and height
	$download_grid_featured_images_width = mp_core_get_post_meta($post_id, 'download_grid_featured_images_width', '300px', array( 'after' => 'px' ) );
	$download_grid_featured_images_height = mp_core_get_post_meta($post_id, 'download_grid_featured_images_height', '200px', array( 'after' => 'px' ));
	
	//Show Post Titles
	$download_grid_show_titles = mp_core_get_post_meta($post_id, 'download_grid_show_titles');
	
	//Titles placement
	$download_grid_titles_placement = mp_core_get_post_meta($post_id, 'download_grid_titles_placement', 'below_image_left');
	
	//Show Post Excerpts
	$download_grid_show_excerpts = mp_core_get_post_meta($post_id, 'download_grid_show_excerpts');
	
	//Excerpts Placement
	$download_grid_excerpt_placement = mp_core_get_post_meta($post_id, 'download_grid_excerpt_placement', 'below_image_left');
	
	//Show Load More Button?
	$download_grid_show_load_more_button = mp_core_get_post_meta($post_id, 'download_grid_show_load_more_button');

	//Load More Button Text
	$download_grid_load_more_text = mp_core_get_post_meta($post_id, 'download_grid_load_more_text', __( 'Load More', 'mp_stacks_download_grid' ) );
	
	//get word limit for exceprts
	$word_limit = mp_core_get_post_meta($post_id, 'download_grid_excerpt_word_limit', 20);
	
	$read_more_text = __('...More', 'mp_stacks_download_grid');
	
	//Get Download Output
	$download_grid_output = '<div class="mp-stacks-download-grid">';
	
	//Get JS output to animate the titles on mouse over and out
	$download_grid_output .= mp_core_js_mouse_over_animate_child( '.mp-stacks-download-grid-item', '.mp-stacks-download-grid-item-title-holder', get_post_meta( $post_id, 'download_grid_title_animation_keyframes', true ) ); 
	
	//Get JS output to animate the excerpts on mouse over and out
	$download_grid_output .= mp_core_js_mouse_over_animate_child( '.mp-stacks-download-grid-item', '.mp-stacks-download-grid-item-excerpt-holder', get_post_meta( $post_id, 'download_grid_excerpt_animation_keyframes', true ) ); 
	
	//Get JS output to animate the images on mouse over and out
	$download_grid_output .= mp_core_js_mouse_over_animate_child( '.mp-stacks-download-grid-item', '.mp-stacks-download-grid-item-image', get_post_meta( $post_id, 'download_grid_image_animation_keyframes', true ) ); 
	
	//Get JS output to animate the images overlays on mouse over and out
	$download_grid_output .= mp_core_js_mouse_over_animate_child( '.mp-stacks-download-grid-item', '.mp-stacks-download-grid-item-image-overlay', get_post_meta( $post_id, 'download_grid_image_overlay_animation_keyframes', true ) ); 
	
	//Set counter to 0
	$counter = 1;
			
	//Set the args for the new query
	$download_grid_args = array(
		'order' => 'DESC',
		'posts_per_page' => $download_grid_per_page,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'download_category',
				'field'    => 'id',
				'terms'    => $download_grid_taxonomy_term,
				'operator' => 'IN'
			)
		)
	);	
		
	//Create new query for stacks
	$download_grid_query = new WP_Query( apply_filters( 'download_grid_args', $download_grid_args ) );
	
	$total_posts = $download_grid_query->found_posts;
	
	$css_output = NULL;
	
	//Set the offset of posts to be 0
	$post_offset = 0;
	
	//Loop through the stack group		
	if ( $download_grid_query->have_posts() ) { 
		
		while( $download_grid_query->have_posts() ) : $download_grid_query->the_post(); 
		
				$grid_post_id = get_the_ID();
		
				$download_grid_output .= '<div class="mp-stacks-download-grid-item">';
					
					//If we should show the featured images
					if ($download_grid_show_featured_images){
						
						$download_grid_output .= '<div class="mp-stacks-download-grid-item-image-holder">';
						
							$download_grid_output .= '<div class="mp-stacks-download-grid-item-image-overlay"></div>';
							
							$download_grid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-download-grid-image-link">';
							
							$download_grid_output .= '<img src="' . mp_core_the_featured_image($grid_post_id, $download_grid_featured_images_width, $download_grid_featured_images_height) . '" class="mp-stacks-download-grid-item-image" title="' . the_title_attribute( 'echo=0' ) . '" />';
							
							//Top Over
							$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-top">';
							
								$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table">';
								
									$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $download_grid_titles_placement, 'over') !== false && strpos( $download_grid_titles_placement, 'top') !== false && $download_grid_show_titles){
											
											$download_grid_output .= mp_stacks_download_grid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $download_grid_excerpt_placement, 'over') !== false && strpos( $download_grid_excerpt_placement, 'top') !== false && $download_grid_show_excerpts){
											
											$download_grid_output .= mp_stacks_download_grid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
									
									$download_grid_output .= '</div>';
									
								$download_grid_output .= '</div>';
							
							$download_grid_output .= '</div>';
							
							//Middle Over
							$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-middle">';
							
								$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table">';
								
									$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $download_grid_titles_placement, 'over') !== false && strpos( $download_grid_titles_placement, 'middle') !== false && $download_grid_show_titles){
											
											$download_grid_output .= mp_stacks_download_grid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $download_grid_excerpt_placement, 'over') !== false && strpos( $download_grid_excerpt_placement, 'middle') !== false && $download_grid_show_excerpts){
											
											$download_grid_output .= mp_stacks_download_grid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
									
									$download_grid_output .= '</div>';
									
								$download_grid_output .= '</div>';
							
							$download_grid_output .= '</div>';
							
							//Bottom Over
							$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-bottom">';
							
								$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table">';
								
									$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $download_grid_titles_placement, 'over') !== false && strpos( $download_grid_titles_placement, 'bottom') !== false && $download_grid_show_titles){
											
											$download_grid_output .= mp_stacks_download_grid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $download_grid_excerpt_placement, 'over') !== false && strpos( $download_grid_excerpt_placement, 'bottom') !== false && $download_grid_show_excerpts){
											
											$download_grid_output .= mp_stacks_download_grid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
									
									$download_grid_output .= '</div>';
									
								$download_grid_output .= '</div>';
							
							$download_grid_output .= '</div>';
							
							$download_grid_output .= '</a>';
							
						$download_grid_output .= '</div>';
						
					}
					
					//If we should show the title below the image
					if ( strpos( $download_grid_titles_placement, 'below') !== false && $download_grid_show_titles){
						
						$download_grid_output .= mp_stacks_download_grid_title( $grid_post_id );
			
					}
					//If we should show the excerpt below the image
					if ( strpos( $download_grid_excerpt_placement, 'below') !== false && $download_grid_show_excerpts){
						
						$download_grid_output .= mp_stacks_download_grid_excerpt( $grid_post_id, $word_limit, $read_more_text );
					}
			
				$download_grid_output .= '</div>';
				
				if ( $download_grid_per_row == $counter ){
					
					//Add clear div to bump a new row
					$download_grid_output .= '<div class="mp-stacks-download-grid-item-clearedfix"></div>';
					
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
	
	//If there are still more posts in this taxonomy
	if ( $total_posts > $post_offset && $download_grid_show_load_more_button ){
		$download_grid_output .= '<a mp_post_id="' . $post_id . '" mp_brick_offset="' . $post_offset . '" mp_stacks_download_grid_counter="' . $counter . '" class="button mp-stacks-download-grid-load-more-button">' . $download_grid_load_more_text . '</a>';	
	}
	
	$download_grid_output .= '</div>';
	
	//Content output
	$content_output .= $download_grid_output;
	
	//Return
	return $content_output;

}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_download_grid', 10, 3);

/**
 * Output more posts using ajax
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_download_grid_ajax_load_more(){
			
	if (!isset( $_POST['mp_stacks_download_grid_post_id'] ) || !isset( $_POST['mp_stacks_download_grid_offset'] ) || !isset( $_POST['mp_stacks_download_grid_counter'] ) ){
		return;	
	}
	
	$post_id = $_POST['mp_stacks_download_grid_post_id'];
	$post_offset = $_POST['mp_stacks_download_grid_offset'];
	$counter = $_POST['mp_stacks_download_grid_counter'];

	//Get Download Taxonomy Term to Loop through
	$download_grid_taxonomy_term = mp_core_get_post_meta($post_id, 'download_grid_taxonomy_term', '');
		
	//Download per row
	$download_grid_per_row = mp_core_get_post_meta($post_id, 'download_grid_per_row', '3');
	
	//Download per page
	$download_grid_per_page = mp_core_get_post_meta($post_id, 'download_grid_per_page', '9');
	
	//Show Download Images?
	$download_grid_show_featured_images = mp_core_get_post_meta($post_id, 'download_grid_show_featured_images');
	
	//Download Image width and height
	$download_grid_featured_images_width = mp_core_get_post_meta($post_id, 'download_grid_featured_images_width', '300px', array( 'after' => 'px' ) );
	$download_grid_featured_images_height = mp_core_get_post_meta($post_id, 'download_grid_featured_images_height', '200px', array( 'after' => 'px' ));
	
	//Show Post Titles
	$download_grid_show_titles = mp_core_get_post_meta($post_id, 'download_grid_show_titles');
	
	//Titles placement
	$download_grid_titles_placement = mp_core_get_post_meta($post_id, 'download_grid_titles_placement', 'below_image_left');
	
	//Show Post Excerpts
	$download_grid_show_excerpts = mp_core_get_post_meta($post_id, 'download_grid_show_excerpts');
	
	//Excerpts Placement
	$download_grid_excerpt_placement = mp_core_get_post_meta($post_id, 'download_grid_excerpt_placement', 'below_image_left');
	
	//Show Load More Button?
	$download_grid_show_load_more_button = mp_core_get_post_meta($post_id, 'download_grid_show_load_more_button');

	//Load More Button Text
	$download_grid_load_more_text = mp_core_get_post_meta($post_id, 'download_grid_load_more_text', __( 'Load More', 'mp_stacks_download_grid' ) );
	
	//get word limit for exceprts
	$word_limit = mp_core_get_post_meta($post_id, 'download_grid_excerpt_word_limit', 20);
	
	$read_more_text = __('...More', 'mp_stacks_download_grid');
	
	//Set the args for the new query
	$download_grid_args = array(
		'order' => 'DESC',
		'posts_per_page' => $download_grid_per_page,
		'offset'     =>  $post_offset,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'download_category',
				'field'    => 'id',
				'terms'    => $download_grid_taxonomy_term,
				'operator' => 'IN'
			)
		)
	);	
		
	//Create new query for stacks
	$download_grid_query = new WP_Query( apply_filters( 'download_grid_args', $download_grid_args ) );
	
	$total_posts = $download_grid_query->found_posts;
	
	$css_output = NULL;
	
	//jQuery Trigger to reset all downloadgrid animations to their first frames
	$download_grid_output = '<script type="text/javascript">jQuery(document).ready(function($){ $(document).trigger("mp_stacks_download_grid_set_first_keyframe_trigger"); });</script>';
	
	//Loop through the stack group		
	if ( $download_grid_query->have_posts() ) {
		
		while( $download_grid_query->have_posts() ) : $download_grid_query->the_post(); 
		
				$grid_post_id = get_the_ID();
		
				$download_grid_output .= '<div class="mp-stacks-download-grid-item">';
					
					//If we should show the featured images
					if ($download_grid_show_featured_images){
						
						$download_grid_output .= '<div class="mp-stacks-download-grid-item-image-holder">';
						
							$download_grid_output .= '<div class="mp-stacks-download-grid-item-image-overlay"></div>';
							
							$download_grid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-download-grid-image-link">';
							
							$download_grid_output .= '<img src="' . mp_core_the_featured_image($grid_post_id, $download_grid_featured_images_width, $download_grid_featured_images_height) . '" class="mp-stacks-download-grid-item-image" title="' . the_title_attribute( 'echo=0' ) . '" />';
							
							//Top Over
							$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-top">';
							
								$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table">';
								
									$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $download_grid_titles_placement, 'over') !== false && strpos( $download_grid_titles_placement, 'top') !== false && $download_grid_show_titles){
											
											$download_grid_output .= mp_stacks_download_grid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $download_grid_excerpt_placement, 'over') !== false && strpos( $download_grid_excerpt_placement, 'top') !== false && $download_grid_show_excerpts){
											
											$download_grid_output .= mp_stacks_download_grid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
									
									$download_grid_output .= '</div>';
									
								$download_grid_output .= '</div>';
							
							$download_grid_output .= '</div>';
							
							//Middle Over
							$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-middle">';
							
								$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table">';
								
									$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $download_grid_titles_placement, 'over') !== false && strpos( $download_grid_titles_placement, 'middle') !== false && $download_grid_show_titles){
											
											$download_grid_output .= mp_stacks_download_grid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $download_grid_excerpt_placement, 'over') !== false && strpos( $download_grid_excerpt_placement, 'middle') !== false && $download_grid_show_excerpts){
											
											$download_grid_output .= mp_stacks_download_grid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
									
									$download_grid_output .= '</div>';
									
								$download_grid_output .= '</div>';
							
							$download_grid_output .= '</div>';
							
							//Bottom Over
							$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-bottom">';
							
								$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table">';
								
									$download_grid_output .= '<div class="mp-stacks-download-grid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $download_grid_titles_placement, 'over') !== false && strpos( $download_grid_titles_placement, 'bottom') !== false && $download_grid_show_titles){
											
											$download_grid_output .= mp_stacks_download_grid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $download_grid_excerpt_placement, 'over') !== false && strpos( $download_grid_excerpt_placement, 'bottom') !== false && $download_grid_show_excerpts){
											
											$download_grid_output .= mp_stacks_download_grid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
									
									$download_grid_output .= '</div>';
									
								$download_grid_output .= '</div>';
							
							$download_grid_output .= '</div>';
							
							$download_grid_output .= '</a>';
							
						$download_grid_output .= '</div>';
						
					}
					
					//If we should show the title below the image
					if ( strpos( $download_grid_titles_placement, 'below') !== false && $download_grid_show_titles){
						
						$download_grid_output .= mp_stacks_download_grid_title( $grid_post_id );
			
					}
					//If we should show the excerpt below the image
					if ( strpos( $download_grid_excerpt_placement, 'below') !== false && $download_grid_show_excerpts){
						
						$download_grid_output .= mp_stacks_download_grid_excerpt( $grid_post_id, $word_limit, $read_more_text );
					}
			
				$download_grid_output .= '</div>';
				
				if ( $download_grid_per_row == $counter ){
					
					//Add clear div to bump a new row
					$download_grid_output .= '<div class="mp-stacks-download-grid-item-clearedfix"></div>';
					
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
	
	//If there are still more posts in this taxonomy
	if ( $total_posts > $post_offset && $download_grid_show_load_more_button ){
		$download_grid_output .= '<a mp_post_id="' . $post_id . '" mp_brick_offset="' . $post_offset . '" mp_stacks_download_grid_counter="' . $counter . '" class="button mp-stacks-download-grid-load-more-button">' . $download_grid_load_more_text . '</a>';	
	}
	
	$download_grid_output .= '</div>';
	
	echo $download_grid_output;
	
	die();
			
}
add_action( 'wp_ajax_mp_stacks_download_grid_load_more', 'mp_download_grid_ajax_load_more' );
add_action( 'wp_ajax_nopriv_mp_stacks_download_grid_load_more', 'mp_download_grid_ajax_load_more' );

/**
 * Get the HTML for the title in the grid
 *
 * @access   public
 * @since    1.0.0
 * @post_id  $post_id Int - The ID of the post to get the title of
 * @return   $html_output String - A string holding the html for a title in the grid
 */
function mp_stacks_download_grid_title( $post_id ){
	
	$download_grid_output = '<div class="mp-stacks-download-grid-item-title-holder">';
	
		$download_grid_output .= get_the_title( $post_id );
		
	$download_grid_output .= '</div>';
	
	return $download_grid_output;
	
}

/**
 * Get the HTML for the excerpt in the grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $post_id Int - The ID of the post to get the excerpt of
 * @param    $word_limit Int - The total number of words to include in the excerpt
 * @param    $read_more_text String - The ID of the post to get the title of
 * @return   $html_output String - A string holding the html for an excerpt in the grid
 */
function mp_stacks_download_grid_excerpt( $post_id, $word_limit, $read_more_text ){
	
	//Add clear div to bump download_grid below title and icon
	$download_grid_output = '<div class="mp-stacks-download-grid-item-clearedfix"></div>';
	
	$the_excerpt = mp_core_get_excerpt_by_id($post_id);
	
	//Check word limit for excerpt				
	if (!empty($word_limit)){							
		//Cut the excerpt off at X number of words
		$the_excerpt = mp_core_limit_text_to_words($the_excerpt, $word_limit);
	}
	
	$download_grid_output .= '<div class="mp-stacks-download-grid-item-excerpt-holder">';
		
		$download_grid_output .= '<p>' . strip_tags($the_excerpt);
		
		//If there is more than 0 words in this excerpt
		if (mp_core_word_count($the_excerpt) > 0 ){
			//$download_grid_output .= ' <a href="' . get_permalink($post_id) . '">' . $read_more_text .'</a>';
		}
		
		$download_grid_output .= '</p>';
			
	$download_grid_output .= '</div>';
	
	//If there is more than 0 words in this excerpt
	if (mp_core_word_count($the_excerpt) > 0 ){
		return $download_grid_output;	
	}
	
}