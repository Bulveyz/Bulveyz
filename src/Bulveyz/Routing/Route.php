<?php

namespace Bulveyz\Routing;

/*
 * Route Class
 *
 * Used as a filter and a template for adding a new route
 */

class Route extends RouterCollection
{
  public $route;
  public $handler;
  public $method;
  public $matches;

  /**
   * Route constructor.
   * @param $route
   * @param $handler
   * @param array $method
   *
   * Valid data route
   */
  public function __construct($route, $handler, array $method)
  {
    $this->route = $route;
    $this->handler = $handler;
    $this->method = $method;
  }
}

