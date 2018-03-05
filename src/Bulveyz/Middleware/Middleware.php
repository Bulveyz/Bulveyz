<?php

namespace Bulveyz\Middleware;

use RedBeanPHP\R;

class Middleware
{
  public static function access(string $userGroup)
  {
    if (!isset($_SESSION[$userGroup])) {
      redirect('/');
    }
  }

  public static function fatalAccess(string $userGroup, string $error)
  {
    if (!isset($_SESSION[$userGroup])) {
      exit($error);
    }
  }

  public static function elseAccess(string $userGroup)
  {
    if (isset($_SESSION[$userGroup])) {
      redirect('/');
    }
  }

  public static function fatalElseAccess(string $userGroup, string $error)
  {
    if (isset($_SESSION[$userGroup])) {
      exit($error);
    }
  }
}