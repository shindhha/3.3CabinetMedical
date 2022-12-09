<?php
/*
 * Sample without database connexion
 */
 $hostname = "sql.alphaline.ml";
 $dbname = "test";
 $user = "guillaume";
 $password = "guillaume";
 $port = 3306;
 $charset = "utf8";

spl_autoload_extensions(".php");
spl_autoload_register();
date_default_timezone_set("UTC");
use yasmf\Router;
use yasmf\DataSource;

$router = new Router();
$pdo = new DataSource($hostname,$port,$dbname,$user,$password,$charset);
$router->route($pdo);
