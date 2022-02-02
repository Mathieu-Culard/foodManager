import axios from 'axios';
import {
  SUBMIT_REGISTRATION, clearForm, SUBMIT_CONNECTION, CHECK_TOKEN, logIn, LOG_OUT, logOut,
} from 'src/actions/connection';
import { fetchPublicRecipes } from 'src/actions/recipes';
import { clearAddStock } from 'src/actions/ingredients';
import { saveUserInfo, clearUserInfo, CLEAR_USER_INFO } from 'src/actions/user';
import { CLOSE_MODAL, closeModal, openSnackbar } from 'src/actions/utils';
import { push } from 'connected-react-router';

const ConnectionMiddleware = (store) => (next) => (action) => {
  switch (action.type) {
    case CLEAR_USER_INFO: {
      localStorage.clear();
      store.dispatch(push('/'));
      next(action);
      break;
    }
    case CLOSE_MODAL: {
      store.dispatch(clearForm());
      store.dispatch(clearAddStock());
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
      axios.post('http://localhost:8000/api/register', {
        username,
        email,
        password,
        passwordConf,
      }).then((response) => {
        store.dispatch(closeModal());
        store.dispatch(openSnackbar(response.data, 'success'));
      }).catch((error) => {
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
      axios.post('http://localhost:8000/api/login', {
        username,
        password,
      }).then((response) => {
        // console.log(response.data.user.stock[1]);
        localStorage.setItem('jwt', response.data.token);
        store.dispatch(closeModal());
        store.dispatch(fetchPublicRecipes());
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
      axios.get('http://localhost:8000/api/checktoken',
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
      store.dispatch(fetchPublicRecipes());
      store.dispatch(openSnackbar('deconnexion effectué', 'success'));
      next(action);
      break;
    }
    default:
      next(action);
  }
};

export default ConnectionMiddleware;
