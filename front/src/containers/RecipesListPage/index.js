import { connect } from 'react-redux';
import RecipesListPage from 'src/pages/RecipesListPage';

const mapStateToProps = (state) => ({
  recipes: state.recipe.recipesList,
  isLoading: state.recipe.isLoading,
  userRecipes: state.user.recipes,
});

const mapDispatchToProps = (dispatch) => ({
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(RecipesListPage);
