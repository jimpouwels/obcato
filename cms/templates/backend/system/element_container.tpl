<fieldset class="admin_fieldset element_container">
	<p class="fieldset-title">Inhoud</p>
	{if isset($elements)}
		<div id="sortable">
			{foreach from=$elements item=element}
				{$element}
			{/foreach}
		</div>
	{else}
		{$message}
	{/if}
</fieldset>