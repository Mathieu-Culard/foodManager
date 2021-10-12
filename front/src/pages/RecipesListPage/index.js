import React from 'react';
import PropTypes from 'prop-types';
import RecipeCard from 'src/components/RecipeCard';
import { useLocation, Link } from 'react-router-dom';
import './recipesListPage.scss';

const RecipesListPage = ({ recipes, userRecipes }) => {
  let recipesList = recipes;
  const location = useLocation();

  if (location.pathname === "/my-recipes") {
    recipesList = userRecipes;
  }

  return (
    <>
      <div className="recipes-list">
        {recipesList.map((recipe) => (
          <RecipeCard {...recipe} key={recipe.id} />
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
    </>
  );
};

RecipesListPage.propTypes = {
  recipes: PropTypes.array.isRequired,
  userRecipes: PropTypes.array.isRequired,
};
export default RecipesListPage;
