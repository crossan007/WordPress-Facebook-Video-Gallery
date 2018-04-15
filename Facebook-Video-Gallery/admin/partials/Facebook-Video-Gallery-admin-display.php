<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       
 * @since      1.00
 *
 * @package    Facebook_Video_Gallery
 * @subpackage Facebook_Video_Gallery/admin/partials
 */


// Check Nonce and then update options
if ( !empty($_POST) && check_admin_referer( 'facebook-video-gallery-options', 'facebook-video-gallery-options' ) ) {
	update_option( '_fbvg_page_id', $_POST[ "fbvg_page_id"] );
	update_option( '_fbvg_access_token', $_POST[ "fbvg_access_token"]);
	
	$fbvg_page_id = stripslashes_deep( get_option('_fbvg_page_id') );
	$fbvg_access_token = stripslashes_deep( get_option('_fbvg_access_token') );
	// We've updated the options, send off an AJAX request to flush the rewrite rules
	#TODO# Should move these options to use the Settings API instead of our own custom thing - or maybe just make it all AJAX - no need for a page refresh
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var data = {
				'action': 'sslp_flush_rewrite_rules',
			}
			
			$.post( "<?php echo admin_url( 'admin-ajax.php' ); ?>", data, function(response){});
		});
	</script>
<?php

} else {
	$fbvg_page_id = stripslashes_deep( get_option('_fbvg_page_id') );
	$fbvg_access_token = stripslashes_deep( get_option('_fbvg_access_token') );
}


$output = '<div class="wrap sslp-options">';
	$output .= '<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div>';
	$output .= '<h2>' . __( 'Facebook Video Gallery' , 'Facebook-Video-Gallery' ) . '</h2>';
	$output .= '<h2>' . __( 'Options', 'Facebook-Video-Gallery' ) . '</h2>';
	
	$output .= '<div class="sslp-content sslp-column">';
		$output .= '<form method="post" action="">';
			$output .= '<fieldset id="fbvg_page_id" class="sslp-fieldset">';
			$output .= '<legend class="sslp-field-label">' . __( 'Facebook Page Id' , 'Facebook-Video-Gallery' ) . '</legend>';
			$output .= '<input type="text" name="fbvg_page_id" value="' . $fbvg_page_id . '"></fieldset>';
			
			$output .= '<fieldset id="fbvg_access_token" class="sslp-fieldset">';
			$output .= '<legend class="sslp-field-label">' . __( 'Access Token' , 'Facebook-Video-Gallery' ) . '</legend>';
			$output .= '<input type="text" name="fbvg_access_token" value="' . $fbvg_access_token . '"></fieldset>';
			$output .= '<p><input type="submit" value="' . __( 'Save ALL Changes' , 'Facebook-Video-Gallery' ) . '" class="button button-primary button-large"></p>';
			
			$output .= wp_nonce_field('facebook-video-gallery-options', 'facebook-video-gallery-options');
		$output .= '</form>';
	$output .= '</div>';
	$output .= '<div class="sslp-sidebar sslp-column last">';
	$output .= '</div>';
$output .= '</div>';
    
echo $output;
