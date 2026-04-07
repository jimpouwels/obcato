<div class="selected_image_display">
    <div class="selected_image_info" style="display: flex; align-items: center; justify-content: space-between;">
        <p style="margin: 0;"><i><a title="{$article_title}" href="{$article_url}">{$article_title}</a></i></p>
        <button type="button" class="delete-article-btn" data-delete-field="{$delete_field_name}" style="margin-left: 10px; padding: 2px 6px; background: none; border: none; cursor: pointer;">
            <img src="{$delete_icon_url}" alt="Verwijderen" />
        </button>
    </div>
</div>
<input type="hidden" id="{$delete_field_name}" name="{$delete_field_name}" value="" />
