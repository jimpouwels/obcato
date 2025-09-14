<div class="admin_form_v2">
    {$title_field}
    {$alignment_field}
    {$width_field}
    {$height_field}
    {$link_selector_field}
    {$url_field}
    {$image_picker}
    {if !is_null($image_id) && $image_id != ""}
        <br/>
        <div class="image_element_image">
            <img title="{$selected_image_title}" src="{$image_base_url}/{$image_id}?thumb=true" />
        </div>
    {/if}
</div>