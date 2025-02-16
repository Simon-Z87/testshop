<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'testshop');

session_start();

// Database functions
function getDB()
{
	static $conn;
	
	if ($conn) {
		return $conn;
	} else {		
		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
		
		if (!$conn) {		
			die("MySQL connection failed");
		} else {
		
			mysqli_select_db($conn, DB_NAME);
			mysqli_query($conn, "SET NAMES utf8");	
			
			return $conn;		
		}
	}
}

function db_load_object_list($query, $db = null)
{
	if (!$db) {
		$db = getDB();
	}
	
	$result = mysqli_query($db, $query);
	
	if ($result) {
		while($sql_string = mysqli_fetch_object($result)) {
			$output[] = $sql_string;
		}
	}
	
	return $output;
}

function db_load_object($query, $db = null)
{
	if (!$db) {
		$db = getDB();
	}
	
	$result = mysqli_query($db, $query);
	
	$output = mysqli_fetch_object($result);
	
	return $output;
}	

function db_load_field_array($query, $db = null)
{
	if (!$db) {
		$db = getDB();
	}
	
	$result = mysqli_query($db, $query);
	
	if ($result) {
		while($sql_string = mysqli_fetch_row($result)) {
			$output[] = $sql_string[0];
		}
	}
	
	return $output;
}

function db_query($query, $db = null)
{
	if (!$db) {
		$db = getDB();
	}
	
	return mysqli_query($db, $query);
}

function db_multi_query($query, $db = null)
{
	if (!$db) {
		$db = getDB();
	}
	
	return mysqli_multi_query($db, $query);
}

function db_escape($unescaped_string, $db = null)
{
	if (!$db) {
		$db = getDB();
	}
	
	return mysqli_real_escape_string($db, $unescaped_string);
}

switch ($_REQUEST['action']) {
	case 'upd_cart' :
		if ($_REQUEST['quantity'] > 0) {
			$_SESSION['CART'][$_REQUEST['product_id']] = $_REQUEST['quantity'];
		} else {
			if (isset($_SESSION['CART'][$_REQUEST['product_id']])) unset($_SESSION['CART'][$_REQUEST['product_id']]);
		}
	break;
	case 'clean_cart' :
		unset($_SESSION['CART']);
	break;
	case 'checkout' :
		if (isset($_SESSION['CART'])) {
			$cart_products = Array();
			$total_price = 0;

			if ($_SESSION['CART']) {
				foreach ($_SESSION['CART'] as $product_id => $quantity) {
					$cart_product = db_load_object("SELECT * FROM products WHERE product_id='$product_id'");
					$cart_product->quantity = $quantity;
					$cart_products[] = $cart_product;
					$total_price += $cart_product->product_price * $cart_product->quantity;
				}
			}
			
			db_query("INSERT INTO orders SET order_date=NOW(), order_items='".db_escape(json_encode($cart_products, JSON_UNESCAPED_UNICODE))."', order_total='".(float)$total_price."'");
			$order_id = db_load_field_array("SELECT LAST_INSERT_ID()");
			$order_id = $order_id[0];	
			
			unset($_SESSION['CART']);
			
			$_SESSION['message'] = 'Заказ '.$order_id.' успешно оформлен!';
			
			header('Location: /orders.php');
		}
	break;
	case 'delete_order' :
		if ($_REQUEST['order_id'] > 0) {
			db_query("DELETE FROM orders WHERE order_id='".(int)$_REQUEST['order_id']."'");
		}
	break;
}

?>