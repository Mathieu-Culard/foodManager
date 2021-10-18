import React from 'react';
import './userIngredientCard.scss';

const UserIngredientCard = ({
  id, name, quantity, image, type, unity, changeQuantity, deleteIngredient, deleteRecipeIngredient, changeRecipeIngredientQuantity, minBuy
}) => (
    <div className="user-ingredient-card">
      <img className="user-ingredient-card__image" src={`http://localhost:8000/assets/ingredients/${image}`} alt="aliment" />
      <p className="user-ingredient-card__name">{name}</p>
      {type === 'stock' && <input type="number" value={quantity} className="user-ingredient-card__input" onChange={(e) => changeQuantity(id, e.target.value, type)} />}
      {type === 'shop' && <input type="number" value={quantity} className="user-ingredient-card__input" step={minBuy} onChange={(e) => changeQuantity(id, e.target.value, type)} />}
      {type === 'recipe' && <input type="number" value={quantity} className="user-ingredient-card__input" onChange={(e) => changeRecipeIngredientQuantity(id, e.target.value)} />}
      <p>{unity}</p>
      <div className="user-ingredient-card__buttons">
        {(type === 'stock' || type === 'shop') && (
          <>
            {/* <button type="button">buy</button> */}
            <button type="button" onClick={() => deleteIngredient(id, type)}>delete</button>
          </>
        )}
        {type === 'recipe' && (
          <>
            <button type="button" onClick={() => deleteRecipeIngredient(id)}>delete</button>
          </>
        )}
      </div>
    </div>
  );

export default UserIngredientCard;
