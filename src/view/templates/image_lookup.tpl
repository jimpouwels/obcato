<input type="text" value="{$field_value}" id="{$field_name}" name="{$field_name}" class="admin_field {$classes}" />
<div id="{$field_name}_search_results">

</div>
<script type="text/javascript">
    $(document).ready(() => {
        let resultContainer = $('#{$field_name}_search_results');
        let timeoutId = 0;
        $('#{$field_name}').on("input", function() {
            let keyword = $(this).val();
            if (keyword === '') {
                clearTimeout(timeoutId);
                resultContainer.empty();
            } else {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(search, 300, keyword);
            }
        });
        $('#{$field_name}').on("focusout", function() {
            resultContainer.empty();
        });

        function search(keyword) {
            $.ajax({
                url: '/admin/api/image/search?keyword=' + keyword,
                method: 'GET',
                success: function(response) {
                    resultContainer.empty();
                    response.forEach(function(result) {
                        resultContainer.append("<div class=\"image_lookup_result\" onclick=\"addImage('" + result.id + "')\"><div class=\"image_lookup_result_thumb\"><img src=\"" + result.url + "\" /></div><div class=\"image_lookup_result_details\"><p><strong>Titel: </strong>" + result.title + "</p><p><strong>AltText: </strong>" + result.alternative_text + "</p></div</div>")
                    })
                },
                error: function(xhr, status, error) {
                    console.error(status, error);
                }
            });
        }
    });
</script>