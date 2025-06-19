<input type="hidden" id="{$add_element_form_id}" name="{$add_element_form_id}" value="" />
<input type="hidden" id="{$edit_element_holder_id}" name="{$edit_element_holder_id}" value="{$current_article_id}" />
<input type="hidden" id="{$delete_element_form_id}" name="{$delete_element_form_id}" value="" />
<input type="hidden" id="draggable_order" name="draggable_order" value="" />
<input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
<input type="hidden" id="delete_lead_image_field" name="delete_lead_image_field" value="" />
<input type="hidden" id="delete_wallpaper_field" name="delete_wallpaper_field" value="" />
<input type="hidden" id="delete_parent_article_field" name="delete_parent_article_field" value="" />

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
    {$parent_article_field}
    <div class="admin_form_field_v2">
        {if isset($parent_article)}
            <div class="admin_label_wrapper">
                <label class="admin_label">{$text_resources.article_editor_select_parent_article_label}</label>
            </div>
            <div class="admin_field_wrapper">
                <div class="selected_parent_article">
                    <p><i><a title="{$parent_article.title}"
                             href="{$parent_article.url}">{$parent_article.title}</a></i></p>
                </div>
                <div class="selected_parent_article_delete_button">
                    {$delete_parent_article_button}
                </div>
            </div>
        {/if}
    </div>
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
    {if !is_null($lead_image_id)}
        <div class="admin_form_field_v2">
            <div class="admin_label_wrapper">
                <label class="admin_label">{$text_resources.article_editor_selected_image_label}</label>
            </div>
            <div class="admin_field_wrapper">
                <img class="article_selected_image" title="Afbeelding verwijderen"
                     src="/admin/upload.php?image={$lead_image_id}&amp;thumb=true" />
                {$delete_lead_image_button}
            </div>
        </div>
    {/if}
    {$wallpaper_picker_field}
    {if !is_null($wallpaper_id)}
        <div class="admin_form_field_v2">
            <div class="admin_label_wrapper">
                <label class="admin_label">{$text_resources.article_editor_selected_wallpaper_label}</label>
            </div>
            <div class="admin_field_wrapper">
                <img class="article_selected_wallpaper" title=""
                     src="/admin/upload.php?image={$wallpaper_id}&amp;thumb=true" />
                {$delete_wallpaper_button}
            </div>
        </div>
    {/if}
    <span style="margin-bottom: 15px">[<a href="{$url}?mode=preview" target="_blank">Preview</a>]</span>
</div>
