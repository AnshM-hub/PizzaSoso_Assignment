<?php
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
require __DIR__ . '/../config/db.php';
const SIZE_ADD = ['Small' => 0, 'Medium' => 100, 'Large' => 200];
const CRUST_ADD = ['Thin' => 0, 'Thick' => 30, 'Stuffed' => 60];
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($v) { return 'Rs ' . number_format((float)$v, 2); }
function csrf() { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf() . '">'; }
function csrf_check() { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(403); exit('Invalid request.'); } }
function user() { return $_SESSION['user'] ?? null; }
function require_login() { if (!user()) { header('Location: login.php?next=' . urlencode(basename($_SERVER['PHP_SELF']))); exit; } }
function flash($m = null) { if ($m !== null) { $_SESSION['flash'] = $m; return null; } $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function cart_lines(PDO $pdo) {
    $lines = [];
    foreach ($_SESSION['cart'] ?? [] as $k => $it) {
        $p = $pdo->prepare('SELECT * FROM pizza WHERE pizza_id=? AND is_active=1'); $p->execute([$it['pizza_id']]);
        $pz = $p->fetch(); if (!$pz) continue;
        $tops = [];
        if ($it['toppings']) {
            $in = implode(',', array_fill(0, count($it['toppings']), '?'));
            $t = $pdo->prepare("SELECT * FROM topping WHERE topping_id IN ($in)"); $t->execute($it['toppings']); $tops = $t->fetchAll();
        }
        $unit = $pz['base_price'] + SIZE_ADD[$it['size']] + CRUST_ADD[$it['crust']] + array_sum(array_column($tops, 'price'));
        $lines[] = ['key' => $k, 'pizza' => $pz, 'it' => $it, 'tops' => $tops, 'unit' => $unit, 'sub' => $unit * $it['qty']];
    }
    return $lines;
}
function totals($lines, $type = 'Pickup') {
    $sub = array_sum(array_column($lines, 'sub'));
    $fee = ($type === 'Delivery' && $sub < 500) ? 50 : 0;
    return [$sub, $fee, $sub + $fee];
}
function pizza_card($p) { $img = $p['image_url'] ?: 'assets/img/pizza.svg'; ?>
<article class="card"><img src="<?= e($img) ?>" alt="<?= e($p['name']) ?>"><div class="card-b">
<div class="row"><h3><?= e($p['name']) ?></h3><b class="price"><?= money($p['base_price']) ?></b></div>
<p><?= e($p['description']) ?></p>
<div class="row"><a class="link" href="pizza.php?id=<?= (int)$p['pizza_id'] ?>">Customize</a>
<form method="post" action="cart.php"><?= csrf_field() ?><input type="hidden" name="action" value="add"><input type="hidden" name="pizza_id" value="<?= (int)$p['pizza_id'] ?>"><input type="hidden" name="size" value="Small"><input type="hidden" name="crust" value="Thin"><input type="hidden" name="qty" value="1"><button class="btn">Add</button></form></div>
</div></article>
<?php }
