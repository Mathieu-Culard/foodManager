import { SAVE_USER_INFO, CLEAR_USER_INFO } from 'src/actions/user';
import { SAVE_MY_RECIPES } from 'src/actions/recipes';
import {
  CHANGE_STOCK_QUANTITY, DELETE_INGREDIENT, SAVE_USER_STOCK, VALIDATE_SHOPPING_LIST,
} from 'src/actions/ingredients';
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
  shop: [],
  recipesShop: [],
  recipes: [],
};

const UserReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    // case VALIDATE_SHOPPING_LIST:
    //   return {
    //     ...state,
    //     shop: [],
    //     recipesShop: [],
    //   };
    case SAVE_MY_RECIPES:
      return {
        ...state,
        recipes: action.recipes,
      };
    case SAVE_USER_STOCK: {
      if (action.identifier === 'shop') {
        return {
          ...state,
          recipesShop: action.stock.recipes,
          shop: action.stock.ingredients,
        };
      }
      return {
        ...state,
        stock: action.stock,
      };
    }
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
        shop: [],
        recipes: [],
      };
    // case CHANGE_STOCK_QUANTITY: {
    //   let list = state.stock;
    //   if (action.identifier === 'shop') {
    //     list = state.shop;
    //   }
    //   return {
    //     ...state,
    //     [action.identifier]: updateStock(list, action.id, action.newValue),
    //   };
    // }
    // case DELETE_INGREDIENT: {
    //   let list = state.stock;
    //   if (action.identifier === 'shop') {
    //     list = state.shop;
    //   }
    //   return {
    //     ...state,
    //     [action.identifier]: deleteIngredient(list, action.id),
    //   };
    // }
    default: return state;
  }
};

export default UserReducer;
