import React from 'react';
import './recipeShopCard.scss';

const RecipeShopCard = ({
  recipe, buyRecipe, buyLess, deleteRecipe,
}) => (
  <div className="recipe-shop-card">
    <div className="recipe-shop-card__head">
      <h2>{recipe.name}</h2>
      <div className="recipe-shop-card__head__quantity">
        <button type="button" onClick={() => (buyLess(recipe.id))}>-</button>
        <p>{recipe.quantity}</p>
        <button type="button" onClick={() => (buyRecipe(recipe.id))}>+</button>
      </div>
      <button className="recipe-shop-card__delete" type="button" onClick={() => (deleteRecipe(recipe.id))}>&times;</button>
    </div>
    <div className="recipe-shop-card__content">
      {recipe.ingredients.map((ingredient) => (
        <div className="recipe-shop-card__content__ingredient" key={`${recipe.id}-${ingredient.id}`}>
          <img src={`http://localhost:8000/assets/ingredients/${ingredient.image}`} alt={ingredient.name} className="recipe-shop-card__content__ingredient__image" />
          <p className="recipe-shop-card__content__ingredient__name">{ingredient.name}</p>
          <p>{`${ingredient.quantity} ${ingredient.unity ?? ''}`}</p>
        </div>
      ))}
      {
          recipe.ingredients.length === 0 && (
            <p>Vous n'avez rien à acheter pour faire cette recette {recipe.quantity} fois </p>
          )
        }
    </div>
  </div>
);

export default RecipeShopCard;
