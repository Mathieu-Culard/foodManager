import axios from 'axios';

import { FETCH_RECIPES, saveRecipes } from 'src/actions/recipes';

const RecipesMiddleware = (store) => (next) => (action) => {
  switch (action.type) {
    case FETCH_RECIPES: {
      axios.get('http://localhost:8000/recipes')
        .then((response) => {
          store.dispatch(saveRecipes(response.data));
        }).catch((error) => {
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
