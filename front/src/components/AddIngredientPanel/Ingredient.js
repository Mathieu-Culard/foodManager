import React from 'react';

const Ingredient = ({
  id, name, unity, isTracked, changeValue, addedValues, image, type, minBuy, changeTrack, quantity,
}) => {
  const currentItem = addedValues.find((item) => item.id === id);
  // let quantity = 0;
  let checked;
  if (typeof currentItem !== 'undefined') {
    quantity = currentItem.quantity;
  }

  if (parseInt(quantity, 10) === -1) {
    checked = true;
  } else {
    checked = false;
  }
  const handleChange = (e) => {
    console.log('yes');
    if (e.target.checked) {
      console.log('zbleh');
      changeValue(id, '-1');
    } else {
      console.log('zblouh');
      changeValue(id, '0');
    }
  };

  return (
    <div className="ingredient">
      <div className="ingredient__content">
        <img className="ingredient__image" src={`http://localhost:8000/assets/ingredients/${image}`} alt="aliment" />
        <p className="ingredient__content__name">{name}</p>
        {type === 'stock' && <input className="ingredient__content__input" type="number" value={currentItem ? currentItem.quantity : '0'} onChange={(e) => changeValue(id, e.target.value)} />}
        {(type === 'recipe' && parseInt(quantity, 10) !== -1) && <input className="ingredient__content__input" type="number" min="0" value={quantity} onChange={(e) => changeValue(id, e.target.value)} />}
        {(type === 'recipe' && parseInt(quantity, 10) === -1) && <p>quantité au choix</p>}
        {type === 'shop' && <input className="ingredient__content__input" type="number" step={minBuy} value={currentItem ? currentItem.quantity : '0'} onChange={(e) => changeValue(id, e.target.value)} />}
        {(type === 'shop' || type === 'stock' || (type === 'recipe' && quantity !== '-1')) && <p className="ingredient__content__unity">{unity}</p>}
      </div>
      {(type === 'recipe' && isTracked === '0') && (
        <label htmlFor="untrack" className="ingredient__checkbox">Quantité libre
          <input name="untrack" className="ingredient__checkbox__untrack" type="checkbox" onChange={(e) => handleChange(e, id)} checked={checked} />
        </label>
      )}
      {/* <button type="button" className="ingredient__button">Valider</button> */}
    </div>
  );
};

export default Ingredient;
