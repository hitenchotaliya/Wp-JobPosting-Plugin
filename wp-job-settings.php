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
    echo "this is job reorder admin page";
}
