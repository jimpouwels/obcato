{if isset($fields) && count($fields) > 0}
	<table class="listing" cellspacing="0" cellpadding="5" border="0">
		{foreach from=$fields item=field}
			<colgroup width="100px"></colgroup>
		{/foreach}
        <thead>
            <tr>
                {foreach from=$fields item=field}
                    <th>{$field}</th>
                {/foreach}
            </tr>
        </thead>
        <tbody>
            {foreach from=$values item=rows}
                <tr>
                    {foreach from=$rows item=cell}
                        <td>{$cell}</td>
                    {/foreach}
                </tr>
            {/foreach}
        </tbody>
	</table>
{/if}
