<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk - Sistem Gudang</title>
<link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body class="login">
<form class="login-box" method="post" action="<?php echo e(route('login.proses')); ?>">
  <?php echo csrf_field(); ?>
  <h1>Sistem Gudang</h1>
  <p>Masuk untuk melanjutkan.</p>
  <?php if(session('err')): ?><div class="msg err"><?php echo e(session('err')); ?></div><?php endif; ?>
  <?php if($errors->any()): ?>
    <div class="msg err"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><?php echo e($m); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
  <?php endif; ?>
  <label for="username">Username</label>
  <input id="username" name="username" value="<?php echo e(old('username')); ?>" autofocus required>
  <label for="password">Password</label>
  <input id="password" name="password" type="password" required>
  <button class="btn pri" type="submit">Masuk</button>
</form>
</body>
</html>
<?php /**PATH C:\laragon\www\sistem-gudang\resources\views/auth/login.blade.php ENDPATH**/ ?>