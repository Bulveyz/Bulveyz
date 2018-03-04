<?php

/** @var \Bulveyz\Routing\RouterCollection $router */
$router->any('login', function (){
    $loader = new \Twig_Loader_Filesystem(__DIR__ . '/templates/');
    $twig = new \Twig_Environment($loader, array(
        'cache' => false
    ));
    echo $twig->render('login.tmp');
});

$router->any('register', function (){
  $loader = new \Twig_Loader_Filesystem(__DIR__ . '/templates/');
  $twig = new \Twig_Environment($loader, array(
      'cache' => false
  ));
  echo $twig->render('register.tmp');
});

$router->any('reset', function (){
  $loader = new \Twig_Loader_Filesystem(__DIR__ . '/templates/');
  $twig = new \Twig_Environment($loader, array(
      'cache' => false
  ));
  echo $twig->render('reset.tmp');
});

$router->any('restore/{token}', function (){
  $loader = new \Twig_Loader_Filesystem(__DIR__ . '/templates/');
  $twig = new \Twig_Environment($loader, array(
      'cache' => false
  ));
  echo $twig->render('restore.tmp');
});