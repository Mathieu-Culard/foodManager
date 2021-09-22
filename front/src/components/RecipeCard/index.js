import React from 'react';
import PropTypes from 'prop-types';

const RecipeCard = ({ name }) => (
  <div>
    <p>{name}</p>
  </div>
);

RecipeCard.propTypes = {
  name: PropTypes.string.isRequired,
};

export default RecipeCard;
