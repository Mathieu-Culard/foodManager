import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';

import './errorPage.scss';

const ErrorPage = ({ errorMessage, errorCode }) => (
  <div className="error-page">
    <div className="error-page__content">
      <p className="error-page__content__message">
        <span className="error-page__content__message__code">{errorCode}</span>{errorMessage}
      </p>
      <Link className="error-page__content__link" to="/">Revenir au menu</Link>
    </div>
  </div>
);

export default ErrorPage;
