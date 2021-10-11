import React, { useEffect } from 'react';
import './stockPanel.scss';
import Category from './Category';

const StockPanel = ({
  open, isOpen, stock, openModal,
}) => (
  <div className={`stock-panel ${isOpen ? 'open' : 'close'}`}>
    <h1 className="stock-panel__title">Stock</h1>
    <a href="#" id="stockPanel" onClick={(e) => (open(e.target.id))} className="stock-panel__button">
      >
    </a>
    <div className="stock-panel__content">
      <a href="#" onClick={() => openModal('Ajouter des ingredient à votre stock', 'AddIngredientPanel')}>Ajouter un aliment</a>
      {stock.map((cat) => (
        cat.ingredients.length !== 0
        && <Category key={cat.name} {...cat} />
      ))}
    </div>
  </div>
);

export default StockPanel;
