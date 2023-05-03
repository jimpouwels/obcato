<form action="{$backend_base_url}&image={$id}" method="post" id="webform-editor-form" enctype="multipart/form-data">
	<input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
	<input type="hidden" id="webform_id" name="webform_id" value="{$id}" />

	<ul class="admin_form">
		<li>{$title_field}</li>
	</ul>
</form>