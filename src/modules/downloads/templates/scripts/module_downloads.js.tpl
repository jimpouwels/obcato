// initialize event handlers
$(document).ready(function () {

    // update download button
    $('#update_download').on('click', function () {
        $('#action').attr('value', 'update_download');
        $('#download-editor-form').trigger('submit');
    });

    // delete download button
    $('#delete_download').on('click', function () {
        confirmDialog("{$text_resources.downloads_confirm_delete|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'delete_download');
                $('#download-editor-form').trigger('submit');
            }
        });
        return false;
    });

    // add download button
    $('#add_download').on('click', function () {
        $('#add_download_action').attr('value', 'add_download');
        $('#add_form_hidden').trigger('submit');
    });

});