import React from 'react';
import PropTypes from 'prop-types';

import './header.scss';

const Header = ({ openModal, isLogged, logOut }) => (
  <header className="header">
    <h1 className="header__title">FOOD MANAGER</h1>
    {!isLogged && (
      <div className="header__links">
        <a className="header_link" href="#" onClick={() => openModal('Inscription', 'RegisterForm')}>
          Inscription
        </a>
        <a className="header_link" href="#" onClick={() => openModal('Connexion', 'ConnectionForm')}>
          Connexion
        </a>
      </div>
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
