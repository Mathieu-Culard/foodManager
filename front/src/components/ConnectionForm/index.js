import React from 'react';
import PropTypes from 'prop-types';
import './connectionForm.scss';

const ConnectionForm = ({
  username, password, changefield, submitConnection,
}) => {
  const handleSubmit = (e) => {
    e.preventDefault();
    submitConnection();
  };

  return (
    <form className="connection-form" onSubmit={handleSubmit}>
      <h4 className="connection-form__title">connexion</h4>
      <input
        className="connection-form__item"
        type="text"
        id="username"
        value={username}
        name="username"
        placeholder="Nom d'utilisateur"
        onChange={(e) => changefield('username', e.target.value)}
      />
      <input
        className="connection-form__item"
        type="password"
        id="password"
        placeholder="Mot de passe"
        value={password}
        name="password"
        onChange={(e) => changefield('password', e.target.value)}
      />
      <input className="connection-form__submit" type="submit" id="submit" value="Se connecter" />
    </form>
  );
};

ConnectionForm.propTypes = {
  username: PropTypes.string.isRequired,
  password: PropTypes.string.isRequired,
  changefield: PropTypes.func.isRequired,
  submitConnection: PropTypes.func.isRequired,
};

export default ConnectionForm;
