import React from 'react';
import './userIngredientCard.scss';

const UserIngredientCard = ({
  id, name, quantity, image, type, unity, changeQuantity, deleteIngredient, deleteRecipeIngredient, changeRecipeIngredientQuantity, minBuy, validateQuantity,
}) => (
  <div className={`user-ingredient-card ${type}`}>
    <img className="user-ingredient-card__image" src={`http://localhost:8000/assets/ingredients/${image}`} alt="aliment" />
    <p className="user-ingredient-card__name">{name}</p>
    {type === 'stock' && <input type="number" value={quantity} className="user-ingredient-card__input" onChange={(e) => changeQuantity(id, e.target.value, type)} onBlur={(e) => validateQuantity(id, e.target.value, type)} />}
    {type === 'shop' && <input type="number" value={quantity} className="user-ingredient-card__input" step={minBuy} onChange={(e) => changeQuantity(id, e.target.value, type)} onBlur={(e) => validateQuantity(id, e.target.value, type)}/>}
    {(type === 'recipe' && parseInt(quantity, 10) !== -1) && <input type="number" value={quantity} className="user-ingredient-card__input" onChange={(e) => changeRecipeIngredientQuantity(id, e.target.value)} />}
    {(type === 'recipe' && parseInt(quantity, 10) === -1) && <p>quantité au choix</p>}
    {(type === 'shop' || type === 'stock' || (type === 'recipe' && quantity !== '-1')) && <p className="user-ingredient-card__unity">{unity}</p>}
    <div className="user-ingredient-card__buttons">
      {(type === 'stock' || type === 'shop') && (
        <>
          {/* <button type="button">buy</button> */}
          <button type="button" onClick={() => deleteIngredient(id, type)}>&times;</button>
        </>
      )}
      {type === 'recipe' && (
        <>
          <button type="button" onClick={() => deleteRecipeIngredient(id)}>&times;</button>
        </>
      )}
    </div>
  </div>
);

export default UserIngredientCard;
