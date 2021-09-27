import { connect } from 'react-redux';
import Footer from 'src/components/Footer';

import { openModal } from 'src/actions/utils';

const mapStateToProps = (state) => ({

});

const mapDispatchToProps = (dispatch) => ({
  openModal: (title, component) => dispatch(openModal(title, component)),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Footer);
