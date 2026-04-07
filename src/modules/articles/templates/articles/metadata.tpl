<input type="hidden" id="{$add_element_form_id}" name="{$add_element_form_id}" value="" />
<input type="hidden" id="{$edit_element_holder_id}" name="{$edit_element_holder_id}" value="{$current_article_id}" />
<input type="hidden" id="{$delete_element_form_id}" name="{$delete_element_form_id}" value="" />
<input type="hidden" id="draggable_order" name="draggable_order" value="" />
<input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
<input type="hidden" id="delete_parent_article_field" name="delete_parent_article_field" value="" />
<input type="hidden" id="article_preview_url" name="article_preview_url" value="{$url}?mode=preview" />

<div class="admin_form_v2">
    {$name_field}
    {$title_field}
    {$url_title_field}
    {$url_field}
    {$description_field}
    {$keywords_field}
    {$published_field}
    {$publication_date_field}
    {$sort_date_field}
    {$target_pages_field}
    {if !isset($parent_article)}
        {$parent_article_field}
    {else}
        <div class="admin_form_field_v2">
            <div class="admin_label_wrapper">
                <label class="admin_label">{$text_resources.article_editor_parent_article_label}</label>
            </div>
            <div class="admin_field_wrapper">
                <div class="selected_image_display">
                    <div class="selected_image_info">
                        <p><i><a title="{$parent_article.title}" href="{$parent_article.url}">{$parent_article.title}</a></i></p>
                    </div>
                    <div class="selected_parent_article_delete_button">
                        <button type="button" class="parent-article-delete-btn" id="delete_parent_article">
                            <img src="/admin?file=/default/img/default_icons/delete_small.png" alt="Verwijderen" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    {if count($child_articles) > 0}
        <div class="admin_form_field_v2">
            <div class="admin_label_wrapper">
                <label class="admin_label">{$text_resources.article_editor_child_articles_label}</label>
            </div>
            <div class="admin_field_wrapper">
                <ul style="margin: 0; padding: 0 0 0 15px;">
                    {foreach from=$child_articles item=child_article}
                        <li>
                            <i><a title="{$child_article.title}" href="{$child_article.url}">{$child_article.title}</a></i>
                        </li>
                    {/foreach}
                </ul>
            </div>
        </div>
    {/if}
    {$comment_forms_field}
    {$template_field}
    {$image_picker_field}
    {$wallpaper_picker_field}
    
    <div class="metadata-fields-section collapsed">
        <h3 class="metadata-fields-toggle">
            Metadata
            <span class="toggle-icon"></span>
        </h3>
        <div class="metadata-fields-content">
            {foreach from=$metadata_fields item=metadata_field}
                {$metadata_field}
            {/foreach}
        </div>
    </div>
</div>
