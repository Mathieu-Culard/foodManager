import {
  SAVE_RECIPES,
  SAVE_CURRENT_RECIPE,
  CHANGE_STEPS,
  ADD_STEP,
  DELETE_STEP,
  CHANGE_SWITCH,
  CHANGE_NAME,
  CHANGE_PICTURE,
  CLEAR_ADD_RECIPE_FORM,
  SET_INFO_FOR_EDIT,
  END_RECIPE_LOAD,
  LOAD_RECIPE,
  DELETE_RECIPE,
  ADD_RECIPE,
} from 'src/actions/recipes';
import {
  ADD_TO_RECIPE,
  DELETE_RECIPE_INGREDIENT,
  CHANGE_RECIPE_INGREDIENT_QUANTITY,
  CHANGE_TRACK,
} from 'src/actions/ingredients';
import {
  manageSteps,
  addStep,
  deleteStep,
  getRecipesIngredients,
  changeRecipeIngredientQuantity,
  // getCurrentRecipe,
} from 'src/utils';


const initialState = {
  name: '',
  picture: '',
  pictureURL: '',
  ingredients: [],
  steps: [{
    id: 1,
    text: '',
  }],
  shared: false,
  recipesList: [],
  currentRecipe: {},
  isLoading: true,
};

const recipesReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case ADD_RECIPE: {
      return {
        ...state,
        recipesList: action.recipe.public
          ? [...state.recipesList, action.recipe] : [...state.recipesList],
      };
    }
    case DELETE_RECIPE: {
      return {
        ...state,
        recipesList: state.recipesList.filter((item) => item.id !== action.id),
      };
    }
    case LOAD_RECIPE:
      return {
        ...state,
        isLoading: true,
      };
    case END_RECIPE_LOAD: {
      return {
        ...state,
        isLoading: false,
      };
    }
    case SET_INFO_FOR_EDIT: {
      const { name, image } = action.recipe.infos;
      console.log(name);
      console.log(action.recipe.infos.public === '1');
      return {
        ...state,
        name,
        pictureURL: `http://localhost:8000/assets/recipes/${image}`,
        shared: action.recipe.infos.public === '1',
        ingredients: action.recipe.ingredients,
        steps: action.recipe.steps,
      };
    }
    case CLEAR_ADD_RECIPE_FORM:
      return {
        ...state,
        name: '',
        picture: '',
        pictureURL: '',
        ingredients: [],
        steps: [{
          id: 1,
          text: '',
        }],
        shared: false,
      };
    case CHANGE_RECIPE_INGREDIENT_QUANTITY:
      return {
        ...state,
        ingredients: changeRecipeIngredientQuantity(state.ingredients, action.id, action.newValue),
      };
    case CHANGE_TRACK:
      return {
        ...state,
      };
    case DELETE_RECIPE_INGREDIENT:
      return {
        ...state,
        ingredients: state.ingredients.filter((item) => item.id !== action.id),
      };
    case ADD_TO_RECIPE:
      return {
        ...state,
        ingredients: getRecipesIngredients(
          action.ingredientsList,
          action.ingredientsToAdd, state.ingredients,
        ),
      };
    case CHANGE_PICTURE:
      return {
        ...state,
        picture: action.picture,
        pictureURL: URL.createObjectURL(action.picture),
      };
    case CHANGE_NAME:
      return {
        ...state,
        name: action.newValue,
      };
    case CHANGE_SWITCH:
      return {
        ...state,
        shared: !state.shared,
      };
    case DELETE_STEP:
      return {
        ...state,
        steps: deleteStep(state.steps, action.id),
      };
    case SAVE_RECIPES:
      return {
        ...state,
        recipesList: action.recipes,
        // currentRecipe: getCurrentRecipe(action.recipes, state.currentRecipe.infos.id),
        // isLoading: false,
      };
    case SAVE_CURRENT_RECIPE:
      return {
        ...state,
        currentRecipe: action.recipe,
      };
    case CHANGE_STEPS:
      return {
        ...state,
        steps: manageSteps(state.steps, action.newValue, action.index),
      };
    case ADD_STEP:
      return {
        ...state,
        steps: addStep(state.steps),
      };
    default: return state;
  }
};

export default recipesReducer;
