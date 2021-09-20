import { createStore, applyMiddleware } from 'redux';
import { composeWithDevTools } from 'redux-devtools-extension';
import ConnectionMiddleware from 'src/middlewares/ConnectionMiddleware';
import reducer from 'src/reducers';

const enhancers = composeWithDevTools(
  applyMiddleware(
    ConnectionMiddleware,
  ),
);

const store = createStore(
  reducer,
  enhancers,
);

export default store;
