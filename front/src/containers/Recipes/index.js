import { connect } from 'react-redux';
import Recipes from 'src/pages/Recipes';

const mapStateToProps = (state) => ({
  recipes: state.recipe.recipesList,
  isLoading: state.recipe.isLoading,
});

const mapDispatchToProps = (dispatch) => ({
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Recipes);
