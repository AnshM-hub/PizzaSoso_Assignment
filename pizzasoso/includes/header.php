<?php $cnt = array_sum(array_column($_SESSION['cart'] ?? [], 'qty')); $u = user(); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? 'Pizza SoSo') ?> | Pizza SoSo</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="nav"><div class="wrap navin"><a class="logo" href="index.php"><span class="mark"></span>Pizza <b>Soso</b></a>
<button class="burger" id="burger" aria-label="Toggle menu">&#9776;</button>
<nav id="links"><a href="menu.php">Menu</a><a href="index.php#deals">Deals</a><a href="index.php#delivery">Delivery</a><a href="index.php#about">About Us</a><a href="index.php#delivery">Location</a></nav>
<div class="navr"><?php if ($u): ?><a href="orders.php"><?= e($u['name']) ?></a><a href="logout.php">Sign Out</a><?php else: ?><a href="login.php">Sign In</a><?php endif; ?>
<a class="btn" href="cart.php">Items<?= $cnt ? " ($cnt)" : '' ?></a></div></div></header>
<?php if ($f = flash()): ?><div class="wrap flash"><?= e($f) ?></div><?php endif; ?>
