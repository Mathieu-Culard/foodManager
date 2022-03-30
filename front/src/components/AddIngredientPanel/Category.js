import React from 'react';
import { getQuantity } from 'src/utils';
import Ingredient from './Ingredient';

const Category = ({
  name, ingredients, changeValue, addedValues, type, changeTrack, recipeIngredients,
}) => (
  <div className="category">
    <h2 className="category__name">{name}</h2>
    <div className="category__content">
      {ingredients.map((ingredient) => (
        <Ingredient
          key={ingredient.name}
          {...ingredient}
          changeValue={changeValue}
          addedValues={addedValues}
          changeTrack={changeTrack}
          type={type}
          quantity={getQuantity(ingredient, recipeIngredients)}
        />
      ))}
    </div>
  </div>
);

export default Category;
{ /* let quantity;
        for (let i = 0; i < recipeIngredients.length; i += 1) {
          if (recipeIngredients[i].id === ingredient.id) {
            return (
              <Ingredient
                key={ingredient.name}
                {...ingredient}
                changeValue={changeValue}
                addedValues={addedValues}
                changeTrack={changeTrack}
                type={type}
                {...recipeIngredients[i]}
              />
            );
          } */ }
