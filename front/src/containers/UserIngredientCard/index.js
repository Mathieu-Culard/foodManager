import { connect } from 'react-redux';
import UserIngredientCard from 'src/components/UserIngredientCard';

import { changeStockQuantity, deleteIngredient } from 'src/actions/ingredients';

const mapStateToProps = (state) => ({

});

const mapDispatchToProps = (dispatch) => ({
  changeQuantity: (id, newValue) => dispatch(changeStockQuantity(id, newValue)),
  deleteIngredient: (id) => dispatch(deleteIngredient(id)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserIngredientCard);
