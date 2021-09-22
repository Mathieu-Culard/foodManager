export const FETCH_RECIPES = 'FETCH_RECIPES';
export const SAVE_RECIPES = 'SAVE_RECIPES';
export const LOAD = 'LOAD';
export const END_LOAD = 'END_LOAD';

export const endLoad = () => ({
  type: END_LOAD,
});

export const saveRecipes = (recipes) => ({
  type: SAVE_RECIPES,
  recipes,
});

export const fetchRecipes = () => ({
  type: FETCH_RECIPES,
});
