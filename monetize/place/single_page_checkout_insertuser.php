<?php
global $site_url;	
if($_POST)
{
	if ($_SESSION['place_info']['user_email'] == '' )	{
		$_SESSION['userinset_error'] = array();
		$_SESSION['userinset_error'][] = __('Email for Contact Details is Empty. Please enter Email, your all informations will sent to your Email.','templatic');
		wp_redirect($site_url.'/?ptype=post_listing&backandedit=1&usererror=1');
		exit;
	}
	
	require( 'wp-load.php' );
	require(ABSPATH.'wp-includes/registration.php');
	
	global $wpdb;
	$errors = new WP_Error();
	
	$user_email = $_SESSION['place_info']['user_email'];
	$user_fname = $_SESSION['place_info']['user_fname'];
	$user_login = $user_fname;	
	$user_login = sanitize_user( $user_login );
	$user_email = apply_filters( 'user_registration_email', $user_email );
	
	// Check the username
	if ( $user_login == '' )
		$errors->add('empty_username', __('ERROR: Please enter a username.','templatic'));
	elseif ( !validate_username( $user_login ) ) {
		$errors->add('invalid_username', __('<strong>ERROR</strong>: This username is invalid.  Please enter a valid username.','templatic'));
		$user_login = '';
	} elseif ( username_exists( $user_login ) )
		$errors->add('username_exists', __('<strong>ERROR</strong>: '.$user_email.' This username is already registered, please choose another one.','templatic'));

	// Check the e-mail address
	if ($user_email == '') {
		$errors->add('empty_email', __('<strong>ERROR</strong>: Please type your e-mail address.','templatic'));
	} elseif ( !is_email( $user_email ) ) {
		$errors->add('invalid_email', __('<strong>ERROR</strong>: The email address isn&#8217;t correct.','templatic'));
		$user_email = '';
	} elseif ( email_exists( $user_email ) )
		$errors->add('email_exists', __('<strong>ERROR</strong>: '.$user_email.' This email is already registered, please choose another one.','templatic'));

	do_action('register_post', $user_login, $user_email, $errors);	
	
	$errors = apply_filters( 'registration_errors', $errors );
	if($errors)
	{
		$_SESSION['userinset_error'] = array();
		foreach($errors as $errorsObj)
		{
			foreach($errorsObj as $key=>$val)
			{
				for($i=0;$i<count($val);$i++)
				{
					$_SESSION['userinset_error'][] = $val[$i];
					if($val[$i]){break;}
				}
			} 
		}
	}	
	if ( $errors->get_error_code() )
	{
		wp_redirect($site_url.'/?ptype=post_listing&backandedit=1&usererror=1');
		exit;
	}
		
	$user_pass = wp_generate_password(12,false);
	$user_id = wp_create_user( $user_login, $user_pass, $user_email );
	
	if ( !$user_id ) {
		//$errors->add('registerfail', sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !'), get_option('admin_email')));
		$_SESSION['userinset_error'][] = sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !'), get_option('admin_email'));
		wp_redirect($site_url.'/?ptype=post_listing&backandedit=1&usererror=1');
		exit;
	}
	
	$user_fname = $_SESSION['place_info']['user_fname'];
	$user_phone = $_SESSION['place_info']['user_phone'];
	$user_twitter = $_SESSION['place_info']['user_twitter'];
	$user_facebook = $_SESSION['place_info']['user_facebook'];
	$userName = $_SESSION['place_info']['user_fname'];
	$user_nicename = strtolower(str_replace(array("'",'"',"?",".","!","@","#","$","%","^","&","*","(",")","-","+","+"," "),array('','','','-','','-','-','','','','','','','','','','-','-',''),$user_login));
	$user_nicename = get_user_nice_name($user_fname,''); //generate nice name
		
	$user_address_info = array(
							"user_phone" 	=> $user_phone,
							"user_twitter"	=> $user_twitter,	
							"user_facebook"	=> $user_facebook,	
							"first_name"	=>	$_SESSION['place_info']['user_fname'],					
							);
		foreach($user_address_info as $key=>$val)
		{
			update_usermeta($user_id, $key, $val); // User Address Information Here
		}
		$updateUsersql = "update $wpdb->users set user_nicename=\"$user_nicename\" , display_name=\"$user_fname\"  where ID=\"$user_id\"";
		$wpdb->query($updateUsersql);
	
	//wp_new_user_notification($user_id, $user_pass);
	if ( $user_id) 
	{
		//wp_new_user_notification($user_id, $user_pass);
		///////REGISTRATION EMAIL START//////
		global $site_url;
		if(strstr($site_url,'?') ){ $op= "&"; }else{ $op= "?"; }
		$fromEmail = get_site_emailId();
		$fromEmailName = get_site_emailName();
		$store_name = get_option('blogname');
		$subject = stripslashes(get_option('registration_success_email_subject'));
		$client_message = stripslashes(get_option('registration_success_email_content'));
		if($client_message ==''){
		$subject = __('Registration Email','templatic');
		$client_message = __('Registration Email<p>Dear [#user_name#],</p>
			<p>Your login information:</p>
			<p>Username: [#user_login#]</p>
			<p>Password: [#user_password#]</p>
			<p>You can login from [#site_login_url#] or</p><p> the URL is : [#site_login_url_link#].</p>
			<p>We hope you enjoy. Thanks!</p>
			<p>[#site_name#]</p>','templatic');
	
		
		$subject = $subject;
		
		$client_message = $client_message;
		}
		if($subject == '')
		{
			$subject = __("Registration Email","templatic");
		}
		$store_login = '<a href="'.$site_url.$op.'ptype=login">'.__('Click Login','templatic').'</a>';
		$store_login_link = $site_url.$op.'ptype=login';
		/////////////customer email//////////////
		$search_array = array('[#user_name#]','[#user_login#]','[#user_password#]','[#site_name#]','[#site_login_url#]','[#site_login_url_link#]');
		$replace_array = array($user_fname ,$user_login,$user_pass,$store_name,$store_login,$store_login_link);
		$client_message = str_replace($search_array,$replace_array,$client_message);	
		$client_message = stripslashes($client_message);
		templ_sendEmail($fromEmail,$fromEmailName,$user_email,$userName,$subject,$client_message,$extra='');///To clidne email
		//////REGISTRATION EMAIL END////////
	}
	$current_user_id = $user_id;
}
?>