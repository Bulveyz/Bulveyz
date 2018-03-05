<?php

require_once 'vendor/autoload.php';

$_SESSION['red'] = $_SERVER['REQUEST_URI'];

echo $_SESSION['red'];