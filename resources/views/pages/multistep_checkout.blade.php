@extends('layouts.checkout')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<script src="https://core.spreedly.com/iframe/iframe-v1.min.js"></script>
<link rel="stylesheet" href="{{ secure_asset('/public/css/custom_checkout.css').'?t='.time() }}">

<body onload="onload()">
   <div id="content" class="checkout-page">
   <div class="container">
      <!--Right Section-->
      <div id="primary">
      {{ Form::open(array('url' => secure_url('swapp/checkout/shipping'), 'class' =>'form kachyng_checkout', 'id' =>'payment-form', 'onsubmit' => 'tokenizeCard1(); return false;')) }}
      <input type="hidden"  name="payment_method_token" id="payment_method_token" value="">
      <input type="hidden"  name="usertype" id="usertype" value="{{$user_type}}">
      <input type="hidden"  name="customer_ID" id="customerID" value="{{@$customer_id}}">
      <input type="hidden"  name="order_id" id="order_id" value="{{$order_id}}">
      <div id="order_review">
      <table class="product-table">
      <tbody>
      <?php
         // echo"<pre>"; print_r($cart_datas); die;
         ?>
      @foreach ($cart_datas as $cart_data)
      @if(array_key_exists('image',$cart_data))
      <tr class="product">
      <td class="product__image">
      <div class="product-thumbnail">
      <div class="product-thumbnail__wrapper">
      <div class="img-with-quantity" >
      <div class="prdct-img"><div class="thubnail-img-width"><img class="product-thumbnail__image" src="{{$cart_data['image']}}">
      <span class="quantity-digits">{{ $cart_data['quantity'] }}</span></div>
      <span id="pname" attr_pid="{{@$cart_data['product_id']}}">{{@$cart_data['productTitle']}}</span>
      <span id="pdescription">{{@$cart_data['productDES']}}</span>
      </div>
      <span class="product-thumbnail__quantity" style="display: none;">{{ $cart_data['quantity'] }}</span> 
      @if(array_key_exists('product_price',$cart_data))
      <span class="product__price">
      <strong>{{$currency_symbol}} {{@$cart_data['product_price']}}</strong>
      </span>
      @endif
      </div>
      </div>
      </div>
      </td>
      </tr>
      @endif
      @endforeach
      </tbody>
      </table>
      <!--   <div class="quantity-main">
         <div class="quantity-sec">
         <strong>Quantity : </strong><span><strong>{{ $cart_data['quantity'] }}</strong></span>
         </div>
         </div> -->
      <table class="shop_table">
      <tr class="tax_line" >
      <th>Tax</th>
      <td>
      <strong id="show_tax_line">
      <span class="woocommerce-Price-currencySymbol" >{{$currency_symbol}}</span>
      {{$tax}}
      </strong>
      </td>
      </tr>
      <tbody>
      <tr class="cart-subtotal">
      <th><strong>Subtotal</strong></th>
      <td><strong><span class="woocommerce-Price-currencySymbol">{{$currency_symbol}}</span> {{$total_price }}</strong></td>
      </tr>
      <tr class="cart_item">
      <th class="product-name"><strong>Shipping</strong></th>
      <td class="product-total">
      <span id="showID" >
      @if($cart_data['shipping_rates'] != 0) 
      <strong><span class="woocommerce-Price-currencySymbol">{{$currency_symbol}}</span>
      <span id="shipp_price"> 
      {{round($cart_data['shipping_rates'], 2)}}
      </span>
      </strong>
      @else
      <strong>
      <span id="shipp_price">
      <strong>Calculated at next step</strong>
      </span>
      </strong>  
      @endif
      </span>
      </td>
      </tr>
      </tbody>
      <tfoot>
      
      <tr class="discount_line" style="display: none;">
      <th>Discount</th>
      <td>
      <strong id="discount_line_1">
      <span class="woocommerce-Price-currencySymbol" >{{$currency_symbol}}</span>
      0.00
      </strong>
      </td>
      </tr>
      <tr class="shipping_method" style="display: none;">
      <th>Shipping method</th>
      <td>
      <strong id="shipping_method_line" class="shipping_method_line_dek">
      {{@$cart_data['h_shipping_method']}}
      </strong>
      </td>
      </tr>
      <tr class="order-total">
      <th>Total</th>
      <td>
      <strong id="old_total_pri"><span class="woocommerce-Price-currencySymbol" >{{$currency_symbol}}</span> {{ number_format($cart_data['shipping_rates'] + $total_price, 2) }}</strong>
      <span id="showID1" style="display:none">
      <strong><span class="woocommerce-Price-currencySymbol"> $</span>
      <span id="updated_price"> </span>
      </strong> 
      </span>
      </td>
      </tr>
      </tfoot>
      </table>
      <!-- <a class="step__footer__previous-link" href="https://{{$shop}}/cart">
      <svg focusable="false" aria-hidden="true" class="icon-svg icon-svg--color-accent icon-svg--size-10 previous-link__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
      <path d="M8 1L7 0 3 4 2 5l1 1 4 4 1-1-4-4"></path>
      </svg>
      <span class="step__footer__previous-link-content">Return to cart</span>
      </a> -->
      </div><!--End Right Section-->
      <!--Left-section-->
      <div class="col2-set" id="customer_details">
      <div class="col-1">
      <!--Logo-sec-->
      <!--Breadcrmbs-->                          
      <div class="site-branding log-desktop">
      <div class="beta site-title">
      <a href="https://{{$shop}}" rel="home">
      {{$store_name}}
      </a>
      </div>
      <div class="stepwizard">
         <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step"> 
               <a href="https://{{$shop}}/cart" type="button" class="btn btn-default" >Cart <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
            </div>
            
            <div class="stepwizard-step"> 
               <a href="javascript:void(0)" type="button" class="btn btn-default btn-success">Information <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
            </div>
            <div class="stepwizard-step"> 
               <a href="#step-2" type="button" class="btn btn-default" >Shipping <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
            </div>
            <div class="stepwizard-step"> 
               <a href="#step-3" type="button" class="btn btn-default" >Payment <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
            </div>
         </div>
      </div>
      </div>

      <section class="round-border">
         <div class="top-header">
            <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__icon">
               <path d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z"></path>
            </svg>
            <p href="#collapse1" class="nav-toggle">Show Order Summery <i class="fa fa-chevron-down" aria-hidden="true"></i></p>
            @foreach ($cart_datas as $cart_data)
            <?php
               // echo"<pre>"; print_r($cart_data);  die;
               ?>
            <strong href="" class="nav-price">{{$currency_symbol}}
            {{ number_format(@$cart_data['shipping_rates'] + @$cart_data['price'], 2) }}
            </strong>
         </div>
         <div id="collapse1" style="display:none">
            <div id="order_review">
               <table class="product-table">
                  <tbody>
                     <?php
                        // echo"<pre>"; print_r($cart_data['image']);  die;
                        ?>
                     <tr class="product mobile-view">
                        <td class="product__image">
                           <div class="product-thumbnail">
                              <div class="product-thumbnail__wrapper">
                                 <div class="prdct-img-mobile">
                                    <div class="thubnail-img-width"><img class="product-thumbnail__image" src="{{$cart_data['image']}}"><span class="quantity-digits">{{@$cart_data['quantity'] }}</span></div>
                                    <span id="pname" attr_pid="{{@$cart_data['product_id']}}">{{@$cart_data['productTitle']}}</span>
                                    <span id="pdescription">{{@$cart_data['productDES']}}</span>
                                 </div>
                                 @if(array_key_exists('product_price',$cart_data))
                                 <span class="product__price">
                                 <strong>{{$currency_symbol}} {{@$cart_data['product_price']}}</strong>
                                 </span>
                                 @endif
                                 <!--<span class="product-thumbnail__quantity">{{$cart_data['quantity'] }}</span>-->
                              </div>
                           </div>
                        </td>
                        <td class="product__description">
                           <span></span>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
               <!--  <div class="quantity-main">
                  <div class="quantity-sec">
                  <strong>Quantity : </strong><span><strong>{{@$cart_data['quantity'] }}</strong></span>
                  </div>
                  </div> -->
               <table class="shop_table">
                     <tr class="tax_line">
                        <th>Tax</th>
                        <td>
                           <strong id="show_tax_line">
                           <span class="woocommerce-Price-currencySymbol" >{{@$currency_symbol}}</span> {{$tax}}
                           </strong>
                        </td>
                     </tr>
                  <tbody>
                     <tr class="cart-subtotal">
                        <th><strong>Subtotal</strong></th>
                        <td>
                           <strong>
                           <span class="woocommerce-Price-currencySymbol">{{$currency_symbol}}</span>{{$total_price }}
                           </strong>
                        </td>
                     </tr>
                     <tr>
                        <th><strong>Shipping</strong></th>
                        @if(@$cart_data['shipping_rates'] != 0) 
                        <td><strong><span class="woocommerce-Price-currencySymbol">{{@$currency_symbol}}</span>
                           <span id="shipp_price"> 
                           {{round($cart_data['shipping_rates'], 2)}}
                           </span>
                           </strong>
                        </td>
                        @else
                        <td>
                           <span id="shipp_price">
                           <strong>Calculated at next step</strong>
                           </span>
                        </td>
                        @endif
                     </tr>
                     <tr class="discount_line" style="display: none;">
                        <th>Discount</th>
                        <td>
                           <strong id="discount_line_1">
                           <span class="woocommerce-Price-currencySymbol" >{{@$currency_symbol}}</span>
                           0.00
                           </strong>
                        </td>
                     </tr>
                     <tr class="shipping_method" style="display: none;">
                        <th>Shipping method</th>
                        <td>
                           <strong id="shipping_method_line" class="shipping_method_line_mob">
                           {{@$cart_data['h_shipping_method']}}
                           </strong>
                        </td>
                     </tr>
                  </tbody>
                  <tfoot>
                     <tr class="order-total">
                        <th>Total</th>
                        <td><strong>
                           <span class="woocommerce-Price-currencySymbol">{{@$currency_symbol}}</span> {{ number_format(@$cart_data['shipping_rates'] + @$total_price, 2) }}</strong> 
                        </td>
                     </tr>
                  </tfoot>
               </table>
            </div>
      </section>

      <br>
      <!-- login with facebook -->
      @empty($customer_id)
      <div id="status"></div>
      <!-- Facebook login or logout button -->
      <!--  <div class="login_fb loginBtn loginBtn--facebook"><a href="javascript:void(0);" onclick="fbLogin()" id="fbLink">Login with Facebook</a></div>or Checkout As Guest</span> -->
      <!-- Display user profile data -->
      <div id="userData"></div><br>
      @endempty
      <!-- login with facebook -->

      
      <div class="panel-heading">
          <h5 class="panel-title">Contact information 
            @if (($user) == 'guest')
               <span class="account-login"> Already have an account? <a href="https://{{$shop}}/account/login">Log in</a></span>
            @endif
          </h5>
      </div>
      

      <p class="group section__content group form-row">
      <label >Email<span style="color:red;"> *</span></label>
      @if (($user) != 'guest')
      {{Form::email('customer_email', $customer_data['customer_email'], array('class' => 'customer_email','id' => 'customer_email', 'placeholder' => 'Email', 'required', 'readonly'))}}
      @else
      {{Form::email('customer_email', $customer_data['customer_email'], array('class' => 'customer_email','id' => 'customer_email', 'placeholder' => 'Email', 'required'))}}
      @endif
      </p>
      <div class="section__header">
      <div class="contact-information-inner margin-botton-20">
         <div class="radio__input">
            <input type="checkbox" id="shipping-val-6" name="shipping-val-6" value="" checked="check">
         </div>
         <label class="radio__label" aria-hidden="true" for="">
            <span class="radio__label__primary">Keep me up to date on news and exclusive offers </span>
            <span class="radio__label__accessory"></span>
         </label>
      </div>
      </div>
      <!--
         old place Card details
         -->
      <!--Billing Address-->
      <div class="panel-heading">
      <h5 class="panel-title margin-top-35">Shipping address</h5>
      <div class="woocommerce-billing-fields__field-wrapper">
      <div class="form-row">
         <div class="form-group col-md-6">
            <p class="form-row form-row-first group" id="billing_first_name_field">
            <label>First Name<span style="color:red;"> *</span></label>
            {{Form::text('customer_first_name', $customer_data['cus_add_first_name'], array('class' => 'customer_first_name tile-case','id' => 'customer_first_name', 'placeholder' => 'First Name'))}}
            </p>
         </div>
         <div class="form-group col-md-6">
            <p class="form-row form-row-last group" id="billing_last_name_field">
            <label>Last Name<span style="color:red;"> *</span></label>
            {{Form::text('customer_last_name', $customer_data['cus_add_last_name'], array('class' => 'customer_last_name customer_last_check tile-case', 'id' => 'customer_last_name_test', 'placeholder' => 'Last Name'))}}
            </p>
         </div>
      </div>
      
      <p class="group form-row form-row-wide address-field validate-required" id="billing_address_1_field">
      <label>Address<span style="color:red;"> *</span></label>
      {{Form::text('cus_add_address1', $customer_data['cus_add_address1'], array('class' => 'cus_add_address1 tile-case', 'id' => 'cus_add_address1', 'placeholder' => 'Address'))}}
      </p>
      <p class="group form-row form-row-wide address-field" id="billing_address_2_field" >
      <label>Apartment, suite, etc. (optional)</label>
      {{Form::text('cus_add_address2', $customer_data['cus_add_address2'], array('class' => 'cus_add_address2 tile-case','id' => 'cus_add_address2', 'placeholder' => 'Apartment, suite, etc. (optional)'))}}
      </p>
      <p class="form-row form-row-wide address-field" id="billing_city_field" data-priority="70">
      <label>City<span style="color:red;"> *</span></label>
      {{Form::text('cus_add_city', $customer_data['cus_add_city'], array('class' => 'cus_add_city tile-case','id' => 'cus_add_city', 'placeholder' => 'City'))}}
      </p>
      <p class="group form-row form-row-wide 2" id="billing_phone_field" style="display:none;">
      <label>Mobile number</label>
      {{Form::text('customer_phone', $customer_data['cus_add_phone'], array('class' => 'customer_phone', 'id' => 'customer_phone', 'placeholder' => 'Mobile number'))}}
      </p>
      <div class="shipping_class">  
      <div class="row pt-0">
      
      
      <div class="col-md-4 zip-fld pr-0">
      <div class="form-group form-group-default input-group fw-100">
      <label class="">Country/Region<span style="color:red;"> *</span></label>
      <input name="country" type="text" class="form-control usd" id="country" value="{{@$cart_data['h_country']}}" disabled>
      </div>
      </div>
      @if($checkout_type == "multistep")
         <div class="row" style="width: 100%">
            <div class="col-md-6">
               <div class="form-group form-group-default form-group-default-select2">
               <label class="">State<span style="color:red;"> *</span></label>
               <div class="state-main-top">
                  <select name="cus_add_province" class="full-width" data-placeholder="Select State" id="state" data-init-plugin="select2">
                  </select>
               </div>
               </div>
            </div>
            <div class="col-md-6" style="padding: 0px;">
               <label>ZIP code<span style="color:red;"> *</span></label>
               <input name="zipcode" type="tel" class="form-control usd" id="zip_code" value="{{@$cart_data['h_zipcode']}}" style="border-radius: 4px;">
            </div>
         </div>  
      @else
         <div class="form-row">
            <div class="form-group col-md-6" style="padding: 0px;">
               <div class="form-group form-group-default form-group-default-select2">
               <label class="">State<span style="color:red;"> *</span></label>
               <input name="state" type="text" class="form-control usd" id="state" value="{{@$cart_data['h_state']}}" disabled>
               </div>
            </div>
            <div class="form-group col-md-6" style="padding: 0px;">
               <div class="form-input-group">
               <label>ZIP code<span style="color:red;"> *</span></label>
               <input name="zipcode" type="tel" class="form-control usd" id="zip_code" value="{{@$cart_data['h_zipcode']}}" disabled style="border-radius: 4px;">
               </div>
            </div>
         </div>
      @endif 
      <!--Ship to a different address-->
      <!--
         OLD PLACE
         -->
      <!--End Right Section-->
      <!-- <p class="m-t-10">All transactions are secure and encrypted.</p> -->
      <div class="step__footer mobfotr">
      <input id="submit-add-card-button" type="submit" value="Pay now" disabled style="display: none;"><br>
      <input type="button" id="continue-shipping" value="Continue to shipping" style="display: none;">
      <a href="https://{{$shop}}/cart"><i class="fa fa-chevron-left" aria-hidden="true"></i> Return to Cart</a>

      </div> 
      <div class="step__footer">
      <span class="btn btn-default" id="submit-add-card-button-mob" >Pay now</span>
      <!-- <a id="one_step_back" class="step__footer__previous-link" href="https://{{$shop}}/cart">
      <svg focusable="false" aria-hidden="true" class="icon-svg icon-svg--color-accent icon-svg--size-10 previous-link__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
      <path d="M8 1L7 0 3 4 2 5l1 1 4 4 1-1-4-4"></path>
      </svg>
      <span class="step__footer__previous-link-content">Return to cart</span>
      </a> -->
      </div>
      <div class="" id="ship_err_msg" style="display:none;"></div>
      <div class="card-body" id="shipping_carriers" onkeyup='saveValue(this);'> 
      </div>
      </div>
      </div>
      </div>
      </div>
      </div>
      @include('pages/checkoutinfo')
      {{Form::hidden('raw_data', $raw, array('id' => 'raw_data'))}}
      {{Form::hidden('user', $user, array('id' => 'user'))}}
      {{ Form::close() }}
      </div>
      <!-- #primary -->
      </div>
      </div>
   </div>
</body>
@if (($user) != 'guest')
@if ($saved_cards)
<script>
   $(document).ready(function(){
     $("#new_cc").hide();
     $("#cvv_saved").show();
   });
</script>
@endif
@endif  
@if($checkout_type == "multistep" && $user == 'guest')
<script>
   $(document).ready(function(){
     $("#submit-add-card-button").hide();
     $(".shipping-section").hide();
     $(".section-payment-info").hide();
     $("#continue-shipping").show();
     if(localStorage.getItem("customer_email") != "" && localStorage.getItem("customer_email") != "undefined")
     {
         $('#customer_email').val(localStorage.getItem("customer_email"));
     }
     if(localStorage.getItem("customer_first_name") != "" && localStorage.getItem("customer_first_name") != "undefined")
     {
         $('#customer_first_name').val(localStorage.getItem("customer_first_name"));
     }
     if(localStorage.getItem("customer_last_name") != "" && localStorage.getItem("customer_last_name") != "undefined")
     {
         $('#customer_last_name_test').val(localStorage.getItem("customer_last_name"));
     }
     if(localStorage.getItem("cus_add_address1") != "" && localStorage.getItem("cus_add_address1") != "undefined")
     {
         $('#cus_add_address1').val(localStorage.getItem("cus_add_address1"));
     }
     if(localStorage.getItem("cus_add_address2") != "" && localStorage.getItem("cus_add_address2") != "undefined")
     {
         $('#cus_add_address2').val(localStorage.getItem("cus_add_address2"));
     }
     if(localStorage.getItem("customer_phone") != "" && localStorage.getItem("customer_phone") != "undefined")
     {
         $('#customer_phone').val(localStorage.getItem("customer_phone"));
     }
     if(localStorage.getItem("zip_code") != "" && localStorage.getItem("zip_code") != "undefined")
     {
         $('#zip_code').val(localStorage.getItem("zip_code"));
     }
     if(localStorage.getItem("cus_add_city") != "" && localStorage.getItem("cus_add_city") != "undefined")
     {
         $('#cus_add_city').val(localStorage.getItem("cus_add_city"));
     }
   });
</script>
@endif


@if($checkout_type == "multistep")
<script>
   $(document).ready(function(){
     $("#submit-add-card-button").hide();
     $(".shipping-section").hide();
     $(".section-payment-info").hide();
     $("#continue-shipping").show();   
      $('#continue-shipping').on('click',function(e) {
        e.preventDefault();
         //jQuery("#shipping_package").trigger('click');
         //$('input[name=shipping_package]').trigger("change");
         getPackages();
         var customer_email = $("#customer_email");
         var customer_first_name = $("#customer_first_name");
         var customer_last_name = $("#customer_last_name_test");
         var cus_add_address1 = $("#cus_add_address1");
         var cus_add_city = $("#cus_add_city");
         var country_to_state = $("#country");
         var state_select = $("#state");
         var billing_postcode_field = $("#zip_code");
         if (customer_email.val().length == 0) {
           border_function(customer_email);
           // return false;
         }else{
            customer_email.removeAttr('style');
         } 
         if(customer_first_name.val().length < '2' ){
            border_function(customer_first_name);
            // return false;
         }else{
            customer_first_name.removeAttr('style');
         }
         if(customer_last_name.val().length < '2'){
            border_function(customer_last_name);
            // return false;
         }else{
            customer_last_name.removeAttr('style');
         }
         if(cus_add_address1.val().length == '0'){
            border_function(cus_add_address1);
            // return false;
         }else{
            cus_add_address1.removeAttr('style');
         }
         if(cus_add_city.val().length == 0){
            border_function(cus_add_city);
            // return false;
         }else{
            cus_add_city.removeAttr('style');
         }
         if(country_to_state.val().length == 0){
            border_function(country_to_state);
            // return false;
         }else{
            country_to_state.removeAttr('style');
         }
         if(state_select.val().length == 0){
            border_function(state_select);
            // return false;
         }else{
            state_select.removeAttr('style');
         }
         if(billing_postcode_field.val().length == 0){
            border_function(billing_postcode_field);
            // return false;
         }else{
            billing_postcode_field.removeAttr('style');
         }
         if(customer_email.val().length != "" && customer_first_name.val().length != "" && customer_last_name.val().length != "" && cus_add_address1.val().length != "" && cus_add_city.val().length != "" && country_to_state.val().length != "" && state_select.val().length != "" && billing_postcode_field.val().length != ""){
              $("#customer_details .col-1").hide();
              $(".shipping-section").show();
              var add1 = $("#cus_add_address1").val();
              var add2 = $("#cus_add_address2").val();
              var ct = $("#cus_add_city").val();
              var country = $("#country").attr("value");
              // var st = $("#state").attr("value");
              var st = $('#state').find(":selected").text();
              // var zc = $("#zip_code").attr("value");
              var zc = $("#zip_code").val();
              $(".address.address--tight").text(add1+', ('+add2+'); '+ct+', '+st+', '+zc);
              $("bdo").text($('#customer_email').val());
              localStorage.setItem("customer_email", $('#customer_email').val());
              localStorage.setItem("customer_first_name", $('#customer_first_name').val());
              localStorage.setItem("customer_last_name", $('#customer_last_name_test').val());
              localStorage.setItem("cus_add_address1", $('#cus_add_address1').val());
              localStorage.setItem("cus_add_address2", $('#cus_add_address2').val());
              localStorage.setItem("customer_phone", $('#customer_phone').val());
              localStorage.setItem("zip_code", $('#zip_code').val());
              localStorage.setItem("cus_add_city", $('#cus_add_city').val());

               var totalPrice = "<?php echo $total_price; ?>";
               var currencySymbol = "<?php echo $currency_symbol; ?>";
               var n1 = parseFloat(totalPrice);
               var n2 = parseFloat($('input[name=shipping-val]:checked').val());
               var totalprice = n1 + n2;
               $('#total_mob').text(currencySymbol+' '+totalprice.toFixed(2));
               $('#shipp_price strong').text("$ "+$('input[name=shipping-val]:checked').val());
               if($('input[name=shipping-val]:checked').val() == 0)
               {
                  $('#shipp_price strong').text('Free');
               }
               else
               {
                $('#shipp_price strong').text(currencySymbol+' '+parseFloat($('input[name=shipping-val]:checked').val()));
               }
               $('#old_total_pri').text(currencySymbol+' '+totalprice.toFixed(2));
         }
      });
   });
   
   $('#back-ship, #backtoship, .review-change_ship_add').on('click',function(e) {
     e.preventDefault();
     $(".shipping-section").show();
     $(".section-payment-info").hide();
     $("#customer_details .col-1").hide(); 
     $("#submit-add-card-button").hide(); 
   });
   
   $('#back-info, #back-info-page, #backtoinfo, .review-change_value span, .review-change_shipadd span').on('click',function(e) {
      e.preventDefault();
      $(".shipping-section").hide();
      $(".section-payment-info").hide();
      $("#customer_details .col-1").show(); 
      $("#submit-add-card-button").hide(); 
   });

</script>
@endif
<script type="text/javascript">
   $(".return-btn-sec-shipping .breadcrumb__link-nfo").on('click',function(){
     $("#customer_details .col-1").show();
     $(".shipping-section").hide();
   });
   
   $(".return-btn-sec .breadcrumb__link-shipping").on('click',function(){
     $("#customer_details.col-1").hide();
     $(".section-payment-info").hide();
     $(".shipping-section").show();
   });
   
   $(".breadcrumb__link-nfo").on('click',function(){
     $("#customer_details .col-1").show();
     $(".shipping-section").hide();
   });
   
   $(".breadcrumb__link-shipping").on('click',function(){
     $("#customer_details.col-1").hide();
     $(".section-payment-info").hide();
     $(".shipping-section").show();
   });

   $("input[name=shipping-val]").change(function(){
      var totalPrice = "<?php echo $total_price; ?>";
      var currencySymbol = "<?php echo $currency_symbol; ?>";
      var n1 = parseFloat(totalPrice);
      var n2 = parseFloat($('input[name=shipping-val]:checked').val());
      var totalprice = n1 + n2;
      $('.shipping_method_line_dek').text($('input[name=shipping-val]:checked').attr('ship_method'));
      $('.shipping_method_line_mob').text($('input[name=shipping-val]:checked').attr('ship_method'));
      if($('input[name=shipping-val]:checked').val() == 0)
      {
         $('.shipp_price_mob').text('Free');
         $('#showID').text('Free');
         $('#ship_method').text($( 'input[name=shipping-val]:checked' ).attr('ship_method')+' Free Shipping');
      }
      else
      {
         $('.shipp_price_mob').text($('input[name=shipping-val]:checked').val());
         $('#showID').text(currencySymbol+' '+n2);
         $('#ship_method').text($( 'input[name=shipping-val]:checked' ).attr('ship_method')+' '+currencySymbol+' '+n2);
      }
      $('#total_mob').text(currencySymbol+' '+totalprice.toFixed(2));
      $('#old_total_pri').text(currencySymbol+' '+totalprice.toFixed(2));
   });


   $("#continue-to-payment").on('click',function(){
      //var x = document.getElementById("shipping-val").required;
      if ($('input[name=shipping-val]').is(':checked')) {
         $("#customer_details .col-1").hide();
         $(".shipping-section").hide();
         $(".section-payment-info").show();
         $("#submit-add-card-button").show();
        
         var totalPrice = "<?php echo $total_price; ?>";
         var currencySymbol = "<?php echo $currency_symbol; ?>";
         $('.shipping_method_line_dek').text($('input[name=shipping-val]:checked').attr('ship_method'));
         //console.log(totalPrice)
         var n1 = parseFloat(totalPrice);
         var n2 = parseFloat($('input[name=shipping-val]:checked').val());
         var totalprice = n1 + n2;
         $('.review-block__content bdo').text($('#customer_email').val());
         $('#old_total_pri').text(currencySymbol+' '+totalprice.toFixed(2));
         if($('input[name=shipping-val]:checked').val() == 0)
         {
            $('#shipp_price strong').text('Free');
            $('#ship_method').text($( 'input[name=shipping-val]:checked' ).attr('ship_method')+' Free Shipping');
            localStorage.setItem("ship_method", "Free Shipping");
         }else
         {
            $('#shipp_price strong').text("$ "+$('input[name=shipping-val]:checked').val());
            $('#ship_method').text($( 'input[name=shipping-val]:checked' ).attr('ship_method')+' '+currencySymbol+' '+n2);

            localStorage.setItem("ship_method", $( 'input[name=shipping-val]:checked' ).attr('ship_method')+' '+currencySymbol+' '+n2);
         }
         localStorage.setItem("customer_email", $('#customer_email').val());
         localStorage.setItem("total", currencySymbol+' '+totalprice.toFixed(2));
         // $('#ship_price').text(currencySymbol+' '+n2);
         //console.log($( 'input[name=shipping-val]:checked' ).attr('ship_day'));
         $.ajax({
            url: 'https://pay.kachyng.com/swapp/update_order',
            type: 'POST',
            data: {
              'email': $('#customer_email').val(),
              'order_id': $('input[name=order_id]').val(),
              'shipping': $( 'input[name=shipping-val]:checked' ).val(),
              'ship_method': $( 'input[name=shipping-val]:checked' ).attr('ship_method'),
              'ship_day': $( 'input[name=shipping-val]:checked' ).attr('ship_day'),
              'total_price': totalprice,
            },
            success: function (response) {
              console.log(response);
            }
         });
      } else {
         alert('Please select Shipping method..!');
         return false;
      }
   });


</script>
<script>
   $(document).ready(function() {
    $('.card-number').keyup(function() {
      console.log('keyup event is workinh.');
      console.log(this.value);
     // $('.cardNumber').val(this.value)
    });
   
      // $("#cvv").attr("required", true);
   
     // Default dropdown action to show/hide dropdown content
     $('.js-dropp-action').click(function(e) {
    e.preventDefault();
    $(this).toggleClass('js-open');
    $(this).parent().next('.dropp-body').toggleClass('js-open');
     });
   
     // Using as fake input select dropdown
     $('label').click(function() {
    $(this).addClass('js-open').siblings().removeClass('js-open');
    $('.dropp-body,.js-dropp-action').removeClass('js-open');
     });
     // get the value of checked input radio and display as dropp title
     $('input[name="dropp"]').change(function() {
    var value = $("input[name='dropp']:checked").val();
    $('.js-value').text(value);
     });
   
   });
   
</script>
<script>
   $(".check-my-btn").on('click',function(){
     $('.check-my-btn.after-my').removeClass('after-my');
     $(this).addClass('after-my');
     $('#cvv_saved').val('');
     $("#cvv_saved").show();
     $(".dropp-header__title.js-value").text($(".check-my-btn.after-my").attr("value"));
   });
</script>
<script>
   //#1 Initialize Spreedly
   function onload() {
     console.log("Initialize");
     Spreedly.init("JX1JC8nP7RWJfi6gCyxodWLhAo9", {
       "numberEl": "spreedly-number",
       "cvvEl": "spreedly-cvv"
     });
   }
   //#2. Can csutomize the card number and cvv field by css
   Spreedly.on("ready", function () {
     console.log("On Ready");
     Spreedly.setFieldType('text')
     Spreedly.setNumberFormat('prettyFormat');
     Spreedly.setPlaceholder("number", "Card number");
     Spreedly.setPlaceholder("cvv", "CVV");
     Spreedly.setStyle("number", "font-size: 12px; text-align: left");
     Spreedly.setStyle('cvv', 'width: 100%;');      
     Spreedly.setStyle("cvv", "font-size: 12px; text-align: center");
     //Spreedly.setStyle("cvv", "padding: 7px 6px;");
     Spreedly.setStyle("number", "border: none;");
     Spreedly.setStyle("cvv", "border: none;");
     var submitButton = document.getElementById('submit-add-card-button');
     submitButton.disabled = false;
   });
   //#3. Add card / Tokenize the card with Spreedly
   function tokenizeCard() {
     console.log("tokenize the card");
     var requiredFields = {};
     // Get required, non-sensitive, values from host page
     requiredFields["full_name"] = document.getElementById("full_name").value;
     requiredFields["month"] = document.getElementById("month").value;
     requiredFields["year"] = document.getElementById("year").value;
     Spreedly.tokenizeCreditCard(requiredFields);
   }
   //#4. Receive the card token from Spreedly
   Spreedly.on('paymentMethod', function (token, pmData) {
     // Set the token in the hidden form field
     var tokenField = document.getElementById("payment_method_token");
     tokenField.setAttribute("value", token);
     console.log('The card token is ' + document.getElementById("payment_method_token").value);
     console.dir(pmData);
     var tkn = document.getElementById("payment_method_token").value;
     var user_type = $('#usertype').val();
       var user_email = $('#customer_email').val();
       $.ajax({
         headers: {
             'X-CSRF-Token': $('input[name="_token"]').val()
         },
         type: 'POST',
         url: 'https://pay.kachyng.com/swapp/cart-token',
         data: {cardtoken: tkn, metadata: pmData, utype: user_type, uemail: user_email},
         success: function (result) {
             console.log('success');
             mypopup();
         },
         error: function (result) {
             console.log('HEREQWE');
         }
       });
   });
   //#5. Pay using the card token
   
   //6. Any errors
   Spreedly.on('errors', function (errors) {
     for (var i = 0; i < errors.length; i++) {
       var error = errors[i];
       console.log(error);
       //alert(error.message);
        swal({
          type: 'info',
          html: error.message,
          showCloseButton: true
        });
     };
   });
</script>
@if ($saved_cards)
<script>
   $(document).ready(function () {
     localStorage.removeItem("card_type");
     setTimeout(function(){
       localStorage.setItem('card_type', 'Existing Card');
     }, 1000);
   });
   
   $("#full_name").keypress(function(){
     localStorage.removeItem("card_type");
     setTimeout(function(){
       localStorage.setItem('card_type', 'New Card');
     }, 1000);
   });
</script>
@endif
<script>
   $("#remove-class").on('click',function(){
     localStorage.setItem('card_type', 'Existing Card');
   });
   $("#add-class").on('click',function(){
       localStorage.setItem('card_type', 'New Card');
   });
   
   if (window.history.replaceState) {
     window.history.replaceState(null, null, window.location.href);
   }
   
   function show2() {
     document.getElementById('submit-add-card-button').style.display = 'block';
   }
   
   $(document).ready(function () {
     localStorage.removeItem("card_type"); 
     //var site_url= '<?php echo $shop;?>';
     var site_url = 'https://pay.kachyng.com/swapp';
     //  alert(site_url);
     $('#dis_code').click(function () {
       $('#waiting_div_discount').show();
     });
     $('#discount_form').submit(function () {
       // show that something is loading
       $('#response').html("<b>Loading response...</b>");
       // Call ajax for pass data to other place
       $.ajax({
           type: 'POST',
           dataType: "json",
           url: "{{secure_asset('swapp/checkout/apply-code')}}",
           data: $(this).serialize() // getting filed value in serialize form
         })
         .done(function (data) { // if getting done then call.
           var msg_check = '';
           msg_check = data.msg;
           if (!msg_check) {
             $("#total_price").hide();
             $("#purchase").show();
             $("#err_msg").hide();
             $("#updated_price").show();
             $('#waiting_div_discount').hide();
             $("#purchase").html('<td class="font-montserrat all-caps fs-12 w-50"> Purchase Code : </td><td class="text-right b-r b-dashed b-grey w-25"><span class="hint-text small">' + data.cpn_title + '</span></td><td class="w-25"><span class="font-montserrat fs-18">' + data.cpn_price + '</span></td>');
             $("#updated_price").html('<h4 class="m-b-20"><span><strong>Amount Due:</strong></span> <span class="text-success"><strong>' + data.total_onions + ' Onions</strong></span></h4>');
           } else {
             console.log(msg_check);
             $("#err_msg").show();
             $("#err_msg").html(msg_check);
             $('#waiting_div_discount').hide();
           }
         })
         .fail(function () { // if fail then getting message
           // just in case posting your form failed
           alert("Posting failed.");
         });
       // to prevent refreshing the whole page page
       return false;
     });
   
     $(window).on('load', function () {
        // var countryID = $(this).val();
        var countryID = 'US';
        console.log(countryID)
        localStorage.setItem('countryid', countryID);
        localStorage.setItem('country', $("#country option:selected").html());
        if (countryID) {
         $.ajax({
           type: "GET",
           url: "{{secure_asset('swapp/checkout-states/fetch-states')}}?code=" + countryID,
   
           success: function (res) {
             if (res) {
               $("#state").empty();
               $.each(res, function (key, value) {
                 //console.log(res);
                 $("#state").append('<option value="' + value.st_code + '">' + value.st_name + '</option>');
               });
             } else {
               $("#state").empty();
             }
           }
         });
        }
         setTimeout(function(){ 
            var pcode = "<?php echo @$province_code; ?>";
            console.log(pcode)
            $('#state').val(pcode);
         }, 1000);


         if (countryID) {
         $.ajax({
           type: "GET",
           url: "{{secure_asset('swapp/checkout-states/fetch-states')}}?code=" + countryID,
   
           success: function (res) {
             if (res) {
               $("#gds-cr-3").empty();
               $.each(res, function (key, value) {
                 //console.log(res);
                 $("#gds-cr-3").append('<option value="' + value.st_code + '">' + value.st_name + '</option>');
               });
             } else {
               $("#gds-cr-3").empty();
             }
           }
         });
        }
     });
     $('#state').change(function () {
       var stateid = $(this).val();
       localStorage.setItem('stateid', stateid);
       localStorage.setItem('state', $("#state option:selected").html());
   
     });
   });
   var barcode_value = '<?php //echo $barcode_path;?>';
   var site_url = '<?php echo $shop;?>';
   
   function copy_password() {
     var copyText = document.getElementById("pwd_spn");
     var textArea = document.createElement("textarea");
     textArea.value = copyText.textContent;
     document.body.appendChild(textArea);
     var elementTitle = document.getElementById('wallet_address_code').title;
     textArea.select();
     document.execCommand("Copy");
     $('#wallet_address_code').attr("data-original-title", "Copied successfully.");
     textArea.remove();
   }
</script>
<script>
   var cart_url = site_url + '/cart/clear.js';
   var line_items = <?php echo json_encode($cart_datas); ?>;
   var addon_address = <?php echo json_encode($wallet_address); ?>;
   var shipping_rates = <?php echo json_encode($shipping_rates); ?>;
   $('.form-control').on('input', function () {
     var email = $("#customer_email").val();
     var fname = $("#customer_first_name").val();
     var lname = $("#customer_last_name_test").val();
     var address = $("#cus_add_address1").val();
     var city = $("#city").val();
     var zip_code = $("#zip_code").val();
     var country = $("#country").val();
     var state = $("#state").val();
     var zip_code = $("#zip_code").val();
   
     $('#cus_add_zip').val(zip_code);
   
     localStorage.setItem('zip_code', zip_code);
     if (email != "" && fname != "" && lname != "" && zip_code != "") {
       AjaxValidate.call(email, fname, lname, zip_code, address, city, country, state);
     }
   });
   var AjaxValidate = {
     call: function (email, fname, lname, zip_code, address, city, country, state) {
       if (typeof this.xhr !== 'undefined') {
         this.xhr.abort();
       }
   
       this.xhr = $.ajax({
         url: "{{secure_asset('swapp/checkout/activecarriers')}}",
         dataType: "json",
         cache: false,
         type: 'POST',
         data: {
           email: email,
           fname: fname,
           lname: lname,
           zip_code: zip_code,
           address: address,
           city: city,
           state: state,
           country: country,
           line_items: line_items,
           addon_address: addon_address
         },
         beforeSend: function () {
           $("#waiting_div_ship").show();
           $("#ship_err_msg").hide();
           $("#shipping_carriers").hide();
         },
         /* complete: function(){
          $('#image').hide();
              $("#waiting_div_ship").hide();
          $("#shipping_carriers").hide();
   
          },*/
   
         success: function (res) {
           var order_check = '';
           //console.log(res);
           if (res.msg === 'No shipping Available') {
             $('#submit-add-card-button').css("display", "block");
           }
           // jQuery(".radio-success").click(function(){
           // alert('zz'); 
           // jQuery("#submit-add-card-button").trigger('click');
           // });
   
           var check = res.success;
           order_check = res.withoutship;
           var ship_check = '';
           ship_check = res.msg;
           if (order_check) {
             //console.log('order');
             if (res.success === 'true') {
               $('#waiting_div').hide();
               $('#success_div').show();
               $("#waiting_div_ship").hide();
               $("#shipping_carriers").hide();
               $.getScript(cart_url);
               $.ajax({
                 url: cart_url,
                 cache: false,
                 success: function (html) {}
               });
               window.onbeforeunload = null;
               window.location.href = res.redirect_url;
             } else {
               var minuteMS = 20 * 1000; // seconds * milliSeconds
               setInterval(function () {
                 this.xhr = $.ajax({
                   url: "{{secure_asset('swapp/checkout/withoutShipping')}}",
                   dataType: "json",
                   cache: false,
                   type: 'POST',
                   data: {
                     email: email,
                     fname: fname,
                     lname: lname,
                     zip_code: zip_code,
                     address: address,
                     city: city,
                     state: state,
                     country: country,
                     line_items: line_items,
                     addon_address: addon_address
                   },
                   success: function (response) {
                     if (response.success === 'true') {
                       $.getScript(cart_url);
                       $.ajax({
                         url: cart_url,
                         cache: false,
                         success: function (html) {}
                       });
                       $('#waiting_div').hide();
                       $('#success_div').show();
                       $("#waiting_div_ship").hide();
                       $("#shipping_carriers").hide();
                       window.onbeforeunload = null;
                       window.location.href = response.redirect_url;
                     } else {
                       $("#waiting_div_ship").hide();
                       $('#waiting_div').show();
                       $("#ship_err_msg").show();
                       $("#ship_err_msg").html(ship_check);
                     }
                   }
                 });
               }, minuteMS);
             }
           }
           if (!ship_check) {
             $("#ship_err_msg").hide();
             $("#waiting_div_ship").hide();
             $("#shipping_carriers").show();
             $("#shipping_carriers").html('<h5>Shipping Method</h5>');
             $.each(shipping_rates, function (key, val) {
               //console.log(val.delivery_range);
             });
             for (var i = 0; i < res.length; i++) {
               var rang = res[i].delivery_range;
               $('<div class="radio radio-success" id="' + i + '" ><input value="' + res[i].checkout.total_price + '"  class="radiobutton" name="optionyes" id="mycheckbox' + i + '" type="radio"><div class="radio_lable"><label for="mycheckbox' + i + '">' + res[i].id + '</label><div class="shipp_rang">(Within ' + rang + ' Business Days)</div></div></div>').appendTo('#shipping_carriers');
             }
   
           } else {
             $("#waiting_div_ship").hide();
             $("#ship_err_msg").show();
             $("#shipping_carriers").hide();
             $("#ship_err_msg").html(ship_check);
           }
         }
       });
     }
   };
   /***************
    *sending carrier and current checked checkbox info to the controller
    **/
   $(document).on("click", ".radio,.radio-success", function () {
     var carrier_name;
     var id = $(this).attr('id');
     var value = $('#mycheckbox' + id).val();
     carrier_name = $('label[for="mycheckbox' + id + '"]').text();
     var cname = carrier_name;
     var combine = value + "-" + cname;
     $('#mycheckbox' + id).prop('checked', true);
     $.ajax({
       url: "{{secure_asset('swapp/checkout/total-price')}}/" + combine,
       type: "GET",
       dataType: "json",
       cache: false,
       success: function (data) {
         $("#total_price").hide();
         $("#updated_price").show();
         $("#updated_price").html(data.price);
         $("#shipp_price").html(data.shipping);
         $("#old_total_pri").hide();
         $("#showID").css("display", "block");
         $("#showID1").css("display", "block");
         checkBalance(data.onions, cname);
       }
     });
     $("#submit-add-card-button").trigger('click');
     return false;
   });
   /***************
    *keep checking balance against the merchnat address
    **/
   function checkBalance(onions, cname) {
     var all_values = {};
     all_values.fname = localStorage.getItem('customer_first_name');
     all_values.lname = localStorage.getItem('customer_last_name_test');
     all_values.email = localStorage.getItem('customer_email');
     all_values.address = localStorage.getItem('address');
     all_values.address2 = localStorage.getItem('address2');
     all_values.investor = localStorage.getItem('investor');
     all_values.city = localStorage.getItem('city');
     all_values.country = localStorage.getItem('country');
     all_values.countryid = localStorage.getItem('countryid');
     all_values.stateid = localStorage.getItem('stateid');
     all_values.state = localStorage.getItem('state');
     //  all_values.carrier_name=localStorage.getItem('carrier_name');
     all_values.zip_code = $("#zip_code").val();
     var onions = onions;
     var cname = cname;
     all_values.onions = onions;
     all_values.cname = cname;
     //  all_values.token=token;
     var encod = JSON.stringify(all_values);
     var value = 0;
     var xhr;
   }
   
   function hideTotal() {
     var divobj = document.getElementById('totalPrice');
     divobj.style.display = 'none';
   }
   document.getElementById("fname").value = getSavedValue("fname"); // set the value to this input
   document.getElementById("lname").value = getSavedValue("lname"); // set the value to this input
   document.getElementById("email").value = getSavedValue("email");
   document.getElementById("address").value = getSavedValue("address");
   document.getElementById("city").value = getSavedValue("city");
   document.getElementById("address2").value = getSavedValue("address2");
   document.getElementById("investor").value = getSavedValue("investor");
   /* Here you can add more inputs to set value. if it's saved */
   
   //Save the value function - save it to localStorage as (ID, VALUE)
   function saveValue(e) {
     var id = e.id; // get the sender's id to save it . 
     var val = e.value; // get the value. 
     localStorage.setItem(id, val); // Every time user writing something, the localStorage's value will override . 
   }
   //get the saved value function - return the value of "v" from localStorage. 
   function getSavedValue(v) {
     if (localStorage.getItem(v) === null) {
       return ""; // You can change this to your defualt value. 
     }
     return localStorage.getItem(v);
   }
</script>      
<!-- #content -->
<script> 

   function tokenizeCard1() {
   // $(function () {
      // window.onbeforeunload = function (e) {
      //    run_ajax();
      //    return true;
      // }
     // if ($(window).width() >= 767) {
       // console.log('ekk');
       // var confirmButton = $('#submit-add-card-button');
       var confirmButton = $('.pay-nw-btn-sec #submit-add-card-button');
     // } else {
     //   console.log('do');
     //   var confirmButton = $('#submit-add-card-button-mob');
     // }

      confirmButton.click(function (e) {
      e.preventDefault();
      console.log("ABC");
      var cardType = localStorage.getItem('card_type');
      console.log(cardType);
      var owner = $('#full_name');
      var cardNumber = $('#spreedly-number');
      var cardNumberField = $('#card-number-field');
      var CVV = $("#spreedly-cvv");
      var mastercard = $("#mastercard");
      var visa = $("#visa");
      var amex = $("#amex");
      var expiration_date_month = $("#month");
      var expiration_date_year = $("#year");
      cardNumber.payform('formatCardNumber');
      CVV.payform('formatCardCVC');
      // if ($('#checkbox_green').is(':checked')) {
      $('input[type=radio][name=shipping-val-3]').change(function() {
         if($(this).attr("value") == "different"){
            console.log('checkbox_green');
            var customer_email = $("#green_email");
            var customer_first_name = $("#green_first_name");
            var customer_last_name = $("input[name*='green_last']");
            var cus_add_address1 = $("#green_add_address1");
            var cus_add_city = $("#green_city");
            // var country_to_state = $(".green_country_to_state");
            //  var state_select = $(".green_state_select");
            var billing_address_2_field = $("#green_add_address2");
            var billing_postcode_field = $("#green_add_zip");
            var billing_phone_field = $("#green_phone");
         }
      });
      if ($('#checkbox_red').is(':checked')) {
         console.log('checkbox_red');
         var customer_email = $("#red_email");
         var customer_first_name = $("#red_first_name");
         var customer_last_name = $("input[name*='red_last']");
         var cus_add_address1 = $("#red_add_address1");
         var cus_add_city = $("#red_city");
         // var country_to_state = $(".red_country_to_state");
         //    var state_select = $(".red_state_select");
         var billing_address_2_field = $("#red_add_address2");
         var billing_postcode_field = $("#red_add_zip");
         var billing_phone_field = $("#red_phone");
      } else {
         console.log('else');
         var customer_email = $("#customer_email");
         var customer_first_name = $("#customer_first_name");
         var customer_last_name = $("#customer_last_name_test");
         var cus_add_address1 = $("#cus_add_address1");
         var cus_add_city = $("#cus_add_city");
         var country_to_state = $("#country");
         var state_select = $("#state");
         var billing_address_2_field = $("#cus_add_address2");
         var billing_postcode_field = $("#cus_add_zip");
         var billing_phone_field = $("#customer_phone");
      }
      if ($("#saved_card").val() && cardType == "Existing Card") { 
         console.log('Existing Card..');
         // console.log(cardType);
         var cvv_saved = $("#cvv_saved");
         var isCvvValid = $("#cvv_saved").val();
         var i = 0;
         if (customer_last_name.val().length < '2') {
           border_function(customer_last_name);
           i++;
         } else {
           customer_last_name.removeAttr('style');
         }
         if (isCvvValid.length < 3) {
           border_function(cvv_saved);
           i++;
         } else {
           cvv_saved.removeAttr('style');
         }
         if (customer_email.val().length == 0) {
           console.log('customer_email');
           border_function(customer_email);
           i++;
         } else {
           customer_email.removeAttr('style');
         }
         if (customer_first_name.val().length < '2') {
           border_function(customer_first_name);
           i++;
         } else {
           customer_first_name.removeAttr('style');
         }
         if (cus_add_address1.val().length == '0') {
           border_function(cus_add_address1);
           i++;
         } else {
           cus_add_address1.removeAttr('style');
         }
         if (cus_add_city.val().length == 0) {
           border_function(cus_add_city);
           i++;
         } else {
           cus_add_city.removeAttr('style');
         }
         if (i == 0) {
           // tokenizeCard();
           mypopup();
         }
      } else if ($("#saved_card").val() && cardType == "New Card") { 
         console.log('New Card..');
         var isCardValid = valid_credit_card(cardNumber.val());
         var isCvvValid = $.payform.validateCardCVC(CVV.val());
         var i = 0;
         if (owner.val().length < 5) {
           border_function(owner);
           i++;
         } else {
           owner.removeAttr('style');
         }
         if (customer_last_name.val().length < '2') {
           border_function(customer_last_name);
           i++;
         } else {
           customer_last_name.removeAttr('style');
         }
         if (expiration_date_month.val().length < 1) {
           border_function(expiration_date_month);
           i++;
         } else {
           expiration_date_month.removeAttr('style');
         }
         if (expiration_date_year.val().length < 1) {
           border_function(expiration_date_year);
           i++;
         } else {
           expiration_date_year.removeAttr('style');
         }
         if (customer_email.val().length == 0) {
           border_function(customer_email);
           i++;
         } else {
           customer_email.removeAttr('style');
         }
         if (customer_first_name.val().length < 2) {
           border_function(customer_first_name);
           i++;
         } else {
           customer_first_name.removeAttr('style');
         }
         if (cus_add_address1.val().length == 0) {
           border_function(cus_add_address1);
           i++;
         } else {
           cus_add_address1.removeAttr('style');
         }
         if (cus_add_city.val().length == 0) {
           border_function(cus_add_city);
           i++;
         } else {
           cus_add_city.removeAttr('style');
         }
         if (i == 0) {
           if (confirm('Are You sure your details are correct?')) {
             tokenizeCard()
             // mypopup();
           }
         }
      } else { 
         console.log('else1');
         var isCardValid = valid_credit_card(cardNumber.val());
         var isCvvValid = $.payform.validateCardCVC(CVV.val());
         var i = 0;
         if (owner.val().length < 5) {
           border_function(owner);
           i++;
         } else {
           owner.removeAttr('style');
         }
         if (customer_last_name.val().length < '2') {
           border_function(customer_last_name);
           i++;
         } else {
           customer_last_name.removeAttr('style');
         }
         if (expiration_date_month.val().length < 1) {
           border_function(expiration_date_month);
           i++;
         } else {
           expiration_date_month.removeAttr('style');
         }
         if (expiration_date_year.val().length < 1) {
           border_function(expiration_date_year);
           i++;
         } else {
           expiration_date_year.removeAttr('style');
         }
         if (customer_email.val().length == 0) {
           border_function(customer_email);
           i++;
         } else {
           customer_email.removeAttr('style');
         }
         if (customer_first_name.val().length < 2) {
           border_function(customer_first_name);
           i++;
         } else {
           customer_first_name.removeAttr('style');
         }
         if (cus_add_address1.val().length == 0) {
           border_function(cus_add_address1);
           i++;
         } else {
           cus_add_address1.removeAttr('style');
         }
         if (cus_add_city.val().length == 0) {
           border_function(cus_add_city);
           i++;
         } else {
           cus_add_city.removeAttr('style');
         }
         if (i == 0) {
           if (confirm('Are You sure your details are correct?')) {
             tokenizeCard()
             // mypopup();
           }
         }
       }
      });
   // });
   }
   function valid_credit_card(value) {
   
     if (value.length < 1) return false;
     // accept only digits, dashes or spaces
     if (/[^0-9-\s]+/.test(value)) return false;
     // The Luhn Algorithm. It's so pretty.
      var nCheck = 0,
      nDigit = 0,
      bEven = false;
      value = value.replace(/\D/g, "");
   
      for (var n = value.length - 1; n >= 0; n--) {
       var cDigit = value.charAt(n),
         nDigit = parseInt(cDigit, 10);
   
       if (bEven) {
         if ((nDigit *= 2) > 9) nDigit -= 9;
       }
   
       nCheck += nDigit;
       bEven = !bEven;
     }
   
     return (nCheck % 10) == 0;
   }
   
   function border_function(selector) {
     selector.css("border", "2px solid red");
   }
   
   function mypopup() {
     var data = $('#raw_data').val();
     var shippVal = $('#shipp_price').text();
     //alert(shippVal);
     $('#loading').hide();
     $.ajax({
       url: 'https://pay.kachyng.com/swapp/checkout-card-submit/card-submit1',
       type: 'POST',
       data: {
         'payment_form': $('#payment-form').serialize(),
         'shipp_value': shippVal,
         'order_id': "<?php echo $order_id; ?>",
         'shop': "<?php echo $shop; ?>"
       },
       beforeSend: function () {
         $('#loading').show();
         console.log('Order is Processing');
       },
       success: function (response) {
         console.log('QWEEEEE');
         console.log(response);
         var objJSON = JSON.parse(response);
         if (objJSON.success == 'true') {
           console.log('response');
           swal({
             type: 'info',
             html: 'Order Complete Successfully',
             showCloseButton: true
           });
           window.onbeforeunload = null;
           window.location.href = objJSON.redirect_url;
         } else {
           console.log('responseQWE');
           var result_err = JSON.parse(response);
           console.log(result_err);
           var err_resl = result_err.error;
           swal({
             type: 'info',
             html: err_resl.error,
             showCloseButton: true
           });
           $('#loading').hide();
         }
       }
     });
   }
   
   $(document).ready(function () {
     var isMobile = window.matchMedia("only screen and (max-width: 760px)");
     if (isMobile.matches) {
       $("#order_review").fadeOut('slow');
       $("#arrow").html('<span class="glyphicon glyphicon-chevron-up"></span>');
       $('#order_review_to').click(function () {
         var button = $(this);
         if (button.attr('fade') == 'FadeOut') {
           $('#order_review').fadeOut('slow');
           $("#arrow").html('<span class="glyphicon glyphicon-chevron-down"></span>');
           button.attr('fade', 'FadeIn');
         } else {
           $('#order_review').fadeIn('slow');
           $("#arrow").html('<span class="glyphicon glyphicon-chevron-up"></span>');
           button.attr('fade', "FadeOut");
         }
       });
     } else {
       $("#order_review_to").hide();
     }
     $(".red").hide();
     $(".green").hide();
     // $('#checkbox_green').click(function () {
     $('input[type=radio][name=shipping-val-3]').change(function() {
      if($(this).attr("value") == "different"){
         
         $(".green.box.shipping_address").show();
         $(".red.box.shipping_address").show();
         // var inputValue = $(this).attr("value");
         // if ($(this).prop("checked") == true) {
         //    $("." + inputValue).show();
         // } else {
         //    $("." + inputValue).hide();
         // }
      }else{
         $(".green.box.shipping_address").hide();
         $(".red.box.shipping_address").hide();
      }
     });
     $('#checkbox_red').click(function () {
       var inputValue = $(this).attr("value");
       if ($(this).prop("checked") == true) {
         $("." + inputValue).show();
       } else {
         $("." + inputValue).hide();
       }
     });
     $('.nav-toggle').click(function () {
       //get collapse content selector
       var collapse_content_selector = $(this).attr('href');
   
       //make the collapse content to be shown or hide
       var toggle_switch = $(this);
       $(collapse_content_selector).toggle(function () {
         if ($(this).css('display') == 'none') {
           //change the button label to be 'Show'
           toggle_switch.html('Show Order Summery <i class="fa fa-angle-down" aria-hidden="true"></i> ');
         } else {
           //change the button label to be 'Hide'
           toggle_switch.html('Hide Order Summery <i class="fa fa-angle-up" aria-hidden="true"></i>');
         }
       });
     });


     $('.nav-toggle-2').click(function () {
       var collapse_content_selector = $(this).attr('href');
       var toggle_switch = $(this);
       $(collapse_content_selector).toggle(function () {
         if ($(this).css('display') == 'none') {
           toggle_switch.html('Show Order Summery <i class="fa fa-angle-down" aria-hidden="true"></i> ');
         } else {
           toggle_switch.html('Hide Order Summery <i class="fa fa-angle-up" aria-hidden="true"></i>');
         }
       });
     });

     $('.nav-toggle-3').click(function () {
       var collapse_content_selector = $(this).attr('href');
       var toggle_switch = $(this);
       $(collapse_content_selector).toggle(function () {
         if ($(this).css('display') == 'none') {
           toggle_switch.html('Show Order Summery');
         } else {
           toggle_switch.html('Hide Order Summery <i class="fa fa-angle-up" aria-hidden="true"></i>');
         }
       });
     });
   });
   
   $('#saved_card').on('change', function () {
     if ($(this).val() != '') {
       $('#new_cc').hide();
       // $('#add-class a').show();
     } else {
       $('#new_cc').show();
       // $('#add-class a').hide();
     }
   });
   
   $('#add-class').on('click', function () {
     $('#add-class').hide();
     $('#remove-class').show();
     $('#new_cc').show();
   
     $('.save-credit').hide();
     $('.saved-credit-c').hide();
     $('#cvv_saved').hide();
   });
   $('#remove-class').on('click', function () {
     $('#add-class').show();
     $('#remove-class').hide();
     $('#new_cc').hide();
   
     $('.save-credit').show();
     $('.saved-credit-c').show();
     $('#cvv_saved').show();
   });   
   
   
   $('#calculate_shipping').on('click', function () {
     $.ajax({
       type: 'GET',
       url: '{{$shop}}/cart/shipping_rates.json',
       data: 'shipping_address[zip]=133001&shipping_address[country]=India&shipping_address[province]=Ambala',
       dataType: 'jsonp',
       crossDomain: true,
       success: function (data) {
         console.log(data);
       }
     });
   });
   
   // Timeout After Nothing Happen
   document.getElementById("submit-add-card-button-mob").onclick = function () {
     window.onbeforeunload = null;
   };
   document.getElementById("submit-add-card-button").onclick = function () {
     window.onbeforeunload = null;
   };
   //window.setInterval(function(){ 
   // setTimeout(function () {
   //   var customer_email = $('.customer_email').val();
   //   if (customer_email) {
   //     swal({
   //       type: 'info',
   //       html: 'You are not doing anything. we send your order as Abandoned Order',
   //       showCloseButton: true
   //     });
   //     run_ajax();
   //   }
   // }, 600000);
   
   //}, 5000);
   
   // abandoned_checkouts
   // function run_ajax() {
   //   var customer_email = $('.customer_email').val();
   //   if (customer_email) {
   //     console.log('customer_email: ' + customer_email);
   //     $.ajax({
   //       url: 'https://pay.kachyng.com/swapp/orders/abandoned_checkouts',
   //       type: 'POST',
   //       data: {
   //         'raw_data': $('#raw_data').val(),
   //         'customer_email': customer_email,
   //       },
   //       success: function (response) {
   //         console.log(response);
   //       }
   //     });
   //   }
   // }
   
   
   // All Facebook Login JQuery
   window.fbAsyncInit = function () {
     FB.init({
       appId: '374644873015798',
       cookie: true,
       xfbml: true,
       version: 'v3.1'
     });
   
     FB.AppEvents.logPageView();
   
   };
   
   (function (d, s, id) {
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {
       return;
     }
     js = d.createElement(s);
     js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
   
   FB.getLoginStatus(function (response) {
     statusChangeCallback(response);
   });
   
   
   function checkLoginState() {
     FB.getLoginStatus(function (response) {
       statusChangeCallback(response);
     });
   }
   
   // Facebook login with JavaScript SDK
   function fbLogin() {
     FB.login(function (response) {
       if (response.authResponse) {
         // Get and display the user profile data
         getFbUserData();
       } else {
         document.getElementById('status').innerHTML = 'User canceled login or did not fully authorize.';
       }
     }, {
       scope: 'email'
     });
   }
   
   // Fetch the user profile data from facebook
   function getFbUserData() {
     FB.api('/me', {
         locale: 'en_US',
         fields: 'id,first_name,last_name,email,link,gender,locale,picture'
       },
       function (response) {
         document.getElementById('fbLink').setAttribute("onclick", "fbLogout()");
         document.getElementById('fbLink').innerHTML = 'Logout from Facebook';
         $('.customer_email').val(response.email);
         $('.customer_first_name').val(response.first_name);
         $('.customer_last_name').val(response.last_name);
         $('#user').val('facebook');
       });
   }
   
   // Logout from facebook
   function fbLogout() {
     FB.logout();
   }
</script>

@stop