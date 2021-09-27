import { connect } from 'react-redux';
import App from 'src/components/App';

import { fetchPublicRecipes } from 'src/actions/recipes';

const mapStateToProps = (state) => ({
});

const mapDispatchToProps = (dispatch) => ({
  fetchPublicRecipes: () => dispatch(fetchPublicRecipes()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(App);
