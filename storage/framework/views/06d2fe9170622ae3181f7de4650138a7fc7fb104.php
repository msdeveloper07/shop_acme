
<?php $__env->startSection('content'); ?>

   <?php if($checkout_type == "multistep"): ?>
      <?php echo $__env->make('pages/multistep_checkout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
   <?php else: ?>
      <?php echo $__env->make('pages/single_checkout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
   <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.checkout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>