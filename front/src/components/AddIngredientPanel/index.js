import React from 'react';
import Category from './Category';
import './addIngredientPanel.scss';

const AddIngredientPanel = ({
  ingredients, addToStock, addToRecipe, addedValues, changeValue, modalUse, changeTrack, recipeIngredients,
}) => {
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
      {modalUse === 'shop' && <h2 className="add-ingredient-panel__title">Ajouter un element à votre liste de courses</h2>}
      {modalUse === 'recipe' && <h2 className="add-ingredient-panel__title">Ajouter un element à votre recette</h2>}
      {modalUse === 'stock' && <h2 className="add-ingredient-panel__title">Ajouter un element à votre stock</h2>}
      <div className="add-ingredient-panel__content scroll">
        {ingredients.map((category) => (
          <Category
            key={category.name}
            {...category}
            changeValue={changeValue}
            addedValues={addedValues}
            changeTrack={changeTrack}
            recipeIngredients={recipeIngredients}
            type={modalUse}
          />
        ))}
      </div>
      <div className="add-ingredient-panel__submit">
        <button className="add-ingredient-panel__submit__button" type="button" onClick={submit}>Valider</button>
      </div>
    </div>
  );
};

export default AddIngredientPanel;
