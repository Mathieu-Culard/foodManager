import React, { useEffect } from 'react';
import PropTypes from 'prop-types';
import { useParams } from 'react-router-dom';
import './recipePage.scss';
import IngredientCard from 'src/components/IngredientRecipeCard';

const RecipePage = ({ fetchRecipe, recipe, isLoading }) => {
  const { id } = useParams();
  useEffect(() => {
    fetchRecipe(id, false);
  }, []);

  return (
    <div className="recipe-page">
      {isLoading && (
        <p>charge</p>)}
      {!isLoading && (
        <>
          <div className="recipe-page__head">
            <div className="recipe-page__head__container">
              <h1 className="recipe-page__head__container__title">{recipe.infos.name}</h1>
              <img src={`http://localhost:8000/assets/recipes/${recipe.infos.image}`} className="recipe-page__head__container__image" alt={`${recipe.infos.name}`} />
            </div>
            <div className="recipe-page__head__ingredients">
              <h2 className="recipe-page__head__ingredients__title">Liste des ingrédients</h2>
              <div className="recipe-page__head__ingredients__container">
                {
                  recipe.ingredients.map((ingredient) => (
                    <IngredientCard key={`ingredient ${ingredient.id}`} {...ingredient} />
                  ))
                }
              </div>
            </div>
          </div>
          <div className="recipe-page__body">
            <div className="recipe-page__body__steps">
              <h2 className="recipe-page__body__steps__title">Préparation</h2>
              {
                recipe.steps.map((step, i) => (
                  <div key={`step ${step.id}`}>
                    <h3 className="recipe-page__body__steps__counters" >{`Etape ${i + 1}`}</h3>
                    <p className="recipe-page__body__steps__text">{step.text}</p>
                  </div>
                ))
              }
            </div>
            <div className="recipe-page__body__owner-card">
              <h2 className="recipe-page__body__owner-card__title">Proposé par</h2>
              <a href="#" className="recipe-page__body__owner-card__card">
                <img className="recipe-page__body__owner-card__card__avatar" src={`http://localhost:8000/assets/avatars/${recipe.owner.avatar}`} alt="avatar" />
                <p className="recipe-page__body__owner-card__card__username">{recipe.owner.username}</p>
              </a>
            </div>
          </div>
        </>
      )}
    </div>
  );
};

RecipePage.propTypes = {
  fetchRecipe: PropTypes.func.isRequired,
};
export default RecipePage;
