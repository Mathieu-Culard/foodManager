import React from 'react';
import Ingredient from './Ingredient';

const Category = ({ name, ingredients, changeValue, addedValues, type }) => (
  <div className="category">
    <h2 className="category__name">{name}</h2>
    <div className="category__content">
      {ingredients.map((ingredient) => (
        <Ingredient
          key={ingredient.name}
          {...ingredient}
          changeValue={changeValue}
          addedValues={addedValues}
          type={type}
        />
      ))}
    </div>
  </div>
);

export default Category;
