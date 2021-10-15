import React from 'react';
import PropTypes from 'prop-types';
import { Link, useLocation } from 'react-router-dom';

import './header.scss';

const Header = ({ openModal, isLogged, logOut }) => {
  const location = useLocation();

  return (
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
        <>
          {/* {location.pathname === '/my-recipes'
            && ( */}
          <Link to="/" className="header_link">
            Recettes publiques
          </Link>
          {/* )}
          { */}
          {/* location.pathname === '/'
            && ( */}
          <Link to="/my-recipes" className="header_link">
            Mes recettes
          </Link>
          {/* )
          } */}
          <a className="header_link" href="#" onClick={() => logOut()}>
            Deconnexion
          </a>
        </>
      )}
    </header>
  );
};

Header.propTypes = {
  openModal: PropTypes.func.isRequired,
  isLogged: PropTypes.bool.isRequired,
  logOut: PropTypes.func.isRequired,
};

export default Header;
