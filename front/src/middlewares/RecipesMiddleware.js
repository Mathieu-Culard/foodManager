import axios from 'axios';
import { push } from 'connected-react-router';
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
} from 'src/actions/recipes';
import { endLoad } from 'src/actions/utils';

const RecipesMiddleware = (store) => (next) => (action) => {
  switch (action.type) {
    case DELETE_RECIPE: {
      axios.delete(`http://localhost:8000/recipe/delete/${action.id}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response)=>{
          console.log(response.data);
          store.dispatch(fetchMyRecipes());
        })
        .catch((error)=>{
          console.log(error);
        });
      next(action);
      break;
    }
    case FETCH_MY_RECIPES: {
      axios.get('http://localhost:8000/recipe/my-recipes',
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
          },
        })
        .then((response) => {
          store.dispatch(saveMyRecipes(response.data));
        }).catch((error) => {
          console.log(error);
        });
      next(action);
      break;
    }
    case EDIT_RECIPE: {
      console.log('bler');
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

      axios.post(`http://localhost:8000/recipe/edit/${action.id}`,
        formData,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
            'Content-Type': 'multipart/form-data',
          },
        }).then((response) => {
          console.log(response);
          store.dispatch(clearAddRecipeForm());
          store.dispatch(push('/my-recipes'));
          console.log('mais dou ?');
        }).catch((error) => {
          console.log(error);
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

      axios.post('http://localhost:8000/recipe/add',
        formData,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
            'Content-Type': 'multipart/form-data',
          },
        }).then((response) => {
          console.log(response);
          store.dispatch(clearAddRecipeForm());
          store.dispatch(push('/my-recipes'));
          console.log('mais dou ?');
        }).catch((error) => {
          console.log(error);
        });
      next(action);
      break;
    }
    case FETCH_PUBLIC_RECIPES: {
      axios.get('http://localhost:8000/recipes')
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
      axios.get(`http://localhost:8000/recipe/${action.id}`)
        .then((response) => {
          if (action.isEdit) {
            store.dispatch(setInfoForEdit(response.data));
          }
          else {
            store.dispatch(saveCurrentRecipe(response.data));
          }

          console.log(response.data);
          store.dispatch(endLoad());
          console.log(response.data.steps[0].text);
        })
        .catch((error) => {
          console.log(error);
        });
      next(action);
      break;
    }
    default:
      next(action);
  }
};

export default RecipesMiddleware;
