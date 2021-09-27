import React from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import './ingredientRecipeCard.scss';

const IngredientRecipeCard = ({ name, image, quantity, unity }) => (
  <div className="ingredient-card">
    <img src={`http://localhost:8000/assets/ingredients/${image}`} alt={name} className="ingredient-card__image" />
    <div className="ingredient-card__infos">
      <p className="ingredient-card__infos__name">{name}</p>
      <p className="ingredient-card__infos__quantity">{`${quantity} ${unity}`}</p>
    </div>
  </div>
);

export default IngredientRecipeCard;
