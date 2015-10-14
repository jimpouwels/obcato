<div>{$title_field}</div>
<div>{$alternative_text_field}</div>
<div>{$alignment_field}</div>
<div>{$width_field}</div>
<div>{$height_field}</div>
<div>
    {$image_picker}
    {if !is_null($image_id) && $image_id != ""}
        <br />
        <div class="image_element_image">
            <img title="{$selected_image_title}" src="/admin/upload.php?image={$image_id}&amp;thumb=true" />	
        </div>
    {/if}
</div>