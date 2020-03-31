import React from 'react';
import ReactDOM from 'react-dom';
import { HydraAdmin, ResourceGuesser, hydraDataProvider, hydraSchemaAnalyzer } from "@api-platform/admin";
import UserList from './entities/users/UserList';
import UserShow from './entities/users/UserShow';
import { fetchUtils, Admin, Resource, ListGuesser } from 'react-admin';
import jsonServerProvider from 'ra-data-simple-rest';
import simpleRestProvider from 'ra-data-simple-rest';

const httpClient = (url, options = {}) => {
    if (!options.headers) {
        options.headers = new Headers({ Accept: 'application/vnd.api+json' });
    }
    return fetchUtils.fetchJson(url, options);
};
const dataProvider = jsonServerProvider('http://localhost:8000/api', httpClient);

const App = () => (
    <Admin dataProvider={dataProvider}>
        <Resource name="users" list={ListGuesser} />
    </Admin>
);

export default App;
// <Resource name="users" list={UserList} />

ReactDOM.render(<App />, document.getElementById('scandicraft_admin'));