{assign var='li_class' value=''}
{if {$published}}
	{assign var='li_class' value='class="published"'}
{else}
	{assign var='li_class' value='class="depublished"'}
{/if}
<li {$li_class}>
	{assign var='class' value='page_tree_link'}
	{if $active}
		{assign var='class' value=$class|cat:' active'}
	{/if}
	<a title="Dummy" href="/admin/index.php?page={$id}" class="{$class}">{$title}</a>
	{if isset($sub_pages) && count($sub_pages) > 0}
		<ul>
			{foreach from=$sub_pages item=value}
				{$value}
			{/foreach}
		</ul>
	{/if}
</li>