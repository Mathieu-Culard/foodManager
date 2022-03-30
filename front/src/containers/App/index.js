import { connect } from 'react-redux';
import App from 'src/components/App';

import { fetchPublicRecipes } from 'src/actions/recipes';
import { fetchIngredients } from 'src/actions/ingredients';
import { clearUserInfo } from 'src/actions/user';
import { refreshToken } from 'src/actions/connection';

const mapStateToProps = (state) => ({
  isLogged: state.connection.isLogged,
  isLoading: state.utils.isLoading,
});

const mapDispatchToProps = (dispatch) => ({
  fetchPublicRecipes: () => dispatch(fetchPublicRecipes()),
  fetchIngredients: () => dispatch(fetchIngredients()),
  clearUserInfo: () => dispatch(clearUserInfo()),
  refreshToken: () => dispatch(refreshToken()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(App);
