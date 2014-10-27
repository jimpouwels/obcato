$(document).ready(function() {

    // install button
    $('#upload_component').click(function() {
        $('#action').attr('value', 'install_component');
        $('#install_component_form').submit();
    });

});