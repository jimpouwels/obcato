// initialize event handlers
$(document).ready(function () {
    // add webform action button
    $('#add_webform').on('click', function () {
        $('#add_webform_action').attr('value', 'add_webform');
        $('#add_form_hidden').trigger('submit');
        return false;
    });

    // update webform action button
    $('#update_webform').on('click', function () {
        $('#action').attr('value', 'update_webform');
        $('#webform-editor-form').trigger('submit');
    });

    // delete webform action button
    $('#delete_webform').on('click', function () {
        confirmDialog("{$text_resources.webforms_confirm_delete_webform|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'delete_webform');
                $('#webform-editor-form').trigger('submit');
            }
        });
    });
});

function addFormField(type) {
    $('#action').attr('value', 'add_' + type);
    $('#webform-editor-form').trigger('submit');
}

function deleteFormField(itemId, confirmMessage) {
    confirmDialog(confirmMessage).then(function(confirmed) {
        if (confirmed) {
            $('#action').attr('value', 'delete_form_item');
            $('#webform_item_to_delete').attr('value', itemId);
            $('#webform-editor-form').trigger('submit');
        }
    });
}

function addFormHandler(type) {
    $('#action').attr('value', 'add_handler_' + type);
    $('#webform-editor-form').trigger('submit');
}

function deleteFormHandler(handlerId, confirmMessage) {
    confirmDialog(confirmMessage).then(function(confirmed) {
        if (confirmed) {
            $('#action').attr('value', 'delete_form_handler');
            $('#webform_handler_to_delete').attr('value', handlerId);
            $('#webform-editor-form').trigger('submit');
        }
    });
}

function onCaptchaChanged(captchaKeyFieldClass) {
    $('.' + captchaKeyFieldClass).toggleClass('displaynone');
}