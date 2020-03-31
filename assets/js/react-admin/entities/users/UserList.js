import React from 'react';
import { ListGuesser, FieldGuesser } from '@api-platform/admin';

import { List, Datagrid, Edit, Create, SimpleForm, DateField, TextField, EditButton, TextInput, DateInput } from 'react-admin';
import BookIcon from '@material-ui/icons/Book';
export const PostIcon = BookIcon;

export const UserList = (props) => (
    <List {...props}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="username" />
            <EditButton basePath="/users" />
        </Datagrid>
    </List>
);

export default UserList;