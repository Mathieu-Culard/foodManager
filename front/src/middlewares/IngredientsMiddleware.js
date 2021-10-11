import axios from 'axios';
import { logOut } from 'src/actions/connection';
import { openSnackbar, closeModal } from 'src/actions/utils';
import {
  CHANGE_STOCK_QUANTITY,
  DELETE_INGREDIENT,
  FETCH_INGREDIENTS,
  saveIngredients,
  ADD_TO_STOCK,
  fetchUserStock,
  FETCH_USER_STOCK,
  saveUserStock,
} from 'src/actions/ingredients';

const IngredientsMiddleware = (store) => (next) => (action) => {
  switch (action.type) {
    case ADD_TO_STOCK: {
      const { addStock } = store.getState().ingredients;
      console.log('sheeeeh');
      console.log(addStock);
      axios.post('http://localhost:8000/stock/add',
        { addStock },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          store.dispatch(fetchUserStock());
          store.dispatch(closeModal());
          console.log('xxxxxxxxxxx');
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
      next(action);
      break;
    }
    case FETCH_USER_STOCK: {
      axios.get('http://localhost:8000/stock/list',
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          store.dispatch(saveUserStock(response.data));
        })
        .catch((error) => {
          console.log(error);
        });
      next(action);
      break;
    }
    case FETCH_INGREDIENTS: {
      axios.get('http://localhost:8000/ingredients')
        .then((response) => {
          store.dispatch(saveIngredients(response.data));
        }).catch((error) => {
          console.log(error.response);
        });
      next(action);
      break;
    }
    case CHANGE_STOCK_QUANTITY: {
      axios.post(`http://localhost:8000/stock/edit/${action.id}`,
        { newValue: action.newValue },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then(() => {
          next(action); // store.dispatch(saveRecipes(response.data));
        }).catch((error) => {
          if (error.response.status === 401) {
            localStorage.clear();
            store.dispatch(logOut());
            store.dispatch(openSnackbar(error.response.data, 'warning'));
          }
          console.log(error.response);
        });
      break;
    }
    case DELETE_INGREDIENT: {
      axios.delete(`http://localhost:8000/stock/delete/${action.id}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          next(action);
        }).catch((error) => {
          console.log(error.response);
        });
      break;
    }
    default:
      next(action);
  }
};

export default IngredientsMiddleware;
