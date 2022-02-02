import React from 'react';
import PropTypes from 'prop-types';
import './registerForm.scss';

const RegisterForm = ({
  username, password, changefield, submitRegistration, email, passwordConf,
}) => {
  const handleSubmit = (e) => {
    e.preventDefault();
    submitRegistration();
  };

  return (
    <form className="register-form" onSubmit={handleSubmit}>
      <h2 className="register-form__title">Inscription</h2>
      <input
        className="register-form__item"
        type="text"
        id="username"
        value={username}
        name="username"
        placeholder="Nom d'utilisateur"
        onChange={(e) => changefield('username', e.target.value)}
      />
      <input
        className="register-form__item"
        type="email"
        id="email"
        value={email}
        name="email"
        placeholder="Adresse e-mail"
        onChange={(e) => changefield('email', e.target.value)}
      />
      <input
        className="register-form__item"
        type="password"
        id="password"
        placeholder="Mot de passe"
        value={password}
        name="password"
        onChange={(e) => changefield('password', e.target.value)}
      />
      <input
        className="register-form__item"
        type="password"
        id="passwordConf"
        placeholder="Confirmez le mot de passe"
        value={passwordConf}
        name="password"
        onChange={(e) => changefield('passwordConf', e.target.value)}
      />
      <input
        type="submit"
        id="submit"
        value="s'inscrire"
        className="register-form__submit"
      />
    </form>
  );
};

RegisterForm.propTypes = {
  username: PropTypes.string.isRequired,
  password: PropTypes.string.isRequired,
  changefield: PropTypes.func.isRequired,
  submitRegistration: PropTypes.func.isRequired,
  email: PropTypes.string.isRequired,
  passwordConf: PropTypes.string.isRequired,
};

export default RegisterForm;
