<div class="row">
   <div class="col-sm-12">
{{ Form::open(array('url' => secure_url('main/get_display_option'), 'class' =>'form_get_display_option form-horizontal')) }}
         <!-- <div class="form-group">
            {{ Form::label('business_name', 'Business Name', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('business_name', $business_name, array('placeholder' => 'Business Name', 'class'=> 'form-control', 'id'=> 'business_name', 'required')) }}
            </div>
         </div>

         <div class="form-group">
            {{ Form::label('dba_name', 'Dba Name', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
               {{ Form::text('dba_name', $dba_name, array('placeholder' => 'Dba Name', 'class'=> 'form-control', 'id'=> 'dba_name', 'required')) }}
            </div>
         </div>

         <div class="form-group">
            {{ Form::label('business_logo_url', 'Business Logo Url', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
               {{ Form::text('business_logo_url', $business_logo_url, array('placeholder' => 'Business Logo Url', 'class'=> 'form-control', 'id'=> 'business_logo_url', 'required')) }}
            </div>
         </div> -->

         <div class="form-group">
            {{ Form::label('payment_processor', 'Payment Processor', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('payment_processor', $processor_name, array('placeholder' => 'Payment Processor', 'class'=> 'form-control', 'id'=> 'payment_processor1', 'required')) }}
            </div>
         </div>

         <div class="form-group">
            {{ Form::label('selected_payment_processor_id_or_key', 'Stripe key', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
               {{ Form::text('selected_payment_processor_id_or_key', $processor_userid, array('placeholder' => 'Selected Payment Processor Id or key', 'class'=> 'form-control', 'id'=> 'selected_payment_processor_id_or_key1', 'required')) }}
            </div>
         </div>

         <div class="form-group">
            {{ Form::label('selected_payment_processor_password_or_secret_value', 'Stripe Secret', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
               {{ Form::text('selected_payment_processor_password_or_secret_value', $processor_password, array('placeholder' => 'Selected Payment Processor password or secret value', 'class'=> 'form-control', 'id'=> 'selected_payment_processor_password_or_secret_value1', 'required')) }}
            </div>
         </div>

         <div class="form-group">
            <div class="col-sm-12 mrch_btn">
               {{ Form::hidden('shop', $shop) }}
               {{ Form::submit('Save', array('class'=>'btn btn-default')) }}
            </div>
         </div>
         {{ Form::close() }}
      </form>
   </div>
</div>