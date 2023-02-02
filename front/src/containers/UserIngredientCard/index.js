import { connect } from 'react-redux';
import UserIngredientCard from 'src/components/UserIngredientCard';

import { changeStockQuantity, deleteIngredient, deleteRecipeIngredient, changeRecipeIngredientQuantity, validateQuantity } from 'src/actions/ingredients';

const mapStateToProps = (state) => ({

});

const mapDispatchToProps = (dispatch) => ({
  validateQuantity: (id, value, identifier) => dispatch(validateQuantity(id, value, identifier)),
  changeQuantity: (id, newValue, identifier) => dispatch(changeStockQuantity(id,
    newValue,
    identifier)),
  deleteIngredient: (id, identifier) => dispatch(deleteIngredient(id, identifier)),
  deleteRecipeIngredient: (id) => dispatch(deleteRecipeIngredient(id)),
  changeRecipeIngredientQuantity:
    (id, newValue) => dispatch(changeRecipeIngredientQuantity(id, newValue)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserIngredientCard);
