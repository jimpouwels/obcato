/*
	Author: Jim Pouwels
	Date: June 11th, 2011
*/

// initialize event handlers
$(document).ready(function() {
	// apply button
	$('#apply_settings').click(function() {
		$('#settings_form').submit();
	});
});