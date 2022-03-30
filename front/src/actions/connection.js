export const CHANGE_FIELD = 'CHANGE_FIELD';
export const SUBMIT_REGISTRATION = 'SUBMIT_REGISTRATION';
export const SUBMIT_CONNECTION = 'SUBMIT_CONNECTION';
export const CLEAR_FORM = 'CLEAR_FORM';
export const CHECK_TOKEN = 'CHECK_TOKEN';
export const LOG_IN = 'LOG_IN';
export const LOG_OUT = 'LOG_OUT';
export const REFRESH_TOKEN = 'REFRESH_TOKEN';

export const logOut = () => ({
  type: LOG_OUT,
});

export const logIn = () => ({
  type: LOG_IN,
});

export const checkToken = () => ({
  type: CHECK_TOKEN,
});

export const changefield = (identifier, newValue) => ({
  type: CHANGE_FIELD,
  identifier,
  newValue,
});

export const submitRegistration = () => ({
  type: SUBMIT_REGISTRATION,
});

export const submitConnection = () => ({
  type: SUBMIT_CONNECTION,
});

export const clearForm = () => ({
  type: CLEAR_FORM,
});

export const refreshToken = () => ({
  type: REFRESH_TOKEN,
});
