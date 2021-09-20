import { connect } from 'react-redux';

import ConnectionForm from 'src/components/ConnectionForm';
import { changefield, submitConnection } from 'src/actions/connection';

const mapStateToProps = (state) => ({
  username: state.connection.username,
  password: state.connection.password,
});

const mapDispatchToProps = (dispatch) => ({
  changefield: (identifier, newValue) => dispatch(changefield(identifier, newValue)),
  submitConnection: () => dispatch(submitConnection()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ConnectionForm);
