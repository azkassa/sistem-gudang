<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $__env->yieldContent('title', 'Sistem Gudang'); ?></title>
<link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>
<aside>
  <h1>Sistem Gudang</h1>
  <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'on' : ''); ?>">Ringkasan</a>
  <?php $__currentLoopData = config('gudang.menu'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $judul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(auth()->user()->izin($k, 'r') || auth()->user()->izin($k, 'c')): ?>
      <a href="<?php echo e(route($k.'.index')); ?>" class="<?php echo e(request()->routeIs($k.'.*') ? 'on' : ''); ?>"><?php echo e($judul); ?></a>
    <?php endif; ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <div class="who"><b><?php echo e(auth()->user()->nama); ?></b><br>
    <?php echo e(auth()->user()->role === 'admin' ? 'Pengelola' : 'User'); ?> ·
    <form method="post" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button type="submit">Keluar</button></form>
  </div>
</aside>
<main>
  <?php if(session('ok')): ?><div class="msg ok"><?php echo e(session('ok')); ?></div><?php endif; ?>
  <?php if(session('err')): ?><div class="msg err"><?php echo e(session('err')); ?></div><?php endif; ?>
  <?php echo $__env->yieldContent('content'); ?>
</main>
</body>
</html>
<?php /**PATH C:\laragon\www\sistem-gudang\resources\views/layouts/app.blade.php ENDPATH**/ ?>