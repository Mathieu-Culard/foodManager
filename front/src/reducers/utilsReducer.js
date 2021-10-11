import {
  OPEN_MODAL,
  CLOSE_MODAL,
  OPEN_SNACKBAR,
  CLOSE_SNACKBAR,
  OPEN_PANEL,
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
  stockPanel: false,
  shoppingListPanel: false,
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
    case OPEN_PANEL:
      return {
        ...state,
        [action.identifier]: !state[action.identifier],
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
