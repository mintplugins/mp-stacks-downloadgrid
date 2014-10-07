<?php
/**
 * This file contains the enqueue scripts function for the downloadgrid plugin
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
 * Enqueue JS and CSS for downloadgrid 
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */

/**
 * Enqueue css and js
 *
 * Filter: mp_stacks_downloadgrid_css_location
 */
function mp_stacks_downloadgrid_enqueue_scripts(){
			
	//Enqueue downloadgrid CSS
	wp_enqueue_style( 'mp_stacks_downloadgrid_css', plugins_url( 'css/downloadgrid.css', dirname( __FILE__ ) ) );
	
	//Enqueue velocity JS
	wp_enqueue_script( 'velocity_js', plugins_url( 'js/jquery.velocity.min.js', dirname( __FILE__ ) ), array( 'jquery' ) );
	
	//masonry script
	wp_enqueue_script( 'masonry' );
			
	//Enqueue downloadgrid JS
	wp_enqueue_script( 'mp_stacks_downloadgrid_js', plugins_url( 'js/downloadgrid.js', dirname( __FILE__ ) ), array( 'jquery', 'velocity_js', 'masonry' ) );
	
	//Localize the downloadgrid js
	wp_localize_script( 'mp_stacks_downloadgrid_js', 'mp_stacks_downloadgrid_vars', array(
		'loading_text' =>  __('Loading...', 'mp_stacks_downloadgrid')
	)
	);

}
 
/**
 * Enqueue css face for downloadgrid
 */
add_action( 'wp_enqueue_scripts', 'mp_stacks_downloadgrid_enqueue_scripts' );

/**
 * Enqueue css and js
 *
 * Filter: mp_stacks_downloadgrid_css_location
 */
function mp_stacks_downloadgrid_admin_enqueue_scripts(){
	

}
 
/**
 * Enqueue css face for downloadgrid
 */
add_action( 'admin_enqueue_scripts', 'mp_stacks_downloadgrid_admin_enqueue_scripts' );