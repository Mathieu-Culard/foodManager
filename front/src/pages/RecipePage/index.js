import React, { useEffect } from 'react';
import PropTypes from 'prop-types';
import { useParams } from 'react-router-dom';
import './recipePage.scss';
import IngredientCard from 'src/components/IngredientRecipeCard';
import Loader from 'src/components/Loader';

const RecipePage = ({ fetchRecipe, recipe, isLoading, cook, stock }) => {
  const { id } = useParams();
  useEffect(() => {
    fetchRecipe(id, false);
  }, [stock]);

  return (
    <main className="recipe-page">
      {isLoading && (
        <Loader />)}
      {!isLoading && (
        <>
          <img src={`http://localhost:8000/assets/recipes/${recipe.infos.image}`} className="recipe-page__image" alt={`${recipe.infos.name}`} />
          <div className="recipe-page__infos">
            <h1 className="recipe-page__infos__title">{recipe.infos.name}</h1>
            <div className="recipe-page__infos__owner">
              <img className="recipe-page__infos__owner__avatar" src={`http://localhost:8000/assets/avatars/${recipe.owner.avatar}`} alt="avatar" />
              <p className="recipe-page__infos__owner__username">Proposée par <span>{recipe.owner.username}</span></p>
            </div>
          </div>
          <div className="recipe-page__recipe">
            <div className="recipe-page__recipe__ingredients">
              <h2 className="recipe-page__recipe__ingredients__title">Liste des ingredients</h2>
              {
                recipe.ingredients.map((ingredient) => (
                  <IngredientCard key={`ingredient ${ingredient.id}`} {...ingredient} />
                ))
              }
            </div>
            <div className="recipe-page__recipe__steps">
              <h2 className="recipe-page__recipe__steps__title">Préparation</h2>
              {
                recipe.steps.map((step, i) => (
                  <div key={`step ${step.id}`}>
                    <h3 className="recipe-page__recipe__steps__counters">{`Etape ${i + 1}`}</h3>
                    <p className="recipe-page__recipe__steps__text">{step.text}</p>
                  </div>
                ))
              }
            </div>
            <div className="recipe-page__recipe__validate">
              {recipe.infos.isDoable && (
                <button type="button" className="recipe-page__recipe__validate__button" onClick={() => cook(recipe.infos.id)}>Bon appétit</button>
              )}
            </div>
          </div>
        </>
      )}
    </main>
  );
};

RecipePage.propTypes = {
  fetchRecipe: PropTypes.func.isRequired,
};
export default RecipePage;

{/* 
        <>
          <div className="recipe-page__head">
            <div className="recipe-page__head__container">
              <h1 className="recipe-page__head__container__title"></h1>

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
                    <h3 className="recipe-page__body__steps__counters">{`Etape ${i + 1}`}</h3>
                    <p className="recipe-page__body__steps__text">{step.text}</p>
                  </div>
                ))
              }
            </div>
            <div className="recipe-page__body__infos">
              {recipe.infos.isDoable && (
                <div className="recipe-page__body__infos__validate">
                  <button type="button" className="recipe-page__body__validate_button" onClick={() => cook(recipe.infos.id)}>Bon App'</button>
                </div>
              )}
              <div className="recipe-page__body__infos__owner-card">
                <h2 className="recipe-page__body__infos__owner-card__title">Proposé par</h2>
                <a href="#" className="recipe-page__body__infos__owner-card__card">
                  <img className="recipe-page__body__infos__owner-card__card__avatar" src={`http://localhost:8000/assets/avatars/${recipe.owner.avatar}`} alt="avatar" />
                  <p className="recipe-page__body__infos__owner-card__card__username">{recipe.owner.username}</p>
                </a>
              </div>
            </div>
          </div>
        </> */}
