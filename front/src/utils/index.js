export const updateStock = (stock, id, newValue) => {
  const newStock = stock.map((cat) => {
    const index = cat.ingredients.findIndex((ingredient) => ingredient.id === id);
    if (index > -1) {
      if (newValue === "0") {
        console.log('blerbler');
        cat.ingredients.splice(index, 1);
      } else {
        cat.ingredients[index].quantity = newValue;
      }
    }
    return cat;
  });
  return newStock;
};

export const getCurrentRecipe = (recipes, id) => {
  const currentRecipe = recipes.filter((recipe) => (
    recipe.id === parseInt(id, 10)
  ));
  return currentRecipe[0];
};

export const changeRecipeIngredientQuantity = (ingredients, id, newValue) => {
  const newIngredients = [...ingredients];
  const index = newIngredients.findIndex((ingredient) => ingredient.id === id);
  newIngredients[index].quantity = newValue;
  return newIngredients;
};

export const deleteIngredient = (stock, id) => {
  const newStock = stock.map((cat) => {
    const index = cat.ingredients.findIndex((ingredient) => ingredient.id === id);
    if (index > -1) {
      cat.ingredients.splice(index, 1);
    }
    return cat;
  });
  return newStock;
};

export const addToStock = (addStock, id, quantity) => {
  const newAddStock = [...addStock];
  const currentItem = newAddStock.find((item) => item.id === id);
  if (currentItem) {
    currentItem.quantity = quantity;
    if (quantity === '0') {
      const index = newAddStock.map((ingredient) => ingredient.id).indexOf(id);
      newAddStock.splice(index, 1);
    }
  }
  else {
    newAddStock.push({
      id,
      quantity,
    });
  }
  return newAddStock;
};
export const addStep = (steps) => {
  const stepsList = [...steps];
  stepsList.push({
    id: steps.length + 1,
    text: '',
  });
  return stepsList;
};

export const manageSteps = (steps, newValue, index) => {
  const newSteps = [...steps];
  for (let i = 0; i < newSteps.length; i += 1) {
    if (index === i) {
      newSteps[i].text = newValue;
      console.log(newSteps[i]);
    }
  }
  return newSteps;
};

export const deleteStep = (steps, id) => {
  const newSteps = [...steps];
  const index = newSteps.map((step) => step.id).indexOf(id);
  newSteps.splice(index, 1);
  console.log(newSteps);
  return newSteps.map((step, i) => ({
    id: i + 1,
    text: step.text,
  }));
};

export const getRecipesIngredients = (ingredientsList, addStock, addedIngredients) => {
  const ingredients = [...addedIngredients];
  for (let i = 0; i < addedIngredients.length; i += 1) {
    for (let j = 0; j < addStock.length; j += 1) {
      if (addedIngredients[i].id === addStock[j].id) {
        addedIngredients[i].quantity = parseInt(addStock[j].quantity, 10)
          + parseInt(addedIngredients[i].quantity, 10);
        addStock.splice(j, 1);
      }
    }
  }
  for (let i = 0; i < addStock.length; i += 1) {
    for (let j = 0; j < ingredientsList.length; j += 1) {
      for (let k = 0; k < ingredientsList[j].ingredients.length; k += 1) {
        if (ingredientsList[j].ingredients[k].id === addStock[i].id) {
          ingredientsList[j].ingredients[k].quantity = addStock[i].quantity;
          ingredients.push(ingredientsList[j].ingredients[k]);
        }
      }
    }
  }
  console.log(ingredients);
  return ingredients;
};



// export const checkDuplicateInRecipesIngredients = (addStock, addedIngredients) => {

// };
// const currentItem = ingredientsList[j].ingredients.find((item) => item.id === addedIngredients[i].id);
// console.log("ménon");
// console.log(currentItem);
// if (currentItem) {
//   console.log("bahoui");
//   currentItem.quantity += addStock[i].quantity;
// } else {

