import React, { useEffect } from 'react';
import './stockPanel.scss';
import Category from './Category';

const StockPanel = ({
  open, isOpen, stock, openModal,
}) => (
  <div className={`stock-panel ${isOpen ? 'stock-open' : 'stock-close'}`}>
    <div className="stock-panel__opener">
      <h1 className="stock-panel__opener__title">Vos stocks</h1>
      <div className={`stock-panel__opener__arrow-${isOpen ? 'close' : 'open'}`} />
      <div className="stock-panel__opener__button" id="stockPanel" onClick={(e) => (open(e.target.id))} />
      {/* <a href="#" id="stockPanel" onClick={(e) => (open(e.target.id))} className="stock-panel__button">
          >
        </a> */}
    </div>
    <div className="stock-panel__content">
      <div className="stock-panel__content__ingredients">
        {stock.map((cat) => (
          cat.ingredients.length !== 0
            && <Category key={cat.name} {...cat} />
        ))}
      </div>
      <a href="#" className="stock-panel__content__add" onClick={() => openModal('Ajouter des ingredient à votre stock', 'AddIngredientPanel', 'stock')}>Ajouter un aliment</a>
    </div>
  </div>
);

export default StockPanel;
