{if isset($elements)}
	<div id="element_container">
		{foreach from=$elements item=element}
			{$element}
		{/foreach}
	</div>
{else}
	{$message}
{/if}
