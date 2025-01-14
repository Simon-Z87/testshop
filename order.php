<?php 

require_once('init.php');

header('Content-type: text/html; charset=utf-8');

$order = db_load_object("SELECT * FROM orders WHERE order_id='".(int)$_GET['order_id']."'");

?><!doctype html>
<html lang="en-US">
<head>
	<title>Заказы - Тестовый магазин</title>
</head>
<body>
	<table align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td><h1>Заказ - <?php echo $_GET['order_id'].' - '.($order ? $order->order_date : 'не найден'); ?></h1></td>
			<td align="right" style="padding-left:10px;">
				<a href="orders.php">Заказы</a><br />
				<a href=".">Каталог</a>
			</td>
		</tr>
		<?php 
			$order_items = json_decode($order->order_items);
			
			if ($order_items) :
		?>
			<tr>
				<td colspan="2">
					<h2>Товары</h2>
					
					<table border="1" cellpadding="5">
						<tr>
							<th>ID</th>
							<th>Название</th>
							<th>Цена</th>
							<th>Кол-во</th>
							<th>Сумма</th>
						</tr>
						<?php foreach ($order_items as $order_item) :  ?>
							<tr>
								<td><?php echo $order_item->product_id; ?></td>
								<td><?php echo $order_item->product_name; ?></td>
								<td><?php echo $order_item->product_price; ?></td>
								<td><?php echo $order_item->quantity; ?></td>
								<td><?php echo $order_item->product_price * $order_item->quantity; ?></td>
							</tr>				
						<?php endforeach; ?>
						<td>
							<th colspan="3" align="right">Итог</th>
							<td><?php echo $order->order_total; ?></td>				
						</td>
					</table>
				</td>
			</tr>
		<?php endif; ?>
	</table>
</body>
</html>