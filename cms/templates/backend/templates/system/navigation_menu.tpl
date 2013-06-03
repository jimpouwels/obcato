<div id="navigation-box">
	<ul id="menu">
		{foreach from=$menu_items key=title item=menu_item}
			<li class="module-group">
				<a href="#" class="parent" onclick="return false;">{$title}</a>
				<div class="submenu">
					<ul>
						{foreach from=$menu_item item=sub_item}
							{$sub_item}
						{/foreach}
					</ul>
				</div>
			</li>
		{/foreach}
	</ul>
</div>