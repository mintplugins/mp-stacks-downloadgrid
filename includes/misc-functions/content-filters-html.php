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
	
	//If this stack content type is set to be a download grid	
	if ($mp_stacks_content_type != 'downloadgrid'){
		
		return $default_content_output;
		
	}
	
	//Set default value for $content_output to NULL
	$content_output = NULL;	
	
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
	$downloadgrid_show_featured_images = mp_core_get_post_meta($post_id, 'downloadgrid_show_featured_images');
	
	//Download Image width and height
	$downloadgrid_featured_images_width = mp_core_get_post_meta( $post_id, 'downloadgrid_featured_images_width', '300' );
	$downloadgrid_featured_images_height = mp_core_get_post_meta( $post_id, 'downloadgrid_featured_images_height', '200' );
	
	//Show Post Titles
	$downloadgrid_show_titles = mp_core_get_post_meta($post_id, 'downloadgrid_show_titles');
	
	//Show Post Title Backgrounds?
	$downloadgrid_show_title_backgrounds = mp_core_get_post_meta($post_id, 'downloadgrid_show_title_backgrounds');
	
	//Titles placement
	$downloadgrid_titles_placement = mp_core_get_post_meta($post_id, 'downloadgrid_titles_placement', 'below_image_left');
	
	//Show Post Excerpts
	$downloadgrid_show_excerpts = mp_core_get_post_meta($post_id, 'downloadgrid_show_excerpts');
	
	//Excerpts Placement
	$downloadgrid_excerpt_placement = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_placement', 'below_image_left');
	
	//Show Prices
	$downloadgrid_show_prices = mp_core_get_post_meta( $post_id, 'downloadgrid_show_prices' );
	
	//Prices Placements
	$downloadgrid_price_placement = mp_core_get_post_meta( $post_id, 'downloadgrid_price_placement', 'over_image_top_left' );
	
	//Show Load More Button?
	$downloadgrid_show_load_more_button = mp_core_get_post_meta($post_id, 'downloadgrid_show_load_more_button');

	//Load More Button Text
	$downloadgrid_load_more_text = mp_core_get_post_meta($post_id, 'downloadgrid_load_more_text', __( 'Load More', 'mp_stacks_downloadgrid' ) );
	
	//get word limit for exceprts
	$word_limit = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_word_limit', 20);
	
	$read_more_text = __('...', 'mp_stacks_downloadgrid');
	
	//Get Download Output
	$downloadgrid_output = '<div class="mp-stacks-downloadgrid">';
	
	//Get JS output to animate the titles on mouse over and out
	$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item', '.mp-stacks-downloadgrid-item-title-holder', mp_core_get_post_meta( $post_id, 'downloadgrid_title_animation_keyframes', array() ) ); 
	
	//Get JS output to animate the titles background on mouse over and out
	if ( $downloadgrid_show_title_backgrounds ){
		$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item', '.mp-stacks-downloadgrid-item-title-background', mp_core_get_post_meta( $post_id, 'downloadgrid_title_background_animation_keyframes', array() ) ); 
	}
	
	//Get JS output to animate the excerpts on mouse over and out
	$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item', '.mp-stacks-downloadgrid-item-excerpt-holder', mp_core_get_post_meta( $post_id, 'downloadgrid_excerpt_animation_keyframes', array() ) ); 
	
	//Get JS output to animate the price on mouse over and out
	$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item', '.mp-stacks-downloadgrid-item-price-holder', mp_core_get_post_meta( $post_id, 'downloadgrid_price_animation_keyframes', array() ) ); 
	
	//Get JS output to animate the images on mouse over and out
	$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item', '.mp-stacks-downloadgrid-item-image', mp_core_get_post_meta( $post_id, 'downloadgrid_image_animation_keyframes', array() ) ); 
	
	//Get JS output to animate the images overlays on mouse over and out
	$downloadgrid_output .= mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-downloadgrid-item', '.mp-stacks-downloadgrid-item-image-overlay',mp_core_get_post_meta( $post_id, 'downloadgrid_image_overlay_animation_keyframes', array() ) ); 
	
	//Set counter to 0
	$counter = 1;
		
	//Create new query for stacks
	$downloadgrid_query = new WP_Query( apply_filters( 'downloadgrid_args', $downloadgrid_args ) );
	
	$total_posts = $downloadgrid_query->found_posts;
	
	$css_output = NULL;
	
	//Set the offset of posts to be 0
	$post_offset = 0;
	
	//Loop through the stack group		
	if ( $downloadgrid_query->have_posts() ) { 
		
		while( $downloadgrid_query->have_posts() ) : $downloadgrid_query->the_post(); 
		
				$grid_post_id = get_the_ID();
		
				$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item">';
					
					//If we should show the featured images
					if ($downloadgrid_show_featured_images){
						
						$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item-image-holder">';
						
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item-image-overlay"></div>';
							
							$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-downloadgrid-image-link">';
							
							$downloadgrid_output .= '<img src="' . mp_core_the_featured_image($grid_post_id, $downloadgrid_featured_images_width, $downloadgrid_featured_images_height) . '" class="mp-stacks-downloadgrid-item-image" title="' . the_title_attribute( 'echo=0' ) . '" />';
							
							//Top Over
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-top">';
							
								$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $downloadgrid_titles_placement, 'over') !== false && strpos( $downloadgrid_titles_placement, 'top') !== false && $downloadgrid_show_titles){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $downloadgrid_excerpt_placement, 'over') !== false && strpos( $downloadgrid_excerpt_placement, 'top') !== false && $downloadgrid_show_excerpts){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
										
										//If we should show the price over the image
										if ( strpos( $downloadgrid_price_placement, 'over') !== false && strpos( $downloadgrid_price_placement, 'top') !== false && $downloadgrid_show_prices){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_price( $grid_post_id );
											
										}
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							//Middle Over
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-middle">';
							
								$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $downloadgrid_titles_placement, 'over') !== false && strpos( $downloadgrid_titles_placement, 'middle') !== false && $downloadgrid_show_titles){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $downloadgrid_excerpt_placement, 'over') !== false && strpos( $downloadgrid_excerpt_placement, 'middle') !== false && $downloadgrid_show_excerpts){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
										
										//If we should show the price over the image
										if ( strpos( $downloadgrid_price_placement, 'over') !== false && strpos( $downloadgrid_price_placement, 'middle') !== false && $downloadgrid_show_prices){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_price( $grid_post_id );
											
										}
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							//Bottom Over
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-bottom">';
							
								$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $downloadgrid_titles_placement, 'over') !== false && strpos( $downloadgrid_titles_placement, 'bottom') !== false && $downloadgrid_show_titles){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $downloadgrid_excerpt_placement, 'over') !== false && strpos( $downloadgrid_excerpt_placement, 'bottom') !== false && $downloadgrid_show_excerpts){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
										
										//If we should show the price over the image
										if ( strpos( $downloadgrid_price_placement, 'over') !== false && strpos( $downloadgrid_price_placement, 'bottom') !== false && $downloadgrid_show_prices){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_price( $grid_post_id );
											
										}
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</a>';
							
						$downloadgrid_output .= '</div>';
						
					}
						
					//If we should show the title below the image
					if ( strpos( $downloadgrid_titles_placement, 'below') !== false && $downloadgrid_show_titles){
						
						$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-downloadgrid-title-link">';	
							$downloadgrid_output .= mp_stacks_downloadgrid_title( $grid_post_id );
						$downloadgrid_output .= '</a>';
					
					}
					//If we should show the excerpt below the image
					if ( strpos( $downloadgrid_excerpt_placement, 'below') !== false && $downloadgrid_show_excerpts){
						
						$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-downloadgrid-excerpt-link">';	
							$downloadgrid_output .= mp_stacks_downloadgrid_excerpt( $grid_post_id, $word_limit, $read_more_text );
						$downloadgrid_output .= '</a>';
						
					}
					
					//If we should show the price over the image
					if ( strpos( $downloadgrid_price_placement, 'below') !== false && $downloadgrid_show_prices){
						
						$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-downloadgrid-price-link">';	
							$downloadgrid_output .= mp_stacks_downloadgrid_price( $grid_post_id );
						$downloadgrid_output .= '</a>';
						
					}
				
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
	
	//If there are still more posts in this taxonomy
	if ( $total_posts > $post_offset && $downloadgrid_show_load_more_button ){
		$downloadgrid_output .= '<a mp_post_id="' . $post_id . '" mp_brick_offset="' . $post_offset . '" mp_stacks_downloadgrid_counter="' . $counter . '" class="button mp-stacks-downloadgrid-load-more-button">' . $downloadgrid_load_more_text . '</a>';	
	}
	
	$downloadgrid_output .= '</div>';
	
	//Content output
	$content_output .= $downloadgrid_output;
	
	//Return
	return $content_output;

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
	$counter = $_POST['mp_stacks_downloadgrid_counter'];

	//Get Download Taxonomy Term to Loop through
	$downloadgrid_taxonomy_term = mp_core_get_post_meta($post_id, 'downloadgrid_taxonomy_term', '');
		
	//Download per row
	$downloadgrid_per_row = mp_core_get_post_meta($post_id, 'downloadgrid_per_row', '3');
	
	//Download per page
	$downloadgrid_per_page = mp_core_get_post_meta($post_id, 'downloadgrid_per_page', '9');
	
	//Show Download Images?
	$downloadgrid_show_featured_images = mp_core_get_post_meta($post_id, 'downloadgrid_show_featured_images');
	
	//Download Image width and height
	$downloadgrid_featured_images_width = mp_core_get_post_meta($post_id, 'downloadgrid_featured_images_width', '300' );
	$downloadgrid_featured_images_height = mp_core_get_post_meta($post_id, 'downloadgrid_featured_images_height', '200');
	
	//Show Post Titles
	$downloadgrid_show_titles = mp_core_get_post_meta($post_id, 'downloadgrid_show_titles');
	
	//Show Post Title Backgrounds
	$downloadgrid_show_title_backgrounds = mp_core_get_post_meta($post_id, 'downloadgrid_show_title_backgrounds');
	
	//Titles placement
	$downloadgrid_titles_placement = mp_core_get_post_meta($post_id, 'downloadgrid_titles_placement', 'below_image_left');
	
	//Show Post Excerpts
	$downloadgrid_show_excerpts = mp_core_get_post_meta($post_id, 'downloadgrid_show_excerpts');
	
	//Excerpts Placement
	$downloadgrid_excerpt_placement = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_placement', 'below_image_left');
	
	//Show Prices
	$downloadgrid_show_prices = mp_core_get_post_meta( $post_id, 'downloadgrid_show_prices' );
	
	//Prices Placements
	$downloadgrid_price_placement = mp_core_get_post_meta( $post_id, 'downloadgrid_price_placement', 'over_image_top_left' );
	
	//Show Load More Button?
	$downloadgrid_show_load_more_button = mp_core_get_post_meta($post_id, 'downloadgrid_show_load_more_button');

	//Load More Button Text
	$downloadgrid_load_more_text = mp_core_get_post_meta($post_id, 'downloadgrid_load_more_text', __( 'Load More', 'mp_stacks_downloadgrid' ) );
	
	//get word limit for exceprts
	$word_limit = mp_core_get_post_meta($post_id, 'downloadgrid_excerpt_word_limit', 20);
	
	$read_more_text = __('...', 'mp_stacks_downloadgrid');
	
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
			'posts_per_page' => $downloadgrid_per_page,
			'post_type' => 'download',
			'offset'     =>  $post_offset,
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
			'posts_per_page' => $downloadgrid_per_page,
			'offset'     =>  $post_offset,
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
		
	//Create new query for stacks
	$downloadgrid_query = new WP_Query( apply_filters( 'downloadgrid_args', $downloadgrid_args ) );
	
	$total_posts = $downloadgrid_query->found_posts;
	
	$css_output = NULL;
	
	//jQuery Trigger to reset all downloadgrid animations to their first frames
	$downloadgrid_output = '<script type="text/javascript">jQuery(document).ready(function($){ $(document).trigger("mp_core_animation_set_first_keyframe_trigger"); });</script>';
	
	//Loop through the stack group		
	if ( $downloadgrid_query->have_posts() ) {
		
		while( $downloadgrid_query->have_posts() ) : $downloadgrid_query->the_post(); 
		
				$grid_post_id = get_the_ID();
		
				$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item">';
					
					//If we should show the featured images
					if ($downloadgrid_show_featured_images){
						
						$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item-image-holder">';
						
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-item-image-overlay"></div>';
							
							$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-downloadgrid-image-link">';
							
							$downloadgrid_output .= '<img src="' . mp_core_the_featured_image($grid_post_id, $downloadgrid_featured_images_width, $downloadgrid_featured_images_height) . '" class="mp-stacks-downloadgrid-item-image" title="' . the_title_attribute( 'echo=0' ) . '" />';
							
							//Top Over
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-top">';
							
								$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $downloadgrid_titles_placement, 'over') !== false && strpos( $downloadgrid_titles_placement, 'top') !== false && $downloadgrid_show_titles){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $downloadgrid_excerpt_placement, 'over') !== false && strpos( $downloadgrid_excerpt_placement, 'top') !== false && $downloadgrid_show_excerpts){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
										
										//If we should show the price over the image
										if ( strpos( $downloadgrid_price_placement, 'over') !== false && strpos( $downloadgrid_price_placement, 'top') !== false && $downloadgrid_show_prices){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_price( $grid_post_id );
											
										}
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							//Middle Over
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-middle">';
							
								$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $downloadgrid_titles_placement, 'over') !== false && strpos( $downloadgrid_titles_placement, 'middle') !== false && $downloadgrid_show_titles){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $downloadgrid_excerpt_placement, 'over') !== false && strpos( $downloadgrid_excerpt_placement, 'middle') !== false && $downloadgrid_show_excerpts){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
										
										//If we should show the price over the image
										if ( strpos( $downloadgrid_price_placement, 'over') !== false && strpos( $downloadgrid_price_placement, 'middle') !== false && $downloadgrid_show_prices){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_price( $grid_post_id );
											
										}
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							//Bottom Over
							$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-bottom">';
							
								$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table">';
								
									$downloadgrid_output .= '<div class="mp-stacks-downloadgrid-over-image-text-container-table-cell">';
									
										//If we should show the title over the image
										if ( strpos( $downloadgrid_titles_placement, 'over') !== false && strpos( $downloadgrid_titles_placement, 'bottom') !== false && $downloadgrid_show_titles){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_title( $grid_post_id );
								
										}
										
										//If we should show the excerpt over the image
										if ( strpos( $downloadgrid_excerpt_placement, 'over') !== false && strpos( $downloadgrid_excerpt_placement, 'bottom') !== false && $downloadgrid_show_excerpts){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_excerpt( $grid_post_id, $word_limit, $read_more_text );
											
										}
										
										//If we should show the price over the image
										if ( strpos( $downloadgrid_price_placement, 'over') !== false && strpos( $downloadgrid_price_placement, 'bottom') !== false && $downloadgrid_show_prices){
											
											$downloadgrid_output .= mp_stacks_downloadgrid_price( $grid_post_id );
											
										}
									
									$downloadgrid_output .= '</div>';
									
								$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</div>';
							
							$downloadgrid_output .= '</a>';
							
						$downloadgrid_output .= '</div>';
						
					}
					
					//If we should show the title below the image
					if ( strpos( $downloadgrid_titles_placement, 'below') !== false && $downloadgrid_show_titles){
						
						$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-downloadgrid-title-link">';	
							$downloadgrid_output .= mp_stacks_downloadgrid_title( $grid_post_id );
						$downloadgrid_output .= '</a>';	
					}
					//If we should show the excerpt below the image
					if ( strpos( $downloadgrid_excerpt_placement, 'below') !== false && $downloadgrid_show_excerpts){
						
						$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-downloadgrid-excerpt-link">';	
							$downloadgrid_output .= mp_stacks_downloadgrid_excerpt( $grid_post_id, $word_limit, $read_more_text );
						$downloadgrid_output .= '</a>';	
					}
					
					//If we should show the price below the image
					if ( strpos( $downloadgrid_price_placement, 'below') !== false && $downloadgrid_show_prices){
						
						$downloadgrid_output .= '<a href="' . get_permalink() . '" class="mp-stacks-downloadgrid-price-link">';	
							$downloadgrid_output .= mp_stacks_downloadgrid_price( $grid_post_id );
						$downloadgrid_output .= '</a>';	
						
					}
			
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
	
	//If there are still more posts in this taxonomy
	if ( $total_posts > $post_offset && $downloadgrid_show_load_more_button ){
		$downloadgrid_output .= '<a mp_post_id="' . $post_id . '" mp_brick_offset="' . $post_offset . '" mp_stacks_downloadgrid_counter="' . $counter . '" class="button mp-stacks-downloadgrid-load-more-button">' . $downloadgrid_load_more_text . '</a>';	
	}
	
	$downloadgrid_output .= '</div>';
	
	echo $downloadgrid_output;
	
	die();
			
}
add_action( 'wp_ajax_mp_stacks_downloadgrid_load_more', 'mp_downloadgrid_ajax_load_more' );
add_action( 'wp_ajax_nopriv_mp_stacks_downloadgrid_load_more', 'mp_downloadgrid_ajax_load_more' );

/**
 * Get the HTML for the title in the grid
 *
 * @access   public
 * @since    1.0.0
 * @post_id  $post_id Int - The ID of the post to get the title of
 * @return   $html_output String - A string holding the html for a title in the grid
 */
function mp_stacks_downloadgrid_title( $post_id ){
	
	$downloadgrid_output = mp_stacks_grid_highlight_text_html( array( 
		'class_name' => 'mp-stacks-downloadgrid-item-title',
		'output_string' => get_the_title( $post_id ), 
	) );
	
	return $downloadgrid_output;
	
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
function mp_stacks_downloadgrid_excerpt( $post_id, $word_limit, $read_more_text ){
	
	//Add clear div to bump downloadgrid below title and icon
	$downloadgrid_output = '<div class="mp-stacks-downloadgrid-item-clearedfix"></div>';
	
	$the_excerpt = mp_core_get_excerpt_by_id($post_id);
	
	//Check word limit for excerpt				
	if (!empty($word_limit)){							
		//Cut the excerpt off at X number of words
		$the_excerpt = mp_core_limit_text_to_words($the_excerpt, $word_limit);
	}
	
	//If there are 0 words in this excerpt
	if (mp_core_word_count($the_excerpt) == 0 ){
		return NULL;	
	}
	else{
		
		$output_string = strip_tags($the_excerpt);
	
		//$output_string .= $read_more_text;
		
	}
	
	$downloadgrid_output .= mp_stacks_grid_highlight_text_html( array( 
		'class_name' => 'mp-stacks-downloadgrid-item-excerpt',
		'output_string' => $output_string, 
	) );
	
	return $downloadgrid_output;	

	
}

/**
 * Get the HTML for the price in the grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $post_id Int - The ID of the post to get the excerpt of
 * @return   $html_output String - A string holding the html for an excerpt in the grid
 */
function mp_stacks_downloadgrid_price( $post_id ){
	
	//Add clear div to bump downloadgrid below title and icon
	$downloadgrid_output = '<div class="mp-stacks-downloadgrid-item-clearedfix"></div>';
	
	$the_price = edd_price( $post_id, false );

	$downloadgrid_output .= mp_stacks_grid_highlight_text_html( array( 
		'class_name' => 'mp-stacks-downloadgrid-item-price',
		'output_string' => $the_price, 
	) );
	
	return $downloadgrid_output;	

	
}