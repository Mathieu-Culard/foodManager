import { SAVE_USER_INFO, CLEAR_USER_INFO } from 'src/actions/user';
import { CHANGE_STOCK_QUANTITY, DELETE_INGREDIENT, SAVE_USER_STOCK } from 'src/actions/ingredients';
import { updateStock, deleteIngredient } from 'src/utils';

const initialState = {
  id: -1,
  username: '',
  password: '',
  passwordConf: '',
  email: '',
  avatar: '',
  role: '',
  stock: [],
  shoppingList: [],
};

const UserReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case SAVE_USER_STOCK:
      return {
        ...state,
        stock: action.stock,
      };
    case SAVE_USER_INFO:
      return {
        ...state,
        ...action.data,
      };
    case CLEAR_USER_INFO:
      return {
        ...state,
        id: -1,
        username: '',
        password: '',
        passwordConf: '',
        email: '',
        avatar: '',
        role: '',
        stock: [],
        shoppingList: [],
      };
    case CHANGE_STOCK_QUANTITY:
      return {
        ...state,
        stock: updateStock(state.stock, action.id, action.newValue),
      };
    case DELETE_INGREDIENT:
      return {
        ...state,
        stock: deleteIngredient(state.stock, action.id),
      }
    default: return state;
  }
};

export default UserReducer;
