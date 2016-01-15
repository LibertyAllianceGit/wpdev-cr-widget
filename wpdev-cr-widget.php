<?php
/*
Plugin Name: WP Developers Conservative Review Liberty Score Widget
Plugin URI: http://wpdevelopers.com
Description: Plugin to enable Conservative Review's Liberty Score to run on sites, with a [score][/score] shortcode.
Version: 1.0.4
Author: Ted Slater
Author URI: http://libertyalliance.com
Author Email: ted@libertyalliance.com
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
Enqueue Admin Plugin Styles
**********/
function wpdev_cr_score_plugin_css() {
        wp_register_style( 'wpdev-cr-score-plugin-css', plugin_dir_url(__FILE__) . 'cr-style.css' );
        wp_enqueue_style( 'wpdev-cr-score-plugin-css' );
}
add_action('admin_enqueue_scripts', 'wpdev_cr_score_plugin_css', 20);

/**********
Setup Options Page
**********/

add_action( 'admin_menu', 'wpdevcrscore_add_admin_menu' );
add_action( 'admin_init', 'wpdevcrscore_settings_init' );

function wpdevcrscore_add_admin_menu() { 
	add_submenu_page( 
        'options-general.php', 
        'WPDev CR Score', 
        'WPDev CR Score', 
        'manage_options', 
        'wpdevcrscore', 
        'wpdevcrscore_options_page' 
    );
}

function wpdevcrscore_settings_init() { 
	register_setting( 'pluginPage', 'wpdevcrscore_settings' );

	add_settings_section(
		'wpdevcrscore_pluginPage_section', 
		__( 'Settings', 'wpdevcrscore' ), 
		'wpdevcrscore_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'wpdevcrscore_appid', 
		__( 'APP ID', 'wpdevcrscore' ), 
		'wpdevcrscore_appid_render', 
		'pluginPage', 
		'wpdevcrscore_pluginPage_section' 
	);
}

function wpdevcrscore_appid_render() { 
	$options = get_option( 'wpdevcrscore_settings' );
	?>
	<input type='text' name='wpdevcrscore_settings[wpdevcrscore_appid]' placeholder='CRTV-...' value='<?php echo $options['wpdevcrscore_appid']; ?>'>
	<?php
}

function wpdevcrscore_settings_section_callback() { 
	echo __( 'Plugin to enable Conservative Review\'s Liberty Score to run on sites, with a [score][/score] shortcode.', 'wpdevcrscore' );
}

function wpdevcrscore_options_page() { 
	?>
<div class="wpdev-cr-score-settings">
	<form action='options.php' method='post'>
		<h2><img src="<?php echo plugin_dir_url(__FILE__) . 'wpdev-cr-score-widget-logo.png'; ?>" /></h2>
		<hr/>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
	</form>
</div>
	<?php
}

/**********
Add Conservative Review Liberty Score Code to Footer
**********/

$wpdevcrscoreoptions = get_option( 'wpdevcrscore_settings' );

$lsappid = $wpdevcrscoreoptions['wpdevcrscore_appid'];

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
	    return $content.' <span data-cr-member="'.$names[1].'|'.$names[0].'" style="vertical-align:middle"></span>';
}
add_shortcode("score", "crls_shortcode");
