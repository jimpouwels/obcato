$(document).ready(function () {
    $('#update_sitewide_pages').click(() => {
        $('#action').attr('value', 'add_sitewide_page');
        $('#update_sitewide_pages_form').submit();
    });

    $('#remove_sitewide_pages').click(() => {
        let confirmed = confirm("Weet u zeker dat u de geselecteerde sitewide pages wilt verwijderen?");
        if (confirmed) {
            $('#action').attr('value', 'remove_sitewide_pages');
            $('#update_sitewide_pages_form').submit();
        } else {
            return false;
        }
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