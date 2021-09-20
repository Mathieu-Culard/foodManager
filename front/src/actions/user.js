export const SAVE_USER_INFO = 'SAVE_USER_INFO';
export const CLEAR_USER_INFO = 'CLEAR_USER_INFO';

export const clearUserInfo = () => ({
  type: CLEAR_USER_INFO,
});

export const saveUserInfo = (data) => ({
  type: SAVE_USER_INFO,
  data,
});
