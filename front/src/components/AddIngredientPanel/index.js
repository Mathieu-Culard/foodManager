import React from 'react';
import Category from './Category';
import './addIngredientPanel.scss';

const AddIngredientPanel = ({ ingredients, addToStock, addedValues, changeValue }) => (
  <div className="add-ingredient-panel">
    <div className="add-ingredient-panel__content">
      {ingredients.map((category) => (
        <Category
          key={category.name}
          {...category}
          changeValue={changeValue}
          addedValues={addedValues}
        />
      ))}
    </div>
    <button className="add-ingredient-panel__submit" type="button" onClick={() => addToStock()}>Valider</button>
  </div>
);

export default AddIngredientPanel;
