export const updateStock = (stock, id, newValue) => {
  const newStock = stock.map((cat) => {
    const index = cat.ingredients.findIndex((ingredient) => ingredient.id === id);
    if (index > -1) {
      cat.ingredients[index].quantity = newValue;
    }
    return cat;
  });
  return newStock;
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
  }
  else {
    newAddStock.push({
      id,
      quantity,
    });
  }
  return newAddStock;
};
