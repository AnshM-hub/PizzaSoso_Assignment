<?php require 'includes/bootstrap.php'; $title = 'Menu';
$q = trim($_GET['q'] ?? ''); $cat = $_GET['category'] ?? 'All'; $sort = $_GET['sort'] ?? 'popular';
$cats = ['All', 'Pizzas', 'Sides', 'Drinks', 'Desserts', 'Combos'];
$sorts = ['popular' => 'Most Popular', 'recent' => 'Most Recent', 'price' => 'Price: Low to High'];
if (!isset($sorts[$sort])) $sort = 'popular'; if (!in_array($cat, $cats, true)) $cat = 'All';
$order = ['popular' => 'cnt DESC, p.name', 'recent' => 'p.pizza_id DESC', 'price' => 'p.base_price ASC'][$sort];
$sql = "SELECT p.*, COALESCE(SUM(oi.quantity),0) cnt FROM pizza p LEFT JOIN orderitem oi ON oi.pizza_id=p.pizza_id WHERE p.is_active=1";
$args = [];
if ($q !== '') { $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)"; $args[] = $args[] = '%' . $q . '%'; }
if ($cat !== 'All') { $sql .= " AND p.category=?"; $args[] = rtrim($cat, 's') === 'Pizza' ? 'Pizzas' : $cat; }
$st = $pdo->prepare($sql . " GROUP BY p.pizza_id ORDER BY $order"); $st->execute($args); $items = $st->fetchAll();
function qs($o) { return '?' . http_build_query(array_merge($_GET, $o)); }
include 'includes/header.php'; ?>
<main class="wrap sec"><span class="pill">CRAVING SOMETHING DELICIOUS?</span><h1>Browse Our Hot Oven Menu</h1>
<form class="search" method="get"><input type="search" name="q" value="<?= e($q) ?>" placeholder="Search pizzas, sides, drinks..." maxlength="100"><input type="hidden" name="category" value="<?= e($cat) ?>"><input type="hidden" name="sort" value="<?= e($sort) ?>"></form>
<div class="chips"><?php foreach ($cats as $c): ?><a class="<?= $c === $cat ? 'on' : '' ?>" href="<?= e(qs(['category' => $c])) ?>"><?= e($c) ?></a><?php endforeach; ?></div>
<div class="chips"><small>SORT MENU BY</small><?php foreach ($sorts as $k => $l): ?><a class="<?= $k === $sort ? 'on' : '' ?>" href="<?= e(qs(['sort' => $k])) ?>"><?= e($l) ?></a><?php endforeach; ?></div>
<div class="grid4"><?php foreach ($items as $p) pizza_card($p); ?></div>
<?php if (!$items): ?><p class="center">Nothing found. Try a different search or category.</p><?php endif; ?></main>
<?php include 'includes/footer.php'; ?>
