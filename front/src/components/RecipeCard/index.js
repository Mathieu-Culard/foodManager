import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';
import './recipeCard.scss';

const RecipeCard = ({ name, id, image, location, deleteRecipe }) => (

  <div className="recipe-card">
    <div className="recipe-card__content">
      <Link to={`/recipe/${id}`}>
        <img src={`http://localhost:8000/assets/recipes/${image}`} alt="recipe" className="recipe-card__content__image" />
      </Link>
      {location === '/my-recipes'
        && (
          <div className="recipe-card__content__buttons">
            <button type="button">b</button>
            <Link to={`/my-recipes/edit-recipe/${id}`}>
              <button type="button">u</button>
            </Link>
            <button type="button" onClick={() => deleteRecipe(id)}>d</button>
          </div>
        )}
    </div>
    <h3 className="recipe-card__title">{name}</h3>
  </div>

);

RecipeCard.propTypes = {
  name: PropTypes.string.isRequired,
  id: PropTypes.string.isRequired,
  image: PropTypes.string.isRequired,
  location: PropTypes.string.isRequired,
};

export default RecipeCard;
