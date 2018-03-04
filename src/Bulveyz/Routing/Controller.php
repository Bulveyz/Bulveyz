<?php

namespace Bulveyz\Routing;

class Controller
{
  public $params = [];
  public $path;

  public function render($view, $params = [])
  {
    if (isset($params)) {
      $this->params = $params;
    }

    $loader = new \Twig_Loader_Filesystem(getenv('TEMPLATES_DIR'));
    $twig = new \Twig_Environment($loader, array(
        'cache' => false
    ));

    echo $twig->render($view . '.tmp', $this->params);

  }
}