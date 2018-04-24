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


function convert_video_meta($video) {
	$date = DateTime::createFromFormat(
		DATE_ATOM, 
		$video->created_time)
		->setTimeZone(new DateTimeZone('America/New_York'));
	$video->datetime =  $date->format("M/j/y g:i A");//'Y-m-d H:i:sP'); //date("M/j/y g:i A",strtotime());
	$maxlen = 30;
	if(strlen($video->title)>$maxlen)
	{
		$video->title = substr($video->title,0,$maxlen)."...";
	}

	$video->preferred_thumb = $video->thumbnails->data[0];
	foreach($video->thumbnails->data as $t) {
		if ($t->is_preferred){
			$video->preferred_thumb=$t;
		}
	}
	$video->thumbnails = null;

	return $video;
}

function update_video_cache($AccessToken) {
	$FBGraph="https://graph.facebook.com/v2.12/me/videos/uploaded";
    //$FilterLabels = array("Sermon","asdf");// explode(",",get_option('_fbvg_access_token'));

    $fields = array(
        "custom_labels",
        "description",
        "thumbnails",
        "created_time",
		"title",
		"permalink_url"
    );

    $Params = array(
        "debug=all",
        "fields=". join("%2C", $fields),
        "format=json",
        "method=get",
        "pretty=0",
        "suppress_http_code=1");

    $URL = $FBGraph."?access_token=".$AccessToken."&".join("&", $Params);   
    $response = json_decode(file_get_contents($URL));
    if (isset($response->error))
    {
		echo "<span style='color:red'>Error fetching videos.  Please check access token</span>";
	}
	else {
		$videos = array_map("convert_video_meta",$response->data);
		update_option("_fbvg_api_response",$videos);
	}
}

// Check Nonce and then update options
if ( !empty($_POST) && check_admin_referer( 'facebook-video-gallery-options', 'facebook-video-gallery-options' ) ) {

	$fbvg_access_token = stripslashes_deep( $_POST[ "fbvg_access_token"] );
	update_video_cache($fbvg_access_token);
	
} 

$videos = get_option("_fbvg_api_response");

$output = '<div class="wrap sslp-options">';
	$output .= '<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div>';
	$output .= '<h2>' . __( 'Facebook Video Gallery' , 'Facebook-Video-Gallery' ) . '</h2>';
	$output .= '<h2>' . __( 'Options', 'Facebook-Video-Gallery' ) . '</h2>';
	
	
	$output .= '<div class="sslp-content sslp-column">';
		$output .= '<form method="post" action="">';
			
			$output .= '<fieldset id="fbvg_access_token" class="sslp-fieldset">';
			$output .= '<legend class="sslp-field-label">' . __( 'Access Token' , 'Facebook-Video-Gallery' ) . '</legend>';
			$output .= '<input type="text" name="fbvg_access_token" ></fieldset>';
			$output .= '<p><input type="submit" value="' . __( 'Update Video Cache' , 'Facebook-Video-Gallery' ) . '" class="button button-primary button-large"></p>';
			
			$output .= wp_nonce_field('facebook-video-gallery-options', 'facebook-video-gallery-options');
		$output .= '</form>';
	$output .= '</div>';

	$output .= '<div class="sslp-content sslp-column">';
	$output .= '<h3>Video Cache '.count($videos).'</h3>';
	$output .= '<ul>';
	foreach ($videos as $video)
	{
		date_default_timezone_set('America/New_York');  
		$output .= '<li><a target="_blank" href="https://www.facebook.com' .$video->permalink_url.'" >'. $video->datetime.": ".$video->title . '</a></li>';
	}
	$output .= '</ul>';
	$output .= '</div>';

	$output .= '<div class="sslp-sidebar sslp-column last">';
	$output .= '</div>';
$output .= '</div>';
    
echo $output;
