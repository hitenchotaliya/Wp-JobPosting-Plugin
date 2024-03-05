// In reorder.js

jQuery(document).ready(function ($) {
    var sortList = $('ul#custom-type-list');
    var animation = $('#loading-animation');
    var pageTitle = $('div h2');

    if (sortList.length) {
        sortList.sortable({
            update: function (event, ui) {
                animation.show();

                var order = sortList.sortable('toArray');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'save_sort',
                        order: order,
                        security: WP_JOB_LISTING.security,
                    },
                    success: function (response) {
                        animation.hide();
                        if (true === response.success) {
                            pageTitle.after('<div id="message" class="updated"><p>' + WP_JOB_LISTING.success + '</p></div>');
                        } else {
                            pageTitle.after('<div id="message" class="error"><p>' + WP_JOB_LISTING.failure + '</p></div>'); // Correct the failure message
                        }
                    },
                    error: function (response) {
                        animation.hide();
                        pageTitle.after('<div id="message" class="error"><p>' + WP_JOB_LISTING.failure + '</p></div>'); // Correct the failure message
                    }
                });
            }
        });
    }
});
