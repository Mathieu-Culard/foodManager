import React from 'react';
import Category from './Category';
import './addIngredientPanel.scss';

const AddIngredientPanel = ({ ingredients, addToStock, addToRecipe, addedValues, changeValue, modalUse }) => {

  console.log(modalUse);
  const submit = () => {
    console.log('mer');
    switch (modalUse) {
      case 'recipe':
        addToRecipe();
        break;
      default:
        addToStock(modalUse);
    }
  };

  return (
    <div className="add-ingredient-panel">
      <div className="add-ingredient-panel__content">
        {ingredients.map((category) => (
          <Category
            key={category.name}
            {...category}
            changeValue={changeValue}
            addedValues={addedValues}
            type={modalUse}
          />
        ))}
      </div>
      <button className="add-ingredient-panel__submit" type="button" onClick={submit}>Valider</button>
    </div>
  );
};

export default AddIngredientPanel;
