
import React from 'react';

import './loader.scss';

const Loader = () => (
  <div className="loading">
    <svg className="loader" viewBox="0 0 500 500">
      <circle cx="250" cy="250" r="160" stroke="#E2007C" />
      <circle cx="250" cy="250" r="135" stroke="#404041" />
      <circle cx="250" cy="250" r="110" stroke="#E2007C" />
      <circle cx="250" cy="250" r="85" stroke="#404041" />
      <circle cx="250" cy="250" r="60" stroke="#E2007C" />

      {/* <polygon points=" 50,50 50,75 0,100 50,125 50,150 75,150
       100,200 125,150 150,150 150,125 200,100
      150,75 150,50 125,50 100,0 75,50" /> */}
    </svg>
  </div>
);

export default Loader;
