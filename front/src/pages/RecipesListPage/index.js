import React, { useEffect } from 'react';
import PropTypes from 'prop-types';
import RecipeCard from 'src/components/RecipeCard';
import { useLocation, Link } from 'react-router-dom';
import './recipesListPage.scss';

const RecipesListPage = ({
  recipes, userRecipes, fetchMyRecipes, fetchPublicRecipes, isLogged, deleteRecipe
}) => {
  useEffect(() => {
    fetchPublicRecipes();
    if (isLogged) {
      fetchMyRecipes();
    }
  }, []);
  let recipesList = recipes;
  const location = useLocation();
  if (location.pathname === '/my-recipes') {
    recipesList = userRecipes;
  }

  return (
    <div className="recipes-list">
      <div className="recipes-list__content">
        {recipesList.map((recipe) => (
          <RecipeCard
            {...recipe}
            key={recipe.id}
            location={location.pathname}
            deleteRecipe={deleteRecipe}
          />
        ))}
      </div>
      {
        recipesList.length === 0 && (
          <p>Vous n'avez pas encore de recettes </p>
        )
      }
      {
        location.pathname === '/my-recipes' && (
          <Link to="add-recipe">
            Ajouter une recette
          </Link>
        )
      }
    </div>
  );
};

RecipesListPage.propTypes = {
  recipes: PropTypes.array.isRequired,
  userRecipes: PropTypes.array.isRequired,
  fetchMyRecipes: PropTypes.func.isRequired,
};
export default RecipesListPage;
