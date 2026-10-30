<?php require 'includes/bootstrap.php'; require_login(); $uid = user()['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') { csrf_check();
  $oid = (int)($_POST['order_id'] ?? 0); $r = (int)($_POST['rating'] ?? 0); $c = trim($_POST['comment'] ?? '');
  $o = $pdo->prepare("SELECT 1 FROM pizzaorder WHERE order_id=? AND user_id=? AND status='Completed'"); $o->execute([$oid, $uid]);
  if (!$o->fetch()) flash('You can only review your completed orders.');
  elseif ($r < 1 || $r > 5 || strlen($c) > 1000) flash('Rating must be 1-5 and comment under 1000 characters.');
  else { try { $pdo->prepare('INSERT INTO review(user_id,order_id,rating,comment) VALUES(?,?,?,?)')->execute([$uid, $oid, $r, $c ?: null]); flash('Thanks for your review!'); } catch (PDOException $e) { flash('This order already has a review.'); } }
  header('Location: orders.php'); exit; }
$title = 'My Orders';
$os = $pdo->prepare('SELECT o.*, p.payment_method, p.status pay_status, r.rating, r.comment FROM pizzaorder o LEFT JOIN payment p ON p.order_id=o.order_id LEFT JOIN review r ON r.order_id=o.order_id WHERE o.user_id=? ORDER BY o.order_id DESC');
$os->execute([$uid]); $orders = $os->fetchAll();
$is = $pdo->prepare('SELECT oi.*, pz.name, GROUP_CONCAT(t.name SEPARATOR ", ") tops FROM orderitem oi JOIN pizza pz ON pz.pizza_id=oi.pizza_id LEFT JOIN orderitem_topping ot ON ot.order_item_id=oi.order_item_id LEFT JOIN topping t ON t.topping_id=ot.topping_id WHERE oi.order_id=? GROUP BY oi.order_item_id');
include 'includes/header.php'; ?>
<main class="wrap sec narrow"><h1>My Orders</h1><?php if (!$orders): ?><p>No orders yet.</p><a class="btn" href="menu.php">Browse Menu</a><?php endif; ?>
<?php foreach ($orders as $o): $is->execute([$o['order_id']]); ?><section class="order"><div class="row"><h3>Order #<?= (int)$o['order_id'] ?></h3><span class="pill"><?= e($o['status']) ?></span></div>
<small><?= e($o['order_date']) ?> &middot; <?= e($o['order_type']) ?> &middot; <?= e($o['payment_method']) ?> (<?= e($o['pay_status']) ?>)</small>
<ul><?php foreach ($is->fetchAll() as $i): ?><li><?= (int)$i['quantity'] ?> x <?= e($i['name']) ?> (<?= e($i['size']) ?>, <?= e($i['crust']) ?><?= $i['tops'] ? ', ' . e($i['tops']) : '' ?>) - <?= money($i['subtotal']) ?></li><?php endforeach; ?></ul>
<b>Total: <?= money($o['total_amount']) ?></b>
<?php if ($o['rating']): ?><p>Your review: <?= (int)$o['rating'] ?>/5 <?= e($o['comment']) ?></p>
<?php elseif ($o['status'] === 'Completed'): ?><form method="post" class="form"><?= csrf_field() ?><input type="hidden" name="order_id" value="<?= (int)$o['order_id'] ?>"><label>Rating<select name="rating"><?php for ($n = 5; $n >= 1; $n--) echo "<option>$n</option>"; ?></select></label><label>Comment<input name="comment" maxlength="1000"></label><button class="btn">Submit Review</button></form><?php endif; ?></section><?php endforeach; ?></main>
<?php include 'includes/footer.php'; ?>
