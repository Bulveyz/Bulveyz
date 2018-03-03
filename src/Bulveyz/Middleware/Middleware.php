<?php

namespace Bulveyz\Middleware;

class Middleware
{
  public static function access(string $userGroup)
  {
    if (!isset($_SESSION[$userGroup])) {
      redirect('/');
    }
  }
}