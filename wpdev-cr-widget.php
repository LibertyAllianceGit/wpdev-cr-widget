<?php
/*
Plugin Name: Conservative Review Liberty Score Widget
Plugin URI: http://wpdevelopers.com
Description: Facebook comments by WP Developers.
Version: 1.0
Author: Ted Slater
Author URI: http://libertyalliance.com
Author Email: tyler@libertyalliance.com
Text Domain: wpdev-cr-widget

Copyright 2016 WP Developers & Liberty Alliance

*/
 
/**********
Check for Plugin Updates
**********/

require 'plugin-update-checker-2.2/plugin-update-checker.php';
$className = PucFactory::getLatestClassVersion('PucGitHubChecker');
$myUpdateChecker = new $className(
    'https://github.com/LibertyAllianceGit/wpdev-cr-widget',
    __FILE__,
    'master'
);
 
/**********
Add Conservative Review Liberty Score Code to Footer
**********/

$lsappid = 'CRTV-LibertyAlliance';

function wpdev_crls_footer() {
    global $lsappid;
    if($lsappid) {
        echo '
<script> var crApiKey = \'' . $lsappid . '\'</script> 
<script src="https://api.conservativereview.com/Scripts/CRWidget.js"></script>
';
    }
}
add_action('wp_footer', 'wpdev_crls_footer', 1000);
 
/**********
Create shortcode
**********/

function crls_shortcode( $atts, $content = null ) {
	$names = explode(" ", $content);
	    return $content.'<span data-cr-member="'.$names[1].'|'.$names[0].'" style="vertical-align:middle"></span>';
}
add_shortcode("score", "crls_shortcode");