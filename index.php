<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="extensions/sticky-header/bootstrap-table-sticky-header.css">


</head>
<body>


<?php

/*
 * Sample without database connexion
 */
 $hostname = "localhost";
 $dbname = "test";
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
?>
<script src="extensions/sticky-header/bootstrap-table-sticky-header.js"></script>
</body>
</html>
