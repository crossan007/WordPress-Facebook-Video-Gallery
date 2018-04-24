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
       
        return $title;
    }

    $crmc_sc_output .= "<script> var videos = ".json_encode($this->videos, JSON_PRETTY_PRINT)."; </script>
    <div id='videoModal' class='modal'>
    <div class='modal-content'></div></div>";

    foreach ($this->videos as $video) 
    {
        $crmc_sc_output .= "<div class=\"video-div 4u 12u(mobile)\" data-permalink=\"".urlencode($video->permalink_url)."\">";
        $crmc_sc_output .= "<div class=\"video-div-header\"><div class=\"left\"><h1>$video->title</h1><h2>". $video->datetime ."</h2></div><span class=\"close\">Close</span></div><div class=\"clear\"></div>";
        //$crmc_sc_output .= "<div class=\"event-div-body\">".$video->embed_html."</div>";
        $crmc_sc_output .= "<div class=\"video-div-body\"><img src=\"".$video->preferred_thumb->uri."\"/></div>";
        $crmc_sc_output .= "</div>";
    }
     
    $crmc_sc_output .= "</div>";