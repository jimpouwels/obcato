$(document).ready(function () {

    // ── Toolbar action buttons ─────────────────────────────────────────────

    $('#add_link').on('click', function () {
        $('#add_link_folder_id').val('');
        $('#add_link_form').trigger('submit');
    });

    $('#add_folder').on('click', function () {
        $('#add_folder_parent_id').val('');
        $('#add_folder_form').trigger('submit');
    });

    $('#update_link').on('click', function () {
        $('#link-editor-form').trigger('submit');
    });

    $('#delete_link').on('click', function () {
        confirmDialog("{$text_resources.links_confirm_delete_link|escape:'javascript'}").then(function (confirmed) {
            if (confirmed) {
                $('#link-editor-form [name=action]').val('delete_link');
                $('#link-editor-form').trigger('submit');
            }
        });
    });

    $('#update_folder').on('click', function () {
        $('#folder-editor-form').trigger('submit');
    });

    $('#delete_folder').on('click', function () {
        confirmDialog("{$text_resources.links_confirm_delete_folder|escape:'javascript'}").then(function (confirmed) {
            if (confirmed) {
                $('#folder-editor-form [name=action]').val('delete_folder');
                $('#folder-editor-form').trigger('submit');
            }
        });
    });

    // ── Tree action buttons ────────────────────────────────────────────────

    $('.links-folder-add-link').on('click', function (e) {
        e.stopPropagation();
        $('#add_link_folder_id').val($(this).data('folder-id'));
        $('#add_link_form').trigger('submit');
    });

    $('.links-folder-add-subfolder').on('click', function (e) {
        e.stopPropagation();
        $('#add_folder_parent_id').val($(this).data('folder-id'));
        $('#add_folder_form').trigger('submit');
    });

    $('.links-folder-delete').on('click', function (e) {
        e.stopPropagation();
        var folderId   = $(this).data('folder-id');
        var folderName = $(this).data('folder-name');
        confirmDialog("{$text_resources.links_confirm_delete_folder|escape:'javascript'} \"" + folderName + "\"?").then(function (confirmed) {
            if (confirmed) {
                $('#delete_folder_id').val(folderId);
                $('#delete_folder_mode').val('unparent');
                $('#delete_folder_form').trigger('submit');
            }
        });
    });

    // ── Drag-and-drop: relocate links between folders ──────────────────────

    var dragLinkId    = null;
    var currentDropFolder = null;

    $(document).on('dragstart', '.links-link-item', function (e) {
        dragLinkId = $(this).data('link-id');
        e.originalEvent.dataTransfer.effectAllowed = 'move';
        e.originalEvent.dataTransfer.setData('text/plain', String(dragLinkId));
        var self = this;
        requestAnimationFrame(function () {
            $(self).addClass('links-dragging');
        });
        currentDropFolder = null;
    });

    $(document).on('dragend', '.links-link-item', function () {
        $(this).removeClass('links-dragging');
        $('.links-folder-header').removeClass('drop-target');
        if (dragLinkId !== null && currentDropFolder !== null) {
            $('#move_link_id').val(dragLinkId);
            $('#move_link_folder_id').val(currentDropFolder);
            $('#move_link_form').trigger('submit');
        }
        dragLinkId        = null;
        currentDropFolder = null;
    });

    $(document).on('dragover', '.links-folder-header', function (e) {
        if (dragLinkId === null) return;
        e.preventDefault();
        e.originalEvent.dataTransfer.dropEffect = 'move';
        $('.links-folder-header').removeClass('drop-target');
        $(this).addClass('drop-target');
    });

    $(document).on('dragleave', '.links-folder-header', function (e) {
        if (!$(this).is($(e.relatedTarget).closest('.links-folder-header'))) {
            $(this).removeClass('drop-target');
        }
    });

    $(document).on('drop', '.links-folder-header', function (e) {
        if (dragLinkId === null) return;
        e.preventDefault();
        currentDropFolder = $(this).closest('.links-folder').data('folder-id');
    });

    // Drop on tree root (move to root, no folder)
    $('.links-tree').on('dragover', function (e) {
        if (dragLinkId === null) return;
        if ($(e.target).closest('.links-folder').length > 0) return;
        e.preventDefault();
    });

    $('.links-tree').on('drop', function (e) {
        if (dragLinkId === null) return;
        if ($(e.target).closest('.links-folder').length > 0) return;
        e.preventDefault();
        currentDropFolder = '';
    });

});
