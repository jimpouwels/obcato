{foreach from=$block_lists item=block_list}
	{if count($block_list.blocks) > 0}
		<p class="position_label">
			{if !is_null($block_list.position)}
				{$block_list.position}
			{else}
				{$text_resources.blocks_without_position_title}
			{/if}
		</p>
		<ul>
			{foreach from=$block_list.blocks item=block}
				{assign var='class' value='depublished'}
				{if $block.published}
					{assign var='class' value='published'}
				{/if}
				<li class="{$class}">
					{if !is_null($current_block) && $current_block.id == $block.id}
						<strong>
					{/if}
					<a href="/admin/index.php?block={$block.id}" title="{$block.title}">{$block.title}</a>
					{if !is_null($current_block) && $current_block.id == $block.id}
						</strong>
					{/if}
				</li>
			{/foreach}
		</ul>
	{/if}
{/foreach}
