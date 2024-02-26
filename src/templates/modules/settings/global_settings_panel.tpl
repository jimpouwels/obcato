<div class="admin_form_v2">
    {$website_title}
    {$email_field}
    {$homepage_picker}

    <div class="admin_form_field_v2">
        {if !is_null($current_homepage_id)}
            <div class="admin_label_wrapper">
                <label class="admin_label">{$text_resources.settings_form_selected_homepage}</label>
            </div>
            <div class="admin_field_wrapper">
                <div class="selected_parent_article">
                    <p><i><a class="link"
                             href="{$backend_base_url}&module_id=3&amp;page={$current_homepage_id}">{$current_homepage_title}</a></i>
                    </p>
                </div>
            </div>
        {/if}
    </div>

    {$page_404_picker}
    <div class="admin_form_field_v2">
        {if isset($current_404_page_id)}
            <div class="admin_label_wrapper">
                <label class="admin_label">{$text_resources.settings_form_selected_404_page}</label>
            </div>
            <div class="admin_field_wrapper">
                <div class="selected_parent_article">
                    <p><i><a class="link"
                             href="{$backend_base_url}&module_id=3&amp;page={$current_404_page_id}">{$current_404_page_title}</a></i>
                    </p>
                </div>
            </div>
        {/if}
    </div>
</div>
