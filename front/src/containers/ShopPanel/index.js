import { connect } from 'react-redux';
import ShopPanel from 'src/components/ShopPanel';

import { openPanel, openModal } from 'src/actions/utils';
import { validateShoppingList } from 'src/actions/ingredients';

const mapStateToProps = (state) => ({
  isOpen: state.utils.shopPanel,
  shoppingList: state.user.shop,
});

const mapDispatchToProps = (dispatch) => ({
  open: (identifier) => dispatch(openPanel(identifier)),
  openModal: (title, component, use) => dispatch(openModal(title, component, use)),
  validate: () => dispatch(validateShoppingList()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ShopPanel);
