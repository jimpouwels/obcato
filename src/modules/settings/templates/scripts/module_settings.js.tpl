/*
	Author: Jim Pouwels
	Date: June 11th, 2011
*/

// initialize event handlers
$(document).ready(function () {
    // apply button
    $('#apply_settings').on('click', function () {
        $('#settings_form').trigger('submit');
    });
});