import axios from 'axios';
import { push } from 'connected-react-router';
import { logOut } from 'src/actions/connection';
import {
  FETCH_PUBLIC_RECIPES,
  FETCH_RECIPE,
  fetchMyRecipes,
  saveRecipes,
  saveCurrentRecipe,
  ADD_NEW_RECIPE,
  FETCH_MY_RECIPES,
  EDIT_RECIPE,
  clearAddRecipeForm,
  saveMyRecipes,
  setInfoForEdit,
  DELETE_RECIPE,
  BUY_RECIPE,
  BUY_LESS_RECIPE,
  DELETE_RECIPE_TO_BUY,
  COOK_RECIPE,
  endRecipeLoad,
} from 'src/actions/recipes';
import { fetchUserStock } from 'src/actions/ingredients';
import { changeErrorMessage, openSnackbar } from 'src/actions/utils';

const RecipesMiddleware = (store) => (next) => (action) => {
  switch (action.type) {
    case COOK_RECIPE: {
      axios.post(`http://localhost:8000/api/recipe/cook/${action.recipeId}`,
        {},
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          console.log(response.data);
          store.dispatch(fetchUserStock('stock'));
          store.dispatch(fetchUserStock('shop'));
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
    case DELETE_RECIPE_TO_BUY: {
      axios.delete(`http://localhost:8000/api/recipe/buy/delete/${action.recipeId}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          console.log(response.data);
          store.dispatch(fetchUserStock('shop'));
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
    case BUY_LESS_RECIPE: {
      axios.post(`http://localhost:8000/api/recipe/buyless/${action.recipeId}`,
        {},
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then(() => {
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
    case BUY_RECIPE: {
      axios.post(`http://localhost:8000/api/recipe/buy/${action.recipeId}`,
        {},
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          store.dispatch(fetchUserStock('shop'));
          console.log(response);
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
    case DELETE_RECIPE: {
      axios.delete(`http://localhost:8000/api/recipe/delete/${action.id}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          console.log(response.data);
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
    case FETCH_MY_RECIPES: {
      axios.get('http://localhost:8000/api/recipe/my-recipes',
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          store.dispatch(saveMyRecipes(response.data));
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
    case EDIT_RECIPE: {
      const {
        name,
        picture,
        ingredients,
        steps,
        shared,
      } = store.getState().recipe;
      const formData = new FormData();
      formData.append('name', name);
      formData.append('picture', picture);
      // formData.append('ingredients[]', ingredients.map((ingredient) => ingredient.id));
      // formData.append('steps[]', steps.map((step) => (step.text)));
      formData.append('ingredients', JSON.stringify(ingredients.map((ingredient) => (
        {
          id: ingredient.id,
          quantity: ingredient.quantity,
        }
      ))));
      formData.append('steps', JSON.stringify(steps.map((step) => step.text)));
      formData.append('shared', shared);
      // formData.append('quantity[]', ingredients.map((ingredient) => ingredient.quantity));

      axios.post(`http://localhost:8000/api/recipe/edit/${action.id}`,
        formData,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
            'Content-Type': 'multipart/form-data',
          },
        })
        .then((response) => {
          console.log(response);
          store.dispatch(clearAddRecipeForm());
          store.dispatch(push('/my-recipes'));
          console.log('mais dou ?');
        }).catch((error) => {
          if (error.response.status === 401) {
            localStorage.clear();
            store.dispatch(logOut());
          }
          store.dispatch(openSnackbar(error.response.data, 'warning'));
        });
      next(action);
      break;
    }
    case ADD_NEW_RECIPE: {
      const {
        name,
        picture,
        ingredients,
        steps,
        shared,
      } = store.getState().recipe;
      const formData = new FormData();
      formData.append('name', name);
      formData.append('picture', picture);
      formData.append('ingredients', JSON.stringify(ingredients.map((ingredient) => (
        {
          id: ingredient.id,
          quantity: ingredient.quantity,
        }
      ))));
      formData.append('steps', JSON.stringify(steps.map((step) => step.text)));
      formData.append('shared', shared);
      axios.post('http://localhost:8000/api/recipe/add',
        formData,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
            'Content-Type': 'multipart/form-data',
          },
        })
        .then((response) => {
          store.dispatch(clearAddRecipeForm());
          store.dispatch(push('/my-recipes'));
        }).catch((error) => {
          if (error.response.status === 401) {
            localStorage.clear();
            store.dispatch(logOut());
            store.dispatch(openSnackbar(error.response.data, 'warning'));
          }
          else {
            store.dispatch(openSnackbar(error.response.data, 'warning'));
          }
        });
      next(action);
      break;
    }
    case FETCH_PUBLIC_RECIPES: {
      axios.get('http://localhost:8000/api/recipes',
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          store.dispatch(saveRecipes(response.data));
        }).catch((error) => {
          console.log(error);
        });
      next(action);
      break;
    }
    case FETCH_RECIPE: {
      console.log(action.id);
      axios.get(`http://localhost:8000/api/recipe/${action.isEdit ? 'edit' : 'get'}/${action.id}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          if (action.isEdit) {
            store.dispatch(setInfoForEdit(response.data));
          }
          else {
            store.dispatch(saveCurrentRecipe(response.data));
          }

          console.log(response.data);
          store.dispatch(endRecipeLoad());
          console.log(response.data.steps[0].text);
        })
        .catch((error) => {
          console.log(typeof error.response.status);
          store.dispatch(changeErrorMessage(error.response.data, error.response.status));
          setTimeout(store.dispatch(push('/error'), 5000));
        });
      next(action);
      break;
    }
    default:
      next(action);
  }
};

export default RecipesMiddleware;
