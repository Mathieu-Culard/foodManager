import { createStore, applyMiddleware } from 'redux';
import { composeWithDevTools } from 'redux-devtools-extension';
import ConnectionMiddleware from 'src/middlewares/ConnectionMiddleware';
import RecipesMiddleware from 'src/middlewares/RecipesMiddleware';
import IngredientsMiddleware from 'src/middlewares/IngredientsMiddleware';
import reducer from 'src/reducers';

const enhancers = composeWithDevTools(
  applyMiddleware(
    ConnectionMiddleware,
    RecipesMiddleware,
    IngredientsMiddleware,
  ),
);

const store = createStore(
  reducer,
  enhancers,
);

export default store;
