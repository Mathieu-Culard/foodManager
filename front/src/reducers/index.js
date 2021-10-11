import { combineReducers } from 'redux';
import utils from './utilsReducer';
import user from './UserReducer';
import connection from './ConnectionReducer';
import recipe from './RecipesReducer';
import ingredients from './IngredientsReducer';

const rootReducer = combineReducers({
  utils,
  user,
  connection,
  recipe,
  ingredients,
});

export default rootReducer;
