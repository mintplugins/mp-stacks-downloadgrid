<?php
/**
 * This file contains the enqueue scripts function for the downloadgrid plugin
 *
 * @since 1.0.0
 *
 * @package    MP Stacks + DownloadGrid
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Make DownloadGrid Content Type Centered by default
 *
 * @access   public
 * @since    1.0.0
 * @param    $centered_content_types array - An array containing a string for each content-type that should default to centered brick alignment.
 * @param    $centered_content_types array - An array containing a string for each content-type that should default to centered brick alignment.
 */
function mp_stacks_downloadgrid_centered_by_default( $centered_content_types ){
	
	$centered_content_types['downloadgrid'] = 'downloadgrid';
	
	return $centered_content_types;
	
}
add_filter( 'mp_stacks_centered_content_types', 'mp_stacks_downloadgrid_centered_by_default' );