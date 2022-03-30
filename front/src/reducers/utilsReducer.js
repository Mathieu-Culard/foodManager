import {
  OPEN_MODAL,
  CLOSE_MODAL,
  OPEN_SNACKBAR,
  CLOSE_SNACKBAR,
  OPEN_PANEL,
  LOAD,
  END_LOAD,
  CHANGE_ERROR_MESSAGE,
} from 'src/actions/utils';
import {
  REFRESH_TOKEN,
} from 'src/actions/connection';

const initialState = {
  isisLoading: true,

  modalComponent: '',
  modalTitle: '',
  modalUse: '',
  modal: false,
  snackbar: false,
  snackbarMessage: '',
  snackbarSeverity: '',
  stockPanel: false,
  shopPanel: false,
  errorMessage: '',
  errorCode: '',
};

const utilsReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case CHANGE_ERROR_MESSAGE:
      return {
        ...state,
        errorMessage: action.message,
        errorCode: action.code,
      };
    case REFRESH_TOKEN:
      return {
        ...state,
        isLoading: true,
      };
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
        modalUse: action.use,
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
        isLoading: true,
      };
    case END_LOAD:
      return {
        ...state,
        isLoading: false,
      };
    default: return state;
  }
};

export default utilsReducer;
