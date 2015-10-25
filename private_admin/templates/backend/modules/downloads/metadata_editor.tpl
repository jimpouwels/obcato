<form action="/admin/index.php?download={$download_id}" method="post" id="download-editor-form" enctype="multipart/form-data">
    <input type="hidden" id="action" name="action" value="" />
    <input type="hidden" id="download_id" name="download_id" value="{$download_id}" />

    <ul class="admin_form">
        <li>{$title_field}</li>
        <li>{$published_field}</li>
        <li>{$upload_field}</li>
    </ul>
</form>
