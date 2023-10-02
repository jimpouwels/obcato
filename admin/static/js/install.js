$(document).ready(function () {
    $('.install_submit_button').click(function () {
        $('#install_form').submit();
    });

    $('.delete_install_files_button').click(function () {
        $('#installation_finish_type').val("delete_install_files");
        $('#install_form').submit();
    });

    $('.redirect_to_login').click(function () {
        $('#installation_finish_type').val("redirect_to_login");
        $('#install_form').submit();
    });
});