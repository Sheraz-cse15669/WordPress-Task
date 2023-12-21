jQuery(document).ready(function($) {

    $.ajax({
        url: ajax_object.ajaxurl,
        type: 'GET',
        dataType: 'json',
        data: {
            action: 'get_architecture_projects'
        },
        success: function(response) {
            if (response.success) {
                console.log(response.data);
            } else {
                console.error('Ajax request failed.');
            }
        },
        error: function(error) {
            console.error('Ajax request error:', error);
        }
    });
});
