import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';

const RecipeCard = ({ name, id }) => (
  <Link to={`/recipe/${id}`}>
    <div>
      <p>{name}</p>
    </div>
  </Link>
);

RecipeCard.propTypes = {
  name: PropTypes.string.isRequired,
  id: PropTypes.string.isRequired,
};

export default RecipeCard;
