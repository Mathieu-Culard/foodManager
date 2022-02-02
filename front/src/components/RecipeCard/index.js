import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';
import './recipeCard.scss';
import { BiEdit } from 'react-icons/bi';

const RecipeCard = ({
  name, id, image, location, deleteRecipe, buyRecipe, isDoable, isLogged,
}) => {
  let cssClass = 'unavailable';
  if (isDoable) {
    cssClass = 'available';
  }
  else if (typeof isDoable === 'undefined') {
    cssClass = '';
  }

  return (
    <div className="recipe-card">
      <div className="recipe-card__content">
        <Link to={`/recipe/${id}`}>
          <img src={`http://localhost:8000/assets/recipes/${image}`} alt="recipe" className="recipe-card__content__image" />
          <h3 className="recipe-card__content__title">{name}</h3>
          {isLogged && isDoable && (
            <p className="recipe-card__content__ready">Prêt à cuisiner</p>
          )}
        </Link>
        {isLogged && <button className="recipe-card__content__buy" type="button" onClick={() => buyRecipe(id)}>Ajouter à la liste</button>}
        <div className="recipe-card__content__buttons">
          {location === '/my-recipes'
            && (
              <>
                <Link className="recipe-card__content__buttons__edit" to={`/my-recipes/edit-recipe/${id}`}>
                  <BiEdit />
                </Link>
                <button className="recipe-card__content__buttons__delete" type="button" onClick={() => deleteRecipe(id)}>&times;</button>
              </>
            )}
        </div>
      </div>
    </div>

  );
};

RecipeCard.propTypes = {
  name: PropTypes.string.isRequired,
  id: PropTypes.string.isRequired,
  image: PropTypes.string.isRequired,
  location: PropTypes.string.isRequired,
};

export default RecipeCard;
