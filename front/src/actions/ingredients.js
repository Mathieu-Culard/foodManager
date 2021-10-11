export const CHANGE_STOCK_QUANTITY = 'CHANGE_QUANTITY';
export const FETCH_USER_STOCK = 'FETCH_USER_STOCK';
export const SAVE_USER_STOCK = 'SAVE_USER_STOCK';
export const FETCH_INGREDIENTS = 'FETCH_INGREDIENT';
export const SAVE_INGREDIENTS = 'SAVE_INGREDIENT';
export const DELETE_INGREDIENT = 'DELETE_INGREDIENT';
export const ADD_TO_STOCK = 'ADD_TO_STOCK';
export const CHANGE_VALUE = 'CHANGE_VALUE';
export const CLEAR_ADD_STOCK = 'CLEAR_ADD_STOCK';

export const clearAddStock = () => ({
  type: CLEAR_ADD_STOCK,
});

export const addToStock = () => ({
  type: ADD_TO_STOCK,
});

export const fetchUserStock = () => ({
  type: FETCH_USER_STOCK,
});

export const saveUserStock = (stock) => ({
  type: SAVE_USER_STOCK,
  stock,
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

export const changeStockQuantity = (id, newValue) => ({
  type: CHANGE_STOCK_QUANTITY,
  id,
  newValue,
});

export const deleteIngredient = (id) => ({
  type: DELETE_INGREDIENT,
  id,
});
