import React from 'react';
import { ShowGuesser, FieldGuesser } from '@api-platform/admin';

const UserShow = props => (
    <ShowGuesser  {...props}>
        <FieldGuesser source={"email"} addLabel={true} />
        <FieldGuesser source={"username"} addLabel={true} />
        <FieldGuesser source={"roles"} addLabel={true} />
        <FieldGuesser source={"hasConfirmEmail"} addLabel={true} />
    </ShowGuesser >
);

export default UserShow;