<?php

require_once 'vendor/autoload.php';

use RedBeanPHP\R;
use Bulveyz\plugins\Paginator;

R::setup( 'mysql:host=localhost;dbname=bulveyz',
    'root', 'root' );

$data = R::findAll('items');
$items = Paginator::paginate('items', 5, 2);
d($items);

$items::links();