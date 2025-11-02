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
        updateSelectedImages();
    })

    function addImage(id) {
        $.ajax({
            url: '/admin/api/photo_album_element/add_image',
            type: 'PUT',
            data: JSON.stringify({
                'id': {$element_id},
                'image': id
            }),
            success: function(response) {
                updateSelectedImages();
            },
            error: function(xhr, status, error) {
                console.log(xhr, status, error);
            }
        });
    }

    function updateSelectedImages() {
        let imagesContainer = $('#photo_album_element_{$element_id}_selected_images');
        $.ajax({
            url: '/admin/api/photo_album_element/images?id={$element_id}',
            type: 'GET',
            success: function(response) {
                imagesContainer.empty();
                response.forEach(result => {
                    imagesContainer.append("<div class=\"photo_album_element_selected_image\"><div class=\"photo_album_element_selected_image_thumb\"><img src=\"" + result.url + "\" /></div><div class=\"photo_album_element_selected_image_details\"><p><strong>Titel: </strong>" + result.title + "</p><p><strong>AltText: </strong>" + result.alternative_text + "</p></div><div class\"photo_album_element_selected_image_delete\"><a href=\"#\" onclick=\"deleteImage(" + result.id + "); return false;\"><img src=\"/admin?file=/default/img/default_icons/delete_small.png\" /></a></div></div>");
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr, status, error);
            }
        });
    }

    function deleteImage(id) {
        $.ajax({
            url: '/admin/api/photo_album_element/delete_image',
            type: 'DELETE',
            data: JSON.stringify({
                'id': {$element_id},
                'image': id
            }),
            success: function(response) {
                updateSelectedImages();
            },
            error: function(xhr, status, error) {
                console.log(xhr, status, error);
            }
        });
    }
</script>