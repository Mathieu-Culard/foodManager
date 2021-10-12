import { createStore, applyMiddleware } from 'redux';
import { createBrowserHistory } from 'history';
import { composeWithDevTools } from 'redux-devtools-extension';
import { routerMiddleware } from 'connected-react-router';

import ConnectionMiddleware from 'src/middlewares/ConnectionMiddleware';
import RecipesMiddleware from 'src/middlewares/RecipesMiddleware';
import IngredientsMiddleware from 'src/middlewares/IngredientsMiddleware';
import reducer from 'src/reducers';

export const history = createBrowserHistory();

const enhancers = composeWithDevTools(
  applyMiddleware(
    routerMiddleware(history),
    ConnectionMiddleware,
    RecipesMiddleware,
    IngredientsMiddleware,
  ),
);

export default function configureStore(preloadedState) {
  const store = createStore(
    reducer(history),
    preloadedState,
    enhancers,
  );
  return store;
}
