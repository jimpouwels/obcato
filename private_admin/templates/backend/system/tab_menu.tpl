<div class="module_tabs">
	<ul>
		{assign var='index' value=0}
		{foreach from=$tab_items item=tab_item}
			{assign var='class' value='tab_item'}
			{if $index == $current_tab}
				{assign var='class' value='tab_item_active'}
			{/if}
			<li class="{$class}">
				<a href="{$backend_base_url_without_tab}&module_tab_id={$tab_item.id}" title="{$tab_item.text}">{$tab_item.text}</a>
			</li>
			{assign var='index' value=$index + 1}
		{/foreach}
	</ul>
</div>
