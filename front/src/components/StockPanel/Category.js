import React from 'react';
import IngredientCard from 'src/containers/UserIngredientCard';

const Category = ({ name, ingredients }) => (
  <div className="stock-panel__content__category">
    <h2 className="stock-panel__content__category__name">{name}</h2>
    {ingredients.map((ingredient) => (
      <IngredientCard key={ingredient.name} {...ingredient} />
    ))}
  </div>

);

export default Category;
