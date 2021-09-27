import {
  OPEN_MODAL,
  CLOSE_MODAL,
  OPEN_SNACKBAR,
  CLOSE_SNACKBAR,
  LOAD,
  END_LOAD,
} from 'src/actions/utils';

const initialState = {
  loading: true,
  modalComponent: '',
  modalTitle: '',
  modal: false,
  snackbar: false,
  snackbarMessage: '',
  snackbarSeverity: '',
};

const utilsReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case OPEN_SNACKBAR:
      return {
        ...state,
        snackbar: true,
        snackbarMessage: action.message,
        snackbarSeverity: action.severity,
      };
    case CLOSE_SNACKBAR:
      return {
        ...state,
        snackbar: false,
      };
    case OPEN_MODAL:
      return {
        ...state,
        modal: true,
        modalComponent: action.component,
        modalTitle: action.title,
      };
    case CLOSE_MODAL:
      return {
        ...state,
        modal: false,
      };
    case LOAD:
      return {
        ...state,
        loading: true,
      };
    case END_LOAD:
      return {
        ...state,
        loading: false,
      };
    default: return state;
  }
};

export default utilsReducer;
