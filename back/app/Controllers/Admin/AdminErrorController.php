<?php

namespace App\Controllers\Admin;

class AdminErrorController extends CoreController
{
  public function err403()
  {
    header('HTTP/1.0 403 Forbidden');
    $this->show('error/err403');
  }
}
