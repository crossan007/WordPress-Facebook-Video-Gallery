<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       
 * @since      
 *
 * @package    Facebook_Video_Gallery
 * @subpackage  Facebook_Video_Gallery/public/partials
 */
 
 	global $crmc_sc_output;

    $atts = $this->fbvg_list_shortcode_atts;
    
    date_default_timezone_set(get_option('timezone_string'));

            
    $FBGraph="https://graph.facebook.com/v2.12/me/videos/uploaded";
    $AccessToken = get_option('_fbvg_access_token');
    $FilterLabels = array("Sermon","asdf");// explode(",",get_option('_fbvg_access_token'));

    $fields = array(
        "custom_labels",
        "embed_html",
        "description",
        "thumbnails",
        "created_time",
        "title"       
    );

    $Params = array(
        "debug=all",
        "fields=". join("%2C", $fields),
        "format=json",
        "method=get",
        "pretty=0",
        "suppress_http_code=1");

    $URL = $FBGraph."?access_token=".$AccessToken."&".join("&", $Params);   
    $videos = json_decode(file_get_contents($URL))->data;
   // echo "<pre>" .htmlspecialchars(json_encode($videos,JSON_PRETTY_PRINT)) . "</pre>";
    //echo "<pre>".json_encode($videos,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT)."</pre>";
    $crmc_sc_output .= "<div class=\"video-container row\">";

    function should_display_video($video, $FilterLabels)
    {
        if (count($FilterLabels)>0)
        {
            $videoLabels = $video->custom_labels;
            if (isset($videoLabels))
            {
                $matchingLabels= array_intersect($videoLabels,$FilterLabels);
                if (count($matchingLabels) > 0){
                    return true;
                }
            }
            else {
                return false;
            }
        }
        else
        {
            return true;
        }
        return false;
    }

    foreach ($videos as $video) 
    {
       if (should_display_video($video, $FilterLabels))
       {
            $thumb = $video->thumbnails->data[0];
            foreach($video->thumbnails->data as $t) {
                if ($t->is_preferred){
                    $thumb=$t;
                }
            }

            $crmc_sc_output .= "<div class=\"video-div 3u\">";
            $crmc_sc_output .= "<div class=\"video-div-header\"><h1>$video->title</h1><h2>".date("M/j/y g:i A",strtotime($video->created_time))."</h2></div>";
            //$crmc_sc_output .= "<div class=\"event-div-body\">".$video->embed_html."</div>";
            $crmc_sc_output .= "<div class=\"video-div-body\"><img src=\"".$thumb->uri."\"/></div>";
            $crmc_sc_output .= "</div>";
       }
    }
        
    $crmc_sc_output .= "</div>";