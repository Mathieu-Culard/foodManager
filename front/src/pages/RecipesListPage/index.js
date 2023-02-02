import React, { useEffect } from 'react';
import PropTypes from 'prop-types';
import RecipeCard from 'src/components/RecipeCard';
import { useLocation, Link } from 'react-router-dom';
import './recipesListPage.scss';

const RecipesListPage = ({
  recipes, userRecipes, fetchMyRecipes, fetchPublicRecipes, isLogged, deleteRecipe, buyRecipe, loadRecipe,
}) => {
  useEffect(() => {
    loadRecipe();
    // fetchPublicRecipes();
    // if (isLogged) {
    //   fetchMyRecipes();
    // }
  }, []);

  let recipesList = recipes;
  const location = useLocation();
  if (location.pathname === '/my-recipes') {
    recipesList = userRecipes;
  }

  return (
    <main className="recipes-list">
      {
        location.pathname === '/my-recipes' && (
          <div className="recipes-list__head">
            <h2 className="recipes-list__head__title">Mes recettes</h2>
            <Link to="add-recipe" className="recipes-list__head__add">
              Ajouter une recette
            </Link>
          </div>
        )
      }
      <div className="recipes-list__content">
        {recipesList.map((recipe) => (
          <RecipeCard
            {...recipe}
            key={recipe.id}
            location={location.pathname}
            deleteRecipe={deleteRecipe}
            buyRecipe={buyRecipe}
            isLogged={isLogged}
          />
        ))}
      </div>
      {
        recipesList.length === 0 && (
          <p className="recipes-list__text">Vous n'avez pas encore de recettes </p>
        )
      }

    </main>
  );
};

RecipesListPage.propTypes = {
  recipes: PropTypes.array.isRequired,
  userRecipes: PropTypes.array.isRequired,
  fetchMyRecipes: PropTypes.func.isRequired,
};
export default RecipesListPage;
