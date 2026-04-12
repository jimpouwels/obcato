$(document).ready(function () {

    // install button
    $('#upload_component').on('click', function () {
        $('#action').attr('value', 'install_component');
        $('#install_component_form').trigger('submit');
    });

    // uninstall button
    $('#uninstall_component').on('click', function () {
        confirmDialog("{$text_resources.components_confirm_delete|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'uninstall_component');
                $('#uninstall_component_form').trigger('submit');
            }
        });
        return false;
    });

});