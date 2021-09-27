export const FETCH_PUBLIC_RECIPES = 'FETCH_PUBLIC_RECIPES';
export const SAVE_RECIPES = 'SAVE_RECIPES';
export const FETCH_RECIPE = 'FETCH_RECIPE';
export const SAVE_CURRENT_RECIPE = 'SAVE_CURRENT_RECIPE';

export const saveCurrentRecipe = (recipe) => ({
  type: SAVE_CURRENT_RECIPE,
  recipe,
});

export const fetchRecipe = (id) => ({
  type: FETCH_RECIPE,
  id,
});

export const saveRecipes = (recipes) => ({
  type: SAVE_RECIPES,
  recipes,
});

export const fetchPublicRecipes = () => ({
  type: FETCH_PUBLIC_RECIPES,
});
