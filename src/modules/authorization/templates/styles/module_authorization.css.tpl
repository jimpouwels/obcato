.user_tree {
    /* Uses content_left_column styling */
}

.user_tree ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

.user_tree ul li {
    list-style-type: none;
    margin: 0;
    border-bottom: 1px solid var(--color-gray-200);
}

.user_tree a {
    text-decoration: none;
    color: var(--color-gray-900);
    padding: var(--spacing-sm) var(--spacing-md);
    display: block;
    font-size: 13px;
    transition: all 0.2s;
}

.user_tree a:hover {
    background: var(--color-gray-50);
    text-decoration: none;
}

.user_tree strong a {
    background: var(--color-blue-50);
    color: var(--color-primary);
    font-weight: 600;
    border-left: 3px solid var(--color-primary);
    padding-left: calc(var(--spacing-md) - 3px);
}

.user_list_item {
    list-style: none;
}

.user_prefix_field {
    width: 60px;
}

.user_firstname_field {
    width: 150px;
}

.user_lastname_field {
    width: 150px;
}

.user_email_field {
    width: 350px;
}

.user_password_field {
    width: 250px;
}
