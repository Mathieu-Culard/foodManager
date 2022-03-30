import { connect } from 'react-redux';
import AddRecipePage from 'src/pages/AddRecipePage';

import {
  changePicture,
  changeName,
  changeSteps,
  addStep,
  deleteStep,
  changeSwitch,
  addNewRecipe,
  fetchRecipe,
  editRecipe,
  clearAddRecipeForm,
} from 'src/actions/recipes';
import { openModal } from 'src/actions/utils';

const mapStateToProps = (state) => ({
  name: state.recipe.name,
  ingredients: state.recipe.ingredients,
  steps: state.recipe.steps,
  shared: state.recipe.shared,
  pictureURL: state.recipe.pictureURL,
  // ingredientsList: state.ingredients.ingredientsList,
  // AddedIngredients: state.ingredients.addStock,
});

const mapDispatchToProps = (dispatch) => ({
  fetchRecipe: (id, isEdit) => dispatch(fetchRecipe(id, isEdit)),
  changeSteps: (newValue, index) => dispatch(changeSteps(newValue, index)),
  addStep: () => dispatch(addStep()),
  deleteStep: (id) => dispatch(deleteStep(id)),
  changeSwitch: () => dispatch(changeSwitch()),
  changeName: (newValue) => dispatch(changeName(newValue)),
  changePicture: (picture) => dispatch(changePicture(picture)),
  openModal: (component, title, use) => dispatch(openModal(component, title, use)),
  addNewRecipe: () => dispatch(addNewRecipe()),
  editRecipe: (id) => dispatch(editRecipe(id)),
  clearForm: () => dispatch(clearAddRecipeForm()),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(AddRecipePage);
