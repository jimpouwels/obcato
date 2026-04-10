<form action="{$backend_base_url}" method="post" id="image-import-form" class="image-import-form" enctype="multipart/form-data">
    <div class="import-form-content">
        <p class="import-help-text">{$text_resources.images_import_instructions}</p>
        <div class="import-fields">
            {$upload_field}
        </div>
    </div>
</form>
