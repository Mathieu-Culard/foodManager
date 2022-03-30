import React from 'react';
import PropTypes from 'prop-types';
import { Link, useLocation } from 'react-router-dom';

import './header.scss';

const Header = ({ openModal, isLogged, logOut }) => {
  const location = useLocation();
  let homeClass = 'header__nav__item';
  let myRecipesClass = 'header__nav__item';
  switch (location.pathname) {
    case '/':
      homeClass = 'header__nav__item active';
      break;
    default:
      myRecipesClass = 'header__nav__item active';
  }
  return (
    <header className="header">
      {isLogged && (
        <nav className="header__nav">
          <Link to="/" className={homeClass}>
            Accueil
          </Link>
          <Link to="/my-recipes" className={myRecipesClass}>
            Mes recettes
          </Link>
          <a className="header__nav__item" href="#" onClick={() => logOut()}>
            Deconnexion
          </a>
        </nav>
      )}
      {!isLogged && location.pathname !== '/' && (
        <nav className="header__nav">
          <a className="header__nav__item" href="#" onClick={() => openModal('Inscription', 'RegisterForm')}>
            Inscription
          </a>
          <a className="header__nav__item" href="#" onClick={() => openModal('Connexion', 'ConnectionForm')}>
            Se connecter
          </a>
        </nav>
      )}
      <Link to="/">
        <h1 className={`header__title ${location.pathname === '/' ? 'low' : 'high'}`}>food manager</h1>
      </Link>
      {location.pathname === '/' && (
        <p className="header__desc">Le site de recette <br /> qui crée votre liste de courses</p>
      )}
      {!isLogged && location.pathname === '/' && (
        <div className="header__start">
          <button type="button" className="header__start__register" href="#" onClick={() => openModal('Inscription', 'RegisterForm')}>
            Inscription
          </button>
          <div className="header__start__login">
            <p className="header__start__login__text">vous avez deja un compte ?</p>
            <a className="header__start__login__link" href="#" onClick={() => openModal('Connexion', 'ConnectionForm')}>
              Se connecter
            </a>
          </div>
        </div>
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

// <h1 className="header__title">FOOD MANAGER</h1>
//       {!isLogged && (
//         <div className="header__links">
//           <a className="header_link" href="#" onClick={() => openModal('Inscription', 'RegisterForm')}>
//             Inscription
//           </a>
//           <a className="header_link" href="#" onClick={() => openModal('Connexion', 'ConnectionForm')}>
//             Connexion
//           </a>
//         </div>
//       )}
//       {isLogged && (
//         <>
//           {/* {location.pathname === '/my-recipes'
//             && ( */}
//           <Link to="/" className="header_link">
//             Recettes publiques
//           </Link>
//           {/* )}
//           { */}
//           {/* location.pathname === '/'
//             && ( */}
//           <Link to="/my-recipes" className="header_link">
//             Mes recettes
//           </Link>
//           {/* )
//           } */}
//           <a className="header_link" href="#" onClick={() => logOut()}>
//             Deconnexion
//           </a>
//         </>
//       )}
