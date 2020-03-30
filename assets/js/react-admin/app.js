import React from 'react';
import ReactDOM from 'react-dom';
import jsonServerProvider from 'ra-data-json-server';
import { Admin, Resource, ListGuesser } from 'react-admin';
import { HydraAdmin } from "@api-platform/admin";

const dataProvider = jsonServerProvider('http://localhost:8000/api');

const App = () => (
    <HydraAdmin entrypoint="http://localhost:8000/api" />
);

export default App;

ReactDOM.render(<App />, document.getElementById('scandicraft_admin'));