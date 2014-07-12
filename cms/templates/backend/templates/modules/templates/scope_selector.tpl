<fieldset class="admin_fieldset scope_selector_fieldset">
	<div class="fieldset-title">
		<p>Presenteerbare componenten</p>
	</div>
	<ul>
		{foreach from=$scopes item=scope}
			<li>
				<a id="scope_item_link" href="/admin/index.php?scope={$scope}">{$scope}</a>
			</li>
		{/foreach}
	</ul>
</fieldset>