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

function updateListItemOrder($container) {
    var orderFieldId = $container.data('order-field-id');
    if (!orderFieldId) {
        return;
    }

    var itemIds = [];
    $container.find('.list-element-sortable-item').each(function() {
        var itemId = parseInt($(this).data('list-item-id'), 10);
        if (!isNaN(itemId) && itemId > 0) {
            itemIds.push(itemId);
        }
    });

    $('#' + orderFieldId).val(itemIds.join(','));
}

function initializeListItemSorting() {
    $('.list-element-sortable-items').each(function() {
        var $container = $(this);

        if ($container.data('sortable-initialized')) {
            updateListItemOrder($container);
            return;
        }

        $container.sortable({
            items: '> .list-element-sortable-item',
            handle: '.list_element_item_drag_handle',
            opacity: 0.85,
            cursor: 'move',
            tolerance: 'pointer',
            cancel: 'input, textarea, select, button, a, iframe, .rich-text-toolbar, .rich-text-content',
            start: function(event, ui) {
                ui.item.addClass('list-element-sortable-item-active');
            },
            stop: function(event, ui) {
                ui.item.removeClass('list-element-sortable-item-active');
            },
            update: function() {
                updateListItemOrder($container);
            }
        });

        $container.data('sortable-initialized', true);
        updateListItemOrder($container);
    });
}

// Auto-resize textareas in list items
function autoResizeTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

// Initialize auto-resize for all list item textareas
$(document).ready(function() {
    initializeListItemSorting();

    // Set rows attribute and auto-resize on input
    $('.list_element_item_value_field textarea').each(function() {
        $(this).attr('rows', '1');
        autoResizeTextarea(this);
    }).on('input', function() {
        autoResizeTextarea(this);
    });
});