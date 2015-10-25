{if !is_null($current_module) || !is_null($current_element)}
    <table class="listing" cellspacing="0" cellpadding="5">
        <colgroup width="150px" />
        <colgroup width="200px" />
        <thead>
            <tr>
                <th>Eigenschap</th>
                <th>Waarde</th>
            </tr>
        </thead>
        <tbody>
            {if !is_null($current_module)}
                <tr>
                    <td><strong>Component type</strong></td>
                    <td>Module</td>
                </tr>
                <tr>
                    <td><strong>Identifier</strong></td>
                    <td>{$current_module.identifier}</td>
                </tr>
                <tr>
                    <td><strong>Titel</strong></td>
                    <td>{$current_module.title}</td>
                </tr>
                <tr>
                    <td><strong>Standaardmodule</strong></td>
                    <td>{if $current_module.system_default}Ja{else}Nee{/if}</td>
                </tr>
                <tr>
                    <td><strong>Activatie class</strong></td>
                    <td>{$current_module.class}</td>
                </tr>
            {/if}
            {if !is_null($current_element)}
                <tr>
                    <td><strong>Component type</strong></td>
                    <td>Element</td>
                </tr>
                <tr>
                    <td><strong>Identifier</strong></td>
                    <td>{$current_element.identifier}</td>
                </tr>
                <tr>
                    <td><strong>Titel</strong></td>
                    <td>{$current_element.name}</td>
                </tr>
                <tr>
                    <td><strong>Standaardelement</strong></td>
                    <td>{if $current_element.system_default}Ja{else}Nee{/if}</td>
                </tr>
                <tr>
                    <td><strong>Bestand</strong></td>
                    <td>{$current_element.object_file}</td>
                </tr>
                <tr>
                    <td><strong>Class</strong></td>
                    <td>{$current_element.class}</td>
                </tr>
            {/if}
        </tbody>
    </table>
    <form id="uninstall_component_form" method="POST" action="/admin/index.php" class="displaynone">
        <input type="hidden" name="action" id="action" value="" />
        {if !is_null($current_module)}
            <input type="hidden" name="module_id" id="module_id" value="{$current_module.id}" />
        {else}
            <input type="hidden" name="element_id" id="element_id" value="{$current_element.id}" />
        {/if}
    </form>
{/if}
