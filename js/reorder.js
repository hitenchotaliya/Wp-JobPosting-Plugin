jQuery(document).ready(function ($) {
    var sortList = $('ul#custom-type-list');
    var animation = $('#load-animation');
    var pageTitle = $('div h2');

    if (sortList.length) {
        sortList.sortable({
            update: function (event, ui) {
                animation.show();

                var order = sortList.sortable('toArray').toString();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'save_post_order',
                        order: order
                    },
                    success: function (response) {
                        // console.log("Success: ", response);
                        animation.hide();
                        pageTitle.after('<div class="updated"><p>Job sort order has been saved</p></div>');
                    },
                    error: function (error) {
                        // console.log("Error: ", error);
                        animation.hide();
                        pageTitle.after('<div class="error"><p>There was an error saving the sort order,or you do not have proper permission.</p></div>');
                    }
                });
            }
        });
    }
});
