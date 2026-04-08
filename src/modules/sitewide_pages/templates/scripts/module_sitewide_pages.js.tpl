$(document).ready(function () {
    $('#update_sitewide_pages').click(() => {
        $('#action').attr('value', 'add_sitewide_page');
        $('#update_sitewide_pages_form').submit();
    });

    $('#remove_sitewide_pages').click(() => {
        confirmDialog("{$text_resources.sitewide_pages_confirm_delete|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'remove_sitewide_pages');
                $('#update_sitewide_pages_form').submit();
            }
        });
        return false;
    });
});

function moveUp(id) {
    $('#action').attr('value', 'move_up');
    $('#moveSitewidePage').attr('value', id);
    $('#update_sitewide_pages_form').submit();
}

function moveDown(id) {
    $('#action').attr('value', 'move_down');
    $('#moveSitewidePage').attr('value', id);
    $('#update_sitewide_pages_form').submit();
}