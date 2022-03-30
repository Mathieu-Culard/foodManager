import { connect } from 'react-redux';
import RecipesListPage from 'src/pages/RecipesListPage';
import { fetchMyRecipes, fetchPublicRecipes, deleteRecipe, buyRecipe, loadRecipe } from 'src/actions/recipes';

const mapStateToProps = (state) => ({
  isLogged: state.connection.isLogged,
  recipes: state.recipe.recipesList,
  userRecipes: state.user.recipes,
});

const mapDispatchToProps = (dispatch) => ({
  fetchMyRecipes: () => dispatch(fetchMyRecipes()),
  fetchPublicRecipes: () => dispatch(fetchPublicRecipes()),
  deleteRecipe: (id) => dispatch(deleteRecipe(id)),
  buyRecipe: (id) => dispatch(buyRecipe(id)),
  loadRecipe: () => dispatch(loadRecipe()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(RecipesListPage);
