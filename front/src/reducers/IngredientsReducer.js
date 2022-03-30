import { SAVE_INGREDIENTS, CHANGE_VALUE, CLEAR_ADD_STOCK, CHANGE_TRACK } from 'src/actions/ingredients';
import { addToStock } from 'src/utils';

const initialState = {
  ingredientsList: [],
  addStock: [],
};

const IngredientsReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case CLEAR_ADD_STOCK:
      return {
        ...state,
        addStock: [],
      };
    case SAVE_INGREDIENTS:
      return {
        ...state,
        ingredientsList: action.ingredients,
      };
    case CHANGE_VALUE:
      return {
        ...state,
        addStock: addToStock(state.addStock, action.id, action.quantity),
      };
    default: return state;
  }
};

export default IngredientsReducer;
