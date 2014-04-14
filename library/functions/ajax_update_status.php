<?php
	$file = dirname(__FILE__);
	$file = substr($file,0,stripos($file, "wp-content"));
	require($file . "/wp-load.php");
	if($_REQUEST['post_id'] !=""){
	$my_post['ID'] = $_REQUEST['post_id'];
	$my_post['post_status'] = 'publish';
	wp_update_post( $my_post );
	echo "<span style='color:green;'>".PUBLISHED_TEXT."</span>";
	}
	if($_REQUEST['trans_id'] !=""){
	global $wpdb,$transection_db_table_name;
	$tid = $_REQUEST['trans_id'];
	$trans_status = $wpdb->query("update $transection_db_table_name SET status = 1 where trans_id = '".$tid."'");
	echo "<span style='color:green; font-weight:normal;'>Approved</span>";
	$posts = $wpdb->get_row("select post_id,user_id from $transection_db_table_name where trans_id = '".$tid."'");
			$last_postid = $posts->post_id;
			$post = get_post($posts->post_id);
			$uinfo = get_userdata($posts->user_id);
			$user_fname = $uinfo->display_name;
			$user_email = $uinfo->user_email;
			$fromEmail = get_site_emailId();
			$fromEmailName = get_site_emailName();
			$store_name = get_option('blogname');
			$email_content = '';
			$email_subject = '';
			
			$email_content_user = '';
			$email_subject_user ='';
			
			if(!$email_subject)
			{
				$email_subject = __('New listing of ID:#'.$last_postid);	
			}
			if(!$email_content)
			{
				$email_content = __('<p>Dear [#to_name#],</p>
				<p>'.__('Here is the information about the '.$post->post_type,'templatic').':</p>
				[#information_details#]
				<br>
				<p>[#site_name#]</p>');
			}
			
			if(!$email_subject_user)
			{
				$email_subject_user = __(sprintf($post->post_type.' listing of ID:#%s',$last_postid));	
			}
			if(!$email_content_user)
			{
				$email_content_user = __('<p>Dear [#to_name#],</p>[#information_details#]<br><p>[#site_name#]</p>');
			}
			
			$information_details = "<p>".__('ID','templatic')." : ".$last_postid."</p>";
			$information_details .= '<p>'.__('Your following '.$post->post_type.' has been approved.View more detail from','templatic').' <a href="'.get_permalink($last_postid).'">'.$post->post_title.'</a></p>';
			
			$search_array = array('[#to_name#]','[#information_details#]','[#site_name#]');
			$replace_array_admin = array($fromEmail,$information_details,$store_name);
			$replace_array_client =  array($user_email,$information_details,$store_name);
			$email_content_admin = str_replace($search_array,$replace_array_admin,$email_content);
			$email_content_client = str_replace($search_array,$replace_array_client,$email_content_user);

				templ_sendEmail($user_email,$user_fname,$fromEmail,$fromEmailName,$email_subject,$email_content_admin,$extra='');///To admin email
				templ_sendEmail($fromEmail,$fromEmailName,$user_email,$user_fname,$email_subject_user,$email_content_client,$extra='');//to client email
	}
?>