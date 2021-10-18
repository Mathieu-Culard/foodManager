import React, { useEffect } from 'react';
// import Category from 'src/components/StockPanel/Category';
import IngredientCard from 'src/containers/UserIngredientCard';
import './shopPanel.scss';

const ShopPanel = ({
  open, isOpen, shoppingList, openModal, validate,
}) => (
  <div className={`shop-panel ${isOpen ? 'open' : 'close'}`}>

    <h1 className="shop-panel__title">Liste de courses</h1>
    <a href="#" id="shopPanel" onClick={(e) => (open(e.target.id))} className="shop-panel__button">
      >
    </a>
    <div className="shop-panel__content">
      <button className="shop-panel__validate" type="button" onClick={validate}>Valider la liste de courses</button>
      <a href="#" onClick={() => openModal('Ajouter des ingredient à votre liste de courses', 'AddIngredientPanel', 'shop')}>Ajouter un aliment</a>
      {shoppingList.map((cat) => cat.ingredients.map((ingredient) => (
        <IngredientCard key={ingredient.name} {...ingredient} type="shop" />
      )))}
    </div>
  </div>
);

export default ShopPanel;
