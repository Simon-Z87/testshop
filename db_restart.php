<?php

require_once('init.php');

header('Content-type: text/html; charset=utf-8');

db_query("DROP TABLE IF EXISTS products");
echo 'DROP TABLE IF EXISTS products<br>';

db_query("DROP TABLE IF EXISTS orders");
echo 'DROP TABLE IF EXISTS orders<br>';

db_multi_query(file_get_contents('testshop.sql'));
echo 'Importing test data<br>';

echo '<a href="/">◄ Go to catalog</a>';

?>