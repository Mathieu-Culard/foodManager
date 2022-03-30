import { connect } from 'react-redux';
import AddIngredientPanel from 'src/components/AddIngredientPanel';
import { addToStock, changeValue, addToRecipe, changeTrack } from 'src/actions/ingredients';

const mapStateToProps = (state) => ({
  ingredients: state.ingredients.ingredientsList,
  addedValues: state.ingredients.addStock,
  modalUse: state.utils.modalUse,
  recipeIngredients: state.recipe.ingredients,
});

const mapDispatchToProps = (dispatch) => ({
  addToStock: (identifier) => dispatch(addToStock(identifier)),
  changeValue: (id, quantity) => dispatch(changeValue(id, quantity)),
  addToRecipe: () => dispatch(addToRecipe()),
  changeTrack: (id) => dispatch(changeTrack(id)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(AddIngredientPanel);
