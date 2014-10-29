<li{if $last} class="last"{/if}>
	<img src="/admin/static.php?file=/modules/{$icon_url}" alt="{$title}" />
	
	<a {if {$popup}}onclick="window.open('/admin/popup_entity.php?module_id={$id}','{$title}','width=640,height=480'); return false;"{/if} href="/admin/index.php?module_id={$id}">{$title}</a>
</li>