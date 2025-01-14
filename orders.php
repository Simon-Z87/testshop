<?php 

require_once('init.php');

header('Content-type: text/html; charset=utf-8');

$orders = db_load_object_list("SELECT * FROM orders ORDER BY order_date DESC");

?><!doctype html>
<html lang="en-US">
<head>
	<title>Заказы - Тестовый магазин</title>
</head>
<body>
	<table align="center" cellspacing="0" cellpadding="0">
		<?php if ($_SESSION['message']) : ?>
			<tr>
				<td colspan="2" align="center" style="border: solid 2px green; padding:10px;">
					<?php echo $_SESSION['message']; ?>
				</td>
			</tr>
		<?php
			unset($_SESSION['message']);
			endif; 
		?>
		<tr>
			<td><h1>Заказы</h1></td>
			<td align="right" style="padding-left:10px;">
				<a href=".">Каталог</a><br />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php if ($orders) : ?>
					<table border="1" cellpadding="5">
						<tr>
							<th>ID</th>
							<th>Дата</th>
							<th>Сумма заказа</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
						</tr>
						<?php foreach ($orders as $order) :  ?>
							<tr>
								<td><?php echo $order->order_id; ?></td>
								<td><?php echo $order->order_date; ?></td>
								<td><?php echo $order->order_total; ?></td>
								<td><a href="order.php?order_id=<?php echo $order->order_id; ?>">Товары</a></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="action" value="delete_order" />
										<input type="hidden" name="order_id" value="<?php echo $order->order_id; ?>" />
										<button type="submit">Удалить заказ</button>
									</form>
								</td>
							</tr>				
						<?php endforeach; ?>
					</table>
				<?php else : ?>
					<p align="center">Нет заказов!</p>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</body>
</html>