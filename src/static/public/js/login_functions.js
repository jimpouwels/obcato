/*
    Fix for submitting the login form by
    hitting the "Enter" key
*/
$(document).ready(function () {
    $('.admin_field').each(function () {
        $(this).on('keydown', function (e) {
            if (e.key === 'Enter') {
                $('#form-login').trigger('submit');
            }
        });
    });
});