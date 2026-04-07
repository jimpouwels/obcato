<div class="selected_image_display">
    <div class="selected_image_info" style="display: flex; align-items: center; justify-content: space-between;">
        <p style="margin: 0;"><i><a href="#" class="image-view-link" 
               data-image-id="{$image_id}" 
               data-image-title="{$image_title}" 
               data-image-type="{$field_name}" 
               data-image-url="{$image_url}" 
               data-picker-field="{$field_name}"
               data-modal-id="image-modal-{$field_name}">{$image_title}</a></i></p>
        <button type="button" class="delete-image-btn" data-delete-field="{$delete_field_name}" style="margin-left: 10px; padding: 2px 6px; background: none; border: none; cursor: pointer;">
            <img src="{$delete_icon_url}" alt="Verwijderen" />
        </button>
    </div>
</div>
<input type="hidden" id="{$delete_field_name}" name="{$delete_field_name}" value="" />

<!-- Image Modal for this picker -->
<div id="image-modal-{$field_name}" class="image-modal" style="display:none;">
    <div class="image-modal-backdrop"></div>
    <div class="image-modal-content">
        <div class="image-modal-close">&times;</div>
        <div class="selected-image-preview">
            <img class="modal-image-img" src="" alt="" />
            <div class="image-overlay">
                <button type="button" class="image-action-btn change-image-btn">
                    <span class="icon-upload"></span>
                    {$text_resources.article_editor_change_image_button}
                </button>
                <button type="button" class="image-action-btn delete-image-btn-modal" data-delete-field="{$delete_field_name}">
                    <span class="icon-delete"></span>
                    {$text_resources.article_editor_delete_button}
                </button>
            </div>
        </div>
    </div>
</div>
