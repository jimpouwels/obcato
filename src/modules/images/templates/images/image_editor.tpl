<div class="admin_form_v2 image_editor_wrapper">
    <div style="float: left">
        {$size_field}
        {$new_width_field}
        {$new_height_field}
        {$crop_top_field}
        {$crop_bottom_field}
        {$crop_left_field}
        {$crop_right_field}
        {$crop_vertical_center_field}
        {$crop_horizontal_center_field}
    </div>
    {if $url}
        <div style="width: fit-content; float: right">
            <img title="{$title}" alt="{$title}" src="{$url}" style="max-width: 400px; max-height: 400px;"/ />
        </div>
    {else}
        <span><em>{$text_resources.images_image_editor_no_image_found_yet_message}</em></span>
    {/if}
    {if isset($reset_button)}
        <div style="clear: both">
            {$reset_button}
        </div>
    {/if}

</div>