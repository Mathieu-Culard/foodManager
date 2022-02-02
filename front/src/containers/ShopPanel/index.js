import { connect } from 'react-redux';
import ShopPanel from 'src/components/ShopPanel';

import { openPanel, openModal } from 'src/actions/utils';
import { validateShoppingList } from 'src/actions/ingredients';
import { buyRecipe, buyLessRecipe, deleteRecipeToBuy } from 'src/actions/recipes';

const mapStateToProps = (state) => ({
  isOpen: state.utils.shopPanel,
  shoppingList: state.user.shop,
  recipes: state.user.recipesShop,
});

const mapDispatchToProps = (dispatch) => ({
  open: (identifier) => dispatch(openPanel(identifier)),
  openModal: (title, component, use) => dispatch(openModal(title, component, use)),
  validate: () => dispatch(validateShoppingList()),
  buyRecipe: (id) => dispatch(buyRecipe(id)),
  buyLess: (id) => dispatch(buyLessRecipe(id)),
  deleteRecipe: (id) => dispatch(deleteRecipeToBuy(id)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ShopPanel);
