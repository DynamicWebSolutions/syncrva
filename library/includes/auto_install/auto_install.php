<?php
ini_set('max_execution_time', 300);
if(get_option('ptthemes_auto_install')=='No' || get_option('ptthemes_auto_install')==''){
	add_action("admin_head", "autoinstall_admin_header"); // please comment this line if you wish to DEACTIVE SAMPLE DATA INSERT.
}
function autoinstall_admin_header(){
	global $wpdb;
	if(strstr($_SERVER['REQUEST_URI'],'themes.php') && (!isset($_REQUEST['template']) && @$_REQUEST['template']=='') && (!isset($_GET['page']) && $_GET['page']=='')){
		//update_option("ptthemes_alt_stylesheet",'1-default');
		$menu_msg = "<p><b>CUSTOMIZE:</b> <a href='".site_url("/wp-admin/customize.php")."'><b>Customize your Theme Options.</b></a><br/> <b>HELP:</b> <a href='http://templatic.com/docs/geoplaces-v4-theme-guide/'> <b>Theme Documentation Guide</b></a> | <b>SUPPORT:</b><a href='http://templatic.com/forums'> <b>Community Forum</b></a></p>";
		$post_counts = $wpdb->get_var("select count(post_id) from $wpdb->postmeta where (meta_key='pt_dummy_content' || meta_key='tl_dummy_content') and meta_value=1");
		if($post_counts>0){
			$dummy_data_msg = 'Sample data has been <b>populated</b> on your site. Your sample portal website is ready, click <strong><a href='.site_url().'>here</a></strong> to see how its looks.<a href="javascript:;" id="dismiss-ajax-notification" class="templatic-dismiss" style="float:right;">Dismiss</a>'.$menu_msg.'<p> Wish to delete sample data?  <a class="button_delete button-primary" href="'.home_url().'/wp-admin/themes.php?dummy=del">Yes Delete Please!</a></p>';
		}else{
			$dummy_data_msg = 'Install sample data: Would you like to <b>auto populate</b> sample data on your site?  <a class="button_insert button-primary" href="'.home_url().'/wp-admin/themes.php?dummy_insert=1&dump=1">Yes, insert please</a><a href="javascript:;" id="dismiss-ajax-notification" class="templatic-dismiss" style="float:right;">Dismiss</a>'.$menu_msg;
		}
		if(isset($_REQUEST['dummy']) && $_REQUEST['dummy']=='del'){
			delete_dummy_data();	
			wp_safe_redirect(admin_url("themes.php"));
		}
		if(isset($_REQUEST['dummy_insert']) && $_REQUEST['dummy_insert']){
			$multicity_db_table_name = $table_prefix . "multicity";
			global $multicity_db_table_name;
			if($wpdb->get_var("SHOW TABLES LIKE \"$multicity_db_table_name\"") == $multicity_db_table_name){
				
					$insert_muticity = $wpdb->query("INSERT INTO $multicity_db_table_name (country_id,zones_id,cityname,lat,lng,scall_factor,sortorder,is_zoom_home,categories,is_default) VALUES
		('223','3713','New York','40.714321', '-74.00579', 13, 7, 'No', '', 1),
		('223','3721','Philadelphia', '39.952473', '-75.164106', 13, 1, 'Yes', '', 0),('223','3682','San Francisco', '37.774936', '-122.4194229', 13, 4, 'Yes', '', 0)");
			
					$newyork = $wpdb->get_var("select city_id from $multicity_db_table_name where cityname = 'New York'");
					$philadelphia = $wpdb->get_var("select city_id from $multicity_db_table_name where cityname = 'Philadelphia'");
					$sanfransisco = $wpdb->get_var("select city_id from $multicity_db_table_name where cityname = 'San Francisco'");
				
			}
			include_once (TT_INCLUDES_FOLDER_PATH . 'auto_install/auto_install_data.php'); // auto install data file
			wp_safe_redirect(admin_url("themes.php"));
		}
		echo '<div id="ajax-notification" class="updated templatic_autoinstall"><p> '.$dummy_data_msg.'</p><span id="ajax-notification-nonce" class="hidden">' . wp_create_nonce( 'ajax-notification-nonce' ) . '</span></div>';
	}
}
function delete_dummy_data(){
	global $wpdb,$table_prefix;
	delete_option('sidebars_widgets'); //delete widgets
	$productArray = array();
	$multicity_db_table_name = $wpdb->prefix."multicity";
	global $multicity_db_table_name;
	$wpdb->query("DELETE FROM $multicity_db_table_name WHERE `cityname` LIKE 'Philadelphia'");
	$wpdb->query("DELETE FROM $multicity_db_table_name WHERE `cityname` LIKE 'New York'");
	$wpdb->query("DELETE FROM $multicity_db_table_name WHERE `cityname` LIKE 'San Francisco'");

	$pids_sql = "select p.ID from $wpdb->posts p join $wpdb->postmeta pm on pm.post_id=p.ID where (meta_key='pt_dummy_content' || meta_key='tl_dummy_content') and meta_value=1";
	$pids_info = $wpdb->get_results($pids_sql);
	foreach($pids_info as $pids_info_obj){	
		wp_delete_post($pids_info_obj->ID,true);
	}
	wp_redirect(admin_url("themes.php"));
}


/* Setting For dismiss auto install notification message from themes.php START */
register_activation_hook( __FILE__, 'activate'  );
register_deactivation_hook( __FILE__, 'deactivate'  );
add_action( 'admin_enqueue_scripts', 'register_admin_scripts'  );
add_action( 'wp_ajax_hide_admin_notification', 'hide_admin_notification' );
function activate() {
	add_option( 'ptthemes_auto_install', 'No' );
}
function deactivate() {
	delete_option( 'ptthemes_auto_install' );
}
function register_admin_scripts() {
	wp_register_script( 'ajax-notification-admin', get_stylesheet_directory_uri().'/js/admin_notification.js'  );
	wp_enqueue_script( 'ajax-notification-admin' );
}
function hide_admin_notification() {
	if( wp_verify_nonce( $_REQUEST['nonce'], 'ajax-notification-nonce' ) ) {
		if( update_option( 'ptthemes_auto_install', 'Yes' ) ) {
			die( '1' );
		} else {
			die( '0' );
		}
	}
}
/* Setting For dismiss auto install notification message from themes.php END */


//Alert warning message when user goes to delete data: start


add_action('admin_footer','delete_sample_data');
if(!function_exists('delete_sample_data')){
function delete_sample_data()
{
?>
<script type="text/javascript">
jQuery(document).ready( function(){
	jQuery('.button_delete').click( function() {
		if(confirm('All the sample data and your modifications done with it, will be deleted forever! Still you want to proceed?')){
			window.location = "<?php echo home_url()?>/wp-admin/themes.php?dummy=del";
		}else{
			return false;
		}	
	});
});
</script>
<?php } }?>