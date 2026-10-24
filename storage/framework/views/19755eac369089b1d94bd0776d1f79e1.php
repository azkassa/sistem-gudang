<?php $__env->startSection('title', $cfg['title'].' - Sistem Gudang'); ?>

<?php $__env->startSection('content'); ?>
  <h2><?php echo e($cfg['title']); ?></h2>
  <p class="sub"><?php echo e($rows->count()); ?> data ditampilkan</p>
  <form class="bar" method="get" action="<?php echo e(route($modul.'.index')); ?>">
    <input class="grow" type="search" name="q" value="<?php echo e($q); ?>" placeholder="Cari data...">
    <?php if(isset($cfg['date'])): ?>
      <input type="date" name="d" value="<?php echo e($d); ?>" aria-label="Filter tanggal">
    <?php endif; ?>
    <button class="btn" type="submit">Cari</button>
    <?php if($q !== '' || $d !== ''): ?>
      <a class="btn" href="<?php echo e(route($modul.'.index')); ?>">Reset</a>
    <?php endif; ?>
    <?php if($bisa['c']): ?>
      <a class="btn pri" href="<?php echo e(route($modul.'.create')); ?>">+ <?php echo e($cfg['add']); ?></a>
    <?php endif; ?>
  </form>
  <div class="card">
  <?php if($rows->isEmpty()): ?>
    <div class="empty">Belum ada data<?php echo $bisa['c'] ? '. Klik “'.e($cfg['add']).'” untuk mulai.' : ' pada tanggal ini.'; ?></div>
  <?php else: ?>
    <table>
      <tr>
        <?php $__currentLoopData = $cfg['cols']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th><?php echo e($label); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <th></th>
      </tr>
      <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <?php $__currentLoopData = $cfg['cols']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <td><?php echo $__env->make('crud._cell', ['k' => $k, 'v' => $r->{$k}], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <td class="act">
            <?php if($bisa['u']): ?>
              <a class="btn sm" href="<?php echo e(route($modul.'.edit', $r->id)); ?>">Ubah</a>
            <?php endif; ?>
            <?php if($bisa['d']): ?>
              <form method="post" action="<?php echo e(route($modul.'.destroy', $r->id)); ?>" onsubmit="return confirm('Hapus data ini?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn sm del" type="submit">Hapus</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
  <?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-gudang\resources\views/crud/index.blade.php ENDPATH**/ ?>