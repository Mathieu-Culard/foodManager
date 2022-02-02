import React, { useEffect } from 'react';
// import Category from 'src/components/StockPanel/Category';
import IngredientCard from 'src/containers/UserIngredientCard';
import RecipeShopCard from 'src/components/RecipeShopCard';
import './shopPanel.scss';
import { test } from 'src/utils';

const ShopPanel = ({
  open, isOpen, shoppingList, openModal, validate, recipes, buyRecipe, buyLess, deleteRecipe,
}) => {
  useEffect(() => {
    // window.addEventListener('scroll', test);
  }, []);

  return (
    <div className={`shop-panel ${isOpen ? 'open' : 'close'}`}>
      <div className="shop-panel__opener">
        <h1 className="shop-panel__opener__title">Liste de courses</h1>
        <div className={`shop-panel__opener__arrow-${isOpen ? 'close' : 'open'}`} />
        <div className="shop-panel__opener__button" id="shopPanel" onClick={(e) => (open(e.target.id))} />
      </div>

      {/* <a href="#" id="shopPanel" onClick={(e) => (open(e.target.id))} className="shop-panel__button">
        >
      </a> */}
      <div className="shop-panel__content">
        <div className="shop-panel__content__list">
          {recipes.map((recipe) => (
            <RecipeShopCard key={`recipe-shop-${recipe.id}`} recipe={recipe} buyRecipe={buyRecipe} buyLess={buyLess} deleteRecipe={deleteRecipe} />
          ))}
          {shoppingList.map((cat) => cat.ingredients.map((ingredient) => (
            <IngredientCard key={ingredient.name} {...ingredient} type="shop" />
          )))}
        </div>
        <a href="#" className="shop-panel__content__add" onClick={() => openModal('Ajouter des ingredient à votre liste de courses', 'AddIngredientPanel', 'shop')}>Ajouter un aliment</a>
        <button className="shop-panel__content__validate" type="button" onClick={validate}>Valider la liste de courses</button>
      </div>
    </div>
  );
};
export default ShopPanel;
