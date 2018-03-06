<?php

namespace Bulveyz\Db;

class Db
{
  private $host;
  private $dbName;
  private $user;
  private $password;

  public function __construct(string $host, string $dbName, string $user, string $password)
  {
    $this->host = $host;
    $this->dbName = $dbName;
    $this->user = $user;
    $this->password = $password;

    return $this;
  }
}