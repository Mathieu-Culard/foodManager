import { combineReducers } from 'redux';
import utils from './utilsReducer';
import user from './UserReducer';
import connection from './ConnectionReducer';

const rootReducer = combineReducers({
  utils,
  user,
  connection,
});

export default rootReducer;
