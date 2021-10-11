import React from 'react';

const Ingredient = ({
  id, name, unity, changeValue, addedValues, image
}) => {
  const currentItem = addedValues.find((item) => item.id === id);
  return (
    <div className="ingredient">
      <img className="ingredient__image" src={`http://localhost:8000/assets/ingredients/${image}`} alt="aliment" />
      <div className="ingredient__content">
        <p className="ingredient__content__name">{name}</p>
        <input className="ingredient__content__input" type="number" value={currentItem ? currentItem.quantity : '0'} onChange={(e) => changeValue(id, e.target.value)} />
        <p className="ingredient__content__unity">{unity}</p>
      </div>
      {/* <button type="button" className="ingredient__button">Valider</button> */}
    </div>
  );
};

export default Ingredient;
