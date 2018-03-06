<?php

require 'vendor/autoload.php';

use RedBeanPHP\R;

R::setup( 'mysql:host=localhost;dbname=bulveyz',
        'root', 'root' );


use Bulveyz\Db\Db;
