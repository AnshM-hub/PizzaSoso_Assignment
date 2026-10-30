<?php require 'includes/bootstrap.php'; require_login();
$lines = cart_lines($pdo); if (!$lines) { flash('Your cart is empty.'); header('Location: menu.php'); exit; }
$v = ['order_type' => 'Pickup', 'contact_number' => '', 'delivery_address' => '', 'payment_method' => 'Card']; $errs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') { csrf_check();
  foreach ($v as $k => $_) $v[$k] = trim($_POST[$k] ?? '');
  if (!in_array($v['order_type'], ['Dine-in', 'Pickup', 'Delivery'], true)) $errs[] = 'Choose a valid order type.';
  if (!in_array($v['payment_method'], ['Card', 'Cash'], true)) $errs[] = 'Choose a valid payment method.';
  if (!preg_match('/^[0-9+\- ]{7,20}$/', $v['contact_number'])) $errs[] = 'Enter a valid contact number.';
  if ($v['order_type'] === 'Delivery' && (strlen($v['delivery_address']) < 5 || strlen($v['delivery_address']) > 255)) $errs[] = 'Enter a delivery address (5-255 characters).';
  if (!$errs) { [, , $total] = totals($lines, $v['order_type']); $paid = $v['payment_method'] === 'Card';
    try { $pdo->beginTransaction();
      $pdo->prepare('INSERT INTO pizzaorder(user_id,order_type,contact_number,delivery_address,status,total_amount) VALUES(?,?,?,?,?,?)')
          ->execute([user()['id'], $v['order_type'], $v['contact_number'], $v['order_type'] === 'Delivery' ? $v['delivery_address'] : null, $paid ? 'Confirmed' : 'Pending', $total]);
      $oid = (int)$pdo->lastInsertId();
      $oi = $pdo->prepare('INSERT INTO orderitem(order_id,pizza_id,quantity,unit_price,size,crust,subtotal) VALUES(?,?,?,?,?,?,?)');
      $ot = $pdo->prepare('INSERT INTO orderitem_topping(order_item_id,topping_id) VALUES(?,?)');
      foreach ($lines as $l) { $oi->execute([$oid, $l['pizza']['pizza_id'], $l['it']['qty'], $l['unit'], $l['it']['size'], $l['it']['crust'], $l['sub']]); $iid = (int)$pdo->lastInsertId(); foreach ($l['tops'] as $t) $ot->execute([$iid, $t['topping_id']]); }
      $pdo->prepare('INSERT INTO payment(order_id,payment_method,amount,status,paid_at,transaction_id) VALUES(?,?,?,?,?,?)')
          ->execute([$oid, $v['payment_method'], $total, $paid ? 'Paid' : 'Pending', $paid ? date('Y-m-d H:i:s') : null, $paid ? 'SIM-' . strtoupper(bin2hex(random_bytes(6))) : null]);
      $pdo->commit(); unset($_SESSION['cart']); flash("Order #$oid placed."); header('Location: orders.php'); exit;
    } catch (Throwable $e) { if ($pdo->inTransaction()) $pdo->rollBack(); $errs[] = 'Could not place your order. Please try again.'; } } }
$title = 'Checkout'; [$sub, $fee, $total] = totals($lines, $v['order_type']); include 'includes/header.php'; ?>
<main class="wrap sec narrow"><h1>Checkout</h1>
<?php foreach ($errs as $m): ?><div class="err"><?= e($m) ?></div><?php endforeach; ?>
<form method="post" class="form"><?= csrf_field() ?>
<label>Order type<select name="order_type"><?php foreach (['Dine-in', 'Pickup', 'Delivery'] as $o): ?><option <?= $v['order_type'] === $o ? 'selected' : '' ?>><?= $o ?></option><?php endforeach; ?></select></label>
<label>Contact number<input name="contact_number" value="<?= e($v['contact_number']) ?>" required maxlength="20"></label>
<label>Delivery address (Delivery only)<input name="delivery_address" value="<?= e($v['delivery_address']) ?>" maxlength="255"></label>
<label>Payment<select name="payment_method"><option value="Card" <?= $v['payment_method'] === 'Card' ? 'selected' : '' ?>>Card (simulated gateway, no card data collected)</option><option value="Cash" <?= $v['payment_method'] === 'Cash' ? 'selected' : '' ?>>Cash</option></select></label>
<p>Subtotal: <?= money($sub) ?><br>Delivery fee: <?= money($fee) ?> <small>(Rs 50 for delivery orders under Rs 500; update by re-selecting)</small></p>
<p class="tot">Total: <?= money($total) ?></p><button class="btn">Place Order</button></form></main>
<?php include 'includes/footer.php'; ?>
