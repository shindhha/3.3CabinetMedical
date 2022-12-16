<?php
/*
 * Sample without database connexion
 */
 $hostname = "sql.alphaline.ml";
 $dbname = "SAE_TESTS";
 $user = "guillaume";
 $password = "guillaume";
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
