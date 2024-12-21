<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Index</title>
<link rel="stylesheet" href="style.css" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ secure_asset('/public/css/front_page.css') }}">
</head>
<body>

<div class="app-installpage">
  <div class="container">
    <div class="logo-app"><a href="#"><img src="{{url('/public/img/app-logo.jpg')}}"></a></div>
      <section class="app-section">
         <h2>Kachyng  payments app</h2>
          {{ Form::open(array('url' => secure_url('shopify'), 'class' => 'form-inline')) }}
          <div class="form-group">
            <!--{{ Form::label('site_url', 'Site URL') }}-->
            <div class="input-group">
              {{ Form::text('site_url', '', array('placeholder' => 'Site URL (eg : myshopify.com)', 'required')) }}
              <!--<div class="input-group-addon">.myshopify.com</div>-->
            </div>
          </div>
        {{ Form::submit('Install App', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
      </section>
  </div>
</div>

</body>
</html>

