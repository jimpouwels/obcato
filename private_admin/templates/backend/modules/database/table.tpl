<table class="table_listing" width="90%" cellspacing="0">
    <colgroup width="33%"></colgroup>
    <colgroup width="33%"></colgroup>
    <colgroup width="33%"></colgroup>

    <tr>
        <th>{$text_resources.database_tables_column_column}</th>
        <th>{$text_resources.database_tables_column_type}</th>
        <th>{$text_resources.database_tables_column_nullable}</th>
    </tr>

    {foreach from=$table.columns item=column}
        <tr>
            <td>{$column.name}</td>
            <td>{$column.type}</td>
            <td>{$column.allowed_null}</td>
        </tr>
    {/foreach}
</table>
