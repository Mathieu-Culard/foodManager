import { connect } from 'react-redux';

import ErrorPage from 'src/pages/ErrorPage';

const mapStateToProps = (state) => ({
  errorMessage: state.utils.errorMessage,
  errorCode: state.utils.errorCode,
});

const mapDispatchToProps = (dispatch) => ({

});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ErrorPage);
