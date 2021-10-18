export const CHANGE_STOCK_QUANTITY = 'CHANGE_QUANTITY';
export const FETCH_USER_STOCK = 'FETCH_USER_STOCK';
export const SAVE_USER_STOCK = 'SAVE_USER_STOCK';
export const FETCH_INGREDIENTS = 'FETCH_INGREDIENT';
export const SAVE_INGREDIENTS = 'SAVE_INGREDIENT';
export const DELETE_INGREDIENT = 'DELETE_INGREDIENT';
export const ADD_TO_STOCK = 'ADD_TO_STOCK';
export const CHANGE_VALUE = 'CHANGE_VALUE';
export const CLEAR_ADD_STOCK = 'CLEAR_ADD_STOCK';
export const ADD_TO_RECIPE = 'ADD_TO_RECIPE';
export const DELETE_RECIPE_INGREDIENT = 'DELETE_RECIPE_INGREDIENT';
export const CHANGE_RECIPE_INGREDIENT_QUANTITY = 'CHANGE_RECIPE_INGREDIENT_QUANTITY';
export const VALIDATE_SHOPPING_LIST = 'VALIDATE_SHOPPING_LIST';

export const validateShoppingList = () => ({
  type: VALIDATE_SHOPPING_LIST,
});

export const changeRecipeIngredientQuantity = (id, newValue) => ({
  type: CHANGE_RECIPE_INGREDIENT_QUANTITY,
  id,
  newValue,
});

export const deleteRecipeIngredient = (id) => ({
  type: DELETE_RECIPE_INGREDIENT,
  id,
});

export const addToRecipe = () => ({
  type: ADD_TO_RECIPE,
});

export const clearAddStock = () => ({
  type: CLEAR_ADD_STOCK,
});

export const addToStock = (identifier) => ({
  type: ADD_TO_STOCK,
  identifier,
});

export const fetchUserStock = (identifier) => ({
  type: FETCH_USER_STOCK,
  identifier,
});

export const saveUserStock = (stock, identifier) => ({
  type: SAVE_USER_STOCK,
  stock,
  identifier,
});

export const changeValue = (id, quantity) => ({
  type: CHANGE_VALUE,
  id,
  quantity,
});

export const fetchIngredients = () => ({
  type: FETCH_INGREDIENTS,
});

export const saveIngredients = (ingredients) => ({
  type: SAVE_INGREDIENTS,
  ingredients,
});

export const changeStockQuantity = (id, newValue, identifier) => ({
  type: CHANGE_STOCK_QUANTITY,
  id,
  newValue,
  identifier,
});

export const deleteIngredient = (id, identifier) => ({
  type: DELETE_INGREDIENT,
  id,
  identifier,
});
