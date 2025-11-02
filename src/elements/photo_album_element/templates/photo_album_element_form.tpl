<div class="admin_form_v2">
    {$title_field}
    {$max_results_field}
    <div id="photo_album_element_{$element_id}_selected_images">
    </div>
    {$image_lookup_field}
    {$label_select_field}
</div>

<script type="text/javascript">
    $(document).ready(function () {
        updateSelectedImages({$element_id});
    });
</script>