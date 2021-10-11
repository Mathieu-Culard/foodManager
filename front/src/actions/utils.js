export const OPEN_MODAL = 'OPEN_MODAL';
export const CLOSE_MODAL = 'CLOSE_MODAL';
export const OPEN_SNACKBAR = 'OPEN_SNACKBAR';
export const CLOSE_SNACKBAR = 'CLOSE_SNACKBAR';
export const LOAD = 'LOAD';
export const END_LOAD = 'END_LOAD';
export const OPEN_PANEL = 'OPEN_PANEL';

export const endLoad = () => ({
  type: END_LOAD,
});

export const load = () => ({
  type: LOAD,
});

export const openSnackbar = (message, severity) => ({
  type: OPEN_SNACKBAR,
  message,
  severity,
});

export const closeSnackbar = () => ({
  type: CLOSE_SNACKBAR,
});

export const openModal = (title, component) => ({
  type: OPEN_MODAL,
  component,
  title,
});

export const closeModal = () => ({
  type: CLOSE_MODAL,
});

export const openPanel = (identifier) => ({
  type: OPEN_PANEL,
  identifier,
});
