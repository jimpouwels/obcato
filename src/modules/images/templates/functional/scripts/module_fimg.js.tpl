$(document).ready(function () {

    // -- Folder collapse/expand -------------------------------------------

    $('.fimg-folder-toggle').on('click', function (e) {
        e.stopPropagation();
        $(this).closest('.fimg-folder').toggleClass('open');
    });

    // Expand ancestor folders of the currently selected item
    $('.fimg-folder-name.selected, .fimg-image-item.selected').parents('.fimg-folder').addClass('open');

    // -- Toolbar action buttons --------------------------------------------

    function selectedFolderId() {
        var el = $('.fimg-folder-name.selected').closest('.fimg-folder');
        return el.length ? el.data('folder-id') : '';
    }

    $('#add_fimg').on('click', function () {
        $('#add_fimg_folder_id').val(selectedFolderId());
        $('#add_fimg_form').trigger('submit');
    });

    $('#add_fimg_folder').on('click', function () {
        $('#add_fimg_parent_folder_id').val(selectedFolderId());
        $('#add_fimg_folder_form').trigger('submit');
    });

    $('#update_fimg').on('click', function () {
        $('#fimg-editor-form').trigger('submit');
    });

    $('#delete_fimg').on('click', function () {
        confirmDialog("{$text_resources.functional_images_confirm_delete|escape:'javascript'}").then(function (confirmed) {
            if (confirmed) {
                $('#fimg-editor-form [name=action]').val('delete_functional_image');
                $('#fimg-editor-form').trigger('submit');
            }
        });
    });

    $('#update_fimg_folder').on('click', function () {
        $('#fimg-folder-editor-form').trigger('submit');
    });

    $('#delete_fimg_folder').on('click', function () {
        confirmDialog("{$text_resources.functional_images_confirm_delete_folder|escape:'javascript'}").then(function (confirmed) {
            if (confirmed) {
                $('#fimg-folder-editor-form [name=action]').val('delete_functional_image_folder');
                $('#fimg-folder-editor-form').trigger('submit');
            }
        });
    });

    // -- Tree action buttons -----------------------------------------------

    $('.fimg-folder-add-image').on('click', function (e) {
        e.stopPropagation();
        $('#add_fimg_folder_id').val($(this).data('folder-id'));
        $('#add_fimg_form').trigger('submit');
    });

    $('.fimg-folder-add-subfolder').on('click', function (e) {
        e.stopPropagation();
        $('#add_fimg_parent_folder_id').val($(this).data('folder-id'));
        $('#add_fimg_folder_form').trigger('submit');
    });

    $('.fimg-folder-delete').on('click', function (e) {
        e.stopPropagation();
        var folderId   = $(this).data('folder-id');
        var folderName = $(this).data('folder-name');
        confirmDialog("{$text_resources.functional_images_confirm_delete_folder|escape:'javascript'} \"" + folderName + "\"?").then(function (confirmed) {
            if (confirmed) {
                $('#delete_fimg_folder_id').val(folderId);
                $('#delete_fimg_folder_form').trigger('submit');
            }
        });
    });

    // -- Drag-and-drop: move images between folders ------------------------

    var dragImageId       = null;
    var currentDropFolder = null;

    $(document).on('dragstart', '.fimg-image-item', function (e) {
        dragImageId = $(this).data('image-id');
        e.originalEvent.dataTransfer.effectAllowed = 'move';
        e.originalEvent.dataTransfer.setData('text/plain', String(dragImageId));
        var self = this;
        requestAnimationFrame(function () {
            $(self).addClass('fimg-dragging');
        });
        currentDropFolder = null;
    });

    $(document).on('dragend', '.fimg-image-item', function () {
        $(this).removeClass('fimg-dragging');
        $('.fimg-folder-header').removeClass('drop-target');
        if (dragImageId !== null && currentDropFolder !== null) {
            $('#move_fimg_id').val(dragImageId);
            $('#move_fimg_folder_id').val(currentDropFolder);
            $('#move_fimg_form').trigger('submit');
        }
        dragImageId       = null;
        currentDropFolder = null;
    });

    $(document).on('dragover', '.fimg-folder-header', function (e) {
        if (dragImageId === null) return;
        e.preventDefault();
        e.originalEvent.dataTransfer.dropEffect = 'move';
        $('.fimg-folder-header').removeClass('drop-target');
        $(this).addClass('drop-target');
    });

    $(document).on('dragleave', '.fimg-folder-header', function (e) {
        if (!$(this).is($(e.relatedTarget).closest('.fimg-folder-header'))) {
            $(this).removeClass('drop-target');
        }
    });

    $(document).on('drop', '.fimg-folder-header', function (e) {
        if (dragImageId === null) return;
        e.preventDefault();
        currentDropFolder = $(this).closest('.fimg-folder').data('folder-id');
    });

    // Drop on tree root (move to root, no folder)
    $('.fimg-tree').on('dragover', function (e) {
        if (dragImageId === null) return;
        if ($(e.target).closest('.fimg-folder').length > 0) return;
        e.preventDefault();
    });

    $('.fimg-tree').on('drop', function (e) {
        if (dragImageId === null) return;
        if ($(e.target).closest('.fimg-folder').length > 0) return;
        e.preventDefault();
        currentDropFolder = '';
    });

});
