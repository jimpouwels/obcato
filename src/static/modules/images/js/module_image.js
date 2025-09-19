/*
	Author: Jim Pouwels
	Date: August 16th, 2011
*/

// initialize event handlers
$(document).ready(function () {

    // update image button
    $('#update_image').click(function () {
        $('#action').attr('value', 'update_image');
        $('#image-editor-form').submit();
    });

    $('#image_mobile_reset').click(function () {
        $('#action').attr('value', 'reset_mobile_image');
        $('#image-editor-form').submit();
    });

    // delete image button
    $('#delete_image').click(function () {
        var confirmed = confirm("Weet u zeker dat u deze afbeelding wilt verwijderen?");
        if (confirmed) {
            $('#action').attr('value', 'delete_image');
            $('#image-editor-form').submit();
        } else {
            return false;
        }
    });

    // add image button
    $('#add_image').click(function () {
        $('#add_image_action').attr('value', 'add_image');
        $('#add_form_hidden').submit();
        return false;
    });

    // add label button
    $('#add_label').click(function () {
        $('#add_label_action').attr('value', 'add_label');
        $('#add_form_hidden').submit();
        return false;
    });

    // update label button
    $('#update_label').click(function () {
        $('#action').attr('value', 'update_label');
        $('#label_form').submit();
    });

    // delete labels button
    $('#delete_labels').click(function () {
        var $checked = false;
        $('input:checkbox').each(function () {
            if ($(this).attr('checked')) {
                $checked = true;
            }
        });
        if (!$checked) {
            alert('U heeft geen labels geselecteerd');
        } else {
            var confirmed = confirm('Weet u zeker dat u de geselecteerde label wilt verwijderen?');
            if (confirmed) {
                $('#label_delete_action').attr('value', 'delete_labels');
                $('#label_delete_form').submit();
            }
        }
    });

    // import images button
    $('#upload_zip').click(function () {
        $('#image-import-form').submit();
    });

});

// toggle image published
function toggleImagePublished(image_id) {
    $('#action').attr('value', 'toggle_image_published');
    $('#image_id').attr('value', image_id);
    $('#toggle_image_published_form').submit();
}