<?php require 'includes/bootstrap.php'; $errs = []; $v = ['name' => '', 'email' => '', 'phone' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') { csrf_check();
  foreach ($v as $k => $_) $v[$k] = trim($_POST[$k] ?? ''); $v['email'] = strtolower($v['email']); $pw = $_POST['password'] ?? '';
  if (strlen($v['name']) < 2 || strlen($v['name']) > 100) $errs[] = 'Name must be 2-100 characters.';
  if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL) || strlen($v['email']) > 150) $errs[] = 'Enter a valid email.';
  if ($v['phone'] !== '' && !preg_match('/^[0-9+\- ]{7,20}$/', $v['phone'])) $errs[] = 'Enter a valid phone number.';
  if (strlen($pw) < 8) $errs[] = 'Password must be at least 8 characters.';
  if ($pw !== ($_POST['confirm'] ?? '')) $errs[] = 'Passwords do not match.';
  if (!$errs) { try { $pdo->prepare("INSERT INTO `user`(name,email,password_hash,role,phone) VALUES(?,?,?,'customer',?)")->execute([$v['name'], $v['email'], password_hash($pw, PASSWORD_DEFAULT), $v['phone'] ?: null]);
      flash('Account created. Please sign in.'); header('Location: login.php'); exit;
    } catch (PDOException $e) { $errs[] = $e->getCode() === '23000' ? 'That email is already registered.' : 'Could not create account.'; } } }
$title = 'Register'; include 'includes/header.php'; ?>
<main class="wrap sec narrow"><h1>Create Account</h1><?php foreach ($errs as $m): ?><div class="err"><?= e($m) ?></div><?php endforeach; ?>
<form method="post" class="form"><?= csrf_field() ?><label>Name<input name="name" value="<?= e($v['name']) ?>" required maxlength="100"></label><label>Email<input type="email" name="email" value="<?= e($v['email']) ?>" required maxlength="150"></label><label>Phone<input name="phone" value="<?= e($v['phone']) ?>" maxlength="20"></label><label>Password (8+ characters)<input type="password" name="password" required></label><label>Confirm password<input type="password" name="confirm" required></label><button class="btn">Register</button></form></main>
<?php include 'includes/footer.php'; ?>
