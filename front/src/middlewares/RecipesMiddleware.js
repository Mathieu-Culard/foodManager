import axios from 'axios';

import { FETCH_PUBLIC_RECIPES, FETCH_RECIPE, saveRecipes, saveCurrentRecipe } from 'src/actions/recipes';
import { endLoad } from 'src/actions/utils';

const RecipesMiddleware = (store) => (next) => (action) => {
  switch (action.type) {
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
          store.dispatch(saveCurrentRecipe(response.data));
          console.log(response.data);
          store.dispatch(endLoad());
          console.log(response.data.steps[0].text);
        })
        .catch((error) => {
          console.log(error.response);
        });
      next(action);
      break;
    }
    default:
      next(action);
  }
};

export default RecipesMiddleware;
