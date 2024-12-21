<!doctype html>
<html>
<head>
	<?php echo $__env->make('includes.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<script src="https://core.spreedly.com/iframe/iframe-v1.min.js"></script>
</head>
<body>
<div class="container">
    <header class="row"><?php echo $__env->make('includes.checkouthead', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?></header>
    <div id="main" class="row"><?php echo $__env->yieldContent('content'); ?></div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</body>
</html>