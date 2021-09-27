import { connect } from 'react-redux';
import HomePage from 'src/pages/HomePage';

const mapStateToProps = (state) => ({
  recipes: state.recipe.recipesList,
  isLoading: state.recipe.isLoading,
});

const mapDispatchToProps = (dispatch) => ({
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(HomePage);
