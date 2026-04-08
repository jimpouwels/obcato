function addListItem(elementId, formId) {
    var $elementAddItemField = $('#element' + elementId + '_add_item');
    if ($elementAddItemField.length == 0) {
        alert('Fout: kan item niet aan element toevoegen');
    } else {
        $elementAddItemField.attr('value', 'TRUE');
        $('#action').attr('value', 'update_element_holder');
        var $formToSubmit = $('#' + formId);
        if ($formToSubmit.length == 0) {
            alert('Fout: kan item niet toevoegen omdat er geen actieve editor is gevonden');
        } else {
            $formToSubmit.submit();
        }
    }
}

// Auto-resize textareas in list items
function autoResizeTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

// Initialize auto-resize for all list item textareas
$(document).ready(function() {
    // Set rows attribute and auto-resize on input
    $('.list_element_item_value_field textarea').each(function() {
        $(this).attr('rows', '1');
        autoResizeTextarea(this);
    }).on('input', function() {
        autoResizeTextarea(this);
    });
});