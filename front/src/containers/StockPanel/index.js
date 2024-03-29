import { connect } from 'react-redux';
import StockPanel from 'src/components/StockPanel';

import { openPanel, openModal } from 'src/actions/utils';

const mapStateToProps = (state) => ({
  isOpen: state.utils.stockPanel,
  stock: state.user.stock,
});

const mapDispatchToProps = (dispatch) => ({
  open: (identifier) => dispatch(openPanel(identifier)),
  openModal: (title, component, use) => dispatch(openModal(title, component, use)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(StockPanel);
