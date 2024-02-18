<div class="selector_panel">
	<select class="label-selector" multiple="multiple" size="10" name="select_labels_{$context_id}[]">
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
		<table class="selected-labels" cellpadding="0" cellspacing="0">
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
</div>
