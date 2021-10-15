import { connect } from 'react-redux';
import RecipePage from 'src/pages/RecipePage';
import { fetchRecipe } from 'src/actions/recipes';

const mapStateToProps = (state) => ({
  recipe: state.recipe.currentRecipe,
  isLoading: state.utils.loading,
});

const mapDispatchToProps = (dispatch) => ({
  fetchRecipe: (id, isEdit) => dispatch(fetchRecipe(id, isEdit)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(RecipePage);
