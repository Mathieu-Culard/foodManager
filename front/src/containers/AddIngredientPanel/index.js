import { connect } from 'react-redux';
import AddIngredientPanel from 'src/components/AddIngredientPanel';
import { addToStock, changeValue, addToRecipe } from 'src/actions/ingredients';

const mapStateToProps = (state) => ({
  ingredients: state.ingredients.ingredientsList,
  addedValues: state.ingredients.addStock,
  modalUse: state.utils.modalUse,
});

const mapDispatchToProps = (dispatch) => ({
  addToStock: (identifier) => dispatch(addToStock(identifier)),
  changeValue: (id, quantity) => dispatch(changeValue(id, quantity)),
  addToRecipe: () => dispatch(addToRecipe()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(AddIngredientPanel);
