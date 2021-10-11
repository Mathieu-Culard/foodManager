import { connect } from 'react-redux';
import AddIngredientPanel from 'src/components/AddIngredientPanel';
import { addToStock, changeValue } from 'src/actions/ingredients';

const mapStateToProps = (state) => ({
  ingredients: state.ingredients.ingredientsList,
  addedValues: state.ingredients.addStock,
});

const mapDispatchToProps = (dispatch) => ({
  addToStock: () => dispatch(addToStock()),
  changeValue: (id, quantity) => dispatch(changeValue(id, quantity)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(AddIngredientPanel);
