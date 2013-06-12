<form id="label_form" method="post" action="/admin/index.php?label={$id}">
	<fieldset class="admin_fieldset">
		<div class="fieldset-title">Label bewerken</div>
		
		<input type="hidden" value="" name="action" id="action" />
		<input type="hidden" value="{$id}" name="label_id" id="label_id" />
		
		<ul class="admin_form">
			<li>{$label_name_field}</li>
		</ul>
	</fieldset>
</form>