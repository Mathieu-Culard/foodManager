import React from 'react';
import PropTypes from 'prop-types';

const ConnectionForm = ({
  username, password, changefield, submitConnection,
}) => {
  const handleSubmit = (e) => {
    e.preventDefault();
    submitConnection();
  };

  return (
    <form className="connection-form" onSubmit={handleSubmit}>
      <input
        type="text"
        id="username"
        value={username}
        name="username"
        placeholder="username"
        onChange={(e) => changefield('username', e.target.value)}
      />
      <input
        type="password"
        id="password"
        placeholder="mot de passe"
        value={password}
        name="password"
        onChange={(e) => changefield('password', e.target.value)}
      />
      <input type="submit" id="submit" value="Connexion" />
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
