<ul class="sidebar-menu">
    {foreach from=$groups item=group}
        <li class="menu-group">
            <div class="menu-group-title">{$group.title}</div>
            <ul class="menu-group-items">
                {if isset($group.elements)}
                    {foreach from=$group.elements item=element}
                        <li class="menu-item">
                            <a href="#" onclick="addElement('{$element.id}', '{$text_resources.navigation_menu_add_element_error}'); return false;">
                                <img src="{$element.icon_url}" alt="" />
                                <span>{$element.name}</span>
                            </a>
                        </li>
                    {/foreach}
                    <li class="menu-item">
                        <a href="#" onclick="addLink(); return false;">
                            <img src="/admin?file=/default/img/element_icons/link.png" alt="" />
                            <span>Link</span>
                        </a>
                    </li>
                {else}
                    {foreach from=$group.modules item=module}
                        <li class="menu-item{if $module.active} active{/if}">
                            <a href="{$backend_base_url_raw}?module_id={$module.id}">
                                <img src="{$module.icon_url}" alt="" />
                                <span>{$module.title}</span>
                            </a>
                        </li>
                    {/foreach}
                {/if}
            </ul>
        </li>
    {/foreach}
</ul>