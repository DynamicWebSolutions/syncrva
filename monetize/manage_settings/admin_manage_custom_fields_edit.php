<?php
global $wpdb,$custom_post_meta_db_table_name,$current_user;
$field_id = $_REQUEST['field_id'];
$post_meta_info = $wpdb->get_results("select * from $custom_post_meta_db_table_name where cid= \"$field_id\"");
if($post_meta_info){
	$post_val = $post_meta_info[0];
}else
{
	$post_val->sort_order = $wpdb->get_var("select max(sort_order)+1 from  $custom_post_meta_db_table_name");
}
if($_POST)
{
	$context = get_option('blogname');
	$ctype = $_POST['ctype'];
	$admin_title = $_POST['admin_title'];
	if($ctype == 'head')
	  {
		 $admin_title = $_POST['site_title'];
	  }
	if($_POST['category'] !=''){
	$cat_array1 = implode(",",$_POST['category']) ; }
	$field_cat = $cat_array1 ;
	if($_POST['selectall'] !=''){
		$field_cat = $cat_array1.",all" ;	
	}
	if(function_exists('icl_register_string')){
		$name = $_REQUEST['site_title'];
		$value = $_REQUEST['site_title'];
		icl_register_string($context,$name,$value);
		$site_title = $_POST['site_title'];
		
	}else{
		$site_title = $_POST['site_title'];
	}
	
	if(function_exists('icl_register_string')){
		$name = $_REQUEST['admin_desc'];
		$value = $_REQUEST['admin_desc'];
		icl_register_string($context,$name,$value);
		$admin_desc = $_POST['admin_desc'];
		
	}else{
		$admin_desc = $_POST['admin_desc'];
	}	
	if(function_exists('icl_register_string')){
		$name = $_REQUEST['field_require_desc'];
		$value = $_REQUEST['field_require_desc'];
		icl_register_string($context,$name,$value);
		$field_require_desc = $_POST['field_require_desc'];
		$field_require_desc = stripslashes($field_require_desc);
		
	}else{
		$field_require_desc = $_POST['field_require_desc'];
		$field_require_desc = stripslashes($field_require_desc);
	}	
	$htmlvar_name = $_POST['htmlvar_name'];

	$clabels = $_POST['clabels'];
	$default_value = $_POST['default_value'];
	$sort_order = $_POST['sort_order'];
	$is_active = $_POST['is_active'];
	$show_on_listing = $_POST['show_on_listing'];
	$show_on_detail = $_POST['show_on_detail'];
	$show_on_page = $_POST['show_on_page'];
	$is_delete = $_POST['is_delete'];
	$is_edit = $_POST['is_edit'];
	$is_search = $_POST['is_search'];
	$ptype = explode(',',$_POST['post_type']);
	$my_post_type = $ptype[0];
	$option_values = $_POST['option_values'];
	$is_require = $_POST['is_require'];
	$extra_parameter = $_POST['extra_parameter'];
	$validation_type = $_POST['validation_type'];
	
	/* for transaltion */
	$opt_val = explode(',',$option_values);
	for($o=0 ; $o <= count($opt_val) ; $o++){
		if(function_exists('icl_register_string')){
			icl_register_string($context,$opt_val[$o],$opt_val[$o]);
		}
	}
	$style_class = $_POST['style_class'];
	$custom_field_check = $wpdb->get_var("SHOW COLUMNS FROM $custom_post_meta_db_table_name LIKE 'field_category'");
	if('field_category' != $custom_field_check)	{
		$custom_dbuser_table_alter = $wpdb->query("ALTER TABLE $custom_post_meta_db_table_name  ADD `field_category` TEXT NOT NULL AFTER `admin_title`");
	}
	$custom_editfield_check = $wpdb->get_var("SHOW COLUMNS FROM $custom_post_meta_db_table_name LIKE 'is_edit'");
	if('is_edit' != $custom_editfield_check)	{
		$custom_editdbuser_table_alter = $wpdb->query("ALTER TABLE $custom_post_meta_db_table_name  ADD `is_edit` tinyint(4) NOT NULL AFTER `is_delete`");
	}
	$custom_editfield_check = $wpdb->get_var("SHOW COLUMNS FROM $custom_post_meta_db_table_name LIKE 'is_search'");
	if('is_search' != $custom_editfield_check)	{
		$custom_editdbuser_table_alter = $wpdb->query("ALTER TABLE $custom_post_meta_db_table_name  ADD `is_search` tinyint(2) NOT NULL AFTER `is_require`");
	}if('show_on_page' != $custom_onpagefield_check)	{
		$custom_onpagefield_check = $wpdb->query("ALTER TABLE $custom_post_meta_db_table_name  ADD `show_on_page` char(10) NOT NULL AFTER `is_require`");
	}
		
	if($_REQUEST['field_id'])
	{
		$field_id = $_REQUEST['field_id'];
		$sql = "update $custom_post_meta_db_table_name set admin_title=\"$admin_title\",field_category= \"$field_cat\",post_type=\"$my_post_type\",site_title=\"$site_title\" ,ctype=\"$ctype\" ,htmlvar_name=\"$htmlvar_name\",admin_desc=\"$admin_desc\" ,clabels=\"$clabels\" ,default_value=\"$default_value\" ,sort_order=\"$sort_order\",is_active=\"$is_active\",is_require=\"$is_require\",is_delete=\"$is_delete\",is_edit=\"$is_edit\",is_search=\"$is_search\",show_on_listing=\"$show_on_listing\",show_on_page=\"$show_on_page\",show_on_detail=\"$show_on_detail\", option_values=\"$option_values\",extra_parameter = '".$extra_parameter."',style_class = '".$style_class."',validation_type = '".$validation_type."',field_require_desc = '".addslashes($field_require_desc)."' where cid=\"$field_id\"";
		$wpdb->query($sql);
		$msgtype = 'edit';

	}else
	{
		$sql = "insert into $custom_post_meta_db_table_name (admin_title,field_category,post_type,site_title,ctype,htmlvar_name,admin_desc,clabels,default_value,sort_order,is_active,is_delete,is_edit,is_require,is_search,show_on_listing,show_on_detail,show_on_page,option_values,field_require_desc,style_class,extra_parameter,validation_type) values ('".$admin_title."','".$field_cat."','".$my_post_type."','".$site_title."','".$ctype."','".$htmlvar_name."','".$admin_desc."','".$clabels."','".$default_value."','".$sort_order."','".$is_active."','".$is_delete."','".$is_edit."','".$is_require."','".$is_search."','".$show_on_listing."','".$show_on_detail."','".$show_on_page."','".$option_values."','".addslashes($field_require_desc)."','".$style_class."','".$extra_parameter."','".$validation_type."')";
		$wpdb->query($sql);
		$field_id = $wpdb->get_var("select max(cid) from $custom_post_meta_db_table_name");
		$msgtype = 'add';		
	}
	
	$location = home_url().'/wp-admin/admin.php';
	echo '<form action="'.$location.'#option_display_custom_fields" method="get" id="frm_edit_custom_fields" name="frm_edit_custom_fields">
	<input type="hidden" value="manage_settings" name="page"><input type="hidden" value="success" name="custom_field_msg"><input type="hidden" value="'.$msgtype.'" name="custom_msg_type">
	</form>
	<script type="text/javascript">document.frm_edit_custom_fields.submit();</script>
	';exit;
}
?>
<!-- Function to fetch categories -->
<script type="text/javascript">
function showcat(str)
{  	
	if (str=="")
	  {
	  document.getElementById("field_category").innerHTML="";
	  return;
	  }else{
	  document.getElementById("field_category").innerHTML="";
	  document.getElementById("process").style.display ="block";
	  }
		if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
		else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
		xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("process").style.display ="none";
		document.getElementById("field_category").innerHTML=xmlhttp.responseText;
		}
	  } 
	  url = "<?php echo get_template_directory_uri(); ?>/monetize/manage_settings/ajax_custom_taxonomy.php?post_type="+str+"&mod=custom_fields";
	  xmlhttp.open("GET",url,true);
	  xmlhttp.send();
} 
function get_html_var(){
	var front_title = document.getElementById('site_title').value;
	if (window.XMLHttpRequest)  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	} else  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById('htmlvar_name').value = xmlhttp.responseText;
		}
	} 
	url = "<?php echo get_template_directory_uri(); ?>/monetize/manage_settings/ajax_set_html_var.php?front_title="+front_title
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}
</script>
<form action="<?php echo home_url();?>/wp-admin/admin.php?page=manage_settings&mod=custom_fields&act=addedit#option_display_custom_fields" method="post" name="custom_fields_frm" onsubmit="return chk_field_form();">
<input type="submit" name="submit" value="<?php _e('Save all changes','templatic');?>" class="button-framework-imp right position_top">
<h4><?php if($_REQUEST['field_id']){  _e('Edit - '.$post_val->admin_title,'templatic'); 
	$custom_msg = 'Here you can edit custom field detail.'; }else { _e('Add custom field','templatic'); $custom_msg = 'Here you can add a new custom field. Specify all details to ensure the custom field works as it should.';}?> 
    
    <a href="<?php echo home_url();?>/wp-admin/admin.php?page=manage_settings#option_display_custom_fields" name="btnviewlisting" class="l_back" title="<?php _e('Back to manage custom field list','templatic');?>"/><?php _e('&laquo; Back to manage custom field list','templatic'); ?></a>
    </h4>
    
    <p class="notes_spec"><?php _e($custom_msg,'templatic');?></p>

<input type="hidden" name="save" value="1" /> 
<?php if($_REQUEST['field_id']){?>
<input type="hidden" name="field_id" value="<?php echo $_REQUEST['field_id'];?>" />
<?php }?>

<div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Assigned Post Type: ','templatic');?></h3>
    <div class="section">
      <div class="element">
	  <?php
				$custom_post_types_args = array();  
                $custom_post_types = get_post_types($custom_post_types_args,'objects');   
				//print_r($custom_post_types);
	
	  ?>
                 <select name="post_type" id="post_type"  onChange="showcat(this.value)">
				  <?php
					foreach ($custom_post_types as $content_type) {
                    if($content_type->name!='nav_menu_item' && $content_type->name!='attachment' && $content_type->name!='revision' && $content_type->name!='page' && $content_type->name!='post'){
                  ?>
                  	<option value="<?php echo $content_type->name.",".$content_type->taxonomies[0].",".$post_val->field_category; ?>" <?php if($post_val->post_type==$content_type->name){ echo 'selected=selected';}?>><?php echo $content_type->label;?></option>
                 <?php }}?>
				<option value="both"<?php if($post_val->post_type=='both') { echo 'selected="selected"'; }else{ if(!$post_val->post_type){ echo 'selected=selected'; } } ?>><?php _e('Both','templatic'); ?></option>
                  </select>
      	   </div>
      <div class="description"><?php _e('A field assigned to show for places for example will only appear on the submission form when a user is submitting a place and not an event.','templatic');?></div>
    </div>
  </div> <!-- #end -->
    
  
  <div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Field categories: ','templatic');?></h3>
    <div class="section">
      <div class="element" id="field_category" style="height:120px;overflow-y: scroll; margin-bottom:5px;">
	     <?php 
		$pctype = $post_val->post_type;
		if($pctype == "both")
		{
			//get_wp_category_checklist('',$post_val->field_category,'','select_all'); 
			get_wp_category_checklist(CUSTOM_CATEGORY_TYPE1,$post_val->field_category,'','select_all'); 
			get_wp_category_checklist(CUSTOM_CATEGORY_TYPE2,$post_val->field_category,'','select_all');
		}elseif($pctype == CUSTOM_POST_TYPE1){
			get_wp_category_checklist(CUSTOM_CATEGORY_TYPE1,$post_val->field_category,'','select_all'); 
		}elseif($pctype == CUSTOM_POST_TYPE2){
			get_wp_category_checklist(CUSTOM_CATEGORY_TYPE2,$post_val->field_category,'','select_all');
		}else{ 
			get_wp_category_checklist(CUSTOM_CATEGORY_TYPE1,'','','select_all'); 
			get_wp_category_checklist(CUSTOM_CATEGORY_TYPE2,'');
		}
		?>  
      </div>
	  <span id='process' style='display:none;'><img src="<?php echo get_template_directory_uri()."/images/process.gif"; ?>" alt='Processing..' /></span>
      <div class="description"><?php _e('Select the categories for which this field should appear in on the place or event submission form.','templatic');?></div>
    </div>
  </div> <!-- #end -->
  
  
  <div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Field type: ','templatic');?></h3>
    <div class="section">
      <div class="element">
                   <select name="ctype" id="ctype" onchange="show_option_add(this.value)" >
                      <option value="text" <?php if($post_val->ctype=='text'){ echo 'selected="selected"';}?>><?php _e('Text','templatic');?></option>
                      <option value="date" <?php if($post_val->ctype=='date'){ echo 'selected="selected"';}?>><?php _e('Date Picker','templatic');?></option>
                      <option value="multicheckbox" <?php if($post_val->ctype=='multicheckbox'){ echo 'selected="selected"';}?>><?php _e('Multi Checkbox','templatic');?></option>
                      <option value="radio" <?php if($post_val->ctype=='radio'){ echo 'selected="selected"';}?>><?php _e('Radio','templatic');?></option>
                      <option value="select" <?php if($post_val->ctype=='select'){ echo 'selected="selected"';}?>><?php _e('Select','templatic');?></option>
                      <option value="texteditor" <?php if($post_val->ctype=='texteditor'){ echo 'selected="selected"';}?>><?php _e('Text Editor','templatic');?></option>
                      <option value="textarea" <?php if($post_val->ctype=='textarea'){ echo 'selected="selected"';}?>><?php _e('Textarea','templatic');?></option>
                      <?php if($post_val->ctype=='image_uploader') { ?>
                      <option value="image_uploader" <?php if($post_val->ctype=='image_uploader'){ echo 'selected="selected"';}?>><?php _e('Multy image uploader','templatic');?></option>
                      <?php } ?>
                      <option value="geo_map" <?php if($post_val->ctype=='geo_map'){ echo 'selected="selected"';}?>><?php _e('Geo Map','templatic');?></option>
                  </select>
      	   </div>
    </div>
  </div> <!-- #end -->


	<div class="option option-select" id="ctype_option_tr_id"  <?php if($post_val->ctype=='select' && $post_val->is_edit == '0'){?> style="display:block;" <?php }else{?> style="display:none;" <?php }?> >
    <h3><?php _e('Option values: ','templatic');?></h3>
		<div class="section">
		  <div class="element"><input type="text" name="option_values" id="option_values" value="<?php echo $post_val->option_values;?>" size="50"  /></div>
		  <div class="description"><?php _e('Seperate multiple option values with a comma. eg. Yes, No','templatic');?></div>
		</div>
	</div> <!-- #end -->
  
  
  <div class="option option-select" id="admin_title_id"  >
    <h3><?php _e('Title : ','templatic');?></h3>
    <div class="section">
      <div class="element"><input type="text" name="admin_title" id="admin_title" value="<?php echo $post_val->admin_title;?>" size="50" /></div>
      <div class="description"><?php _e('Enter a title to display in the admin manage custom fields table.','templatic');?></div>
    </div>
  </div> <!-- #end -->
  
  
  <div class="option option-select" >
    <h3><?php _e('Field name (Front-end): ','templatic');?></h3>
    <div class="section">
      <div class="element">
                   <input type="text" name="site_title" id="site_title" value="<?php echo $post_val->site_title;?>" size="50"  />
      	   </div>
      <div class="description"><?php _e('Enter a title to display on the frontend which will be seen by users.','templatic');?></div>
    </div>
  </div> <!-- #end -->
  

  <div class="option option-select" >
    <h3><?php _e('Admin Field Label: ','templatic');?></h3>
    <div class="section">
      <div class="element">
                   <input type="text" name="clabels" id="clabels" value="<?php echo $post_val->clabels;?>" size="50" />
      	   </div>
      <div class="description"><?php _e('Enter a label to display on the backend which will viewable by admin when adding a place or an event.','templatic');?></div>
    </div>
  </div> <!-- #end -->
  
  
  <div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?> >
    <h3><?php _e('HTML variable name: ','templatic');?></h3>
    <div class="section">
      <div class="element">
                   <input type="text" name="htmlvar_name" id="htmlvar_name" value="<?php echo $post_val->htmlvar_name;?>" size="50"  <?php if($_REQUEST['field_id'] !="") { ?>readonly=readonly style="pointer-events: none;"<?php } ?>/>
      	   </div>
      <div class="description"><?php _e('Enter a unique name in small letters and without spaces. To use more than one word, you must separate words by an underscore ( _ ) You will not be able to edit this name later, so please choose the correct name now.','templatic');?></div>
    </div>
  </div> <!-- #end -->
  
  
  <div class="option option-select" >
    <h3><?php _e('Description: ','templatic');?></h3>
    <div class="section">
      <div class="element">
                   <input type="text" name="admin_desc" id="admin_desc" value="<?php echo $post_val->admin_desc;?>" size="50" />
      	   </div>
      <div class="description"><?php _e('Enter the field description to provide some tips under the field for users when submitting a place or an event.','templatic');?></div>
    </div>
  </div> <!-- #end -->
  
  
  <div class="option option-select"  <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Default value: ','templatic').$post_val->is_edit;?> </h3>
    <div class="section">
      <div class="element">
                   <input type="text" name="default_value" id="default_value" value="<?php echo $post_val->default_value;?>" size="50" />
      	   </div>
      <div class="description"><?php _e('Enter or select a default value to show as the a pre-selected or pre-populated for the field. (optional)','templatic');?></div>
    </div>
  </div> <!-- #end -->
  
  
  <div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Display order: ','templatic');?></h3>
    <div class="section">
      <div class="element">
                   <input type="text" name="sort_order" id="sort_order"  value="<?php echo $post_val->sort_order;?>" size="50" />
      	   </div>
      <div class="description"><?php _e('This is a numeric value that determines the position of the custom field in the front-end and the back-end. e.g. 5','templatic');?></div>
    </div>
  </div> <!-- #end -->
  
  <div class="option option-select" >
    <h3><?php _e('Field status: ','templatic');?></h3>
    <div class="section">
      <div class="element">
                   <select name="is_active" id="is_active">
                  <option value="1" <?php if($post_val->is_active=='1'){ echo 'selected="selected"';}?>><?php _e('Activate','templatic');?></option>
                  <option value="0" <?php if($post_val->is_active=='0'){ echo 'selected="selected"';}?>><?php _e('Deactivate','templatic');?></option>
                  </select>
      	   </div>
      <div class="description"><?php _e('Choose activate to show this field or deactivate instead of deleting it so you can use it in future if the need arises.','templatic');?></div>
    </div>
  </div> <!-- #end -->
  
  
   <div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Required: ','templatic');?></h3>
    <div class="section">
      <div class="element">
                    <select name="is_require" id="is_require" >
                  <option value="1" <?php if($post_val->is_require=='1'){ echo 'selected="selected"';}?>><?php _e('Activate','templatic');?></option>
                  <option value="0" <?php if($post_val->is_require=='0'){ echo 'selected="selected"';}?>><?php _e('Deactivate','templatic');?></option>
                  </select>
      	   </div>
      <div class="description"><?php _e("Choose Activate if you wish to force a user to fill or select an option for this field when submitting a place or an event. (don't forget to select one of the options in validation types below)",'templatic');?></div>
    </div>
  </div> <!-- #end -->
  
  <div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?><?php }?> >
    <h3><?php _e('Display location ','templatic');?></h3>
    <div class="section">
      <div class="element">
                    <select name="show_on_page" id="show_on_page" >
					  <option value="admin_side" <?php if($post_val->show_on_page=='admin_side'){ echo 'selected="selected"';}?>><?php _e('Admin side (Backend side) ','templatic');?></option>
					  <option value="user_side" <?php if($post_val->show_on_page=='user_side'){ echo 'selected="selected"';}?>><?php _e('User side (Frontend side)','templatic');?></option> 
					  <option value="both_side" <?php if($post_val->show_on_page=='both_side'){ echo 'selected="selected"';}?>><?php _e('Both','templatic');?></option>
                  </select>
      	   </div>
    </div>
  </div> <!-- #end -->
 
   <div class="option option-select" <?php if($post_val->is_edit == '0' && is_super_admin($current_user->ID) != '1'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Editable field ','templatic');?></h3>
    <div class="section">
      <div class="element">
                   <select name="is_edit" id="is_edit">
                  <option value="1" <?php if($post_val->is_edit=='1'){ echo 'selected="selected"';}?>><?php _e('Activate','templatic');?></option>
                  <option value="0" <?php if($post_val->is_edit=='0'){ echo 'selected="selected"';}?>><?php _e('Deactivate','templatic');?></option>
                  </select>
      	   </div>
      <div class="description"><?php _e('Specify whether or not this field is editable.','templatic');?></div>
    </div>
  </div> <!-- #end -->
 
 
   <div class="option option-select" <?php if($post_val->is_edit == '0' && is_super_admin($current_user->ID) != '1'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Searchable ','templatic');?></h3>
    <div class="section">
      <div class="element">
                   <select name="is_search" id="is_search">
                  <option value="1" <?php if($post_val->is_search=='1'){ echo 'selected="selected"';}?>><?php _e('Activate','templatic');?></option>
                  <option value="0" <?php if($post_val->is_search=='0'){ echo 'selected="selected"';}?>><?php _e('Deactivate','templatic');?></option>
                  </select>
      	   </div>
      <div class="description"><?php _e("Choosing Activate will make it possible for users to filter results with this field's options or entry when using advanced search.",'templatic');?></div>
    </div>
  </div> <!-- #end -->	
 <div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?> >
    <h3><?php _e('Show on detail page','templatic');?></h3>
    <div class="section">
      <div class="element">
                    <select name="show_on_detail" id="show_on_detail">
                  <option value="1" <?php if($post_val->show_on_detail=='1'){ echo 'selected="selected"';}?>><?php _e('Activate','templatic');?></option>
                  <option value="0" <?php if($post_val->show_on_detail=='0'){ echo 'selected="selected"';}?>><?php _e('Deactivate','templatic');?></option>
                  </select>
      	   </div>
      <div class="description"><?php _e("Choosing Deactivate will not show the field on place or event detail page but it will appear in all other places.",'templatic');?></div>
    </div>
  </div>
  
 <div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Required field warning message','templatic');?></h3>
    <div class="section">
      <div class="element">
			<textarea name="field_require_desc" id="field_require_desc"><?php echo stripslashes($post_val->field_require_desc);?></textarea></div>
      <div class="description"><?php _e('Enter the alert message to be displayed to user if this field is not filled in or an option not selected.','templatic');?></div>
    </div>
  </div>
	<div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Validation type: ','templatic');?></h3>
    <div class="section">
      <div class="element">
			<select name="validation_type" id="validation_type"><?php echo validation_type_cmb($post_val->validation_type);?></select></div>
			<div class="description"><?php _e('Choose a validation type to force user to enter the expected value or select an option.<small><br/><br/><b>Require</b> field cannot be left blank.<br/><b>Phone No.</b> numeric values in phone number format must be entered.<br/><b>Digit</b> numeric values must be entered.<br/><b>Email</b> a valid email must be entered.</small>','templatic');?></div>
		</div>
	</div>
	
	<div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('CSS class','templatic');?></h3>
    <div class="section">
      <div class="element"><input type="text" name="style_class" id="style_class" value="<?php echo $post_val->style_class; ?>"></div>
			<div class="description"><?php _e("Enter a class name here and add its properties in CSS files. (class only applied to field's backend style)",'templatic');?></div>
		</div>
	</div>
	<div class="option option-select" <?php if($post_val->is_edit == '0'){?> style="display:none;" <?php }else{?> style="display:block;" <?php }?>>
    <h3><?php _e('Extra parameter : ','templatic');?></h3>
    <div class="section">
      <div class="element"><input type="text" name="extra_parameter" id="extra_parameter" value="<?php echo $post_val->extra_parameter; ?>"></div>
			<div class="description"><?php _e("Pass an extra parameter for this field for example, maxlength, onchange etc (optional)",'templatic');?></p></div>
		</div>
	</div>
  
  
  <input type="submit" name="submit" value="<?php _e('Save all changes','templatic');?>" class="button-framework-imp right position_bottom"> 
<?php if($post_val->is_delete || $field_id =='' ) { ?>
	<input type="hidden" name="is_delete" id="is_delete" value="1"/>
	<?php } ?>

</form>
<script type="text/javascript">
function show_option_add(htmltype){
	if(htmltype=='select' || htmltype=='multiselect' || htmltype=='radio' || htmltype=='multicheckbox')	{
		document.getElementById('ctype_option_tr_id').style.display='';		
	}else{
		document.getElementById('ctype_option_tr_id').style.display='none';	
	}
	if(htmltype=='head')	{
		document.getElementById('admin_title_id').style.display='none';	
		document.getElementById('default_value_id').style.display='none';	
		document.getElementById('show_on_detail_id').style.display='none';	
		document.getElementById('is_require_id').style.display='none';	
	}else{
		document.getElementById('admin_title_id').style.display='';			
		document.getElementById('default_value_id').style.display='';		
		document.getElementById('show_on_detail_id').style.display='';	
		document.getElementById('is_require_id').style.display='';	
	}
}
if(document.getElementById('ctype').value){
	show_option_add(document.getElementById('ctype').value)	;
}
</script>