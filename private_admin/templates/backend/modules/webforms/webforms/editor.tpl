<form action="{$backend_base_url}&image={$id}" method="post" id="image-editor-form" enctype="multipart/form-data">
	<input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
	<input type="hidden" id="image_id" name="image_id" value="{$id}" />

	<ul class="admin_form">
		<li>{$title_field}</li>
	</ul>
</form>