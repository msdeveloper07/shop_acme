<!-- <p>App Installed Successfully.</p> -->
<p>Now You have to add your merchant account</p>
@if ($saved)
<div class="alert alert-success" role="alert">Saved Successfully</div>
@endif

{{ Form::open(array('url' => secure_url('swapp/main/get_merchant'), 'class' =>'form_login')) }}
<div class="form-group">
{{ Form::label('site_url', 'Site URL') }}
{{ Form::text('site_url',$frontEnd, array('placeholder' => 'Enter Site URL', 'class'=> 'form-control', 'id'=> 'site_url', 'required')) }}
</div>

<div class="form-group">
{{ Form::label('uname', 'API Key') }}
{{ Form::text('uname',$uname, array('placeholder' => 'Enter API Key', 'class'=> 'form-control', 'id'=> 'uname_', 'required')) }}
</div>

<div class="form-group">
{{ Form::label('psw', 'API Secret') }}
{{ Form::text('psw',$psw, array('placeholder' => 'Enter API Secret', 'class'=> 'form-control', 'id'=> 'psw_', 'required')) }}
</div>

<div class="form-group">
	<label for="checkout">Choose a checkout type:</label>
	<select name="checkout" id="checkout">
	  <option value="single">Single</option>
	  <option value="multistep">Multi Step</option>
	</select>
</div>	

{{ Form::hidden('shop', $shop) }}
{{ Form::submit('Save', array('class'=>'btn btn-default')) }}
{{ Form::close() }}