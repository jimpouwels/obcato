<tr>
	<td>{$title_field}</td>
</tr>
<tr>
	<td>{$alternative_text_field}</td>
</tr>
<tr>
	<td>{$alignment_field}</td>
</tr>
<tr>
	<td>
		{$image_picker}
		{if !is_null($image_id) && $image_id != ""}
			<br />
			<div class="image_element_image">
				<img title="{$selected_image_title}" src="/admin/upload.php?image={$image_id}&amp;thumb=true" />	
			</div>
		{/if}
	</td>
</tr>