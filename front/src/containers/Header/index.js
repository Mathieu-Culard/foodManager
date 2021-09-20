import { connect } from 'react-redux';
import Header from 'src/components/Header';

import { openModal } from 'src/actions/utils';
import { logOut } from 'src/actions/connection';

const mapStateToProps = (state) => ({
  isLogged: state.connection.isLogged,
});

const mapDispatchToProps = (dispatch) => ({
  openModal: (title, component) => dispatch(openModal(title, component)),
  logOut: () => dispatch(logOut()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Header);
