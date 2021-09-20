import { SAVE_USER_INFO, CLEAR_USER_INFO } from 'src/actions/user';

const initialState = {
  id: -1,
  username: '',
  password: '',
  passwordConf: '',
  email: '',
  avatar: '',
  role: '',

};

const UserReducer = (state = initialState, action = {}) => {
  switch (action.type) {
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
      };
    default: return state;
  }
};

export default UserReducer;
