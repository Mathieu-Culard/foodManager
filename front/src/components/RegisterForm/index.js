import React from 'react';
import PropTypes from 'prop-types';

const RegisterForm = ({
  username, password, changefield, submitRegistration, email, passwordConf,
}) => {
  const handleSubmit = (e) => {
    e.preventDefault();
    submitRegistration();
  };

  return (
    <form className="register-form" onSubmit={handleSubmit}>
      <input
        type="text"
        id="username"
        value={username}
        name="username"
        placeholder="username"
        onChange={(e) => changefield('username', e.target.value)}
      />
      <input
        type="email"
        id="username"
        value={email}
        name="email"
        placeholder="adresse email"
        onChange={(e) => changefield('email', e.target.value)}
      />
      <input
        type="password"
        id="password"
        placeholder="mot de passe"
        value={password}
        name="password"
        onChange={(e) => changefield('password', e.target.value)}
      />
      <input
        type="password"
        id="passwordConf"
        placeholder="Confiermez le mot de passe"
        value={passwordConf}
        name="password"
        onChange={(e) => changefield('passwordConf', e.target.value)}
      />
      <input type="submit" id="submit" value="s'inscrire" />
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
