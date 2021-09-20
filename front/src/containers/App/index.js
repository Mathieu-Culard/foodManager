import { connect } from 'react-redux';
import App from 'src/components/App';

import { checkToken } from 'src/actions/connection';

const mapStateToProps = (state) => ({
});

const mapDispatchToProps = (dispatch) => ({
  checkToken: () => dispatch(checkToken()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(App);
