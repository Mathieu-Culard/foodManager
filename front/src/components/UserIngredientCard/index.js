import React from 'react';
import './userIngredientCard.scss';

const UserIngredientCard = ({
  id, name, quantity, image, type, unity, changeQuantity, deleteIngredient,
}) => (
  <div className="user-ingredient-card">
    <img className="user-ingredient-card__image" src={`http://localhost:8000/assets/ingredients/${image}`} alt="aliment" />
    <p className="user-ingredient-card__name">{name}</p>
    <input type="number" value={quantity} className="user-ingredient-card__input" onChange={(e) => changeQuantity(id, e.target.value)} />
    <p>{unity}</p>
    <div className="user-ingredient-card__buttons">
      <button type="button">buy</button>
      <button type="button" onClick={() => deleteIngredient(id)}>delete</button>
    </div>

  </div>
);

export default UserIngredientCard;
