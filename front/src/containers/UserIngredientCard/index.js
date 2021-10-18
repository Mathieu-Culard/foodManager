import { connect } from 'react-redux';
import UserIngredientCard from 'src/components/UserIngredientCard';

import { changeStockQuantity, deleteIngredient, deleteRecipeIngredient, changeRecipeIngredientQuantity } from 'src/actions/ingredients';

const mapStateToProps = (state) => ({

});

const mapDispatchToProps = (dispatch) => ({
  changeQuantity: (id, newValue, identifier) => dispatch(changeStockQuantity(id, newValue, identifier)),
  deleteIngredient: (id, identifier) => dispatch(deleteIngredient(id, identifier)),
  deleteRecipeIngredient: (id) => dispatch(deleteRecipeIngredient(id)),
  changeRecipeIngredientQuantity:
    (id, newValue) => dispatch(changeRecipeIngredientQuantity(id, newValue)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserIngredientCard);
