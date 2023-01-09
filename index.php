<?php
session_start();
/*
 * Sample without database connexion
 */
 $hostname = "localhost";
 $dbname = "sae_tests";
 $user = "root";
 $password = "root";
 $port = 3306;
 $charset = "utf8mb4";

spl_autoload_extensions(".php");
spl_autoload_register();
date_default_timezone_set("UTC");
set_time_limit(3600);
use yasmf\Router;
use yasmf\DataSource;

$router = new Router();
$pdo = new DataSource($hostname,$port,$dbname,$user,$password,$charset);
$router->route($pdo);
