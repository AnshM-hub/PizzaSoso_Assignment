<?php require 'includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { csrf_check(); $a = $_POST['action'] ?? '';
  if ($a === 'add') {
    $id = (int)($_POST['pizza_id'] ?? 0); $size = $_POST['size'] ?? ''; $crust = $_POST['crust'] ?? '';
    $qty = max(1, min(20, (int)($_POST['qty'] ?? 1)));
    $tops = array_values(array_unique(array_map('intval', (array)($_POST['toppings'] ?? [])))); sort($tops);
    $ok = $pdo->prepare('SELECT 1 FROM pizza WHERE pizza_id=? AND is_active=1'); $ok->execute([$id]);
    if ($ok->fetch() && isset(SIZE_ADD[$size]) && isset(CRUST_ADD[$crust])) {
      $key = md5("$id|$size|$crust|" . implode(',', $tops));
      if (isset($_SESSION['cart'][$key])) $_SESSION['cart'][$key]['qty'] = min(20, $_SESSION['cart'][$key]['qty'] + $qty);
      else $_SESSION['cart'][$key] = ['pizza_id' => $id, 'size' => $size, 'crust' => $crust, 'toppings' => $tops, 'qty' => $qty];
      flash('Added to cart.');
    } else flash('Could not add that item.');
  } elseif ($a === 'update') { $k = $_POST['key'] ?? ''; if (isset($_SESSION['cart'][$k])) $_SESSION['cart'][$k]['qty'] = max(1, min(20, (int)($_POST['qty'] ?? 1))); }
  elseif ($a === 'remove') { unset($_SESSION['cart'][$_POST['key'] ?? '']); }
  header('Location: cart.php'); exit; }
$title = 'Your Cart'; $lines = cart_lines($pdo); [$sub] = totals($lines);
include 'includes/header.php'; ?>
<main class="wrap sec"><h1>Your Cart</h1>
<?php if (!$lines): ?><p>Your cart is empty.</p><a class="btn" href="menu.php">Browse Menu</a><?php else: ?>
<div class="tbl"><?php foreach ($lines as $l): ?><div class="line"><div><b><?= e($l['pizza']['name']) ?></b><br><small><?= e($l['it']['size']) ?>, <?= e($l['it']['crust']) ?> crust<?= $l['tops'] ? ', ' . e(implode(', ', array_column($l['tops'], 'name'))) : '' ?></small></div>
<div><?= money($l['unit']) ?></div>
<form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="update"><input type="hidden" name="key" value="<?= e($l['key']) ?>"><input type="number" name="qty" min="1" max="20" value="<?= (int)$l['it']['qty'] ?>"><button class="btn ghost">Update</button></form>
<div><b><?= money($l['sub']) ?></b></div>
<form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="remove"><input type="hidden" name="key" value="<?= e($l['key']) ?>"><button class="link">Remove</button></form></div><?php endforeach; ?></div>
<p class="tot">Subtotal: <?= money($sub) ?></p><p><small>Delivery fee is shown at checkout.</small></p>
<a class="btn ghost" href="menu.php">Keep Shopping</a> <a class="btn" href="checkout.php">Checkout</a><?php endif; ?></main>
<?php include 'includes/footer.php'; ?>
