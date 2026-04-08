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

// Global variable to store insert position
var elementInsertPosition = null;

// Save scroll position and insert position before form submit
function saveScrollPosition() {
    var data = {
        position: window.pageYOffset || document.documentElement.scrollTop,
        insertPosition: elementInsertPosition
    };
    sessionStorage.setItem('elementHolderScrollData', JSON.stringify(data));
}

// Restore scroll position after page load, compensating for newly inserted element
function restoreScrollPosition() {
    var savedData = sessionStorage.getItem('elementHolderScrollData');
    if (savedData !== null) {
        try {
            var data = JSON.parse(savedData);
            
            // Small delay to ensure DOM is fully rendered
            setTimeout(function() {
                // If we inserted an element, we need to adjust scroll position
                if (data.insertPosition !== null && data.insertPosition !== undefined) {
                    // Find the newly inserted element (it will be at the insert position)
                    var $elements = $('.draggable_wrapper');
                    if ($elements.length > data.insertPosition) {
                        var newElement = $elements.eq(data.insertPosition)[0];
                        if (newElement) {
                            // Make sure the new element is expanded
                            var elementId = getElementIdFromElementNode($(newElement).closest('.collapsable_root_wrapper'));
                            if (elementId) {
                                showElement(elementId);
                            }
                            
                            // Get the height of the new element plus its margins  
                            var $newElement = $(newElement);
                            var elementHeight = $newElement.outerHeight(true);
                            
                            // Get the position of the new element
                            var elementTop = $newElement.offset().top;
                            
                            // If the saved scroll position was below the insert point,
                            // we need to add the element height to maintain visual position
                            if (data.position > elementTop) {
                                data.position += elementHeight + 32; // +32 for insert button spacing
                            }
                        }
                    }
                }
                
                window.scrollTo(0, data.position);
            }, 100);
        } catch (e) {
            console.error('Error restoring scroll position:', e);
        }
        sessionStorage.removeItem('elementHolderScrollData');
    }
}

// Shows the element selector modal at a specific position
function showElementSelector(position) {
    elementInsertPosition = position;
    $('#element-selector-modal').fadeIn(200);
}

// Hides the element selector modal
function hideElementSelector() {
    $('#element-selector-modal').fadeOut(200);
    elementInsertPosition = null;
}

// Inserts element at the stored position
function insertElementAtPosition(elementTypeId) {
    var $inputField = $('#add_element_type_id');
    if ($inputField.length == 0) {
        alert('Fout: Kan element niet toevoegen');
        return false;
    }
    
    $inputField.attr('value', elementTypeId);
    
    // Set insert position
    var $positionField = $('#element_insert_position');
    if ($positionField.length == 0) {
        // Create hidden field if it doesn't exist
        $('<input>').attr({
            type: 'hidden',
            id: 'element_insert_position',
            name: 'element_insert_position',
            value: elementInsertPosition
        }).appendTo('#element_holder_form_id');
    } else {
        $positionField.attr('value', elementInsertPosition);
    }
    
    hideElementSelector();
    $('#element_holder_form_id').submit();
}

// Restore scroll position on page load
$(document).ready(function() {
    restoreScrollPosition();
    
    // Save scroll position before any element holder form submit
    $('#element_holder_form_id').on('submit', function() {
        saveScrollPosition();
    });
});

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
    // Save scroll position before submitting
    var data = {
        position: window.pageYOffset || document.documentElement.scrollTop,
        insertPosition: null
    };
    sessionStorage.setItem('elementHolderScrollData', JSON.stringify(data));
    
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
    let imagesContainer = $('#photo_album_element_' + elementId + '_selected_images');
    let entityId = imagesContainer.data('entity-id');
    let updateEndpoint = imagesContainer.data('update-endpoint');
    
    $.ajax({
        url: updateEndpoint,
        type: 'PUT',
        data: JSON.stringify({
            'id': entityId,
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
    let getEndpoint = imagesContainer.data('get-endpoint');
    
    $.ajax({
        url: getEndpoint,
        type: 'GET',
        success: function(response) {
            if (response.length === 0) {
                imagesContainer.empty();
                return;
            }
            
            imagesContainer.html('<div class="selected-images-grid"></div>');
            let gridContainer = imagesContainer.find('.selected-images-grid');
            
            response.forEach(result => {
                let card = $('<div class="selected-image-card"></div>');
                
                // Delete button with X icon
                let deleteBtn = $('<div class="selected-image-delete"></div>');
                deleteBtn.html('<svg viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>');
                deleteBtn.on('click', function(e) {
                    e.stopPropagation();
                    if (confirm('Weet u zeker dat u deze afbeelding wilt verwijderen?')) {
                        deleteImage(elementId, result.id);
                    }
                });
                card.append(deleteBtn);
                
                // Thumbnail
                card.append('<div class="selected-image-thumb"><img src="' + result.url + '" alt="' + (result.alternative_text || '') + '" /></div>');
                
                // Info
                let info = $('<div class="selected-image-info"></div>');
                info.append('<div class="selected-image-title">' + (result.title || 'Untitled') + '</div>');
                info.append('<div class="selected-image-alt">' + (result.alternative_text || 'Geen alt-tekst') + '</div>');
                card.append(info);
                
                gridContainer.append(card);
            });
        },
        error: function(xhr, status, error) {
            console.log(xhr, status, error);
        }
    });
}

function deleteImage(elementId, imageId) {
    let imagesContainer = $('#photo_album_element_' + elementId + '_selected_images');
    let entityId = imagesContainer.data('entity-id');
    let deleteEndpoint = imagesContainer.data('delete-endpoint');
    
    $.ajax({
        url: deleteEndpoint,
        type: 'DELETE',
        data: JSON.stringify({
            'id': entityId,
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

// Image selector modal functions
var imageSearchTimeout = null;
var imageSelectMode = 'single'; // 'single' or 'multiple'
var currentImageContext = null;
var currentFieldName = null;

function openImageSelector(contextId, multiple) {
    currentImageContext = contextId;
    imageSelectMode = multiple ? 'multiple' : 'single';
    
    // Get field name from global variable (only set in single mode)
    if (!multiple) {
        currentFieldName = window['imageSelector_fieldName_' + contextId];
    }
    
    $('#image-selector-modal-' + contextId).fadeIn(200);
    
    // Focus on search input
    setTimeout(function() {
        $('#image-search-' + contextId).focus();
    }, 250);
    
    // Setup search event
    $('#image-search-' + contextId).off('input').on('input', function() {
        var keyword = $(this).val();
        clearTimeout(imageSearchTimeout);
        
        if (keyword.length > 0) {
            imageSearchTimeout = setTimeout(function() {
                searchImages(contextId, keyword);
            }, 300);
        } else {
            $('#image-selector-grid-' + contextId).html('<div class="image-selector-loading">Start met typen om afbeeldingen te zoeken...</div>');
        }
    });
    
    // Setup escape key handler
    $(document).off('keydown.imageSelector').on('keydown.imageSelector', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            closeImageSelector(contextId);
        }
    });
}

function closeImageSelector(contextId) {
    $('#image-selector-modal-' + contextId).fadeOut(200);
    $('#image-search-' + contextId).val('');
    $('#image-selector-grid-' + contextId).html('<div class="image-selector-loading">Start met typen om afbeeldingen te zoeken...</div>');
    // Remove escape key handler
    $(document).off('keydown.imageSelector');
}

function searchImages(contextId, keyword) {
    var gridContainer = $('#image-selector-grid-' + contextId);
    gridContainer.html('<div class="image-selector-loading">Zoeken...</div>');
    
    // Get already selected images for multiple mode
    var selectedImageIds = [];
    if (imageSelectMode === 'multiple') {
        var getEndpoint = $('#photo_album_element_' + contextId + '_selected_images').data('get-endpoint');
        // Fetch currently selected images
        $.ajax({
            url: getEndpoint,
            method: 'GET',
            async: false, // Make it synchronous so we have the data before searching
            success: function(images) {
                selectedImageIds = images.map(img => img.id);
            }
        });
    }
    
    $.ajax({
        url: '/admin/api/image/search?keyword=' + encodeURIComponent(keyword),
        method: 'GET',
        success: function(response) {
            if (response.length === 0) {
                gridContainer.html('<div class="image-selector-loading">Geen afbeeldingen gevonden.</div>');
                return;
            }
            
            gridContainer.empty();
            response.forEach(function(image) {
                var isSelected = selectedImageIds.includes(image.id);
                
                var item = $('<div class="image-selector-item' + (isSelected ? ' selected' : '') + '" data-image-id="' + image.id + '"></div>');
                item.append('<div class="image-selector-checkmark"><svg viewBox="0 0 24 24" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg></div>');
                item.append('<div class="image-selector-thumb"><img src="' + image.url + '" alt="' + (image.alternative_text || '') + '" /></div>');
                item.append('<div class="image-selector-info"><div class="image-selector-title">' + (image.title || 'Untitled') + '</div><div class="image-selector-alt">' + (image.alternative_text || 'Geen alt-tekst') + '</div></div>');
                
                item.on('click', function() {
                    selectImage(contextId, image, $(this));
                });
                
                gridContainer.append(item);
            });
        },
        error: function(xhr, status, error) {
            gridContainer.html('<div class="image-selector-loading">Fout bij het zoeken: ' + error + '</div>');
            console.error(status, error);
        }
    });
}

function selectImage(contextId, image, element) {
    if (imageSelectMode === 'single') {
        // Get endpoint and entity ID from data attribute
        let $field = $('#' + currentFieldName);
        let entityId = $field.data('entity-id');
        let updateEndpoint = $field.data('update-endpoint');
        
        // Single selection - save via REST and update preview
        $.ajax({
            url: updateEndpoint,
            type: 'PUT',
            data: JSON.stringify({
                'id': entityId,
                'image': image.id
            }),
            success: function(response) {
                $('#' + currentFieldName).val(image.id);
                updateSingleImagePreview(currentFieldName, image);
                closeImageSelector(contextId);
            },
            error: function(xhr, status, error) {
                console.error('Error saving image:', status, error);
            }
        });
    } else {
        // Multiple selection - toggle selection
        if (element.hasClass('selected')) {
            element.removeClass('selected');
            deleteImage(contextId, image.id);
        } else {
            element.addClass('selected');
            addImage(contextId, image.id);
        }
    }
}

// Update single image preview (for both element and article)
function updateSingleImagePreview(fieldName, image) {
    let previewContainer = $('#' + fieldName + '_preview');
    let $field = $('#' + fieldName);
    let contextId = $field.data('context-id');
    
    // If image is just an ID (number/string), we need to fetch it
    if (typeof image === 'number' || (typeof image === 'string' && !isNaN(image))) {
        let imageId = image;
        if (!imageId) {
            previewContainer.empty();
            return;
        }
        
        // Get endpoint from data attribute
        var getEndpoint = $field.data('get-endpoint');
        
        // Fetch image details
        $.ajax({
            url: getEndpoint,
            method: 'GET',
            success: function(response) {
                if (response) {
                    updateSingleImagePreview(fieldName, response);
                } else {
                    previewContainer.empty();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching image:', status, error);
                previewContainer.empty();
            }
        });
        return;
    }
    
    // If no image data, clear preview
    if (!image) {
        previewContainer.empty();
        return;
    }
    
    // Build preview card
    let card = $('<div class=\"single-image-preview-card\"></div>');
    
    // Delete button
    let deleteBtn = $('<div class=\"single-image-preview-delete\"></div>');
    deleteBtn.html('<svg viewBox=\"0 0 24 24\" fill=\"none\"><path d=\"M18 6L6 18M6 6l12 12\" stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/></svg>');
    deleteBtn.on('click', function(e) {
        e.stopPropagation();
        
        // Get endpoint and entity ID from data attribute
        let $field = $('#' + fieldName);
        let entityId = $field.data('entity-id');
        let deleteEndpoint = $field.data('delete-endpoint');
        
        // Delete via REST API
        $.ajax({
            url: deleteEndpoint,
            type: 'DELETE',
            data: JSON.stringify({
                'id': entityId
            }),
            success: function(response) {
                $('#' + fieldName).val('');
                previewContainer.empty();
            },
            error: function(xhr, status, error) {
                console.error('Error deleting image:', status, error);
            }
        });
    });
    card.append(deleteBtn);
    
    // Thumbnail
    card.append('<div class=\"single-image-preview-thumb\"><img src=\"' + image.url + '\" alt=\"' + (image.alternative_text || '') + '\" /></div>');
    
    // Info
    let info = $('<div class=\"single-image-preview-info\"></div>');
    info.append('<div class=\"single-image-preview-title\">' + (image.title || 'Untitled') + '</div>');
    info.append('<div class=\"single-image-preview-alt\">' + (image.alternative_text || 'Geen alt-tekst') + '</div>');
    card.append(info);
    
    previewContainer.html(card);
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