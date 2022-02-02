<?php

namespace App\Controllers\Admin;


class CoreController
{

  protected $router;

  public function __construct($router, $routeName = '')
  {
    $this->router = $router;
    if ($routeName != 'admin-user-login' && $routeName != 'admin-user-authenticate') {
      $this->checkAuthorization();
    }
    $csrfRoutes = [
      'post' => [
        'admin-ingredients-add-update',
        'admin-units-add-update',
        'admin-categories-add-update',
      ],
      'get' => [
        'admin-ingredients-delete',
        'admin-units-delete',
        'admin-categories-delete',
        'admin-user-update',
      ]
    ];
    if (in_array($routeName, $csrfRoutes['post'])) {
      $this->checkCsrfToken('POST');
    } else if (in_array($routeName, $csrfRoutes['get'])) {
      $this->checkCsrfToken('GET');
    }
  }


  protected function checkAuthorization()
  {
    if (empty($_SESSION['userId']) || $_SESSION['userRole'] != 'ROLE_ADMIN') {
      unset($_SESSION['userId']); 
      unset($_SESSION['userRole']);
      $this->redirectToRoute('admin-user-login');
    }
  }

  /**
   * Méthode permettant de rediriger l'internaute vers une route du projet
   * 
   * @param string $routeName Le nom de la route
   * @param array $routeData Les valeurs pour les parties dynamiques de l'URL
   * @return void
   */
  protected function redirectToRoute(string $routeName, $routeData = [])
  {
    $router = $this->router;
    $url = $router->generate($routeName, $routeData);
    header('Location: ' . $url);
    exit;
  }

  protected function generateCsrfToken()
  {
    $token = bin2hex(random_bytes(16));
    $_SESSION['csrfToken'] = $token;
    return $token;
  }

  protected function checkCsrfToken($httpMethod = 'POST')
  {
    if ($httpMethod == 'POST') {
      $formToken = filter_input(INPUT_POST, 'token');
    } else if ($httpMethod == 'GET') {
      $formToken = filter_input(INPUT_GET, 'token');
    } else {
      return false;
    }
    $sessionToken = isset($_SESSION['csrfToken']) ? $_SESSION['csrfToken'] : '';
    if ($sessionToken != $formToken) {
      $errorController = new AdminErrorController($this->router);
      $errorController->err403();
      exit;
    }
    unset($_SESSION['csrfToken']);
  }

  protected function show(string $viewName, $viewVars = [])
  {
    $router = $this->router;
    extract($viewVars);
    require_once __DIR__ . '/../../views/layout/header.tpl.php';
    require_once __DIR__ . '/../../views/' . $viewName . '.tpl.php';
    require_once __DIR__ . '/../../views/layout/footer.tpl.php';
  }
}
