// Modern Confirm Dialog
// Replaces the native confirm() with a custom styled dialog
function showConfirm(message, options) {
    return new Promise(function(resolve) {
        var opts = options || {};
        var title = opts.title || 'Bevestiging';
        var confirmText = opts.confirmText || 'Bevestigen';
        var cancelText = opts.cancelText || 'Annuleren';
        var isDanger = opts.danger || false;
        
        // Set dialog content
        $('#confirm-dialog-title').text(title);
        $('#confirm-dialog-message').text(message);
        $('#confirm-dialog-confirm').text(confirmText);
        $('#confirm-dialog-cancel').text(cancelText);
        
        // Add/remove danger class
        if (isDanger) {
            $('#confirm-dialog-confirm').addClass('danger');
        } else {
            $('#confirm-dialog-confirm').removeClass('danger');
        }
        
        // Show dialog
        $('#confirm-dialog').fadeIn(200);
        
        // Store handlers so we can remove them later
        var confirmHandler = function() {
            $('#confirm-dialog').fadeOut(200);
            $('#confirm-dialog-confirm').off('click', confirmHandler);
            $('#confirm-dialog-cancel').off('click', cancelHandler);
            $('.confirm-dialog-backdrop').off('click', cancelHandler);
            resolve(true);
        };
        
        var cancelHandler = function() {
            $('#confirm-dialog').fadeOut(200);
            $('#confirm-dialog-confirm').off('click', confirmHandler);
            $('#confirm-dialog-cancel').off('click', cancelHandler);
            $('.confirm-dialog-backdrop').off('click', cancelHandler);
            resolve(false);
        };
        
        // Attach event listeners
        $('#confirm-dialog-confirm').on('click', confirmHandler);
        $('#confirm-dialog-cancel').on('click', cancelHandler);
        $('.confirm-dialog-backdrop').on('click', cancelHandler);
        
        // Keyboard shortcuts
        var keyHandler = function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                cancelHandler();
            } else if (e.key === 'Enter' || e.keyCode === 13) {
                confirmHandler();
            }
        };
        
        $(document).one('keydown', keyHandler);
    });
}

// Convenience function that mimics the old confirm() behavior for easy migration
function confirmDialog(message) {
    return showConfirm(message, { danger: true });
}

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

$(document).ready(function() {
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
    confirmDialog(confirmMessage).then(function(confirmed) {
        if (confirmed) {
            $('#action').attr('value', 'update_element_holder');
            $('#element_holder_form_id').submit();
        }
    });
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
        var $container = $(".draggable_items");
        
        $(".draggable_items").sortable({
            items: '> .collapsable_root_wrapper',
            opacity: 0.6,
            cursor: 'move',
            cancel: '.rich-text-content, .rich-text-toolbar, input, textarea, select, button, a',
            start: function() {
                // Hide all insert buttons during drag
                $('.element-insert-button').css('visibility', 'hidden');
            },
            update: function () {
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
                
                // Remove all existing insert buttons
                $('.element-insert-button').remove();
                
                // Re-insert buttons at correct positions
                var $container = $('#element_container');
                var buttonHtml = '<div class="element-insert-button" data-insert-position="POS">' +
                    '<button type="button" class="insert-btn" onclick="showElementSelector(POS); return false;" title="Element invoegen">' +
                    '<svg width="16" height="16" viewBox="0 0 16 16" fill="none">' +
                    '<path d="M8 3V13M3 8H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                    '</svg></button>' +
                    '<div class="insert-indicator"><div class="insert-line"></div>' +
                    '<div class="insert-arrow">' +
                    '<svg width="12" height="12" viewBox="0 0 12 12" fill="none">' +
                    '<path d="M6 2L6 10M6 10L3 7M6 10L9 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
                    '</svg></div></div></div>';
                
                // Insert button at the beginning
                $container.prepend(buttonHtml.replace(/POS/g, '0'));
                
                // Insert button after each element
                var elementIndex = 0;
                $container.children('.collapsable_root_wrapper').each(function() {
                    elementIndex++;
                    $(this).after(buttonHtml.replace(/POS/g, elementIndex));
                });
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
    confirmDialog("Weet u zeker dat u dit linkdoel wilt verwijderen?").then(function(confirmed) {
        if (confirmed) {
            $('#delete_link_target').attr('value', linkId);
            $('#action').attr('value', 'update_element_holder');
            var $editorForm = $('#element_holder_form_id');
            $editorForm.submit();
        }
    });
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
    var ratio = document.body.scrollHeight > 0 ? scrollPosition / document.body.scrollHeight : 0;
    localStorage.setItem("sa_element_holder_scroll_position", JSON.stringify({
        elementHolderId: elementHolderId,
        ratio: ratio
    }));
}

function getScrollRatio(elementHolderId) {
    var scrollPos = JSON.parse(localStorage.getItem("sa_element_holder_scroll_position"));
    if (!scrollPos) {
        return 0;
    }
    if (scrollPos.elementHolderId == elementHolderId) {
        return scrollPos.ratio || 0;
    } else {
        storeScrollPosition(elementHolderId, 0);
        return 0;
    }
}

$(document).ready(function () {
    var elementHolderId = $('#element_holder_id').attr('value');
    if (elementHolderId) {
        var scrollRatio = getScrollRatio(elementHolderId);
        if (scrollRatio > 0) {
            setTimeout(function () {
                window.scrollTo(0, Math.round(scrollRatio * document.body.scrollHeight));
            }, 100);
            setTimeout(function () {
                window.scrollTo(0, Math.round(scrollRatio * document.body.scrollHeight));
            }, 500);
        }
    }

    $(window).on('scroll', function () {
        if (!elementHolderId) return;
        clearTimeout($.data(this, 'scrollTimer'));
        $.data(this, 'scrollTimer', setTimeout(function () {
            storeScrollPosition(elementHolderId, window.pageYOffset || document.documentElement.scrollTop);
        }, 100));
    });

    $('#element_holder_form_id').on('submit', function () {
        if (elementHolderId) {
            storeScrollPosition(elementHolderId, window.pageYOffset || document.documentElement.scrollTop);
        }
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
                let card = $('<div class="selected-image-card" data-image-id="' + result.id + '"></div>');
                
                // Drag handle with 4-way arrows icon
                let dragHandle = $('<div class="selected-image-drag-handle"></div>');
                dragHandle.html('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="3" x2="12" y2="21" stroke-width="2"/><line x1="3" y1="12" x2="21" y2="12" stroke-width="2"/><path d="M12 3l-3 3m3-3l3 3m-3 15l-3-3m3 3l3-3M3 12l3-3m-3 3l3 3m15-3l-3-3m3 3l-3 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>');
                card.append(dragHandle);
                
                // Delete button with X icon
                let deleteBtn = $('<div class="selected-image-delete"></div>');
                deleteBtn.html('<svg viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>');
                deleteBtn.on('click', function(e) {
                    e.stopPropagation();
                    confirmDialog('Weet u zeker dat u deze afbeelding wilt verwijderen?').then(function(confirmed) {
                        if (confirmed) {
                            deleteImage(elementId, result.id);
                        }
                    });
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
            
            // Initialize drag and drop functionality
            initImageDragDrop(elementId);
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

function initImageDragDrop(elementId) {
    let gridContainer = $('#photo_album_element_' + elementId + '_selected_images .selected-images-grid');
    
    if (gridContainer.length === 0) {
        return;
    }
    
    let draggedCard = null;
    let placeholder = null;
    let offsetX, offsetY;
    let lastTargetCardElement = null; // Store DOM element for hysteresis
    let lastTargetBefore = false; // Store position for hysteresis
    
    gridContainer.find('.selected-image-card').each(function() {
        let card = $(this);
        let dragHandle = card.find('.selected-image-drag-handle');
        
        dragHandle.on('mousedown', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            draggedCard = card;
            
            // For fixed positioning, use viewport coordinates (clientX/Y) not document coordinates (pageX/Y)
            let rect = card[0].getBoundingClientRect();
            offsetX = e.clientX - rect.left;
            offsetY = e.clientY - rect.top;
            
            // Store original dimensions
            let width = card.outerWidth();
            let height = card.outerHeight();
            
            // Create placeholder
            placeholder = $('<div class="selected-image-card-placeholder"></div>');
            placeholder.css({
                width: width + 'px',
                height: height + 'px'
            });
            
            // Insert placeholder where card was
            card.before(placeholder);
            
            // Lift card out of normal flow and make it follow mouse
            card.css({
                position: 'fixed',
                width: width + 'px',
                height: height + 'px',
                left: (e.clientX - offsetX) + 'px',
                top: (e.clientY - offsetY) + 'px',
                zIndex: '10000',
                margin: '0',
                pointerEvents: 'none',
                opacity: '0.95',
                transform: 'scale(1.05) rotate(2deg)',
                boxShadow: '0 12px 40px rgba(0, 0, 0, 0.4)',
                transition: 'none'
            });
            
            $(document).on('mousemove.imagedrag', function(e) {
                if (!draggedCard) return;
                
                // Move card with mouse
                draggedCard.css({
                    left: (e.clientX - offsetX) + 'px',
                    top: (e.clientY - offsetY) + 'px'
                });
                
                // Helper: calculate distance from mouse to element center
                function distanceToCenter(element) {
                    let rect = element.getBoundingClientRect();
                    let dx = e.clientX - (rect.left + rect.width / 2);
                    let dy = e.clientY - (rect.top + rect.height / 2);
                    return Math.sqrt(dx * dx + dy * dy);
                }
                
                let allCards = gridContainer.find('.selected-image-card').not(draggedCard);
                let targetCard = null;
                let targetBefore = false;
                
                // Check if mouse is directly over any card
                allCards.each(function() {
                    let rect = this.getBoundingClientRect();
                    
                    if (e.clientX >= rect.left && e.clientX <= rect.right &&
                        e.clientY >= rect.top && e.clientY <= rect.bottom) {
                        
                        targetCard = $(this);
                        let relativeX = (e.clientX - rect.left) / rect.width;
                        
                        // Hysteresis: wider thresholds for same card to prevent flickering
                        if (lastTargetCardElement === this) {
                            targetBefore = lastTargetBefore ? relativeX < 0.65 : relativeX < 0.35;
                        } else {
                            targetBefore = relativeX < 0.5;
                        }
                        
                        lastTargetCardElement = this;
                        lastTargetBefore = targetBefore;
                        return false; // Break
                    }
                });
                
                // If no direct hit, find closest card (but only use if significantly closer than placeholder)
                if (!targetCard) {
                    let minDist = Infinity;
                    let closest = null;
                    let closestBefore = false;
                    
                    allCards.each(function() {
                        let dist = distanceToCenter(this);
                        if (dist < minDist) {
                            minDist = dist;
                            closest = this;
                            let rect = this.getBoundingClientRect();
                            let dx = e.clientX - (rect.left + rect.width / 2);
                            let dy = e.clientY - (rect.top + rect.height / 2);
                            closestBefore = Math.abs(dy) < rect.height ? dx < 0 : dy < 0;
                        }
                    });
                    
                    // Only use closest card if it's 30px+ closer than placeholder
                    if (closest && minDist < distanceToCenter(placeholder[0]) - 30) {
                        targetCard = $(closest);
                        targetBefore = closestBefore;
                    }
                }
                
                // Move placeholder if needed
                if (targetCard) {
                    let sibling = targetBefore ? placeholder.next()[0] : placeholder.prev()[0];
                    if (sibling !== targetCard[0]) {
                        targetBefore ? targetCard.before(placeholder) : targetCard.after(placeholder);
                    }
                }
            });
            
            $(document).on('mouseup.imagedrag', function() {
                if (draggedCard) {
                    // Reset card styles
                    draggedCard.css({
                        position: '',
                        width: '',
                        height: '',
                        left: '',
                        top: '',
                        zIndex: '',
                        margin: '',
                        pointerEvents: '',
                        opacity: '',
                        transform: '',
                        boxShadow: '',
                        transition: ''
                    });
                    
                    // Put card back in place of placeholder
                    placeholder.replaceWith(draggedCard);
                    
                    // Cleanup
                    draggedCard = null;
                    placeholder = null;
                    lastTargetCardElement = null;
                    lastTargetBefore = false;
                    
                    $(document).off('mousemove.imagedrag');
                    $(document).off('mouseup.imagedrag');
                    
                    // Save order
                    saveImageOrder(elementId);
                }
            });
        });
    });
}

function saveImageOrder(elementId) {
    let imagesContainer = $('#photo_album_element_' + elementId + '_selected_images');
    let entityId = imagesContainer.data('entity-id');
    let gridContainer = imagesContainer.find('.selected-images-grid');
    
    let imageIds = [];
    gridContainer.find('.selected-image-card').each(function() {
        imageIds.push(parseInt($(this).data('image-id')));
    });
    
    $.ajax({
        url: '/admin/api/photo_album_element/reorder_images',
        type: 'POST',
        data: JSON.stringify({
            'id': entityId,
            'imageIds': imageIds
        }),
        contentType: 'application/json',
        success: function(response) {
            // Order saved successfully
        },
        error: function(xhr, status, error) {
            console.log('Error saving order:', xhr, status, error);
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
