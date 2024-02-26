{if $file}
    <table class="listing" cellspacing="0" cellpadding="5" border="0">
        <thead>
        <tr>
            <th>Bestandsnaam</th>
            <th>Grootte</th>
            <th>Bestand gevonden</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{$file.name}</td>
            <td>
                {if isset($file.size)}
                    {$file.size}Kb
                {else}
                    Onbekend
                {/if}
            </td>
            <td class="center">
                {if $file.exists}
                    <img src="/admin/static.php?file=/modules/templates/img/check.gif" alt="Bestand aanwezig"/>
                {else}
                    <img src="/admin/static.php?file=/modules/templates/img/delete.png" alt="Bestand ontbreekt"/>
                {/if}
            </td>
        </tr>
        </tbody>
    </table>
{/if}
