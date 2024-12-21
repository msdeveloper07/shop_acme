
<?php $__env->startSection('content'); ?>
	   <ul class="nav nav-tabs" id="myTabs" role="tablist">
	    <li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="false">API's Details</a></li>
	 	<li role="presentation" class=""><a href="#profile2" role="tab" id="profile-tab2" data-toggle="tab" aria-controls="profile2" aria-expanded="true">Orders</a></li>
	 	<li role="presentation" class=""><a href="#profile4" role="tab" id="profile-tab4" data-toggle="tab" aria-controls="profile4" aria-expanded="true">Sync Product</a></li>
	 	<li role="presentation" class=""><a href="#profile5" role="tab" id="profile-tab5" data-toggle="tab" aria-controls="profile5" aria-expanded="true">Shipping</a></li> 		  
	   </ul>
	   <div class="tab-content" id="myTabContent">
		<div class="tab-pane fade active in" role="tabpanel" id="home" aria-labelledby="home-tab">
			<?php echo $__env->make('pages/basic_login', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
		<div class="tab-pane fade" role="tabpane2" id="profile2" aria-labelledby="profile-tab2">
			<?php echo $__env->make('pages/shopify_orders', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
		<div class="tab-pane fade" role="tabpane4" id="profile4" aria-labelledby="profile-tab3">
			<?php echo $__env->make('pages/sync_product', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
		<div class="tab-pane fade" role="tabpane5" id="profile5" aria-labelledby="profile-tab5">
			<?php echo $__env->make('pages/shipping', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
	   </div>
		<div id="loading-image">
			<div class="alert alert-success">Saved Successfully</div>
		</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.in_app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>