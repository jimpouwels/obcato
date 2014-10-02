<fieldset class="admin_fieldset">
	<div class="fieldset-title">Query editor</div>
	
	<div class="queries_form_wrapper">
		<form action="/admin/index.php" method="post" id="query_execute_form">
			<ul>
				<li>
					{$query_field}
				</li>
			</ul>
			{$execute_query_button}
		</form>
	</div>
</fieldset>
<fieldset class="admin_fieldset">
	<div class="fieldset-title">Resultaten</div>
	
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
            <tbodo>
                {foreach from=$values item=rows}
                    <tr>
                        {foreach from=$rows item=cell}
                            <td>{$cell}</td>
                        {/foreach}
                    </tr>
                {/foreach}
            </tbodo>
		</table>
	{/if}
</fieldset>