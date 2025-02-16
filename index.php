<?php 

require_once('init.php');

header('Content-type: text/html; charset=utf-8');

$limit = 100;
$pg_num = (int)($_GET['pg_num'] ? $_GET['pg_num'] : 1);
$limitstart = ($pg_num - 1) * $limit;

$products = db_load_object_list("SELECT * FROM products LIMIT $limitstart, $limit");
$count = db_load_field_array("SELECT COUNT(*) FROM products");
$count = $count[0];

$pages = floor($count / $limit);

?><!doctype html>
<html lang="en-US">
<head>
	<title>Каталог - Тестовый магазин</title>
</head>
<body>
	<table align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td><h1>Каталог</h1></td>
			<td align="right">
				<a href="cart.php">Корзина (<?php echo $_SESSION['CART'] ? count($_SESSION['CART']) : 0; ?>)</a><br />
				<a href="orders.php">Заказы</a>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php if ($products) : ?>
					<table border="1" cellpadding="5">
						<tr>
							<th>ID</th>
							<th>Название</th>
							<th>Цена</th>
							<th>Корзина</th>
						</tr>
						<?php foreach ($products as $product) :  ?>
							<tr>
								<td><?php echo $product->product_id; ?></td>
								<td><?php echo $product->product_name; ?></td>
								<td><?php echo $product->product_price; ?></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="action" value="upd_cart" />
										<input type="hidden" name="product_id" value="<?php echo $product->product_id; ?>" />
										<input type="number" style="width:50px;" min="0" step="1" name="quantity" value="<?php echo $_SESSION['CART'][$product->product_id] ? $_SESSION['CART'][$product->product_id] : 0; ?>" />
										<button type="submit">В корзину</button>
									</form>
								</td>
							</tr>				
						<?php endforeach; ?>
					</table>
					<p align="center">Страница <?php echo $pg_num; ?> из <?php echo $pages; ?></p>
					<p align="center">
						<?php if ($pg_num > 1) : ?>
							<a href="?pg_num=<?php echo $pg_num - 1; ?>"> ◄ Назад</a>
						<?php endif; ?>
						||
						<?php if ($pg_num < $pages) : ?>
							<a href="?pg_num=<?php echo $pg_num + 1; ?>">Вперед ►</a>
						<?php endif; ?>
					</p>
				<?php else : ?>
					<p align="center">Нет данных, <a href="db_restart.php">перезапустить базу</a></p>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</body>
</html>