<?php require 'includes/bootstrap.php'; $err = '';
$next = $_GET['next'] ?? $_POST['next'] ?? 'menu.php'; if (!in_array($next, ['menu.php', 'cart.php', 'checkout.php', 'orders.php', 'index.php'], true)) $next = 'menu.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { csrf_check();
  $s = $pdo->prepare('SELECT * FROM `user` WHERE email=?'); $s->execute([strtolower(trim($_POST['email'] ?? ''))]); $u = $s->fetch();
  if ($u && password_verify($_POST['password'] ?? '', $u['password_hash'])) { session_regenerate_id(true); $_SESSION['user'] = ['id' => $u['user_id'], 'name' => $u['name'], 'role' => $u['role']]; header("Location: $next"); exit; }
  $err = 'Invalid email or password.'; }
$title = 'Sign In'; include 'includes/header.php'; ?>
<main class="wrap sec narrow"><h1>Sign In</h1><?php if ($err): ?><div class="err"><?= e($err) ?></div><?php endif; ?>
<form method="post" class="form"><?= csrf_field() ?><input type="hidden" name="next" value="<?= e($next) ?>"><label>Email<input type="email" name="email" required maxlength="150"></label><label>Password<input type="password" name="password" required></label><button class="btn">Sign In</button></form>
<p>New here? <a class="link" href="register.php">Create an account</a></p></main><?php include 'includes/footer.php'; ?>
