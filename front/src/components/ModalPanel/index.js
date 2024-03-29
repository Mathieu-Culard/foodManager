import React from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';

import RegisterForm from 'src/containers/RegisterForm';
import ConnectionForm from 'src/containers/ConnectionForm';
import AddIngredientPanel from 'src/containers/AddIngredientPanel';
import './ModalPanel.scss';

const ModalPanel = ({
  isOpen, closeModal, componentName, modalTitle,
}) => (
  isOpen ? ReactDOM.createPortal(
    <>
      <div className="modal-overlay">
        <div className="modal-wrapper">
          <div className="modal">
            <div className="modal-header">

              <button
                type="button"
                className="modal-close-button"
                onClick={closeModal}
              >
                <span>&times;</span>
              </button>
            </div>
            <div className="modal-body">
              {/* <h4>{modalTitle}</h4> */}
              {
                  {
                    RegisterForm: <RegisterForm />,
                    ConnectionForm: <ConnectionForm />,
                    AddIngredientPanel: <AddIngredientPanel />,
                  }[componentName]
                }
            </div>
          </div>
        </div>
      </div>
    </>,
    document.body,
  )
    : null);

ModalPanel.propTypes = {
  closeModal: PropTypes.func.isRequired,
  isOpen: PropTypes.bool.isRequired,
  componentName: PropTypes.string.isRequired,
  modalTitle: PropTypes.string.isRequired,
};

export default ModalPanel;
