<?php 
global $current_user;
if(get_option('set_permission') == '') {
 set_option_selling('set_permission','administrator');
}
function get_current_user_role() {
	global $wp_roles,$current_user;
	if(isset($current_user) && $current_user != ""){
	$roles = $current_user->roles;
	if(count($roles) > 1){
		$role = array_shift($roles);
		return isset($wp_roles->role_names[$role]) ? translate_user_role($wp_roles->role_names[$role] ) : false;
	}else {
		if($roles){
		return $roles[0]; }
	}
	}
}
function restrict_admin(){
	$user_role = strtolower(get_current_user_role());
	$permission = explode(',',get_option('set_permission'));
	if(in_array($user_role,$permission) && get_option('set_permission') != '') {
		echo __('You are not allowed to access this part of the site','templatic')."<a href=".site_url().">".__('Dasgboard','templatic')."</a>";
		exit;
	}
}
if(strtolower(get_current_user_role()) != 'administrator' ){
	add_action( 'admin_init','restrict_admin', 1 );
}
if(get_option('currency_symbol') == '') {
 set_option_selling('currency_symbol','USD');
}
if(get_option('set_permission') == '') {
 set_option_selling('set_permission','administrator');
}
function fetch_currency($currency_code='USD',$field = ''){
	global $wpdb;
	if($field == ''){
		$field = 'currency_symbol';
	} else {
		$field = $field;
	}
	$currency_table = $wpdb->prefix . "currency";
	$currency_sql = $wpdb->get_row("select $field from $currency_table where currency_code = '".$currency_code."'");
	$currency_res = $currency_sql->$field;
	return $currency_res;
} function display_amount_with_currency($amount,$currency = ''){
	$amt_display = '';
	if($amount != ""){
	$currency = fetch_currency(get_option('currency_symbol'),'currency_symbol');
	$position = fetch_currency(get_option('currency_symbol'),'symbol_position');
	if($position == '1'){
		$amt_display = $currency.$amount;
	} else if($position == '2'){
		$amt_display = $currency.' '.$amount;
	} else if($position == '3'){
		$amt_display = $amount.$currency;
	} else {
		$amt_display = $amount.' '.$currency;
	}
	return $amt_display;
	}
}

function is_valid_coupon($coupon)
{
	global $wpdb;
	$couponsql = "select option_value from $wpdb->options where option_name='discount_coupons'";
	$couponinfo = $wpdb->get_results($couponsql);
	if($couponinfo)
	{
		foreach($couponinfo as $couponinfoObj)
		{
			$option_value = json_decode($couponinfoObj->option_value);
			foreach($option_value as $key=>$value)
			{
				if($value->couponcode == $coupon)
				{
					return true;
				}
			}
		}
	}
	return false;
}
function sendEmail($fromEmail,$fromEmailName,$toEmail,$toEmailName,$subject,$message,$extra=''){
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	// Additional headers
	$headers .= 'To: '.$toEmailName.' <'.$toEmail.'>' . "\r\n";
	$headers .= 'From: '.$fromEmailName.' <'.$fromEmail.'>' . "\r\n";
	wp_mail( $toEmail, $subject, $message, $headers );
}
///////REGISTRATION EMAIL FUNCTION START//////
function registration_email($user_id)
{
		$user_info = get_userdata($user_id);
		$user_login = $user_info->user_login;
		$user_pass = get_user_meta($user_id,'userpassword',true);	
		$activation_key = get_user_meta($user_id,'activation_key',true);	
		$subject = stripslashes(get_option('registration_success_email_subject'));
		$client_message = stripslashes(get_option('registration_success_email_content'));
		global $site_url;	
		if(strstr($site_url,'?') ){ $op= "&"; }else{ $op= "?"; } 
		if($subject=="" && $client_message=="")
		{
			registration_email($user_id);
			$subject = __('Registration Email','templatic');
			$client_message = '<p>'.__('Dear','templatic').' [#$user_name#],</p>
			<p>'.__('Your login information:','templatic').'</p>
			<p>'.__('Username:','templatic').' [#$user_login#]</p>
			<p>'.__('Password:','templatic').' [#$user_password#]</p>
			<p>'.__('You can login from','templatic').' [#$store_login_url#] '.__('or the URL is','templatic').' : [#$store_login_url_link#].</p>
			<p>'.__('We hope you enjoy. Thanks!','templatic').'</p>
			<p>[#$store_name#]</p>';
			$subject = $subject;
			$filecontent_arr2 = $client_message;
			$subject = $filecontent_arr2;
			if($subject == '')
			{
				$subject = __("Registration Email",'templatic');
			}
			
			$client_message = $filecontent_arr2[1];
		}
		$store_login_link = '<a href="'.$site_url.$op.'ptype=login&akey='.$activation_key.'&uid='.$user_id.'">'.$site_url.$op.'ptype=login&akey='.$activation_key.'&uid='.$user_id.'</a>';
		$store_login = sprintf(__('<a href="'.home_url().'/?ptype=login&akey='.$activation_key.'&uid='.$user_id.'">'.__('Click Login','templatic').'</a>','templatic'));
	
		/////////////customer email//////////////
		$search_array = array('[#user_name#]','[#user_login#]','[#user_password#]','[#site_name#]','[#site_login_url#]','[#site_login_url_link#]');
		$replace_array = array($user_login,$user_login,$user_pass,$store_name,$store_login,$store_login_link);
		$client_message = str_replace($search_array,$replace_array,$client_message);
		@mail($fromEmail,$fromEmailName,$user_email,$userName,$subject,$client_message,$extra='');///To clidne email
		wp_redirect($site_url."/");
}
//////REGISTRATION EMAIL FUNCTION END////////
/* set option BOF*/
function set_option_selling($option_name,$option_value){
	global $wpdb;
	$option_sql = "select option_value from $wpdb->options where option_name='$option_name'";
	$option_info = $wpdb->get_results($option_sql);
	if($option_info)	{
		update_option($option_name,$option_value);
	} else {
		$insertoption = "insert into $wpdb->options (option_name,option_value) values ('$option_name','$option_value')";
		$wpdb->query($insertoption);
	}
}
/* set option EOF*/

function fecth_country_name($country_id){
	global $country_table;
	$fecth_country_sql = mysql_query("select country_id,country_name from $country_table where country_id = '".$country_id."'");
	$fetch_country_res = mysql_fetch_array($fecth_country_sql);
	return $fetch_country_res['country_name'];
} 
function fecth_zone_name($zones_id){
	global $zones_table;
	$fetch_zones_sql = mysql_query("select zones_id,zone_name from $zones_table where zones_id = '".$zones_id."'");
	$fetch_zones_res = mysql_fetch_array($fetch_zones_sql);
	return $fetch_zones_res['zone_name'];
}
function get_transaction_status($tid){
	global $wpdb,$transection_db_table_name;
	$trans_status = $wpdb->get_var("select status from $transection_db_table_name where trans_id = '".$tid."'");
	echo "<div id='p_status_".$tid."'>";
	if($trans_status == 0){
		echo '<a style="color:#E66F00; font-weight:normal;" onclick="change_transstatus('.$tid.')" href="javascript:void(0);">Pending</a>';
	}else if($trans_status == 1){
		echo '<span style="color:green; font-weight:normal;">Approved</span>';
	}else{
		echo '<span style="color:red">Canceled</span>';
	}
	echo "</div>";	
}

function get_pagination_of($targetpage,$total_pages,$limit=10,$page=0,$extra_url = '')
		{
			/* Setup page vars for display. */
			if ($page == 0) $page = 1;					//if no page var is given, default to 1.
			$prev = $page - 1;							//previous page is page - 1
			$next = $page + 1;							//next page is page + 1
			$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
			$lpm1 = $lastpage - 1;						//last page minus 1
			
			if(strstr($targetpage,'?'))
			{
				$querystr = "&amp;pagination";
			}else
			{
				$querystr = "?pagination";
			}
			$pagination = "";
			if($lastpage > 1)
			{	
				$pagination .= "<div class=\"pagination\">";
				//previous button
				if ($page > 1) 
					$pagination.= '<a href="'.$targetpage.$querystr.'='.$prev.$extra_url.'">&laquo; previous</a>';
				else
					$pagination.= "<span class=\"disabled\">&laquo; previous</span>";	
				
				//pages	
				if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
				{	
					for ($counter = 1; $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= '<a href="'.$targetpage.$querystr.'='.$counter.$extra_url.'">'.$counter.'</a>';					
					}
				}
				elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
				{
					//close to beginning; only hide later pages
					if($page < 1 + ($adjacents * 2))		
					{
						for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else
								$pagination.= '<a href="'.$targetpage.$querystr.'='.$counter.$extra_url.'">'.$counter.'</a>';					
						}
						$pagination.= "...";
						$pagination.= '<a href="'.$targetpage.$querystr.'='.$lpm1.$extra_url.'">'.$lpm1.'</a>';
						$pagination.= '<a href="'.$targetpage.$querystr.'='.$lastpage.$extra_url.'">'.$lastpage.'</a>';		
					}
					//in middle; hide some front and some back
					elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
					{
						$pagination.= '<a href="'.$targetpage.$querystr.'=1'.$extra_url.'">1</a>';
						$pagination.= '<a href="'.$targetpage.$querystr.'=2'.$extra_url.'">2</a>';
						$pagination.= "...";
						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else
								$pagination.= '<a href='.$targetpage.$querystr.'='.$counter.$extra_url.'">'.$counter.'</a>';					
						}
						$pagination.= "...";
						$pagination.= '<a href="'.$targetpage.$querystr.'='.$lpm1.$extra_url.'">'.$lpm1.'</a>';
						$pagination.= '<a href="'.$targetpage.$querystr.'='.$lastpage.$extra_url.'">'.$lastpage.'</a>';		
					}
					//close to end; only hide early pages
					else
					{
						$pagination.= '<a href="'.$targetpage.$querystr.'=1'.$extra_url.'">1</a>';
						$pagination.= '<a href="'.$targetpage.$querystr.'=2'.$extra_url.'">2</a>';
						$pagination.= "...";
						for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else
								$pagination.= '<a href="'.$targetpage.$querystr.'='.$counter.$extra_url.'">'.$counter.'</a>';					
						}
					}
				}
				
				//next button
				if ($page < $counter - 1) 
					$pagination.= '<a href="'.$targetpage.$querystr.'='.$next.$extra_url.'">next &raquo;</a>';
				else
					$pagination.= "<span class=\"disabled\">next &raquo;</span>";
				$pagination.= "</div>\n";		
			}
			return $pagination;
		}

?>