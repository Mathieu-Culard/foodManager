import { SAVE_RECIPES } from 'src/actions/recipes';

const initialState = {
  recipesList: [],
  isLoading: true,
};

const recipesReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case SAVE_RECIPES:
      return {
        ...state,
        recipesList: action.recipes,
        isLoading: false,
      };
    default: return state;
  }
};

export default recipesReducer;
