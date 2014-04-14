<?php //exit;
$csvfilepath = home_url() ."/wp-content/themes/".get_option( 'template' )."/post_sample.csv";
$csvpath = get_template_directory()."/post_sample.csv";
header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=post_sample.csv; Content-Length:".strlen($csvfilepath));
	header("Pragma: no-cache");
	header("Expires: 0");
readfile($csvpath);
//wp_redirect($csvfilepath);
exit;
?>