import React, { useEffect } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import './snackbar.scss';

const Snackbar = ({
  isOpen,
  severity,
  message,
  close,
}) => {
  useEffect(() => {
    setTimeout(() => {
      close();
    }, 5000);
  });

  return (
    isOpen ? ReactDOM.createPortal(
      <div className="snackbar">
        <p className={`snackbar_message ${severity}`}>
          {message}
        </p>
      </div>,
      document.body,
    )
      : null
  );
};

Snackbar.propTypes = {
  isOpen: PropTypes.bool.isRequired,
  severity: PropTypes.string.isRequired,
  message: PropTypes.string.isRequired,
  close: PropTypes.func.isRequired,
};

export default Snackbar;
