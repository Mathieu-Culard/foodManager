import { connect } from 'react-redux';
import Snackbar from 'src/components/Snackbar';
import { closeSnackbar } from 'src/actions/utils';

const mapStateToProps = (state) => ({
  isOpen: state.utils.snackbar,
  severity: state.utils.snackbarSeverity,
  message: state.utils.snackbarMessage,
});

const mapDispatchToProps = (dispatch) => ({
  close: () => dispatch(closeSnackbar()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Snackbar);
