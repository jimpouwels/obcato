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
            <div class="image-crop-container" style="position: relative; display: inline-block; cursor: crosshair; overflow: hidden;">
                <img class="crop-target-image" title="{$title}" alt="{$title}" src="{$url}" style="max-width: 400px; max-height: 400px; display: block;"/ />
                <div class="crop-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
                    <div class="crop-area" style="position: absolute; border: 2px dashed #fff; box-shadow: 0 0 0 9999px rgba(0,0,0,0.5); pointer-events: all; cursor: move; box-sizing: content-box;">
                        <div class="crop-handle crop-handle-tl" style="position: absolute; top: -5px; left: -5px; width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: nw-resize;"></div>
                        <div class="crop-handle crop-handle-tr" style="position: absolute; top: -5px; right: -5px; width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: ne-resize;"></div>
                        <div class="crop-handle crop-handle-bl" style="position: absolute; bottom: -5px; left: -5px; width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: sw-resize;"></div>
                        <div class="crop-handle crop-handle-br" style="position: absolute; bottom: -5px; right: -5px; width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: se-resize;"></div>
                    </div>
                </div>
            </div>
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