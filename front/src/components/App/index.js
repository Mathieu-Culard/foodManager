// == Import npm
import React, { useEffect } from 'react';
import PropTypes from 'prop-types';
import { Switch, Route } from 'react-router-dom';

import Header from 'src/containers/Header';
import ModalPanel from 'src/containers/ModalPanel';
import Snackbar from 'src/containers/Snackbar';
import HomePage from 'src/containers/HomePage';
import RecipePage from 'src/containers/RecipePage';
import Footer from 'src/containers/Footer';
// == Composant
const App = ({ fetchPublicRecipes }) => {
  useEffect(() => {
    fetchPublicRecipes();
  }, []);

  return (
    <div className="app">
      <Header />
      <Switch>
        <Route path="/recipe/:id">
          <RecipePage />
        </Route>
        <Route path="/">
          <HomePage />
        </Route>
      </Switch>
      {/* <h1>Composant : App</h1>
    <input type="button" value="checkToken" onClick={checkToken} /> */}
      <ModalPanel />
      <Snackbar />
      <Footer />
    </div>
  );
};

App.propTypes = {
  fetchPublicRecipes: PropTypes.func.isRequired,
};
// == Export
export default App;
