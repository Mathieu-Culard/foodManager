import axios from 'axios';
import { logOut } from 'src/actions/connection';
import { openSnackbar, closeModal } from 'src/actions/utils';
import {
  CHANGE_STOCK_QUANTITY,
  DELETE_INGREDIENT,
  FETCH_INGREDIENTS,
  saveIngredients,
  ADD_TO_STOCK,
  ADD_TO_RECIPE,
  fetchUserStock,
  FETCH_USER_STOCK,
  saveUserStock,
  VALIDATE_SHOPPING_LIST,
  SEND_SHOPPING_LIST,
} from 'src/actions/ingredients';
import { fetchPublicRecipes, fetchMyRecipes } from 'src/actions/recipes';

const IngredientsMiddleware = (store) => (next) => (action) => {
  switch (action.type) {
    case SEND_SHOPPING_LIST: {
      axios.get('http://localhost:8000/api/send/shopping',
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          console.log(response);
        }).catch((error) => {
          console.log(error);
        });
      next(action);
      break;
    }
    case ADD_TO_RECIPE:
      action.ingredientsList = store.getState().ingredients.ingredientsList;
      action.ingredientsToAdd = store.getState().ingredients.addStock;
      store.dispatch(closeModal());
      next(action);
      break;
    case ADD_TO_STOCK: {
      const { addStock } = store.getState().ingredients;
      axios.post('http://localhost:8000/api/stock/add',
        {
          identifier: action.identifier,
          addStock,
        },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          store.dispatch(fetchUserStock(action.identifier));
          if (action.identifier === 'stock') {
            store.dispatch(fetchUserStock('shop'));
          }
          store.dispatch(closeModal());
          store.dispatch(fetchPublicRecipes());
          store.dispatch(fetchMyRecipes());
        })
        .catch((error) => {
          if (error.response.status === 401) {
            localStorage.clear();
            store.dispatch(logOut());
            store.dispatch(openSnackbar(error.response.data, 'warning'));
          }
        });
      next(action);
      break;
    }
    case FETCH_USER_STOCK: {
      axios.get('http://localhost:8000/api/stock/list',
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
          params: {
            identifier: action.identifier,
          },
        })
        .then((response) => {
          store.dispatch(saveUserStock(response.data, action.identifier));
        })
        .catch((error) => {
          if (error.response.status === 401) {
            localStorage.clear();
            store.dispatch(logOut());
            store.dispatch(openSnackbar(error.response.data, 'warning'));
          }
        });
      next(action);
      break;
    }
    case FETCH_INGREDIENTS: {
      axios.get('http://localhost:8000/api/ingredients')
        .then((response) => {
          store.dispatch(saveIngredients(response.data));
        }).catch((error) => {
          console.log(error.response);
        });
      next(action);
      break;
    }
    case CHANGE_STOCK_QUANTITY: {
      axios.post(`http://localhost:8000/api/stock/edit/${action.id}`,
        {
          newValue: action.newValue,
          identifier: action.identifier,
        },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then(() => {
          store.dispatch(fetchUserStock(action.identifier));
          store.dispatch(fetchPublicRecipes());
          store.dispatch(fetchMyRecipes());
          if (action.identifier === 'stock') {
            store.dispatch(fetchUserStock('shop'));
          }
          // store.dispatch(saveRecipes(response.data));
        }).catch((error) => {
          if (error.response.status === 401) {
            localStorage.clear();
            store.dispatch(logOut());
            store.dispatch(openSnackbar(error.response.data, 'warning'));
          }
          console.log(error.response);
        });
      next(action);
      break;
    }
    case DELETE_INGREDIENT: {
      axios.delete(`http://localhost:8000/api/stock/delete/${action.id}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
          params: {
            identifier: action.identifier,
          },
        })
        .then((response) => {
          store.dispatch(fetchPublicRecipes());
          store.dispatch(fetchMyRecipes());
          store.dispatch(fetchUserStock(action.identifier));
          if (action.identifier === 'stock') {
            store.dispatch(fetchUserStock('shop'));
          }
        }).catch((error) => {
          if (error.response.status === 401) {
            localStorage.clear();
            store.dispatch(logOut());
            store.dispatch(openSnackbar(error.response.data, 'warning'));
          }
        });
      next(action);
      break;
    }
    case VALIDATE_SHOPPING_LIST: {
      const { recipesShop } = store.getState().user;
      axios.post('http://localhost:8000/api/shop/validate',
        { recipesShop },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          store.dispatch(fetchMyRecipes());
          store.dispatch(fetchPublicRecipes());
          store.dispatch(fetchUserStock('stock'));
          store.dispatch(fetchUserStock('shop'));
        }).catch((error) => {
          if (error.response.status === 401) {
            localStorage.clear();
            store.dispatch(logOut());
            store.dispatch(openSnackbar(error.response.data, 'warning'));
          }
        });
      next(action);
      break;
    }
    default:
      next(action);
  }
};

export default IngredientsMiddleware;
