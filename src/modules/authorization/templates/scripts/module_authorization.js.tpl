/*
	Author: Jim Pouwels
	Date: October 30th, 2011
*/

// initialize event handlers
$(document).ready(function () {
    // apply button
    $('#update_user').on('click', function () {
        $('#action').attr('value', 'update_user');
        $('#user_form').trigger('submit');
    });

    // delete button
    $('#delete_user').on('click', function () {
        confirmDialog("{$text_resources.authorization_confirm_delete_user|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'delete_user');
                $('#user_form').trigger('submit');
            }
        });
        return false;
    });

    // add button
    $('#add_user').on('click', function () {
        $('#action').attr('value', 'add_user');
        $('#user_form').trigger('submit');
    });
});