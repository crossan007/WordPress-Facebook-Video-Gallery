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
    //$FilterLabels = array("Sermon","asdf");// explode(",",get_option('_fbvg_access_token'));

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
    $response = json_decode(file_get_contents($URL));
    if (isset($response->error))
    {
        $crmc_sc_output = "Error fetching videos";
        $crmc_sc_output .= print_r($response->error,true);
        return;
    }

    //print_r($response);
    $videos = $response->data;
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

    function get_video_title($video)
    {
        $maxlen = 30;
        $title = $video->title;
        if(strlen($title)>$maxlen)
        {
            $title = substr($title,0,$maxlen)."...";
        }
        return $title;
    }

    foreach ($videos as $video) 
    {
       if (should_display_video($video, $FilterLabels))
       {
            date_default_timezone_set('America/New_York');  
            $title = get_video_title($video);
            $date = DateTime::createFromFormat(
                DATE_ATOM, 
                $video->created_time)
                ->setTimeZone(new DateTimeZone('America/New_York'));
            $datetime =  $date->format("M/j/y g:i A");//'Y-m-d H:i:sP'); //date("M/j/y g:i A",strtotime());
            $thumb = $video->thumbnails->data[0];
            foreach($video->thumbnails->data as $t) {
                if ($t->is_preferred){
                    $thumb=$t;
                }
            }

            $crmc_sc_output .= "<div class=\"video-div 4u 12u(mobile)\" data-embed=\"".base64_encode($video->embed_html)."\">";
            $crmc_sc_output .= "<div class=\"video-div-header\"><div class=\"left\"><h1>$title</h1><h2>". $datetime ."</h2></div><span class=\"close\">Close</span></div><div class=\"clear\"></div>";
            //$crmc_sc_output .= "<div class=\"event-div-body\">".$video->embed_html."</div>";
            $crmc_sc_output .= "<div class=\"video-div-body\"><img src=\"".$thumb->uri."\"/></div>";
            $crmc_sc_output .= "</div>";
       }
    }
        
    $crmc_sc_output .= "</div>";