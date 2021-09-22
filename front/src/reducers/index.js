import { combineReducers } from 'redux';
import utils from './utilsReducer';
import user from './UserReducer';
import connection from './ConnectionReducer';
import recipe from './RecipesReducer';

const rootReducer = combineReducers({
  utils,
  user,
  connection,
  recipe,
});

export default rootReducer;
