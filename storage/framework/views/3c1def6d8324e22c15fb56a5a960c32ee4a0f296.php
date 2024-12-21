<p>App Installed Successfully.</p>
<p>Now You have to add your merchant account</p>
<style>
	#checkout {
	    padding: 10px 10px;
	    width: 20%;
	}
</style>
<?php if($saved): ?>
<div class="alert alert-success" role="alert">Saved Successfully</div>
<?php endif; ?>

<?php echo e(Form::open(array('url' => secure_url('swapp/main/get_merchant'), 'class' =>'form_login'))); ?>

<div class="form-group">
<?php echo e(Form::label('site_url', 'Shopify Store URL')); ?>

<?php echo e(Form::text('site_url',$frontEnd, array('placeholder' => 'Enter Site URL', 'class'=> 'form-control', 'id'=> 'site_url', 'required'))); ?>

</div>

<div class="form-group">
<?php echo e(Form::label('uname', 'Kachyng Merchant API Key')); ?>

<?php echo e(Form::text('uname',$uname, array('placeholder' => 'Enter API Key', 'class'=> 'form-control', 'id'=> 'uname_', 'required'))); ?>

</div>

<div class="form-group">
<?php echo e(Form::label('psw', 'Kachyng Merchant API Secret')); ?>

<?php echo e(Form::text('psw',$psw, array('placeholder' => 'Enter API Secret', 'class'=> 'form-control', 'id'=> 'psw_', 'required'))); ?>

</div>

<div class="form-group">
<?php echo e(Form::label('tax', 'TAX')); ?>

<?php echo e(Form::number('tax',$tax, array('placeholder' => 'TAX', 'class'=> 'form-control', 'id'=> 'tax', 'required'))); ?>

</div>

<div class="form-group">
	<label for="checkout">Choose a checkout type:</label>
	<select name="checkout" id="checkout">
	  <option value="single" <?php echo e($checkout_type == 'single' ? 'selected="selected"' : ''); ?>>single</option>
	  <option value="multistep" <?php echo e($checkout_type == 'multistep' ? 'selected="selected"' : ''); ?>>multistep</option>
	</select>
</div>	

<?php echo e(Form::hidden('shop', $shop)); ?>

<?php echo e(Form::submit('Save', array('class'=>'btn btn-default'))); ?>

<?php echo e(Form::close()); ?>