// == Import npm
import React, { useEffect } from 'react';
import PropTypes from 'prop-types';

import Header from 'src/containers/Header';
import ModalPanel from 'src/containers/ModalPanel';
import Snackbar from 'src/containers/Snackbar';
import { Switch, Route } from 'react-router-dom';

import Recipes from 'src/containers/Recipes';

// == Composant
const App = ({ fetchRecipes }) => {
  useEffect(() => {
    fetchRecipes();
  }, []);

  return (
    <div className="app">
      <Header />
      <Switch>
        <Route path="/">
          <Recipes />
        </Route>
      </Switch>
      {/* <h1>Composant : App</h1>
    <input type="button" value="checkToken" onClick={checkToken} /> */}
      <ModalPanel />
      <Snackbar />
    </div>
  );
};

App.propTypes = {
  fetchRecipes: PropTypes.func.isRequired,
};
// == Export
export default App;
