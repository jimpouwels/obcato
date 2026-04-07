// handles the 'add element' click in the navigation menu
function addElement(elementTypeId, errorMessage) {
    var $inputField = $('#add_element_type_id');
    if ($inputField.length == 0) {
        alert(errorMessage);
        return false;
    } else {
        $inputField.attr('value', elementTypeId);
    }
    var $editorForm = $('#element_holder_form_id');
    $editorForm.submit();
}

// Saves the selected element holder back to the link in the parent window
function submitSelectionBackToOpener(backRef, backValue, backClickId) {
    var $backField = window.opener.$('#' + backRef);
    if ($backField.length > 0) {
        $backField.attr('value', backValue);
        // Delay the click and window close to ensure value is set
        setTimeout(function() {
            window.opener.$('#' + backClickId).click();
            setTimeout(function() {
                window.close();
            }, 200);
        }, 50);
    } else {
        alert('Fout: Kan niet opgeslagen worden, waarschijnlijk is het hoofdscherm gesloten');
    }
}

// handles the 'delete element' click
function deleteElement(elementId, formFieldId, confirmMessage) {
    var $inputField = $('#' + formFieldId);
    if ($inputField.length == 0) {
        alert('Fout: Kan element niet verwijderen');
        return false;
    } else {
        $inputField.attr('value', elementId);
    }
    var confirmed = confirm(confirmMessage);
    if (confirmed) {
        $('#action').attr('value', 'update_element_holder');
        $('#element_holder_form_id').submit();
    } else {
        return false;
    }
}

// elements visibility
$(document).ready(function () {
    getAllElements().each(function () {
        var rootNode = $(this);
        var elementId = getElementIdFromElementNode(rootNode);
        if (!isVisible(elementId)) {
            hideElement(elementId);
        }
        var elementHeader = findElementHeader(rootNode);
        elementHeader.click(function (e) {
            /*
                currentTarget == the node where the click listener is attached to ('.collapsable_header')
                target == the node that captured the event and bubbled it up the tree
                if there was a child element that captured and bubbled the event, don't toggle (unless it's the left part of the header).
            */
            if (e.currentTarget !== e.target && !e.target.className.includes('collapsable_header_left')) {
                return;
            }
            toggleElement(elementId);
        });
    });
});

function toggleElement(elementId) {
    if (isVisible(elementId)) {
        hideElement(elementId);
    } else {
        showElement(elementId);
    }
}

function toggleAllElements(elementId) {
    if (isVisible(elementId)) {
        hideElements();
    } else {
        showElements();
    }
}

function hideElements() {
    getAllElements().each(function () {
        hideElement(getElementIdFromElementNode($(this)));
    });
}

function showElements() {
    getAllElements().each(function () {
        showElement(getElementIdFromElementNode($(this)));
    });
}

function hideElement(elementId) {
    $('#collapsable_body_' + elementId).hide();
    $('#element_summary_text_' + elementId).show();
    localStorage.setItem("sa_element_visible_" + elementId, "false");
}

function showElement(elementId) {
    $('#collapsable_body_' + elementId).show();
    $('#element_summary_text_' + elementId).hide();
    localStorage.setItem("sa_element_visible_" + elementId, "true");
}

function isVisible(elementId) {
    return localStorage.getItem('sa_element_visible_' + elementId) != 'false';
}

function getAllElements() {
    return $('.collapsable_root_wrapper');
}

function getElementIdFromElementNode(elementNode) {
    return elementNode.find('.collapsable_id_holder').text()
}

function findElementHeader(elementNode) {
    return elementNode.find('.collapsable_header');
}

// initializes sortable elements
$(document).ready(function () {
    $(function () {
        $(".draggable_items").sortable({
            opacity: 0.6, cursor: 'move', update: function () {
                var idString = '';
                $('.draggable_id_holder').each(function () {
                    if (idString != '') {
                        idString += ',';
                    }
                    idString = idString + $(this).text();
                });
                var $order_field = $('#draggable_order');
                if ($order_field.length > 0) {
                    $order_field.attr("value", idString);
                }
            }
        });
    });
});

// starts sliding in the notification bar
$(document).ready(function () {
    // slides in the notification bar
    var slider = $("#notification-slider");
    var originalMarginTop = slider.css('margin-top');
    slider.animate({
        marginTop: "0"
    }, 1000);
    setTimeout(function () {
        slider.animate({
            marginTop: originalMarginTop
        }, 1000);
    }, 3000);
});

// handles add link
function addLink() {
    var $elementHolderForm = $('#element_holder_form_id');
    if ($elementHolderForm.length == 0) {
        alert('Fout: Kan link niet toevoegen');
        return false;
    } else {
        var $actionField = $('#action');
        $actionField.attr('value', 'add_link');
        $elementHolderForm.submit();
    }
}

// makes sure every field that should be able to
// contain a link is prepared
var lastFocussedField = undefined;
$(document).ready(function () {
    $('.linkable').each(function () {
        $(this).focus(function () {
            lastFocussedField = document.getElementById($(this).attr("id"));
        });
    });
});

// handles put link
function putLink(linkId) {
    var errorMessage = "Fout: U heeft geen tekst geselecteerd, of het is niet toegestaan aan het huidige veld een link toe te voegen.";
    if (lastFocussedField == undefined) {
        alert(errorMessage);
    } else {
        var len = lastFocussedField.value.length;
        var start = lastFocussedField.selectionStart;
        var end = lastFocussedField.selectionEnd;
        var value = lastFocussedField.value;
        var selectedText = value.substring(start, end);
        if (selectedText != undefined && selectedText.length > 0) {
            newText = "[LINK C=\"" + ($('#link_' + linkId + '_code').val()) + "\"]" + selectedText + "[/LINK]";
            lastFocussedField.value = lastFocussedField.value.substring(0, start) + newText + lastFocussedField.value.substring(end, len);
        } else {
            alert(errorMessage);
        }
    }
}

// deletes the selected link target for a link
function deleteLink(linkId) {
    var confirmed = confirm("Weet u zeker dat u dit linkdoel wilt verwijderen?");
    if (confirmed) {
        $('#delete_link_target').attr('value', linkId);
        $('#action').attr('value', 'update_element_holder');
        var $editorForm = $('#element_holder_form_id');
        $editorForm.submit();
    }
    return false;
}

// horizontal dropdown menu
$(document).ready(function () {
    $('.module-group').mouseover(function () {
        var $submenu = $(this).find('.submenu');
        $submenu.show();
    });
    $('#menu > li').mouseout(function () {
        var $submenu = $(this).find('.submenu');
        $submenu.hide();
    });
});

// element holder scrollbehaviour 
function storeScrollPosition(elementHolderId, scrollPosition) {
    localStorage.setItem("sa_element_holder_scroll_position", JSON.stringify({
        elementHolderId: elementHolderId,
        pos: scrollPosition
    }));
}

function getScrollPosition(elementHolderId) {
    var scrollPos = JSON.parse(localStorage.getItem("sa_element_holder_scroll_position"));
    if (!scrollPos) {
        return 0;
    }
    if (scrollPos.elementHolderId == elementHolderId) {
        return scrollPos.pos;
    } else {
        storeScrollPosition(elementHolderId, 0);
        return 0;
    }
}

$(document).ready(function () {
    var elementHolderId = $('#element_holder_id').attr('value');
    if (elementHolderId) {
        var scrollPos = getScrollPosition(elementHolderId);
        $('#content-wrapper').scrollTop(scrollPos);
    }

    $('#content-wrapper').on('scroll', function () {
        clearTimeout($.data(this, 'scrollTimer'));
        $.data(this, 'scrollTimer', setTimeout(function () {
            storeScrollPosition(elementHolderId, $('#content-wrapper').scrollTop());
        }, 100));
    });
});

// Photo album element selected images
function addImage(elementId, imageId) {
    $.ajax({
        url: '/admin/api/photo_album_element/add_image',
        type: 'PUT',
        data: JSON.stringify({
            'id': elementId,
            'image': imageId
        }),
        success: function(response) {
            updateSelectedImages(elementId);
        },
        error: function(xhr, status, error) {
            console.log(xhr, status, error);
        }
    });
}

function updateSelectedImages(elementId) {
    let imagesContainer = $('#photo_album_element_' + elementId + '_selected_images');
    $.ajax({
        url: '/admin/api/photo_album_element/images?id=' + elementId,
        type: 'GET',
        success: function(response) {
            imagesContainer.empty();
            response.forEach(result => {
                imagesContainer.append("<div class=\"photo_album_element_selected_image\"><div class=\"photo_album_element_selected_image_thumb\"><img src=\"" + result.url + "\" /></div><div class=\"photo_album_element_selected_image_details\"><p><strong>Titel: </strong>" + result.title + "</p><p><strong>AltText: </strong>" + result.alternative_text + "</p></div><div class\"photo_album_element_selected_image_delete\"><a href=\"#\" onclick=\"deleteImage(" + elementId + ", " + result.id + "); return false;\"><img src=\"/admin?file=/default/img/default_icons/delete_small.png\" /></a></div></div>");
            });
        },
        error: function(xhr, status, error) {
            console.log(xhr, status, error);
        }
    });
}

function deleteImage(elementId, imageId) {
    $.ajax({
        url: '/admin/api/photo_album_element/delete_image',
        type: 'DELETE',
        data: JSON.stringify({
            'id': elementId,
            'image': imageId
        }),
        success: function(response) {
            updateSelectedImages(elementId);
        },
        error: function(xhr, status, error) {
            console.log(xhr, status, error);
        }
    });
}

// Auto-dismiss notification bar after 2.5 seconds
$(function() {
    var $notification = $('.notification-holder');
    if ($notification.length > 0) {
        setTimeout(function() {
            $notification.addClass('hiding');
            setTimeout(function() {
                $notification.remove();
            }, 500);
        }, 2500);
    }
});

// Enhanced ImagePicker functionality
$(document).ready(function() {
    // Image view link - open modal
    $(document).on('click', '.image-view-link', function(e) {
        e.preventDefault();
        var $link = $(this);
        var imageId = $link.data('image-id');
        var imageTitle = $link.data('image-title');
        var imageType = $link.data('image-type');
        var imageUrl = $link.data('image-url');
        var pickerField = $link.data('picker-field');
        var modalId = $link.data('modal-id');
        
        var $modal = $('#' + modalId);
        
        // Set modal image
        $modal.find('.modal-image-img').attr('src', imageUrl).attr('alt', imageTitle);
        
        // Store picker field reference on modal
        $modal.data('picker-field', pickerField);
        
        // Show modal
        $modal.fadeIn(300);
    });
    
    // Image overlay actions - Change image
    $(document).on('click', '.change-image-btn', function(e) {
        console.log('Change image button clicked');
        e.preventDefault();
        e.stopPropagation();
        // Find the modal this button is in
        var $modal = $(this).closest('.image-modal');
        var pickerField = $modal.data('picker-field');
        
        console.log('Picker field:', pickerField);
        console.log('Looking for selector: #object_picker_button_wrapper_' + pickerField + ' a.button');
        
        // Close modal
        $modal.fadeOut(300);
        
        // Find and click the picker button using the field name
        if (pickerField) {
            var $pickerButton = $('#object_picker_button_wrapper_' + pickerField + ' a.button');
            console.log('Found picker button:', $pickerButton.length > 0);
            $pickerButton.click();
        }
    });
    
    // Image overlay actions - Delete image (from modal)
    $(document).on('click', '.delete-image-btn-modal', function(e) {
        console.log('Delete image button (modal) clicked');
        e.preventDefault();
        e.stopPropagation();
        var deleteFieldId = $(this).data('delete-field');
        var confirmed = confirm("Weet u zeker dat u deze afbeelding wilt verwijderen?");
        
        if (confirmed) {
            $('#' + deleteFieldId).val('true');
            $('#action').val('update_element_holder');
            // Close modal
            $(this).closest('.image-modal').fadeOut(300);
            $('#element_holder_form_id').submit();
        }
    });
    
    // Image overlay actions - Delete image (from display, not modal)
    $(document).on('click', '.delete-image-btn', function(e) {
        e.preventDefault();
        var deleteFieldId = $(this).data('delete-field');
        var confirmed = confirm("Weet u zeker dat u deze afbeelding wilt verwijderen?");
        
        if (confirmed) {
            $('#' + deleteFieldId).val('true');
            $('#action').val('update_element_holder');
            $('#element_holder_form_id').submit();
        }
    });
    
    // Close modal - only when clicking backdrop directly, not bubbled events
    $(document).on('click', '.image-modal-backdrop', function(e) {
        console.log('Backdrop clicked, target:', e.target, 'this:', this, 'match:', e.target === this);
        if (e.target === this) {
            console.log('Closing modal from backdrop');
            $(this).closest('.image-modal').fadeOut(300);
        }
    });
    
    $(document).on('click', '.image-modal-close', function(e) {
        console.log('Close button clicked');
        e.stopPropagation();
        $(this).closest('.image-modal').fadeOut(300);
    });
    
    // Prevent modal close when clicking inside the content area
    $(document).on('click', '.image-modal-content', function(e) {
        console.log('Modal content clicked, target:', e.target);
        e.stopPropagation();
    });
    
    // ESC key to close modal
    $(document).on('keyup', function(e) {
        if (e.key === 'Escape') {
            $('.image-modal:visible').fadeOut(300);
        }
    });
});