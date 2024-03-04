<?php
function dwwp_add_submenu_page()
{
    add_submenu_page(
        'edit.php?post_type=job',
        'Reorder Jobs',
        'Reorder Jobs',
        'manage_options',
        'reorder_jobs',
        'reorder_admin_jobs_callback'
    );
}
add_action('admin_menu', 'dwwp_add_submenu_page');

function reorder_admin_jobs_callback()
{
    $args = array(
        'post_type' => 'job',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'post_status' => 'publish',
        'no_found_rows' => true,
        'update_page_term_cache' => false,
        'post_per_post' => 50,
    );
    $job_listing = new WP_Query($args);
    // echo "<pre>";
    // var_dump($job_listing->get_posts());
?>

    <div class="job-sort" class="wrap">
        <div class="icon-job-admin" class="icon32"><br></div>
        <h2><?php _e('Sort Job Position', 'wp-job-listing'); ?>
            <img src="<?php echo esc_url(admin_url() . '/images/loading.gif'); ?>" id="loading-animation" alt="">
        </h2>
        <?php if ($job_listing->have_posts()) :  ?>
            <p><?php _e('<strong>Note:</strong> this is only affect the jobs listed using shortcut functions', 'wp-job-listing'); ?></p>
            <ul id="custom-type-list">
                <?php
                while ($job_listing->have_posts()) : $job_listing->the_post(); ?>

                    <li id="<?php esc_attr(the_id()); ?>"><?php esc_html(the_title()); ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else :  ?>
            <p><?php _e('You have no job to sort.', 'wp-job-listing'); ?></p>
        <?php endif; ?>
    </div>

<?php

}

add_action('wp_ajax_save_post_order', 'save_post_order_callback');

function save_post_order_callback()
{
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : '';
    wp_send_json_success('Order saved successfully');
}
