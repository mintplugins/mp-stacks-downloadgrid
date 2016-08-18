=== MP Stacks + DownloadGrid ===
Contributors: johnstonphilip
Donate link: http://mintplugins.com/
Tags: message bar, header
Requires at least: 3.5
Tested up to: 4.6
Stable tag: 1.0.1.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add-On Plugin for MP Stacks which shows a grid of Posts in a Brick. Set the source of posts to a category or tag, set the number of posts per row, featured image size, title and excerpt colours and sizes, or show just images, or just text - or both!

== Description ==

Extremely simple to set up - allows you to show posts on any page, at any time, anywhere on your website. Just put make a brick using “MP Stacks”, put the stack on a page, and set the brick’s Content-Type to be “PostGrid”.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the 'mp-stacks-downloadgrid’ folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Build Bricks under the “Stacks and Bricks” menu. 
4. Publish your bricks into a “Stack”.
5. Put Stacks on pages using the shortcode or the “Add Stack” button.

== Frequently Asked Questions ==

See full instructions at http://mintplugins.com/doc/mp-stacks

== Screenshots ==


== Changelog ==

= 1.0.1.9 = August 18, 2016
* Change order options to include popular, random, date, and price

= 1.0.1.8 = Feburary 18, 2016
* Added Google Font Control for Grid text options

= 1.0.1.7 = January 12, 2016
* Added EDD Check

= 1.0.1.6 = December 24, 2015
* Added mp_stacks_downloadgrid_grid_post_permalink filter.

= 1.0.1.5 = November 4, 2015
* Added Isotope "Load From Scratch" option.

= 1.0.1.4 = September 24, 2015
* Changed grid downloads_per_row to use the "mp_stacks_grid_posts_per_row_percentage" function in MP Stacks.

= 1.0.1.3 = September 19, 2015
* Added the ability to display "All Downloads" instead of a specific category if needed.
* Added link to "Manage Downloads" from the Brick Editor controls. Opens in new tab.
* Brick Metabox controls now load using ajax.
* Admin Meta Scripts now enqueued only when needed.
* Removed PHP $_SESSION for queried_object_id. It now uses a $_POST['mp_stacks_queried_object_id'] var if doing ajax and the global $wp_query->queried_object_id if not doing ajax.
* Make DownloadGrid centred by default for Brick Alignment.

= 1.0.1.2 = June 3, 2015
* Made Grid CSS Add-To Existing BRICK CSS
* Added "mp_stacks_downloadgrid_item_price" Filter to allow filtering of Prices based on Grid Post.
* Added "mp_stacks_downloadgrid_item_excerpt" Filter to allow filtering of Excerpts based on Grid Post.
* Added "mp_stacks_downloadgrid_item_title" Filter to allow filtering of Title based on Grid Post.

= 1.0.1.1 = May 12, 2015
* Set proper default for excerpt placement
* Added orderby options

= 1.0.1.0 = April 24, 2015
* Added Isotope Filtering Features.

= 1.0.0.9 = March 1, 2015
* Made defaults more accurate to coincide with efficiency changes in mp_core.
* Post Bg Controls Added
* Spacing between text items added
* Velocity JS updated to use velocity.min.js instead of jquery.velocity.min.js in MP_CORE.
* Multiple Taxonomy Terms Added. You can now choose multiple sources.
* Better line height presets set for titles and excerpts.
* Added max-width option for grid images.

= 1.0.0.8 = February 1, 2015
* Changed label for Image height to Crop Height
* Changed plugin info link in bundle utility file

= 1.0.0.7 = January 21, 2015
* Fix plugin_licensed to true for Theme Bundles
* Add the title attribute to the feat image
* Switch opacity control for title and excerpt bg to input range (was number)

= 1.0.0.6 = January 5, 2015
* Big overhaul to have better meta options, and use MP Stacks grid functions.
* This release coincides with MP Stacks 1.0.1.4  

= 1.0.0.5 = Aug 1, 2014
* Options added for animation, placement of text, text backgrounds
* Changed post grid choices to only include post categories for the sake of simplicity
* Ajax Load More button now changes to Loading.. when clicked

= 1.0.0.4 = June 8, 2014
* Mobile Sizing
* Only create metabox if current screen is mp_brick

= 1.0.0.3 = May 20, 2014
* Additional options for colours, text sizing, and post spacing
* Ajax “Load More” button added
* Move To Mint 

= 1.0.0.2 = February 20, 2014
* Utility release

= 1.0.0.1 = February 10, 2014
* Changed hook for metabox to be after taxonomies are created - so we can choose from ALL taxonomies. From 'plugins_loaded' to 'widgets_init'.

= 1.0.0.0 = February 9, 2014
* Original release
