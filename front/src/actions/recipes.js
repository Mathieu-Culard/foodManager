export const FETCH_PUBLIC_RECIPES = 'FETCH_PUBLIC_RECIPES';
export const SAVE_RECIPES = 'SAVE_RECIPES';
export const FETCH_RECIPE = 'FETCH_RECIPE';
// export const FETCH_RECIPE_FOR_EDIT = 'FETCH_RECIPE_FOR_EDIT';
export const SAVE_CURRENT_RECIPE = 'SAVE_CURRENT_RECIPE';
export const CHANGE_STEPS = 'CHANGE_STEPS';
export const ADD_STEP = 'ADD_STEP';
export const DELETE_STEP = 'DELETE_STEP';
export const CHANGE_SWITCH = 'CHANGE_SWITCH';
export const CHANGE_NAME = 'CHANGE_NAME';
export const CHANGE_PICTURE = 'CHANGE_PICTURE';
export const ADD_NEW_RECIPE = 'ADD_NEW_RECIPE';
export const FETCH_MY_RECIPES = 'FETCH_MY_RECIPES';
export const SAVE_MY_RECIPES = 'SAVE_MY_RECIPES';
export const CLEAR_ADD_RECIPE_FORM = 'CLEAR_ADD_RECIPE_FORM';
export const SET_INFO_FOR_EDIT = 'SET_INFO_FOR_EDIT';
export const EDIT_RECIPE = 'EDIT_RECIPE';
export const DELETE_RECIPE = 'DELETE_RECIPE';
export const BUY_RECIPE = 'BUY_RECIPE';
export const BUY_LESS_RECIPE = 'BUY_LESS_RECIPE';
export const DELETE_RECIPE_TO_BUY = 'DELETE_RECIPE_TO_BUY';
export const COOK_RECIPE = 'COOK_RECIPE';
export const LOAD_RECIPE = 'LOAD_RECIPE';
export const END_RECIPE_LOAD = 'END_RECIPE_LOAD';
export const ADD_RECIPE = 'ADD_RECIPE';

export const addRecipe = (recipe) => ({
  type: ADD_RECIPE,
  recipe,
});

export const loadRecipe = () => ({
  type: LOAD_RECIPE,
});

export const endRecipeLoad = () => ({
  type: END_RECIPE_LOAD,
});

export const cookRecipe = (recipeId) => ({
  type: COOK_RECIPE,
  recipeId,
});

export const deleteRecipeToBuy = (recipeId) => ({
  type: DELETE_RECIPE_TO_BUY,
  recipeId,
});

export const buyLessRecipe = (recipeId) => ({
  type: BUY_LESS_RECIPE,
  recipeId,
});

export const buyRecipe = (recipeId) => ({
  type: BUY_RECIPE,
  recipeId,
});

export const deleteRecipe = (id) => ({
  type: DELETE_RECIPE,
  id,
});

export const editRecipe = (id) => ({
  type: EDIT_RECIPE,
  id,
});

// export const fetchRecipeForEdit = (id) => ({
//   type: FETCH_RECIPE_FOR_EDIT,
//   id,
// });

export const setInfoForEdit = (recipe) => ({
  type: SET_INFO_FOR_EDIT,
  recipe,
});

export const clearAddRecipeForm = () => ({
  type: CLEAR_ADD_RECIPE_FORM,
});

export const saveMyRecipes = (recipes) => ({
  type: SAVE_MY_RECIPES,
  recipes,
});

export const fetchMyRecipes = () => ({
  type: FETCH_MY_RECIPES,
});

export const addNewRecipe = () => ({
  type: ADD_NEW_RECIPE,
});

export const changePicture = (picture) => ({
  type: CHANGE_PICTURE,
  picture,
});

export const changeName = (newValue) => ({
  type: CHANGE_NAME,
  newValue,
});

export const changeSwitch = () => ({
  type: CHANGE_SWITCH,
});

export const deleteStep = (id) => ({
  type: DELETE_STEP,
  id,
});

export const addStep = () => ({
  type: ADD_STEP,
});

export const changeSteps = (newValue, index) => ({
  type: CHANGE_STEPS,
  newValue,
  index,
});

export const saveCurrentRecipe = (recipe) => ({
  type: SAVE_CURRENT_RECIPE,
  recipe,
});

export const fetchRecipe = (id, isEdit) => ({
  type: FETCH_RECIPE,
  id,
  isEdit,
});

export const saveRecipes = (recipes) => ({
  type: SAVE_RECIPES,
  recipes,
});

export const fetchPublicRecipes = () => ({
  type: FETCH_PUBLIC_RECIPES,
});
