<fieldset class="admin_fieldset">
	<ul>
		{foreach from=$scopes item=scope}
			<li><a id="scope_item_link" href="/admin/index.php?scope={$scope}&mode=ajax">{$scope}</a></li>
		{/foreach}
	</ul>
</fieldset>