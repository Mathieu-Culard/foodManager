// == Import npm
import React, { useEffect } from 'react';
import PropTypes from 'prop-types';
import { Switch, Route } from 'react-router-dom';
import './app.scss';

import Header from 'src/containers/Header';
import ModalPanel from 'src/containers/ModalPanel';
import Snackbar from 'src/containers/Snackbar';
import RecipesListPage from 'src/containers/RecipesListPage';
import RecipePage from 'src/containers/RecipePage';
import Footer from 'src/containers/Footer';
import StockPanel from 'src/containers/StockPanel';
import ShopPanel from 'src/containers/ShopPanel';
import AddRecipePage from 'src/containers/AddRecipePage';
import Loader from 'src/components/Loader';
import ErrorPage from 'src/containers/ErrorPage';
// == Composant
const App = ({
  fetchIngredients, fetchPublicRecipes, isLogged, clearUserInfo, refreshToken, isLoading,
}) => {
  useEffect(() => {
    refreshToken();
    fetchPublicRecipes();
    fetchIngredients();

    // if (!isLogged) {
    //   clearUserInfo();
    // }
  }, []);

  return (
    <div className="app">
      {isLoading && <Loader />}
      {!isLoading && isLogged && (
        <>
          <Header />
          <StockPanel />
          <ShopPanel />
          <Switch>
            <Route path="/recipe/:id">
              <RecipePage />
            </Route>
            <Route path="/add-recipe">
              <AddRecipePage />
            </Route>
            <Route path="/my-recipes/edit-recipe/:id">
              <AddRecipePage />
            </Route>
            <Route path="/my-recipes">
              <RecipesListPage />
            </Route>
            <Route path="/error">
              <ErrorPage />
            </Route>
            <Route path="/">
              <RecipesListPage />
            </Route>
          </Switch>
          {/* <h1>Composant : App</h1>
    <input type="button" value="checkToken" onClick={checkToken} /> */}
          <ModalPanel />
          <Snackbar />
          <Footer />
        </>
      )}
      {!isLoading && !isLogged && (
        <>
          <Header />
          <Switch>
            <Route path="/recipe/:id">
              <RecipePage />
            </Route>
            <Route path="/add-recipe">
              <AddRecipePage />
            </Route>
            <Route path="/my-recipes/edit-recipe/:id">
              <AddRecipePage />
            </Route>
            <Route path="/my-recipes">
              <RecipesListPage />
            </Route>
            <Route path="/">
              <RecipesListPage />
            </Route>
          </Switch>
          {/* <h1>Composant : App</h1>
    <input type="button" value="checkToken" onClick={checkToken} /> */}
          <ModalPanel />
          <Snackbar />
          <Footer />
        </>
      )}
    </div>
  );
};

App.propTypes = {
  fetchPublicRecipes: PropTypes.func.isRequired,
};
// == Export
export default App;
