<?php

function dwwp_sample_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(
        array(
            'title' => 'Default title',
            'src' => 'www.google.com'
        ),
        $atts
    );
    // print_r($atts);
    return '<h1>' . $atts['title'] . '</h1>';
}

//First paramter is unique name that can use in reference 
add_shortcode('job_listing', 'dwwp_sample_shortcode');
