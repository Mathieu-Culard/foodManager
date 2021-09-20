import { connect } from 'react-redux';
import RegisterForm from 'src/components/RegisterForm';

import { changefield, submitRegistration } from 'src/actions/connection';

const mapStateToProps = (state) => ({
  username: state.connection.username,
  password: state.connection.password,
  email: state.connection.email,
  passwordConf: state.connection.passwordConf,
});

const mapDispatchToProps = (dispatch) => ({
  changefield: (identifier, newValue) => dispatch(changefield(identifier, newValue)),
  submitRegistration: () => dispatch(submitRegistration()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(RegisterForm);
