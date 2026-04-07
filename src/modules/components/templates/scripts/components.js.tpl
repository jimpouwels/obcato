$(document).ready(function () {

    // install button
    $('#upload_component').click(function () {
        $('#action').attr('value', 'install_component');
        $('#install_component_form').submit();
    });

    // uninstall button
    $('#uninstall_component').click(function () {
        var confirmed = confirm("{$text_resources.components_confirm_delete}");
        if (confirmed) {
            $('#action').attr('value', 'uninstall_component');
            $('#uninstall_component_form').submit();
        } else {
            return false;
        }
    });

});