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
            $formToSubmit.trigger('submit');
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

        var handle = '.list_element_item_drag_handle';
        var dragSrc = null;
        var lastTarget = null;
        var lastBefore = null;

        $container.children('.list-element-sortable-item').each(function () {
            var item = this;
            item.draggable = true;

            item.addEventListener('dragstart', function (e) {
                if (!$(e.target).closest(handle).length) {
                    e.preventDefault();
                    return;
                }
                dragSrc = item;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', '');
                requestAnimationFrame(function () { $(item).addClass('list-element-sortable-item-active'); });
            });

            item.addEventListener('dragover', function (e) {
                if (!dragSrc || dragSrc === item) return;
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';

                var rect = item.getBoundingClientRect();
                var before = e.clientY < rect.top + rect.height / 2;

                if (before && dragSrc.nextElementSibling === item) return;
                if (!before && item.nextElementSibling === dragSrc) return;
                if (item === lastTarget && before === lastBefore) return;
                lastTarget = item;
                lastBefore = before;

                $container[0].insertBefore(dragSrc, before ? item : item.nextElementSibling);
            });

            item.addEventListener('dragend', function () {
                $(item).removeClass('list-element-sortable-item-active');
                dragSrc = null;
                lastTarget = null;
                lastBefore = null;
                updateListItemOrder($container);
            });
        });

        $container[0].addEventListener('dragover', function (e) {
            if (dragSrc) e.preventDefault();
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