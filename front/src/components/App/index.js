// == Import npm
import React from 'react';

import Header from 'src/containers/Header';
import ModalPanel from 'src/containers/ModalPanel';
import Snackbar from 'src/containers/Snackbar';

// == Composant
const App = ({ checkToken }) => (
  <div className="app">
    <Header />
    <h1>Composant : App</h1>
    <input type="button" value="checkToken" onClick={checkToken} />
    <ModalPanel />
    <Snackbar />
  </div>
);

// == Export
export default App;
