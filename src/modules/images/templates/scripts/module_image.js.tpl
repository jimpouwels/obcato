/*
	Author: Jim Pouwels
	Date: August 16th, 2011
*/

// initialize event handlers
$(document).ready(function () {

    // update image button
    $('#update_image').click(function () {
        $('#action').attr('value', 'update_image');
        $('#image-editor-form').submit();
    });

    $('#image_mobile_reset').click(function () {
        $('#action').attr('value', 'reset_mobile_image');
        $('#image-editor-form').submit();
    });

    // delete image button
    $('#delete_image').click(function () {
        confirmDialog("{$text_resources.images_confirm_delete_image|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'delete_image');
                $('#image-editor-form').submit();
            }
        });
        return false;
    });

    // add image button
    $('#add_image').click(function () {
        $('#add_image_action').attr('value', 'add_image');
        $('#add_form_hidden').submit();
        return false;
    });

    // add label button
    $('#add_label').click(function () {
        $('#add_label_action').attr('value', 'add_label');
        $('#add_form_hidden').submit();
        return false;
    });

    // update label button
    $('#update_label').click(function () {
        $('#action').attr('value', 'update_label');
        $('#label_form').submit();
    });

    // delete labels button
    $('#delete_labels').click(function () {
        var $checked = false;
        $('input:checkbox').each(function () {
            if ($(this).attr('checked')) {
                $checked = true;
            }
        });
        if (!$checked) {
            alert("{$text_resources.images_alert_no_labels_selected}");
        } else {
            confirmDialog("{$text_resources.images_confirm_delete_labels|escape:'javascript'}").then(function(confirmed) {
                if (confirmed) {
                    $('#label_delete_action').attr('value', 'delete_labels');
                    $('#label_delete_form').submit();
                }
            });
        }
    });

    // import images button
    $('#upload_zip').click(function () {
        $('#image-import-form').submit();
    });

    // Initialize image cropping tool
    initImageCropTool();

});

// Image Cropping Tool
function initImageCropTool() {
    // Initialize each crop container independently
    $('.image-crop-container').each(function() {
        var $container = $(this);
        initSingleCropTool($container);
    });
}

function initSingleCropTool($container) {
    var $image = $container.find('.crop-target-image');
    var $cropArea = $container.find('.crop-area');
    var $overlay = $container.find('.crop-overlay');
    
    var isDragging = false;
    var isResizing = false;
    var resizeHandle = null;
    var startX, startY;
    var startCropRect = {};
    var imageNaturalWidth, imageNaturalHeight;
    
    // Wait for image to load
    $image.on('load', function() {
        imageNaturalWidth = this.naturalWidth;
        imageNaturalHeight = this.naturalHeight;
        
        // Initialize crop area to full image
        updateCropAreaFromFields();
    });
    
    // Trigger load if already loaded
    if ($image[0].complete) {
        $image.trigger('load');
    }
    
    // Detect which fields to use (desktop or mobile)
    var fieldPrefix = 'image_crop_';
    var sizePrefix = 'image_';
    var $testField = $('#image_crop_top');
    if ($testField.length === 0 || !$testField.closest('.image_editor_wrapper').find($container).length) {
        // Check if this is a mobile editor
        var $mobileTestField = $('#image_mobile_crop_top');
        if ($mobileTestField.length > 0 && $mobileTestField.closest('.image_editor_wrapper').find($container).length) {
            fieldPrefix = 'image_mobile_crop_';
            sizePrefix = 'image_mobile_';
        }
    }
    
    // Update crop area from field values
    function updateCropAreaFromFields() {
        var cropTop = parseInt($('#' + fieldPrefix + 'top').val()) || 0;
        var cropBottom = parseInt($('#' + fieldPrefix + 'bottom').val()) || 0;
        var cropLeft = parseInt($('#' + fieldPrefix + 'left').val()) || 0;
        var cropRight = parseInt($('#' + fieldPrefix + 'right').val()) || 0;
        
        var imageWidth = $image.width();
        var imageHeight = $image.height();
        var scaleX = imageWidth / imageNaturalWidth;
        var scaleY = imageHeight / imageNaturalHeight;
        
        var top = cropTop * scaleY;
        var left = cropLeft * scaleX;
        var width = imageWidth - (cropLeft + cropRight) * scaleX;
        var height = imageHeight - (cropTop + cropBottom) * scaleY;
        
        $cropArea.css({
            top: top + 'px',
            left: left + 'px',
            width: width + 'px',
            height: height + 'px'
        });
        
        // Update size display with cropped dimensions
        updateSizeDisplay(cropLeft, cropRight, cropTop, cropBottom);
    }
    
    // Update form fields from crop area position
    function updateFieldsFromCropArea() {
        var imageWidth = $image.width();
        var imageHeight = $image.height();
        var scaleX = imageNaturalWidth / imageWidth;
        var scaleY = imageNaturalHeight / imageHeight;
        
        var top = Math.round($cropArea.position().top * scaleY);
        var left = Math.round($cropArea.position().left * scaleX);
        var right = Math.round((imageWidth - $cropArea.position().left - $cropArea.width()) * scaleX);
        var bottom = Math.round((imageHeight - $cropArea.position().top - $cropArea.height()) * scaleY);
        
        // Ensure non-negative values
        top = Math.max(0, top);
        left = Math.max(0, left);
        right = Math.max(0, right);
        bottom = Math.max(0, bottom);
        
        $('#' + fieldPrefix + 'top').val(top);
        $('#' + fieldPrefix + 'left').val(left);
        $('#' + fieldPrefix + 'right').val(right);
        $('#' + fieldPrefix + 'bottom').val(bottom);
        
        // Update size display with cropped dimensions
        updateSizeDisplay(left, right, top, bottom);
    }
    
    // Update the size display text
    function updateSizeDisplay(cropLeft, cropRight, cropTop, cropBottom) {
        var croppedWidth = imageNaturalWidth - cropLeft - cropRight;
        var croppedHeight = imageNaturalHeight - cropTop - cropBottom;
        // Find the size display element within the same container
        var $sizeDisplay = $container.closest('.image_editor_wrapper').find('.image-size-display');
        if ($sizeDisplay.length > 0) {
            $sizeDisplay.text(croppedWidth + ' x ' + croppedHeight);
        }
    }
    
    // Handle mouse down on crop area (for dragging)
    $cropArea.on('mousedown', function(e) {
        if ($(e.target).hasClass('crop-handle')) {
            isResizing = true;
            resizeHandle = e.target.className.match(/crop-handle-(\w+)/)[1];
        } else {
            isDragging = true;
        }
        startX = e.pageX;
        startY = e.pageY;
        startCropRect = {
            top: $cropArea.position().top,
            left: $cropArea.position().left,
            width: $cropArea.width(),
            height: $cropArea.height()
        };
        e.preventDefault();
        e.stopPropagation();
    });
    
    // Handle mouse move
    $(document).on('mousemove', function(e) {
        if (!isDragging && !isResizing) return;
        
        var deltaX = e.pageX - startX;
        var deltaY = e.pageY - startY;
        var imageWidth = $image.width();
        var imageHeight = $image.height();
        
        if (isDragging) {
            // Move crop area
            var newLeft = Math.max(0, Math.min(startCropRect.left + deltaX, imageWidth - startCropRect.width));
            var newTop = Math.max(0, Math.min(startCropRect.top + deltaY, imageHeight - startCropRect.height));
            
            $cropArea.css({
                left: newLeft + 'px',
                top: newTop + 'px'
            });
        } else if (isResizing) {
            // Resize crop area
            var newRect = Object.assign({}, startCropRect);
            
            switch(resizeHandle) {
                case 'tl': // Top-left
                    newRect.left = Math.max(0, Math.min(startCropRect.left + deltaX, startCropRect.left + startCropRect.width - 20));
                    newRect.top = Math.max(0, Math.min(startCropRect.top + deltaY, startCropRect.top + startCropRect.height - 20));
                    newRect.width = startCropRect.width - (newRect.left - startCropRect.left);
                    newRect.height = startCropRect.height - (newRect.top - startCropRect.top);
                    break;
                case 'tr': // Top-right
                    newRect.top = Math.max(0, Math.min(startCropRect.top + deltaY, startCropRect.top + startCropRect.height - 20));
                    newRect.width = Math.max(20, Math.min(startCropRect.width + deltaX, imageWidth - startCropRect.left));
                    newRect.height = startCropRect.height - (newRect.top - startCropRect.top);
                    break;
                case 'bl': // Bottom-left
                    newRect.left = Math.max(0, Math.min(startCropRect.left + deltaX, startCropRect.left + startCropRect.width - 20));
                    newRect.width = startCropRect.width - (newRect.left - startCropRect.left);
                    newRect.height = Math.max(20, Math.min(startCropRect.height + deltaY, imageHeight - startCropRect.top));
                    break;
                case 'br': // Bottom-right
                    newRect.width = Math.max(20, Math.min(startCropRect.width + deltaX, imageWidth - startCropRect.left));
                    newRect.height = Math.max(20, Math.min(startCropRect.height + deltaY, imageHeight - startCropRect.top));
                    break;
            }
            
            $cropArea.css({
                left: newRect.left + 'px',
                top: newRect.top + 'px',
                width: newRect.width + 'px',
                height: newRect.height + 'px'
            });
        }
        
        updateFieldsFromCropArea();
        e.preventDefault();
    });
    
    // Handle mouse up
    $(document).on('mouseup', function() {
        isDragging = false;
        isResizing = false;
        resizeHandle = null;
    });
    
    // Update crop area when fields change
    $('#' + fieldPrefix + 'top, #' + fieldPrefix + 'left, #' + fieldPrefix + 'right, #' + fieldPrefix + 'bottom').on('change keyup', function() {
        updateCropAreaFromFields();
    });
    
    // Update on window resize
    $(window).on('resize', function() {
        updateCropAreaFromFields();
    });
}

// toggle image published
function toggleImagePublished(image_id) {
    $('#action').attr('value', 'toggle_image_published');
    $('#image_id').attr('value', image_id);
    $('#toggle_image_published_form').submit();
}