import React from 'react';
import PropTypes from 'prop-types';
import RecipeCard from 'src/components/RecipeCard';
import { Link } from 'react-router-dom';

const HomePage = ({ recipes }) => (
  <div>
    {recipes.map((recipe) => (
      <RecipeCard {...recipe} key={recipe.id} />
    ))}
  </div>
);

HomePage.propTypes = {
  recipes: PropTypes.array.isRequired,
};
export default HomePage;
