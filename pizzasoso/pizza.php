<?php require 'includes/bootstrap.php';
$s = $pdo->prepare('SELECT * FROM pizza WHERE pizza_id=? AND is_active=1'); $s->execute([(int)($_GET['id'] ?? 0)]); $p = $s->fetch();
$title = $p ? $p['name'] : 'Not found';
if (!$p) { http_response_code(404); include 'includes/header.php'; echo '<main class="wrap sec"><h1>Pizza not found</h1><a class="btn" href="menu.php">Back to menu</a></main>'; include 'includes/footer.php'; exit; }
$tops = $pdo->query('SELECT * FROM topping ORDER BY name')->fetchAll();
include 'includes/header.php'; ?>
<main class="wrap sec two"><img class="cimg" src="<?= e($p['image_url'] ?: 'assets/img/pizza.svg') ?>" alt="<?= e($p['name']) ?>">
<form method="post" action="cart.php" id="cust" data-base="<?= e($p['base_price']) ?>"><?= csrf_field() ?><input type="hidden" name="action" value="add"><input type="hidden" name="pizza_id" value="<?= (int)$p['pizza_id'] ?>">
<h1><?= e($p['name']) ?></h1><p><?= e($p['description']) ?></p>
<h4>Size</h4><div class="opts"><?php foreach (SIZE_ADD as $k => $a): ?><label><input type="radio" name="size" value="<?= $k ?>" data-add="<?= $a ?>" <?= $k === 'Small' ? 'checked' : '' ?>> <?= $k ?><?= $a ? " (+$a)" : '' ?></label><?php endforeach; ?></div>
<h4>Crust</h4><div class="opts"><?php foreach (CRUST_ADD as $k => $a): ?><label><input type="radio" name="crust" value="<?= $k ?>" data-add="<?= $a ?>" <?= $k === 'Thin' ? 'checked' : '' ?>> <?= $k ?><?= $a ? " (+$a)" : '' ?></label><?php endforeach; ?></div>
<h4>Toppings</h4><div class="opts"><?php foreach ($tops as $t): ?><label><input type="checkbox" name="toppings[]" value="<?= (int)$t['topping_id'] ?>" data-add="<?= e($t['price']) ?>"> <?= e($t['name']) ?> (+<?= e($t['price']) ?>)</label><?php endforeach; ?></div>
<h4>Quantity</h4><input type="number" name="qty" value="1" min="1" max="20" id="qty">
<p class="tot">Total: Rs <span id="total"><?= number_format($p['base_price'], 2) ?></span></p><button class="btn">Add to Cart</button></form></main>
<?php include 'includes/footer.php'; ?>
