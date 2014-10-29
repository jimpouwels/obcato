<div class="module_tabs">
	<ul>
		{assign var='index' value=0}
		{foreach from=$tab_items item=tab_item}
			{assign var='class' value='tab_item'}
			{if $index == $current_tab}
				{assign var='class' value='tab_item_active'}
			{/if}
			<li class="{$class}">
				<a href="/admin/index.php?module_tab={$index}" title="{$tab_item}">{$tab_item}</a>
			</li>
			{assign var='index' value=$index + 1}
		{/foreach}
	</ul>
</div>