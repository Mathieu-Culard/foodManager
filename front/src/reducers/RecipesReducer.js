import { SAVE_RECIPES, SAVE_CURRENT_RECIPE } from 'src/actions/recipes';

const initialState = {
  recipesList: [],
  currentRecipe: {},
};

const recipesReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case SAVE_RECIPES:
      return {
        ...state,
        recipesList: action.recipes,
        isLoading: false,
      };
    case SAVE_CURRENT_RECIPE:
      return {
        ...state,
        currentRecipe: action.recipe,
      };
    default: return state;
  }
};

export default recipesReducer;
