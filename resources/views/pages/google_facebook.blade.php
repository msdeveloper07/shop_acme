         <div class="row">
            <div class="col-sm-12">               
               <div class="page-header"><h1>Facebook Settings</h1></div>
               {{ Form::open(array('url' => secure_url('main/set_scripts'), 'class' =>'form_set_scripts form-horizontal')) }}
         

         <div class="form-group">
            {{ Form::label('api_key', 'API Key', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('api_key', $fbapikey, array('placeholder' => 'API key', 'class'=> 'form-control', 'id'=> 'fbapikey_', 'required')) }}
            </div>
         </div>

         <div class="form-group">
            {{ Form::label('secret_key', 'Secret Key', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('secret_key', $fbsecretkey, array('placeholder' => 'Secret Key', 'class'=> 'form-control', 'id'=> 'fbsecretkey_', 'required')) }}
            </div>
         </div>

         
         <div class="form-group">
            {{ Form::label('application_key', 'Application Id', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('application_key', $applicaton_id, array('placeholder' => 'Application ID', 'class'=> 'form-control', 'id'=> 'application_key_', 'required')) }}
            </div>
         </div>

         <div class="form-group">
            {{ Form::label('page_id', 'Page Id', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('page_id', $pageId, array('placeholder' => 'Page Id', 'class'=> 'form-control', 'id'=> 'page_id_', 'required')) }}
            </div>
         </div>

         <div class="form-group">
            {{ Form::label('pixel_id', 'Pixel Id', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('pixel_id', $pixel, array('placeholder' => 'Pixel Id', 'class'=> 'form-control', 'id'=> 'pixel_id_', 'required')) }}
            </div>
         </div>

                  <div class="page-header"><h1>Google Settings</h1></div>            
         <div class="form-group">
            {{ Form::label('client_id', 'Google merchant Client ID', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('client_id', $google_client_key, array('placeholder' => 'Google merchant Client ID', 'class'=> 'form-control', 'id'=> 'client_id_', 'required')) }}
            </div>
         </div>
            
         <div class="form-group">
            {{ Form::label('google_client_secret', 'Google Client Secret', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('google_client_secret', $google_client_secret, array('placeholder' => 'Google Client Secret', 'class'=> 'form-control', 'id'=> 'google_client_secret_', 'required')) }}
            </div>
         </div>

         <div class="form-group">
            {{ Form::label('google_pixel', 'Google Pixel', array('class' => 'col-sm-4 control-label')) }}
            <div class="col-sm-8">
            {{ Form::text('google_pixel', $google_pixel, array('placeholder' => 'Google Pixel', 'class'=> 'form-control', 'id'=> 'google_pixel_', 'required')) }}
            </div>
         </div>                 
                  
            </div>
         </div>
        <div class="form-group">
          <div class="col-sm-12 mrch_btn1">
            {{ Form::hidden('shop', $shop) }}
            {{ Form::submit('Save', array('class'=>'btn btn-default')) }}
          </div>
        </div>
         {{ Form::close() }}