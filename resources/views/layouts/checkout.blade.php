<!doctype html>
<html>
<head>
	@include('includes.head')
	<script src="https://core.spreedly.com/iframe/iframe-v1.min.js"></script>
</head>
<body>
<div class="container">
    <header class="row">@include('includes.checkouthead')</header>
    <div id="main" class="row">@yield('content')</div>
</div>
@include('includes.footer')
</body>
</html>