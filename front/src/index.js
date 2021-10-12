// == Import : npm
import React from 'react';
import { render } from 'react-dom';
// import { BrowserRouter as Router } from 'react-router-dom';
import { ConnectedRouter } from 'connected-react-router';

import { Provider } from 'react-redux';
// == Import : local
// Composants
import App from 'src/containers/App';

import store, { history } from 'src/store';

const myStore = store();

const rootReactElement = (
  <Provider store={myStore}>
    <ConnectedRouter history={history}>
      <App />
    </ConnectedRouter>
  </Provider>
);

const target = document.getElementById('root');

render(rootReactElement, target);
