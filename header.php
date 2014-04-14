<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="<?php echo get_bloginfo('html_type'); ?>; charset=<?php echo get_bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <title>
	<?php wp_title ( '|', true,'right' ); ?>
    </title>
    <?php do_action('templ_head_meta');
       header('Cache-Control: max-age=10800'); ?>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_bloginfo('stylesheet_url'); ?>" />
    <?php do_action('templ_head_css');?>
    <?php
        wp_enqueue_script('jquery');
        wp_enqueue_script('cookie', get_template_directory_uri() . '/js/jquery.cookie.js', 'jquery', false);
        if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
        do_action('templ_head_js');
        remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
        
        if ( is_singular() && !is_page()){ 
        facebook_meta_tags($post); }
        wp_deregister_script( 'google-maps' );
        wp_head();
        ?>
    <script type="text/javascript" src="<?php echo get_template_directory_uri()."/js/geoplaces.js"; ?>"></script>
    <script type="text/javascript">
		var placesearch = "<?php echo SEARCH;?>"; var nearplace = "<?php echo NEAR_TEXT;?>"; var monthNames = ["<?php _e("January",DOMAIN);?>","<?php _e("February",DOMAIN);?>","<?php _e("March",DOMAIN);?>","<?php _e("April",DOMAIN);?>","<?php _e("May",DOMAIN);?>","<?php _e("June",DOMAIN);?>","<?php _e("July",DOMAIN);?>","<?php _e("August",DOMAIN);?>","<?php _e("September",DOMAIN);?>","<?php _e("October",DOMAIN);?>","<?php _e("November",DOMAIN);?>","<?php _e("December",DOMAIN);?>"];
		var monthNamesShort = ["<?php _e("Jan",DOMAIN);?>", "<?php _e("Feb",DOMAIN);?>", "<?php _e("Mar",DOMAIN);?>", "<?php _e("Apr",DOMAIN);?>", "<?php _e("May",DOMAIN);?>", "<?php _e("Jun",DOMAIN);?>", "<?php _e("Jul",DOMAIN);?>", "<?php _e("Aug",DOMAIN);?>", "<?php _e("Sep",DOMAIN);?>", "<?php _e("Oct",DOMAIN);?>", "<?php _e("Nov",DOMAIN);?>", "<?php _e("Dec",DOMAIN);?>"]; var dayNames = ["<?php _e("Sunday",DOMAIN);?>", "<?php _e("Monday",DOMAIN);?>", "<?php _e("Tuesday",DOMAIN);?>", "<?php _e("Wednesday",DOMAIN);?>", "<?php _e("Thursday",DOMAIN);?>", "<?php _e("Friday",DOMAIN);?>", "<?php _e("Saturday",DOMAIN);?>"]; var dayNamesShort = ["<?php _e("Sun",DOMAIN);?>", "<?php _e("Mon",DOMAIN);?>", "<?php _e("Tue",DOMAIN);?>", "<?php _e("Wed",DOMAIN);?>", "<?php _e("Thu",DOMAIN);?>", "<?php _e("Fri",DOMAIN);?>", "<?php _e("Sat",DOMAIN);?>"];
		var dayNamesMin = ["<?php _e("Su",DOMAIN);?>","<?php _e("Mo",DOMAIN);?>","<?php _e("Tu",DOMAIN);?>","<?php _e("We",DOMAIN);?>","<?php _e("Th",DOMAIN);?>","<?php _e("Fr",DOMAIN);?>","<?php _e("Sa",DOMAIN);?>"]; var dateFormat = 'yy-mm-dd'; var firstDay = '<?php echo get_option("start_of_week"); ?>'; var sw = window.innerWidth; var sh = window.innerHeight; jQuery.cookie('swidth',sw); jQuery('select option').remove();
	</script>
    <script src="<?php echo get_bloginfo('stylesheet_directory'); ?>/js/jquery.nivo.slider.pack.js" type="text/javascript"></script>
    <!-- socialize.js script should only be included once -->
    <?php global $site_url;
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if(is_plugin_active('gigya-socialize-for-wordpress/gigya.php') && get_option('users_can_register')){
        $gigya_opt = get_option('gigya_settings_fields');  ?>
		<script type="text/javascript">
	
        var conf=
        {
            siteName: 'localhost'
            ,enabledProviders: 'facebook,twitter,yahoo,messenger,google,linkedin,myspace,aol,foursquare,orkut,vkontakte,renren,kaixin'
            ,redirectURL: '<?php echo $site_url; ?>'
        }
        </script>
    <?php } ?>
    <!--Script for gigya plugin on page EOF-->
   <script type='text/javascript' src='<?php echo get_bloginfo('stylesheet_directory'); ?>/js/jquery.hoverIntent.minified.js'></script>
    <?php 
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
       ?>
	<script src="<?php echo get_template_directory_uri().'/js/jquery.ui.datepicker.js';?>"></script>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri().'/library/css/jquery.ui.all.css';?>">
    <!--[if IE]>
        <link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('stylesheet_directory'); ?>/js/css3-mediaqueries.js" />
    <![endif]-->
    <!-- mediaqueries script for fast loading css-->
    <script src="<?php echo get_bloginfo('stylesheet_directory'); ?>/js/modernizr-custom.js"></script>
</head>
<body <?php body_class(); ?>>
<?php templ_body_start(); // Body Start hooks?>
<?php // templ_get_top_header_navigation_above() ?>
<?php // templ_get_top_header_navigation() ?>

<div class="wrapper">
<?php templ_header_start(); // Header Start hooks?>
<div class="above_header header main-nav"><div class="mid-column"><?php  templ_get_top_header_navigation(); ?><div class="clearfix"></div></div></div>
<div class="header">

  <div class="header_in">
    

    <div class="header_right">	
      <?php if (function_exists('dynamic_sidebar')){ dynamic_sidebar('header_logo_right_side'); }?>    
    </div>
	<div class="logo">
      <?php templ_site_logo(); ?>
    </div>
	
	<div class="header_right header_search_form">	
		<?php include(get_template_directory()."/header_searchform.php");?>
	</div>
  </div> <!-- header inner #end -->
</div> <!-- header #end -->
 
  <?php templ_get_main_header_navigation(); ?>
 <!-- main navi #end -->
<?php templ_header_end(); // Header End hooks ?>

<?php templ_content_start(); // content start hooks ?>

		
<?php  if ( is_home() && !isset($_GET['ptype'])) { ?>

    	
	<?php if ( function_exists('is_sidebar_active') && is_sidebar_active('front_top_banner') ) {?>
	<div class="top_banner_section">
	<?php dynamic_sidebar('front_top_banner'); ?>
	</div>
	<?php } else {?>  <?php }?> 

<?php } else {  ?> <?php } ?> 

<!-- Container -->
<div id="container" class="clearfix">