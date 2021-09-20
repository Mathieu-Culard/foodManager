import { CHANGE_FIELD, CLEAR_FORM, LOG_IN, LOG_OUT } from 'src/actions/connection';

const initialState = {
  isLogged: false,
  username: '',
  password: '',
  passwordConf: '',
  email: '',
};

const utilsReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case CHANGE_FIELD:
      return {
        ...state,
        [action.identifier]: action.newValue,
      };
    case CLEAR_FORM:
      return {
        ...state,
        username: '',
        password: '',
        passwordConf: '',
        email: '',
      };
    case LOG_IN:
      return {
        ...state,
        isLogged: true,
      };
    case LOG_OUT:
      return {
        ...state,
        isLogged: false,
      };
    default: return state;
  }
};

export default utilsReducer;
