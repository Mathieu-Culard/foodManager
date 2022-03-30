import { connect } from 'react-redux';
import RecipePage from 'src/pages/RecipePage';
import { fetchRecipe, cookRecipe } from 'src/actions/recipes';

const mapStateToProps = (state) => ({
  recipe: state.recipe.currentRecipe,
  isLoading: state.recipe.isLoading,
  stock: state.user.stock,
});

const mapDispatchToProps = (dispatch) => ({
  fetchRecipe: (id, isEdit) => dispatch(fetchRecipe(id, isEdit)),
  cook: (id) => dispatch(cookRecipe(id)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(RecipePage);
