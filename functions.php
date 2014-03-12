<?php session_start();
ob_start();

load_theme_textdomain('templatic');
load_textdomain( 'templatic', TEMPLATEPATH.'/language/en_US.mo' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
define('TAGKW_TEXT_COUNT',40);
//error_reporting(0);
/*** Theme setup ***/
global $blog_id;
define('TT_ADMIN_FOLDER_NAME','admin');
define('TT_ADMIN_FOLDER_PATH',TEMPLATEPATH.'/'.TT_ADMIN_FOLDER_NAME.'/'); //admin folder path
update_option('thumbnail_size_w','150'); //admin folder path
update_option('thumbnail_size_h','105'); //admin folder path
if(get_option('upload_path') && !strstr(get_option('upload_path'),'wp-content/uploads')){
	$upload_folder_path = "wp-content/blogs.dir/$blog_id/files/";
}else {
	$upload_folder_path = "wp-content/uploads/";
}

if(file_exists(TT_ADMIN_FOLDER_PATH . 'constants.php')){
	include_once(TT_ADMIN_FOLDER_PATH.'constants.php');  //ALL CONSTANTS FILE INTEGRATOR
}

add_action('init','templ_set_my_city'); 
include_once(TT_ADMIN_FOLDER_PATH.'admin_main.php');  //ALL ADMIN FILE INTEGRATOR
include_once (TT_FUNCTIONS_FOLDER_PATH . 'custom_functions.php');
if(file_exists(TEMPLATEPATH.'/language.php')){
include_once(TEMPLATEPATH.'/language.php');  //ALL CONSTANTS FILE INTEGRATOR
}

if(file_exists(TT_FUNCTIONS_FOLDER_PATH . 'custom_filters.php')){
	include_once (TT_FUNCTIONS_FOLDER_PATH . 'custom_filters.php'); // manage theme filters in the file
}

if(file_exists(TT_FUNCTIONS_FOLDER_PATH . 'image_resizer.php')){
include_once (TT_FUNCTIONS_FOLDER_PATH . 'image_resizer.php');
}

// Theme admin functions

if(file_exists(TT_FUNCTIONS_FOLDER_PATH . 'widgets.php')){
include_once (TT_FUNCTIONS_FOLDER_PATH . 'widgets.php'); // theme widgets in the file
}

if(file_exists(TT_FUNCTIONS_FOLDER_PATH . 'meta_boxes.php')){
include_once (TT_FUNCTIONS_FOLDER_PATH . 'meta_boxes.php'); // theme meta boxes in the file
}

if(file_exists(TT_WIDGET_FOLDER_PATH . 'widgets_main.php')){
include_once (TT_WIDGET_FOLDER_PATH . 'widgets_main.php'); // theme widgets in the file
}
if(file_exists(TEMPLATEPATH . '/library/rating/post_rating.php')) {
	include_once (TEMPLATEPATH . '/library/rating/post_rating.php');
}
if(file_exists(TT_FUNCTIONS_FOLDER_PATH.'listing_filters.php') && !strstr($_SERVER['REQUEST_URI'],'/wp-admin/')) {
	include_once (TT_FUNCTIONS_FOLDER_PATH.'listing_filters.php');
}
if(file_exists(TT_MODULES_FOLDER_PATH . 'modules_main.php')){
include_once (TT_MODULES_FOLDER_PATH . 'modules_main.php'); // Theme moduels include file
}

if(file_exists(TT_INCLUDES_FOLDER_PATH . 'auto_install/auto_install.php')){
include_once (TT_INCLUDES_FOLDER_PATH . 'auto_install/auto_install.php'); // sample data insert file
}


if(file_exists(TT_FUNCTIONS_FOLDER_PATH . "general_functions.php")){
require(TT_FUNCTIONS_FOLDER_PATH . "general_functions.php");
$General = new General();
global $General;
}

if(file_exists(TEMPLATEPATH . '/library/functions/mega_menu_widget.php')) {
	include_once (TEMPLATEPATH . '/library/functions/mega_menu_widget.php');
}
/* Below included file contains problem solution for taxonomy seo url BOF */
if(!file_exists(ABSPATH.'wp-content/plugins/taxonomic-seo-permalinks/taxonomic-seo-permalink.php') && !plugin_is_active('taxonomic-seo-permalinks') && file_exists(TEMPLATEPATH . '/library/functions/taxonomic-seo-permalinks/taxonomic-seo-permalink.php')) {
	include_once (TEMPLATEPATH . '/library/functions/taxonomic-seo-permalinks/taxonomic-seo-permalink.php');
}
/* Below included file contains problem solution for taxonomy seo url BOF */
add_theme_support( 'post-formats', array( 'aside', 'gallery','link', 'image','quote', 'status','video', 'audio','chat') );

if(get_option('ptthemes_alt_stylesheet') == '' || get_option('ptthemes_alt_stylesheet') == '1-default.css'){
update_option("ptthemes_alt_stylesheet",'1-default');
}
if(get_option('ptthemes_enable_claimownership') == ''){
update_option("ptthemes_enable_claimownership",'Yes');
}

?>