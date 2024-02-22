<div class="admin_form_v2">
    {$title_field}
    {$alignment_field}
    {$width_field}
    {$height_field}
    {$image_picker}
    {if !is_null($image_id) && $image_id != ""}
        <br/>
        <div class="image_element_image">
            <img title="{$selected_image_title}" src="/admin/upload.php?image={$image_id}&amp;thumb=true"/>
        </div>
    {/if}
</div>