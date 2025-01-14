<?php 

require_once('init.php');

header('Content-type: text/html; charset=utf-8');

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

?><!doctype html>
<html lang="en-US">
<head>
	<title>Корзина - Тестовый магазин</title>
</head>
<body>
	<table align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td><h1>Корзина</h1></td>
			<td align="right" style="padding-left:10px;">
				<a href=".">Каталог</a><br />
				<a href="orders.php">Заказы</a>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php if ($cart_products) : ?>
					<table border="1" cellpadding="5">
						<tr>
							<th>ID</th>
							<th>Название</th>
							<th>Цена</th>
							<th>Кол-во</th>
							<th>Сумма</th>
							<th>&nbsp;</th>
						</tr>
						<?php foreach ($cart_products as $cart_product) :  ?>
							<tr>
								<td><?php echo $cart_product->product_id; ?></td>
								<td><?php echo $cart_product->product_name; ?></td>
								<td><?php echo $cart_product->product_price; ?></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="action" value="upd_cart" />
										<input type="hidden" name="product_id" value="<?php echo $cart_product->product_id; ?>" />
										<input type="number" style="width:50px;" min="1" step="1" name="quantity" value="<?php echo $cart_product->quantity; ?>" />
										<button type="submit">Обновить</button>
									</form>
								</td>
								<td><?php echo $cart_product->product_price * $cart_product->quantity; ?></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="action" value="upd_cart" />
										<input type="hidden" name="product_id" value="<?php echo $cart_product->product_id; ?>" />
										<input type="hidden" name="quantity" value="0" />
										<button type="submit">Удалить</button>
									</form>
								</td>
							</tr>				
						<?php endforeach; ?>
						<td>
							<th colspan="3" align="right">Итог</th>
							<td><?php echo $total_price; ?></td>
							<td>&nbsp;</td>							
						</td>
					</table>
					<table width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td>
								<form method="POST" action="">
									<input type="hidden" name="action" value="clean_cart" />
									<p><button type="submit">Очистить корзину</button></p>
								</form>
							</td>
							<td align="right">
								<form method="POST" action="" onsubmit="this.submit.disabled = true;">
									<input type="hidden" name="action" value="checkout" />
									<p><button type="submit" name="submit">Заказать</button></p>
								</form>
							</td>
						</tr>
					</table>
				<?php else : ?>
					<p align="center">Корзина пуста!</p>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</body>
</html>