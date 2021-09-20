import React from 'react';
import PropTypes from 'prop-types';

import './header.scss';

const Header = ({ openModal, isLogged, logOut }) => (
  <header className="header">
    {!isLogged && (
      <>
        <a className="header_link" href="#" onClick={() => openModal('Inscription', 'RegisterForm')}>
          Inscription
        </a>
        <a className="header_link" href="#" onClick={() => openModal('Connection', 'ConnectionForm')}>
          Connexion
        </a>
      </>
    )}
    {isLogged && (
      <a className="header_link" href="#" onClick={() => logOut()}>
        deconnexion
      </a>
    )}
  </header>
);

Header.propTypes = {
  openModal: PropTypes.func.isRequired,
  isLogged: PropTypes.bool.isRequired,
  logOut: PropTypes.func.isRequired,
};

export default Header;
