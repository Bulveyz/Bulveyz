<?php

use RedBeanPHP\R;

  function history()
  {
    if (getenv('PLUGIN_HISTORY') == 'true') {
    $name = substr($_SERVER['REQUEST_URI'], 3, -4);


    $check = R::findOne('last', 'name = ?', array($name));

    if ($check)
    {
      $last = R::findAll('last', "ip = ? ORDER BY id DESC LIMIT 5", array($_SERVER['SERVER_ADDR'] ));
      foreach ($last as $ls)
      {
        echo "<a href=".$ls['last'].">".$ls['name']."</a>"."<br>";
      }
    }
    else
    {

      $last = R::dispense('last');
      $last->ip = $_SERVER['SERVER_ADDR'];
      $last->last = $_SERVER['REQUEST_URI'];
      $last->name = $name;
      R::store($last);

      $last = R::findAll('last', "ip = ? ORDER BY id DESC LIMIT 5", array($_SERVER['SERVER_ADDR'] ));
      foreach ($last as $ls)
      {
        echo "<a href=".$ls['last'].">".$ls['name']."</a>"."<br>";
      }

    }

    $start = R::findAll('last', 'ip = ?', array($_SERVER['SERVER_ADDR']));

    $startup = count($start);

    if ($startup >= 6)
    {
      $delte = R::findOne('last', 'ip = ?', array($_SERVER['SERVER_ADDR']));
      R::trash($delte);
    }
  }
}