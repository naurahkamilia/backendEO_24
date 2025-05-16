<div class="header">
  <h5 class="mb-0">Dashboard Admin</h5>
  <form method="POST" action="<?php echo e(route('logout')); ?>">
    <?php echo csrf_field(); ?>
    <button class="btn btn-sm btn-light">
      <i class="bi bi-box-arrow-right"></i> Logout
    </button>
  </form>
</div>
<?php /**PATH C:\laragon\www\coba_laravel\resources\views/partials/header.blade.php ENDPATH**/ ?>