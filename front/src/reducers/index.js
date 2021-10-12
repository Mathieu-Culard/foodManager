import { combineReducers } from 'redux';
import { connectRouter } from 'connected-react-router';
import utils from './utilsReducer';
import user from './UserReducer';
import connection from './ConnectionReducer';
import recipe from './RecipesReducer';
import ingredients from './IngredientsReducer';



const rootReducer = (history) => combineReducers({
  router: connectRouter(history),
  utils,
  user,
  connection,
  recipe,
  ingredients,
});

export default rootReducer;
