$(document).ready(function () {
    $('.install_submit_button').on('click', function () {
        $('#install_form').trigger('submit');
    });

    $('.delete_install_files_button').on('click', function () {
        $('#installation_finish_type').val("delete_install_files");
        $('#install_form').trigger('submit');
    });

    $('.redirect_to_login').on('click', function () {
        $('#installation_finish_type').val("redirect_to_login");
        $('#install_form').trigger('submit');
    });
});