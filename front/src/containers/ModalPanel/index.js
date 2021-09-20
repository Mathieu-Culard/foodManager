import { connect } from 'react-redux';
import ModalPanel from 'src/components/ModalPanel';

import { closeModal } from 'src/actions/utils';

const mapStateToProps = (state) => ({
  isOpen: state.utils.modal,
  componentName: state.utils.modalComponent,
  modalTitle: state.utils.modalTitle,
});

const mapDispatchToProps = (dispatch) => ({
  closeModal: () => dispatch(closeModal()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ModalPanel);
