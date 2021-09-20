import axios from 'axios';

import {
  SUBMIT_REGISTRATION, clearForm, SUBMIT_CONNECTION, CHECK_TOKEN, logIn, LOG_OUT, logOut,
} from 'src/actions/connection';
import { saveUserInfo, clearUserInfo } from 'src/actions/user';
import { CLOSE_MODAL, closeModal, openSnackbar } from 'src/actions/utils';

const ConnectionMiddleware = (store) => (next) => (action) => {
  switch (action.type) {
    case CLOSE_MODAL: {
      store.dispatch(clearForm());
      next(action);
      break;
    }
    case SUBMIT_REGISTRATION: {
      const {
        username,
        email,
        password,
        passwordConf,
      } = store.getState().connection;
      axios.post('http://localhost:8000/register', {
        username,
        email,
        password,
        passwordConf,
      }).then(() => {
        console.log('ca marche??');
        store.dispatch(closeModal());
        store.dispatch(openSnackbar('inscription reussie', 'sucess'));
      }).catch((error) => {
        console.log(error.response.data);
        store.dispatch(openSnackbar(error.response.data, 'warning'));
      });
      next(action);
      break;
    }
    case SUBMIT_CONNECTION: {
      const {
        username,
        password,
      } = store.getState().connection;
      axios.post('http://localhost:8000/login', {
        username,
        password,
      }).then((response) => {
        console.log(response.data);
        localStorage.setItem('jwt', response.data.token);
        store.dispatch(closeModal());
        store.dispatch(logIn());
        store.dispatch(saveUserInfo(response.data.user));
        store.dispatch(openSnackbar('connexion effectuée', 'success'));
      }).catch((error) => {
        store.dispatch(openSnackbar(error.response.data, 'warning'));
      });
      next(action);
      break;
    }
    case CHECK_TOKEN: {
      axios.get('http://localhost:8000/checktoken',
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          console.log(response.data);
          console.log(localStorage.getItem('jwt'));
        })
        .catch((error) => {
          localStorage.clear();
          // store.dispatch(clearUserInfo());
          store.dispatch(logOut());
        });
      next(action);
      break;
    }
    case LOG_OUT: {
      store.dispatch(clearUserInfo());
      store.dispatch(openSnackbar('deconnexion effectué', 'success'));
      next(action);
      break;
    }
    default:
      next(action);
  }
};

export default ConnectionMiddleware;
