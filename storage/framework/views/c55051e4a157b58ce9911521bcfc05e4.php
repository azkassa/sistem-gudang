<?php $__env->startSection('title', $cfg['title'].' - Sistem Gudang'); ?>

<?php $__env->startSection('content'); ?>
  <h2><?php echo e($id ? 'Ubah' : 'Tambah'); ?> <?php echo e(strtolower($cfg['title'])); ?></h2>
  <p class="sub">Isi data di bawah, lalu klik Simpan.</p>

  <?php if($errors->any()): ?>
    <div class="msg err"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><?php echo e($m); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
  <?php endif; ?>

  <form class="form" method="post" action="<?php echo e($id ? route($modul.'.update', $id) : route($modul.'.store')); ?>">
    <?php echo csrf_field(); ?>
    <?php if($id): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <?php $__currentLoopData = $cfg['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $tipe = $f['type'];
        $val = $tipe === 'password'
            ? ''
            : old($k, data_get($row, $k, ($tipe === 'date') ? today()->toDateString() : ''));
        if ($tipe === 'time') { $val = substr((string) $val, 0, 5); }
        $wajib = $tipe === 'password' ? ! $id : ($f['required'] ?? true);
      ?>
      <div class="row">
        <label for="<?php echo e($k); ?>"><?php echo e($f['label']); ?><?php echo e($wajib ? '' : ($tipe === 'password' ? ' (kosongkan jika tidak diganti)' : ' (opsional)')); ?></label>
        <?php if($tipe === 'enum'): ?>
          <select id="<?php echo e($k); ?>" name="<?php echo e($k); ?>">
            <?php $__currentLoopData = $f['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($o); ?>" <?php if((string) $val === (string) $o): echo 'selected'; endif; ?>><?php echo e(ucfirst($o)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        <?php elseif($tipe === 'ref'): ?>
          <select id="<?php echo e($k); ?>" name="<?php echo e($k); ?>" required>
            <option value="">Pilih...</option>
            <?php $__currentLoopData = $refs[$k]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($o->id); ?>" <?php if((string) $val === (string) $o->id): echo 'selected'; endif; ?>><?php echo e($o->label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        <?php else: ?>
          <input id="<?php echo e($k); ?>" name="<?php echo e($k); ?>" type="<?php echo e($tipe); ?>" value="<?php echo e($val); ?>"
                 <?php if($tipe === 'number'): ?> min="1" <?php endif; ?>
                 <?php if($tipe === 'password'): ?> minlength="6" autocomplete="new-password" <?php endif; ?>
                 <?php if($wajib): ?> required <?php endif; ?>>
        <?php endif; ?>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <button class="btn pri" type="submit">Simpan</button>
    <a class="btn" href="<?php echo e($bisaLihat ? route($modul.'.index') : route('dashboard')); ?>">Batal</a>
  </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-gudang\resources\views/crud/form.blade.php ENDPATH**/ ?>