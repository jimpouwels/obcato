/*
	Author: Jim Pouwels
	Date: August 16th, 2011
*/
$(document).ready(function () {

    $('#add_element_holder').click(function () {
        $('#add_article_action').attr('value', 'add_article');
        $('#add_form_hidden').submit();
    });

    $('#update_element_holder').click(function () {
        $('#action').attr('value', 'update_element_holder');
        // Small delay to ensure popup callbacks have completed
        setTimeout(function() {
            $('#element_holder_form_id').submit();
        }, 100);
    });

    $('#delete_element_holder').click(function () {
        confirmDialog("{$text_resources.articles_confirm_delete_article|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'delete_article');
                $('#element_holder_form_id').submit();
            }
        });
        return false;
    });

    $('#add_term').click(function () {
        $('#add_term_action').attr('value', 'add_term');
        $('#add_term_form_hidden').submit();
    });

    $('#update_term').click(function () {
        $('#action').attr('value', 'update_term');
        $('#term_form').submit();
    });

    $('#delete_term').click(function () {
        confirmDialog("{$text_resources.articles_confirm_delete_term|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'delete_term');
                $('#term_form').submit();
            }
        });
    });

    $('#add_metadata_field').click(function () {
        $('#add_metadata_field_form_hidden').submit();
    });

    $('#update_metadata_field').click(function () {
        $('#action').attr('value', 'update_metadata_field');
        $('#metadata_field_form').submit();
    });

    $('#delete_metadata_field').click(function () {
        confirmDialog("{$text_resources.articles_confirm_delete_metadata_field|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'delete_metadata_field');
                $('#metadata_field_form').submit();
            }
        });
    });

    $('#delete_lead_image').click(function () {
        confirmDialog("{$text_resources.articles_confirm_delete_image|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'update_element_holder');
                $('#delete_lead_image_field').attr('value', 'true');
                $('#element_holder_form_id').submit();
            }
        });
        return false;
    });

    $('#delete_wallpaper').click(function () {
        confirmDialog("{$text_resources.articles_confirm_delete_wallpaper|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'update_element_holder');
                $('#delete_wallpaper_field').attr('value', 'true');
                $('#element_holder_form_id').submit();
            }
        });
        return false;
    });

    // Preview article button
    $('#preview_article').on('click', function(e) {
        e.preventDefault();
        var previewUrl = $('#article_preview_url').val();
        if (previewUrl) {
            window.open(previewUrl, '_blank');
        }
    });

    // Metadata fields toggle
    $('.metadata-fields-toggle').on('click', function() {
        $(this).closest('.metadata-fields-section').toggleClass('collapsed');
    });

    $(document).on('click', '.delete-article-btn', function(e) {
        e.preventDefault();
        var deleteFieldId = $(this).data('delete-field');
        
        confirmDialog("{$text_resources.articles_confirm_delete_related_article|escape:'javascript'}").then(function(confirmed) {
            if (confirmed) {
                $('#' + deleteFieldId).val('true');
                $('#action').val('update_element_holder');
                $('#element_holder_form_id').submit();
            }
        });
    });

    $('#update_target_pages').click(function () {
        $('#action').attr('value', 'add_target_page');
        $('#update_target_page_form').submit();
    });

    $('#delete_target_pages').click(function () {
        var $checked = false;
        $('input:checkbox').each(function () {
            if ($(this).attr('checked')) {
                $checked = true;
            }
        });
        if (!$checked) {
            alert("{$text_resources.articles_alert_no_target_pages_selected}");
        } else {
            confirmDialog("{$text_resources.articles_confirm_delete_target_pages|escape:'javascript'}").then(function(confirmed) {
                if (confirmed) {
                    $('#action').attr('value', 'delete_target_pages');
                    $('#update_target_page_form').submit();
                }
            });
        }
    });
});

function changeDefaultTargetPage(pageId) {
    $('#action').attr('value', 'change_default_target_page');
    $('#new_default_target_page').attr('value', pageId);
    $('#update_target_page_form').submit();
}