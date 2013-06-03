<div class="block_list">
	{foreach from=$block_lists item=block_list}
		<fieldset class="admin_fieldset">
			<div class="fieldset-title">
				{if !is_null($block_list.position)}
					{$block_list.position}
				{else}
					Zonder positie
				{/if}
			</div>
			<div class="block_tree">
				{if count($block_list.blocks) > 0}
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
				{else}	
					{$no_results_message}
				{/if}
			</div>
		</fieldset>
	{/foreach}
</div>