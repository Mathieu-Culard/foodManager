import { connect } from 'react-redux';
import RecipesListPage from 'src/pages/RecipesListPage';
import { fetchMyRecipes, fetchPublicRecipes, deleteRecipe } from 'src/actions/recipes';

const mapStateToProps = (state) => ({
  isLogged: state.connection.isLogged,
  recipes: state.recipe.recipesList,
  isLoading: state.recipe.isLoading,
  userRecipes: state.user.recipes,
});

const mapDispatchToProps = (dispatch) => ({
  fetchMyRecipes: () => dispatch(fetchMyRecipes()),
  fetchPublicRecipes: () => dispatch(fetchPublicRecipes()),
  deleteRecipe: (id) => dispatch(deleteRecipe(id)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(RecipesListPage);
