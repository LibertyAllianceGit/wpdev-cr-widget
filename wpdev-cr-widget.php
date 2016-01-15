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
Create Options Page
**********/

add_action( 'admin_menu', 'crls_add_admin_menu' );
add_action( 'admin_init', 'crls_settings_init' );

function crls_add_admin_menu(  ) { 
	add_options_page( 'wpdev-cr-widget', 'wpdev-cr-widget', 'manage_options', 'wpdev-cr-widget', 'wpdev-cr-widget_options_page' );
}

function crls_settings_init(  ) { 
	register_setting( 'pluginPage', 'crls_settings' );
	add_settings_section(
		'crls_pluginPage_section', 
		__( 'Your section description', 'wordpress' ), 
		'crls_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'lsappid', 
		__( 'Settings field description', 'wordpress' ), 
		'lsappid_render', 
		'pluginPage', 
		'crls_pluginPage_section' 
	);
}

function lsappid_render(  ) { 
	$options = get_option( 'crls_settings' );
	?>
	<input type='text' name='crls_settings[lsappid]' value='<?php echo $options['lsappid']; ?>'>
	<?php
}

function crls_settings_section_callback(  ) { 
	echo __( 'This section description', 'wordpress' );
}

function crls_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>wpdev-cr-widget</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}
 
/**********
Add Conservative Review Liberty Score Code to Footer
**********/

//$lsappid = 'CRTV-LibertyAlliance';

function wpdev_crls_footer() {
    global $lsappid;
    if($lsappid) {
        echo '
<script> var crApiKey = \'' . $options['lsappid'] . '\'</script> 
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