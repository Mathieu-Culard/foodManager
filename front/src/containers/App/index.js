import { connect } from 'react-redux';
import App from 'src/components/App';

import { fetchPublicRecipes } from 'src/actions/recipes';
import { fetchIngredients } from 'src/actions/ingredients';
import { clearUserInfo } from 'src/actions/user';

const mapStateToProps = (state) => ({
  isLogged: state.connection.isLogged,
});

const mapDispatchToProps = (dispatch) => ({
  fetchPublicRecipes: () => dispatch(fetchPublicRecipes()),
  fetchIngredients: () => dispatch(fetchIngredients()),
  clearUserInfo: () => dispatch(clearUserInfo()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(App);
