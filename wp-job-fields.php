<?php

function dwwp_add_custom_metabox()
{
    add_meta_box(
        'dwwp_meta',
        'Job Listing',
        'dwwp_meta_callback',
        'job',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'dwwp_add_custom_metabox');
