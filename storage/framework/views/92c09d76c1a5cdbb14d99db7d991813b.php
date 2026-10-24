<?php $__env->startSection('content'); ?>
  <h2>Ringkasan hari ini</h2>
  <p class="sub"><?php echo e(now()->translatedFormat('d F Y')); ?></p>
  <div class="stats">
    <div class="stat"><b><?php echo e(number_format($masukHariIni, 0, ',', '.')); ?></b><span>Barang masuk hari ini</span></div>
    <div class="stat"><b><?php echo e(number_format($eksporHariIni, 0, ',', '.')); ?></b><span>Barang diekspor hari ini</span></div>
    <div class="stat"><b><?php echo e($hadir); ?></b><span>Karyawan hadir</span></div>
  </div>
  <div class="bar">
    <?php if(auth()->user()->izin('supply', 'c')): ?>
      <a class="btn pri" href="<?php echo e(route('supply.create')); ?>">+ Catat supply</a>
    <?php endif; ?>
    <a class="btn <?php echo e(auth()->user()->izin('supply', 'c') ? '' : 'pri'); ?>" href="<?php echo e(route('absensi.create')); ?>">+ Isi daftar hadir</a>
  </div>
  <h3>Stok barang saat ini</h3>
  <div class="card">
  <?php if($stok->isEmpty()): ?>
    <div class="empty">Belum ada barang. Tambahkan di menu Data Barang.</div>
  <?php else: ?>
    <table>
      <tr><th>Kode</th><th>Barang</th><th>Total masuk</th><th>Total ekspor</th><th>Stok</th></tr>
      <?php $__currentLoopData = $stok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php ($masuk = (int) $r->masuk); ?> <?php ($ekspor = (int) $r->ekspor); ?>
        <tr>
          <td><?php echo e($r->kode); ?></td><td><?php echo e($r->nama); ?></td>
          <td><?php echo e(number_format($masuk, 0, ',', '.')); ?></td>
          <td><?php echo e(number_format($ekspor, 0, ',', '.')); ?></td>
          <td><b><?php echo e(number_format($masuk - $ekspor, 0, ',', '.')); ?></b> <?php echo e($r->satuan); ?></td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
  <?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-gudang\resources\views/dashboard.blade.php ENDPATH**/ ?>