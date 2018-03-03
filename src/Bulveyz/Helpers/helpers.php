<?php

/**
 * @param array $array
 *
 * Debug
 *
 * Mini Debug
 */
function debug(Array $array)
{
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

/**
 * @param string $page
 *
 * Redirect
 *
 * Header location to page
 */
function redirect(string $page) {
  header('location: '. $page);
}

/**
 * @return mixed
 *
 * Token
 *
 * Create new token
 */
function token() {
  $token = microtime(true) . rand(100,10000000000000);
  return str_replace('.', '', $token);
}

/**
 * @param $view
 * @param array $params
 * @throws Twig_Error_Loader
 * @throws Twig_Error_Runtime
 * @throws Twig_Error_Syntax
 *
 * Render
 *
 * Create new view and render params to template
 */
function render($view, $params = [])
{
  $loader = new \Twig_Loader_Filesystem(getenv('DIR_TEMPLATES'));
  $twig = new \Twig_Environment($loader, array(
      'cache' => false
  ));

  echo $twig->render($view . '.tmp', $params);

}