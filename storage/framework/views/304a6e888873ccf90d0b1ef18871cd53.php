<?php if($v === null || $v === ''): ?>
<span class="mute">–</span>
<?php elseif(str_starts_with($k, 'tanggal')): ?>
<?php echo e(\Illuminate\Support\Carbon::parse($v)->translatedFormat('d M Y')); ?>

<?php elseif(str_starts_with($k, 'jam_')): ?>
<?php echo e(substr($v, 0, 5)); ?>

<?php elseif(in_array($k, ['jenis', 'status', 'role'])): ?>
<span class="badge <?php echo e(strtolower($v)); ?>"><?php echo e(ucfirst($v)); ?></span>
<?php elseif($k === 'jumlah'): ?>
<b><?php echo e(number_format((int) $v, 0, ',', '.')); ?></b>
<?php else: ?>
<?php echo e($v); ?>

<?php endif; ?>
<?php /**PATH C:\laragon\www\sistem-gudang\resources\views/crud/_cell.blade.php ENDPATH**/ ?>