import { connect } from 'react-redux';
import StockPanel from 'src/components/StockPanel';

import { openPanel, openModal } from 'src/actions/utils';

const mapStateToProps = (state) => ({
  isOpen: state.utils.stockPanel,
  stock: state.user.stock,
});

const mapDispatchToProps = (dispatch) => ({
  open: (identifier) => dispatch(openPanel(identifier)),
  openModal: (title, component) => dispatch(openModal(title, component)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(StockPanel);
