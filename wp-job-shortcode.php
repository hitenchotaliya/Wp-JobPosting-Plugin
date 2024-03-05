<?php

use FluentForm\Framework\Support\Arr;

function dwwp_sample_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(
        array(
            'title' => 'Current job opening in..',
        ),
        $atts
    );

    $locations = get_terms('location');

    if (!empty($locations) && !is_wp_error($locations)) {


        $displayList = '<div id="job-location-list">';
        $displayList .= '<h4>' . esc_html__($atts['title']) . '</h4>';
        $displayList .= '<ul>';

        foreach ($locations as $location) {
            $displayList .= '<li class="job-location">';
            $displayList .= '<a href="' . esc_url(get_term_link($location)) . '">';
            $displayList .= esc_html__($location->name) . '</a></li>';
        }
        $displayList .= '</ul></div>';
    }
    return $displayList;

    // var_dump($locations);
    // print_r($atts);
    // return '<h1>' . $atts['title'] . '</h1>';
}

//First paramter is unique name that can use in reference 
add_shortcode('job_location_list', 'dwwp_sample_shortcode');

function dwwp_list_job_by_location($atts, $content = null)
{

    if (!isset($atts['location'])) {
        return '<p class="job-error">You must provide location for this shortcode to work.</p>';
    }

    $atts = shortcode_atts(array(
        'title' => 'Current job opening in',
        'count' => 2,
        'location' => '',
        'pagination' => 'off'
    ), $atts);

    $pagination = $atts['pagination'] == 'on' ? false : true;
    $paged =  get_query_var('pages') ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'job',
        'post_status' => 'publish',
        'no_found_rows' => $pagination,
        'posts_per_page' => $atts['count'],
        'paged' => $paged,
        'tax_query' => array(
            array(
                'taxonomy' => 'location',
                'field' => 'slug',
                'terms' => $atts['location'],
            ),
        )
    );
    $job_by_location =  new WP_Query($args);
    // var_dump($job_by_location->get_posts());
    if ($job_by_location->have_posts()) :
        $location = str_replace('-', ' ', $atts['location']);

        $displayByLocation = '<div id="job-by-location">';
        $displayByLocation .= '<h4>' . esc_html($atts['title']) . ' ' . esc_html(ucwords($location)) . '</h4>';
        $displayByLocation .= '<ul>';

        while ($job_by_location->have_posts()) : $job_by_location->the_post();

            global $post;

            $deadline = get_post_meta(get_the_ID(), 'application_deadline', true);
            $title = get_the_title();
            $slug = get_permalink();

            $displayByLocation .= '<li class="job-listing">';
            $displayByLocation .= sprintf('<a href="%s">%s</a>  ', esc_url($slug), esc_html__($title));
            $displayByLocation .= '<span>' . esc_html($deadline) . '</span>';
            $displayByLocation .= '</li>';

        endwhile;
        $displayByLocation .= '</ul>';
        $displayByLocation .= '</div>';

    else :

        $displayByLocation = sprintf(__('<p class="job-error">Sorry, no job listed in %s where you found.</p>'), esc_html__(ucwords(str_replace('-', ' ', $atts['location']))));

    endif;


    if ($job_by_location->max_num_pages > 1 && is_page()) {
        $displayByLocation .= '<nav class="prev-next-posts">';
        $displayByLocation .= '<div class="nav-previous">';
        $displayByLocation .= get_next_posts_link(__('<span class="meta-nav">&larr;</span> Previous'), $job_by_location->max_num_pages);
        $displayByLocation .= '</div>';
        $displayByLocation .= '<div call="next-posts-link">';
        $displayByLocation .= get_previous_posts_link(__('<span class="meta-nav">&rarr;</span> Next'));
        $displayByLocation .= '</div>';
        $displayByLocation .= '</nav>';
    }

    wp_reset_postdata();

    return $displayByLocation;
}
add_shortcode('job_by_location', 'dwwp_list_job_by_location');
