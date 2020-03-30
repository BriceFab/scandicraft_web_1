import React from 'react';
import ReactDOM from 'react-dom';
import { Admin } from 'react-admin';
import jsonServerProvider from 'ra-data-json-server';

const dataProvider = jsonServerProvider('http//localhost/api');
const App = () => <Admin dataProvider={dataProvider} />;

export default App;

ReactDOM.render(<App />, document.getElementById('scandicraft_admin'));