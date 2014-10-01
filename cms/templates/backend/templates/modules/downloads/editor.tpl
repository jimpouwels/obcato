<form action="/admin/index.php?download={$download_id}" method="post" id="download-editor-form" enctype="multipart/form-data">
    <fieldset class="admin_fieldset">
        <div class="fieldset-title">Algemeen</div>

        <input type="hidden" id="action" name="action" value="" />
        <input type="hidden" id="download_id" name="download_id" value="{$download_id}" />

        <ul class="admin_form">
            <li>{$title_field}</li>
            <li>{$published_field}</li>
            <li>{$upload_field}</li>
        </ul>
    </fieldset>
    {if $file}
        <fieldset class="admin_fieldset">
            <div class="fieldset-title">Bestandsinformatie</div>
            <table class="listing"  cellspacing="0" cellpadding="5" border="0">
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
                                <img src="/admin/static.php?file=/modules/templates/img/check.gif" alt="Bestand aanwezig" />
                            {else}
                                <img src="/admin/static.php?file=/modules/templates/img/delete.png" alt="Bestand ontbreekt" />
                            {/if}
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    {/if}
</form>