import React, { useEffect } from 'react';
import PropTypes from 'prop-types';
import { useLocation, Link, useParams } from 'react-router-dom';
import './addRecipePage.scss';
import UserIngredientCard from 'src/containers/UserIngredientCard';

const AddRecipePage = ({
  name,
  pictureURL,
  ingredients,
  shared,
  steps,
  lastModifiedStep,
  changefield,
  changeSteps,
  addStep,
  deleteStep,
  changeSwitch,
  changeName,
  changePicture,
  openModal,
  addNewRecipe,
  fetchRecipe,
  editRecipe
}) => {
  const { id } = useParams();

  useEffect(() => {
    if (id) {
      fetchRecipe(id, true);
    }
  }, []);

  const submitAddRecipe = (e) => {
    console.log('yo');
    console.log(id);
    e.preventDefault();
    if (id) {
      console.log('yo');
      editRecipe(id);
    } else {
      addNewRecipe();
    }
  };

  const checked = shared ? 'checked' : '';
  console.log('------------');
  console.log(checked);
  return (
    <div className="add-recipe">
      <form className="add-recipe__form" onSubmit={submitAddRecipe}>
        <div className="add-recipe__form__header">
          <h1 className="add-recipe__form__header__title">Ajouter une recette</h1>
          <label htmlFor="public-witch">
            Rendre la recette publique
            <input
              type="checkbox"
              id="public-switch"
              checked={shared}
              className="add-recipe__form__header__switch"
              // value={shared}
              onChange={() => changeSwitch()}
            />
          </label>
        </div>
        <div className="add-recipe__form__container">
          <div>
            <input
              type="text"
              id="name"
              value={name}
              name="name"
              placeholder="nom de la recette"
              onChange={(e) => changeName(e.target.value)}
              className="add-recipe__form__name-input"
            />
            <div className="add-recipe__form__image">
              {pictureURL !== '' && <img src={pictureURL} alt="recipe" />}
              <label htmlFor="picture" className="add-recipe__form__image__button">
                <input
                  type="file"
                  id="picture"
                  name="picture"
                  onChange={(e) => changePicture(e.target.files[0])}
                  className="add-recipe__form__image__input"
                />
                Télécharger une image
              </label>
            </div>
            <div className="add-recipe__form__ingredients">
              <div className="add-recipe__form__ingredients__container">
                {ingredients.map((ingredient) => (
                  <UserIngredientCard {...ingredient} key={`ingredient-${ingredient.id}`} type="recipe" />
                ))}

              </div>
              <button type="button" onClick={() => openModal('Ajouter des ingredient à votre recette', 'AddIngredientPanel', 'recipe')}>Ajouter un ingrédient</button>
            </div>
          </div>
          <div className="add-recipe__form__steps">
            <div className="add-recipe__form__steps__step">
              {steps.map((step, i) => (
                <label htmlFor={`step-${i}`} key={step.id}>
                  Etape {i + 1}
                  <button type="button" onClick={() => deleteStep(step.id)}>suppr</button>
                  <textarea id={`step-${i}`} name="step" value={step.text} onChange={(e) => changeSteps(e.target.value, i)} />
                </label>
              ))}
              <button type="button" onClick={() => addStep()}>+</button>
            </div>
          </div>
        </div>
        <button type="submit">Ajouter</button>
      </form>
    </div>
  );
};

AddRecipePage.propTypes = {

};
export default AddRecipePage;
