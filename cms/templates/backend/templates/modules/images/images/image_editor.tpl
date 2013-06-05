<form action="/admin/index.php?image={$image_id}" method="post" id="image-editor-form" enctype="multipart/form-data">
	<fieldset class="admin_fieldset image_meta">
		<div class="fieldset-title">Algemeen</div>
		
		<input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
		<input type="hidden" id="image_id" name="image_id" value="{$image_id}" />
		
		<ul class="admin_form">
			<li>{$title_field}</li>
			<li>{$published_field}</li>
			<li>{$upload_field}</li>
		</ul>
	</fieldset>

	<fieldset class="admin_fieldset image_editor">
		<div class="fieldset-title">Labels</div>
		
		<select class="label-selector" multiple="multiple" size="10" name="image_select_labels[]">
			{if count($all_labels) > 0}
				{foreach from=$all_labels item=label}
					{if !$label.is_selected}
						<option value="{$label.id}">{$label.name}</option>
					{/if}
				{/foreach}
			{/if}
			{if (count($all_labels) == 0 && (count($all_labels) - count($image_labels) ==0))}
				<option value="-1"></option>
			{/if}
		</select>
		
		{if count($image_labels) > 0}
			<table class="selected-labels" cellpadding="5" cellspacing="0">
				<colgroup width="100px"></colgroup>
				<colgroup width="50px"></colgroup>
				<thead>
					<th>Geselecteerde labels</th>
					<th>Verwijder</th>
				</thead>
				<tbody>
					{foreach from=$image_labels item=image_label}
						<tr>
							<td>{$image_label.name}</td>
							<td class="delete_column">{$image_label.delete_checkbox}</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{/if}
	</fieldset>
	<fieldset class="admin_fieldset image_editor">
		<div class="fieldset-title">Afbeelding</div>
		
		{if !empty($filename)}
			<img title="{$title}" alt="{$title}" src="/admin/upload.php?image={$id}" />
		{/if}
	</fieldset>
</form>