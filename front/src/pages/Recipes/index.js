import React from 'react';
import PropTypes from 'prop-types';
import RecipeCard from 'src/components/RecipeCard';

const Recipes = ({ recipes }) => (
  <div>
    {recipes.map((recipe) => (
      <RecipeCard {...recipe} />
    ))}
  </div>
);

Recipes.propTypes = {
  recipes: PropTypes.array.isRequired,
};
export default Recipes;
