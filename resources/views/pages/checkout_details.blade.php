@extends('layouts.checkout')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<script src="https://core.spreedly.com/iframe/iframe-v1.min.js"></script>

<style>

/*-----------------css-jass------------------*/
.quantity-main span {
    float: right;
    margin-right: 15px;
}
.quantity-main {
    border-top: 1px solid rgba(175,175,175,0.34);
    display: inline-block;
    width: 100%;
    padding-top: 15px;
}
/* .img-with-quantity span.product-thumbnail__quantity {
    position: absolute !important;
    top: -5px !important;
    left: 50px !important;
} */
tr.innertr {
    display: inline-block;
    padding: 30px 0;
    text-align: right !important;
}
.prdct-img {
    width: 87%;
    display: inline-block;
}
tr.product {
    width: 100%;
}
span.product__price {
    float: right;
    margin-top: 18px;
}
.thubnail-img-width {
    width: 17%;
    float: left;
    margin-right: 10px;
	position: relative;
}

.prdct-img span {
    width: 75% !important;
    float: left;
}
.prdct-img span#pdescription {
    font-size: 11px;
}
.prdct-img span#pname {
    margin-top: 13px;
}
.thubnail-img-width span.quantity-digits {
    position: absolute;
    right: -6px;
    background: #ccc;
    width: 18px !important;
    text-align: center;
    border-radius: 26px;
    top: -7px;
    font-size: 13px !important;
}
.product.mobile-view .prdct-img-mobile .thubnail-img-width {
    width: 55.19px;
}
.product.mobile-view .prdct-img-mobile span {
    width: 65%;
    display: inline-block;
}
.product.mobile-view .prdct-img-mobile span#pname {
    margin-top: 10px;
}

#saved_card {
    display: inline-block;
    width: auto;
    vertical-align: middle;
    margin-right: 10px;
}

#submit-add-card-button-mob {
    display: none;
}





/*-----------------css-end-jass-----------------*/

   .radio.radio-success input {
      width: auto;
      height: inherit;
   }
   div#shipping_carriers .radio_lable label {
      padding: 0;
   }
   div#shipping_carriers {
      float: left;
      width: 100%;
   }
   div#shipping_carriers h5 {
      font-size: 22px;
      margin-top: 20px;
      font-weight: 600!important;
   }
   div#shipping_carriers .radio {
      padding-left: 20px;
      margin: 15px 0;
   }
   div#shipping_carriers .radio input {
      top: 6px;
   }
   div#shipping_carriers .radio input:focus {
      box-shadow: 0 0 0 0px #8d8d8d!important;
   }

  /* .customer_email{
      border: solid 1px #ccc !important;
   }*/
  
   .country-btm{
     margin-bottom:10px;
   }

   .pl-10{
      padding-left: 10px;
   }

   .pl-3{
      padding-left: 3px;  
   }

   .pr-3{
      padding-right: : 3px;  
   }

   .pt-15{
      padding-top: : 15px !important;
   }

   .pr-0{
      padding-right: 0px;
   }

   .pt-0{
      padding-top: 0px !important;
   }

   .ml-3{
      margin-left: 5.7px;
   }
   .fw-100{
      width: 100%;
   }

   .country-fld{
      width:49%;
      padding-left: 0px !important;
   }

   .state-fld{
      width: 49%;
      padding-right: 0px !important;
   }

   .zip-fld{
      width: 100%
   }

    #country{
      padding-left: 10px;
   }

   #state{
    padding-left: 10px;  
   }

   .tile-case{
      text-transform: capitalize;
   }

   ::-webkit-input-placeholder { /* WebKit browsers */
    text-transform: none;
   }
   :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
       text-transform: none;
   }
   ::-moz-placeholder { /* Mozilla Firefox 19+ */
       text-transform: none;
   }
   :-ms-input-placeholder { /* Internet Explorer 10+ */
       text-transform: none;
   }
   ::placeholder { /* Recent browsers */
       text-transform: none;
   }

   .col-25 {
         }
   div#spreedly-number {
      border-radius: 4px;
      margin: 0;
      padding: 10px 5px;
      border: solid 1px #ccc !important;
      width: 145px;
      height: 40px !important;
   }
   .col-25.one {
      margin-top: 0px;
      margin-right: -59px;
   }
   .col-25.two {
      margin-top: 0px;
   }
   div#spreedly-cvv {
      width: 58px !important;
      height: 40px !important;
      border-radius: 4px;
      border: solid 1px #ccc !important;
      padding: 10px 10px;
      /*margin-left:10px;*/
   }
   .col-25.three {
      margin-top: 27px;
      
   }

   .row {
      display: flex;
      flex-wrap: wrap;
      margin: 0 0px;
      padding-top: 15px;
   }
   .input-outline-none {
      outline: none;
      margin-top: 9px !important;
   }
   input[type='number'] {
      -moz-appearance: textfield;
      margin-top: 8px !important;
   }
   .checkbox input[type=checkbox], .checkbox-inline input[type=checkbox], .radio input[type=radio], .radio-inline input[type=radio] {
    position: absolute;
    margin-left: 20px !important;
    margin-top: 5px !important;
   }

   .shipping-address.shipping-address-desk {
      margin-top: -19px !important;
   }

   input#month {
      width: 60px !important;
      text-align: center;
   }

   /*#card_number {
      text-align: center;
      padding: 11px 15px !important;
      border: none !important;
      border-style: none !important;
      width: 100%;
   }*/

   #cvv {
      text-align: center;
      padding: 8px 18px;
   }

   input#year {
      width: 70px !important;
      text-align: center;
   }

   input[type='number'] {
      margin-top: 9px!important;
   }
   input#card_number {
      margin-top: 10px !important;
      width: 100%;
      border: none;
      border-style: none;
   }


   /**Responsive**/
   
   @media only screen and (max-width: 991px){
	.prdct-img span {
		width: 65% !important;
	}  
	.prdct-img span#pname {
		margin-top: 0px;
	} 
	.prdct-img .thubnail-img-width {
		width: 22%;
	}
	span.product__price {
		margin-top: 22px;
	}
	.prdct-img {
		width: 84%;
	}
   }
   
   @media only screen and (max-width: 767px){
	  body .checkout-page .product mobile .product-thumbnail__wrapper {
		width: 100% !important;
	} 
	tr.product.mobile span.product__price {
		float: right;
		padding: 20px 0;
	}
	.product mobile .prdct-img-mobile {
		display: inline-block;
		width: 90%;
		margin-bottom: 10px;
	}
	.product mobile td.product__image {
		width: 100%;
	}
	span.product__price {
		margin-top: 0;
	}
	.quantity-main span {
		margin-right: 0;
	}
	
	.step__footer.mobfotr {
		display:none;
	}
	
	#submit-add-card-button-mob {
		display: block;
		margin-bottom: 15px;
		padding: 10px 15px;
	}
	
	
   }
   
   
   
   
   
   @media only screen and (max-width: 680px){
		body .checkout-page .product mobile .product-thumbnail__wrapper {
			width: 100% !important;
		} 
	   
    }
   
   
   
   @media only screen and (max-width: 480px){
	   .product.mobile-view .prdct-img-mobile span#pdescription {
			font-size: 10px;
		}
		.product.mobile-view .prdct-img-mobile .thubnail-img-width {
			width: 68.19px;
		}
   }
   @media only screen and (max-width: 388px){
	   div#spreedly-number {
			width: 100% !important;
			margin-bottom: 15px;
		}
		input#year, input#month, div#spreedly-cvv {
			width: 33% !important;
		}
		.product.mobile-view .prdct-img-mobile span#pname {
			margin-top: 0;
		}
		.product.mobile-view .prdct-img-mobile span {
			width: 57%;
			display: inline-block;
		}
   }
   
   
   @media only screen and (max-width: 375px){
   div#spreedly-cvv {
       height: 40px !important;
       border-radius: 4px;
       border: solid 1px #ccc !important;
       /*padding: 10px 5px;*/
       /*margin-left: 55px;*/
   }
   .col-25.one {
          }
   
   .col-25.three {
       margin-top: 0px;
      }
   body .kachyng_checkout input, body .kachyng_checkout select {
   font-size: 11px;
   }
   }
   
   
	@media only screen and (max-width: 360px)  {
		#order_review .shop_table tr:nth-child(2) td {
			padding: 0 !important;
		}
		#order_review .shop_table tr:nth-child(2) td:nth-child(2) {
			padding-right: 20px !important;
		}
		.product.mobile-view .prdct-img-mobile .thubnail-img-width {
			width: 55.19px;
		}
		
	}

   @media only screen and (min-width: 425px)  { 
   .col-25.one {
    margin-top: 0px;
    margin-right: -9px;
   }
   div#spreedly-cvv {
       width: 58px !important;
   }
   .col-25.three {
       margin-top: 0px;
       margin-left:5px !important;
   }
   }


  .shipping-address h3#ship-to-different-address span {
    width: auto;
    margin-right: 10px;
  }
  .shipping-address.shipping-address-desk {
    margin-top: 0px !important;
    width: 100%;
  }
  .shipping-address.shipping-address-desk #checkbox_green {
    position: relative;
    margin-left: 0 !important;
  }

  #add-class span, #remove-class span {
    background: #ccc;
    margin-top: 0px;
    display: inline-block;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
  }

  #add-class {
    margin-bottom: 10px;
  }

  @media only screen and (min-width:767px) {
    #one_step_back {
      display:  none;
    } 
  }

  #green_add_zip, #country, #red_add_zip {
    cursor: not-allowed;
    background-color: #eee;
    opacity: 1;
  }


  .firstmn {
    display: inline-block;
    width: 100%;
  }
  .firstmn .input-mn, .firstmn .acc-mn{
      display: inline-block;
  }
  .firstmn:nth-child(2) {
      margin-bottom: 15px;
  }


</style>

<body onload="onload()">
   <div id="content" class="checkout-page">
      <div class="container">
         <section class="round-border">
            <div class="top-header">
               <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__icon">
                  <path d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z"></path>
               </svg>
               <p href="#collapse1" class="nav-toggle">Show Order Summery</p>
               @foreach ($cart_datas as $cart_data)

               <?php
                  // echo"<pre>"; print_r($cart_data);  die;
               ?>

               <strong href="" class="nav-price">{{$currency_symbol}}
                {{ number_format($cart_data['shipping_rates'] + $cart_data['price'], 2) }}
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
                                    @if(array_key_exists('image',$cart_data))
                                       @foreach( $cart_data['image'] as $key => $c_image)
                  											<div class="prdct-img-mobile"><div class="thubnail-img-width"><img class="product-thumbnail__image" src="{{$c_image}}"><span class="quantity-digits">2</span></div>
                                          <span id="pname">{{@$cart_data['productTitle'][$key]}}</span>
                                          <span id="pdescription">{{@$cart_data['productDES'][$key]}}</span>
                                        </div>

                  										 @if(array_key_exists('product_price',$cart_data))
                  											 <span class="product__price">
                  											 <strong>{{$currency_symbol}} {{@$cart_data['product_price'][$key]}}</strong>
                  											 </span>
                  										 @endif
                                       @endforeach
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
				  
        				  <div class="quantity-main">
        					 <div class="quantity-sec">
        						<strong>Quantity : </strong><span><strong>{{$cart_data['quantity'] }}</strong></span>
        					 </div>
        				 </div>
				  
                  <table class="shop_table">
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
                            @if($cart_data['shipping_rates'] != 0) 
                            <td><strong><span class="woocommerce-Price-currencySymbol">{{$currency_symbol}}</span>
                             <span id="shipp_price"> 
                                {{$cart_data['shipping_rates']}}
                             </span>
                             </strong>
                            </td>
                             @else
                             <td>
                             <span id="shipp_price">
                                <strong>Free</strong>
                              </span>
                              </td>
                            @endif
                        </tr>

                        <tr class="tax_line">
                          <th>Tax</th>
                          <td>
                          <strong id="show_tax_line">
                             <span class="woocommerce-Price-currencySymbol" >{{$currency_symbol}}</span> 0.00
                          </strong>
                          </td>
                       </tr>

                       <tr class="discount_line" style="display: none;">
                          <th>Discount</th>
                          <td>
                             <strong id="discount_line_1">
                                <span class="woocommerce-Price-currencySymbol" >{{$currency_symbol}}</span>
                                0.00
                             </strong>
                          </td>
                       </tr>

                        <tr class="shipping_method">
                          <th>Shipping method</th>
                          <td>
                          <strong id="shipping_method_line">
                             <span class="woocommerce-Price-currencySymbol" ></span>
                             {{@$cart_data['h_shipping_method']}}
                          </strong>
                          </td>
                       </tr>
       

                     </tbody>
                     <tfoot>
                        <tr class="order-total">
                           <th>Total</th>
                           <td><strong>
                            <span class="woocommerce-Price-currencySymbol">{{$currency_symbol}}</span> {{ number_format($cart_data['shipping_rates'] + $total_price, 2) }}</strong> 
                          </td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
         </section>
         <!--Right Section-->
         <div id="primary">
         {{ Form::open(array('url' => secure_url('swapp/checkout/shipping'), 'class' =>'form kachyng_checkout', 'id' =>'payment-form', 'onsubmit' => 'tokenizeCard(); return false;')) }}
         <input type="hidden"  name="payment_method_token" id="payment_method_token" value="">
         <input type="hidden"  name="usertype" id="usertype" value="{{$user_type}}">
         <input type="hidden"  name="customer_ID" id="customerID" value="{{@$customer_id}}">
         <div id="order_review">
         <table class="product-table">
         <tbody>
         @foreach ($cart_datas as $cart_data)
         
        @if(array_key_exists('image',$cart_data))
            @foreach( $cart_data['image'] as $key => $c_image)
           <tr class="product">
    			 <td class="product__image">
    				 <div class="product-thumbnail">
    					 <div class="product-thumbnail__wrapper">
    						
    						<div class="img-with-quantity" >
    						   <div class="prdct-img"><div class="thubnail-img-width"><img class="product-thumbnail__image" src="{{$c_image}}"><span class="quantity-digits">2</span></div>
                    <span id="pname">{{@$cart_data['productTitle'][$key]}}</span>
                    <span id="pdescription">{{@$cart_data['productDES'][$key]}}</span>
                   </div>
    						   <span class="product-thumbnail__quantity" style="display: none;">{{ $cart_data['quantity'] }}</span> 
    						   @if(array_key_exists('product_price',$cart_data))
    								 <span class="product__price">
    									<strong>{{$currency_symbol}} {{@$cart_data['product_price'][$key]}}</strong>
    								 </span>
    						   @endif
    						</div>	 
    					 </div>
    				 </div>
    			   </td>
            </tr>
          @endforeach
        @endif

        @endforeach
			
         </tbody>
         </table>
		 	  <div class="quantity-main">
				 <div class="quantity-sec">
					<strong>Quantity : </strong><span><strong>{{ $cart_data['quantity'] }}</strong></span>
				 </div>
			  </div>
         <table class="shop_table">
         
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
            {{$cart_data['shipping_rates']}}
         </span>
         </strong>
         @else
         <strong>
         <span id="shipp_price">
            <strong>Free</strong>
          </span>
          </strong>  
         @endif
         </span>
         </td>
         </tr>
         </tbody>

         <tfoot>

         <tr class="tax_line">
            <th>Tax</th>
            <td>
            <strong id="show_tax_line">
               <span class="woocommerce-Price-currencySymbol" >{{$currency_symbol}}</span>
               0.00
            </strong>
            </td>
         </tr>

         <tr class="discount_line" style="display: none;">
            <th>Discount</th>
            <td>
               <strong id="discount_line_1">
                  <span class="woocommerce-Price-currencySymbol" >{{$currency_symbol}}</span>
                  0.00
               </strong>
            </td>
         </tr>

         <tr class="shipping_method">
            <th>Shipping method</th>
            <td>
            <strong id="shipping_method_line">
               <span class="woocommerce-Price-currencySymbol" ></span>
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
         
         <a class="step__footer__previous-link" href="https://{{$shop}}/cart">
         <svg focusable="false" aria-hidden="true" class="icon-svg icon-svg--color-accent icon-svg--size-10 previous-link__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
         <path d="M8 1L7 0 3 4 2 5l1 1 4 4 1-1-4-4"></path>
         </svg>
         <span class="step__footer__previous-link-content">Return to cart</span>
         </a>

         </div><!--End Right Section-->
         <!--Left-section-->
         <div class="col2-set" id="customer_details">
         <div class="col-1">
         <!--Logo-sec-->
         <!--Breadcrmbs-->                          
         <div class="site-branding log-desktop">
         <div class="beta site-title">
         <a href="{{$shop}}" rel="home">
         {{$store_name}}
         </a>
         </div>
         </div>
         <!-- login with facebook -->
         @empty($customer_id)
         <div id="status"></div>
         <!-- Facebook login or logout button -->
        <!--  <div class="login_fb loginBtn loginBtn--facebook"><a href="javascript:void(0);" onclick="fbLogin()" id="fbLink">Login with Facebook</a></div>or Checkout As Guest</span> -->
         <!-- Display user profile data -->
         <div id="userData"></div><br>
         @endempty
         <!-- login with facebook -->
         <p class="group section__content group form-row">
         <label >Email<span style="color:red;"> *</span></label>
         @if (($user) != 'guest')
         {{Form::email('customer_email', $customer_data['customer_email'], array('class' => 'customer_email','id' => 'customer_email', 'placeholder' => 'Email', 'required', 'readonly'))}}
         @else
         {{Form::email('customer_email', $customer_data['customer_email'], array('class' => 'customer_email','id' => 'customer_email', 'placeholder' => 'Email', 'required'))}}
         @endif
         </p>
         <div class="section__header">
         <!-- <p class="section__text">
         All transactions are secure and encrypted.
         </p> -->
         </div>

         @if (($user) != 'guest')
         @if ($saved_cards)

         <div id="add-class"><span>Add New Card</span></div>
         <div id="remove-class" style="display: none;"><span>Existing User Card</span></div>

         <div class="saved-credit-c"> 
          <p class="save-credit">Saved Credit card </p>
         @foreach ($saved_cards as $index => $saved_card)
            <div class="firstmn">
              <div class="input-mn">
                <input type="radio" id="saved_card" name="Saved Card" value="{{$saved_card['cardRef']}}" @if (!$index) {!! "checked" !!} @endif>
              </div> 
              <div class="acc-mn"> 
                {{$saved_card['cardDisplay']}} 
              </div>
            </div>
         @endforeach
        </div>
        
         {{Form::text('cvv_saved', "", array('class' => 'form-control', 'placeholder' => 'CVV', 'id'=>'cvv_saved'))}}
         @endif
         @endif
         
    <div id="new_cc">
      @if ($saved_cards) 
        <!-- <p class="or-option"> or </p> -->
      @endif

      <label for="full_name">Card details</label>
      <input type="text" id="full_name" name="full_name" class="tile-case" placeholder="Card holder name"><br>

      <div class="row">      
      <div id="spreedly-number" style="width:150px; height:20px; border: 1px solid"></div>

      <input type="tel" id="month" name="month" maxlength="2" placeholder="MM" style="width: 55px;">
      <input type="tel" id="year" name="year" maxlength="4" placeholder="YYYY" style="width: 65px;">

      <div id="spreedly-cvv" style="width:58px; height:20px; border: 2px solid "></div>
      </div>

      <div class="row"> 
      <span class="savedcardt">Save card &nbsp; <input type="checkbox" checked="checked"></span>
      </div>  
    </div>

         <!--Billing Address-->
         <div class="woocommerce-billing-fields">
         <h3 style="text-transform: none;">Shipping address</h3>
         <div class="woocommerce-billing-fields__field-wrapper">
         <p class="form-row form-row-first group" id="billing_first_name_field">
         {{Form::text('customer_first_name', $customer_data['cus_add_first_name'], array('class' => 'customer_first_name tile-case','id' => 'customer_first_name', 'placeholder' => 'First name'))}}
         </p>
         <p class="form-row form-row-last group" id="billing_last_name_field">
         {{Form::text('customer_last_name', $customer_data['cus_add_last_name'], array('class' => 'customer_last_name customer_last_check tile-case', 'id' => 'customer_last_name_test', 'placeholder' => 'Last name'))}}
         </p>
         <p class="group form-row form-row-wide address-field validate-required" id="billing_address_1_field">
         {{Form::text('cus_add_address1', $customer_data['cus_add_address1'], array('class' => 'cus_add_address1 tile-case', 'id' => 'cus_add_address1', 'placeholder' => 'Address line 1'))}}
         </p>
         <p class="group form-row form-row-wide address-field" id="billing_address_2_field" >
         {{Form::text('cus_add_address2', $customer_data['cus_add_address2'], array('class' => 'cus_add_address2 tile-case','id' => 'cus_add_address2', 'placeholder' => 'Address line 2'))}}
         </p>
         <!---<p class="form-row form-row-wide address-field" id="billing_country_field">
            <select name="cus_add_country1" id="bill_country" class="country_to_state country_select cus_add_country form-control gds-cr" country-data-default-value="{{$customer_data['country_code']}}" placeholder="country" country-data-region-id="gds-cr-1"></select> 
            </p>
            <p class="form-row form-row-wide address-field " id="billing_state_field" >
            <select name="cus_add_province1" class="state_select cus_add_province form-control" placeholder="province" id="gds-cr-1" region-data-default-value="{{$customer_data['province']}}"></select>
            </p>
            
            <p class="group form-row form-row-wide  address-field" id="billing_postcode_field" >
            {{Form::text('cus_add_zip', $customer_data['cus_add_zip'], array('class' => 'cus_add_zip','id' => 'cus_add_zip', 'placeholder' => 'Zipcode'))}}
            </p>
            
            --->
         <p class="form-row form-row-wide address-field" id="billing_city_field" data-priority="70">
         {{Form::text('cus_add_city', $customer_data['cus_add_city'], array('class' => 'cus_add_city tile-case','id' => 'cus_add_city', 'placeholder' => 'City'))}}
         </p>

         <div class="shipping_class">  
         <div class="row pt-0">

         <div class="col-md-41 country-btm  country-fld pr-0" >
         <label class="">Country</label>
         <input name="country" type="text" class="form-control usd" id="country" value="{{@$cart_data['h_country']}}" disabled>
         </div>
            
         <div class="col-md-41 state-fld pr-0 ml-3">
         <div class="form-group form-group-default form-group-default-select2">
         <label class="">State</label>
         <input name="state" type="text" class="form-control usd" id="state" value="{{@$cart_data['h_state']}}" disabled>
         </div>
         </div>

         <div class="col-md-41 zip-fld pr-0">
         <div class="form-group form-group-default input-group fw-100">
         <div class="form-input-group">
         <label>ZIP code</label>
         <input name="zipcode" type="tel" class="form-control usd" id="zip_code" value="{{@$cart_data['h_zipcode']}}" disabled>
         </div>
         </div>
         </div>         

         <!--Ship to a different address-->
         <div class="shipping-address shipping-address-desk">
         <h3 id="ship-to-different-address">
         <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
         <span>Ship to a different address?</span><input type="checkbox" id="checkbox_green" value="green">
         </label>
         </h3>
         <div class="green box shipping_address" style="">  
         <div class="woocommerce-shipping-fields__field-wrapper">
         <p class="group section__content group form-row">
         {{Form::email('green_email', '', array('class' => 'green_email','id' => 'green_email', 'placeholder' => 'Email'))}}
         </p>
         <p class="group form-row form-row-wide" id="green_name">
         {{Form::text('green_name','' ,  array('class' => 'green_name', 'id' => 'green_first_name', 'placeholder' => 'First Name'))}}
         </p>
         <p class="group section__content group form-row">
         {{Form::text('green_last','' ,  array('class' => 'green_last', 'id' => 'green_last', 'placeholder' => 'Last Name'))}}
         </p>
         
         <p class="group form-row form-row-wide address-field validate-required" id="billing_address_1_field">
         {{Form::text('green_add_address1', '', array('class' => 'green_add_address1', 'id' => 'green_add_address1', 'placeholder' => 'Address Line 1'))}}
         </p>
         <p class="group form-row form-row-wide address-field" id="green_billing_address_2_field" >
         {{Form::text('green_add_address2', '', array('class' => 'green_add_address2','id' => 'green_add_address2', 'placeholder' => 'Address Line 2'))}}
         </p>

         <p class="group form-row form-row-wide address-field" id="billing_city_field" data-priority="70">
         {{Form::text('green_city','' ,  array('class' => 'green_city', 'id' => 'green_city', 'placeholder' => 'City'))}}
         </p>

         <div class="col-md-41 country-btm  country-fld pr-0" >
         <label class="">Country</label>
         <p class="group form-row form-row-wide" >
         <input name="green_add_country" type="text" class="green_country_to_state country_select    cus_add_country form-control gds-cr" id="gds-cr-3" value="{{@$cart_data['h_country']}}" country-data-default-value="{{@$cart_data['h_country']}}" disabled>
         </p>
         </div>

         <div class="col-md-41 state-fld pr-0 ml-3">
         <div class="form-group form-group-default form-group-default-select2">
         <label class="">State</label>
         <p class="group form-row form-row-wide" >
         <input name="green_add_province" type="text" class="green_state_select cus_add_province form-control"  value="{{@$cart_data['h_state']}}" region-data-default-value="{{@$cart_data['h_state']}}" id="gds-cr-3" disabled>
         </p>
        </div>
        </div>

        <div class="col-md-41 zip-fld pr-0">
         <div class="form-group form-group-default input-group fw-100">
         <div class="form-input-group">
         <label>ZIP code</label>
         <p class="group form-row form-row-wide  address-field" id="billing_postcode_field">
         <input name="green_add_zip" type="tel" class="green_add_zip" id="green_add_zip" value="{{@$cart_data['h_zipcode']}}" disabled>

         </p>
        </div>
        </div>
        </div>

         <p class="group form-row form-row-wide 1" id="billing_phone_field">
         {{Form::tel('green_phone', '', array('class' => 'green_phone', 'id' => 'green_phone', 'placeholder' => 'Mobile number'))}}
         </p>
         </div> 
         </div>
                                 
         </div><!--End Right Section-->



        <div class="shipping-address shipping-address-mob">
         <h3 id="ship-to-different-address">
         <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
         <span>Ship to a different address?</span><input type="checkbox" id="checkbox_red" value="red">
         </label>
         </h3>
         <div class="red box shipping_address" style="">      
         <div class="woocommerce-shipping-fields__field-wrapper">
         <p class="group section__content group form-row">
         {{Form::email('red_email', '', array('class' => 'red_email','id' => 'red_email', 'placeholder' => 'Email'))}}
         </p>
         <p class="group form-row form-row-wide" id="red_name">
         {{Form::text('red_name','' ,  array('class' => 'red_name', 'id' => 'red_first_name', 'placeholder' => 'First Name'))}}
         </p>
         <p class="group section__content group form-row">
         {{Form::text('red_last','' ,  array('class' => 'red_last', 'id' => 'red_last', 'placeholder' => 'Last Name'))}}
         </p>
         <p class="group form-row form-row-wide address-field validate-required" id="billing_address_1_field">
         {{Form::text('red_add_address1', '', array('class' => 'red_add_address1', 'id' => 'red_add_address1', 'placeholder' => 'Address Line 1'))}}
         </p>
         <p class="group form-row form-row-wide address-field" id="red_billing_address_2_field" >
         {{Form::text('red_add_address2', '', array('class' => 'red_add_address2','id' => 'red_add_address2', 'placeholder' => 'Address Line 2'))}}
         </p>
         <p class="group form-row form-row-wide address-field" id="billing_city_field" data-priority="70">
         {{Form::text('red_city','' ,  array('class' => 'red_city', 'id' => 'red_city', 'placeholder' => 'City'))}}
         </p>

         <div class="row pt-0">
         <div class="col-md-4 country-btm  country-fld pr-0" >
         <label class="">Country</label>
         <p class="group form-row form-row-wide" >
         <input name="red_add_country" type="text" class="red_country_to_state" id="country" value="{{@$cart_data['h_country']}}" country-data-default-value="{{@$cart_data['h_country']}}" disabled>
         </p>
         </div>

         <div class="col-md-4 state-fld pr-0 ml-3">
         <div class="form-group form-group-default form-group-default-select2">
         <label class="">State</label>
         <p class="group form-row form-row-wide" >
         <input name="red_add_province" type="text" class="red_state_select cus_add_province form-control"  value="{{@$cart_data['h_state']}}" region-data-default-value="{{@$cart_data['h_state']}}" disabled>
         </p> 
         </div>
         </div>

         <div class="col-md-4 zip-fld pr-0">
         <div class="form-group form-group-default input-group fw-100">
         <div class="form-input-group">
         <label>ZIP code</label>
         <p class="group form-row form-row-wide  address-field" id="billing_postcode_field">
         <input name="red_add_zip" type="tel" class="red_add_zip" id="red_add_zip" value="{{@$cart_data['h_zipcode']}}" disabled>
         </p>
         </div>
         </div>
         </div>
         </div>

         <p class="group form-row form-row-wide 3" id="billing_phone_field">
         {{Form::tel('red_phone', '', array('class' => 'red_phone', 'id' => 'red_phone', 'placeholder' => 'Mobile number'))}}
         </p>

         </div>     
         </div>
         <!-- <div class="step__footer">
         <span class="btn btn-default" id="submit-add-card-button-mob" style="display: none;">Pay now</span>
         <a class="step__footer__previous-link" href="https://{{$shop}}/cart">
         <svg focusable="false" aria-hidden="true" class="icon-svg icon-svg--color-accent icon-svg--size-10 previous-link__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
         <path d="M8 1L7 0 3 4 2 5l1 1 4 4 1-1-4-4"></path>
         </svg>
         <span class="step__footer__previous-link-content">Return to cart</span>
         </a>
         </div> -->
         </div>





         <p class="group form-row form-row-wide 2" id="billing_phone_field">
          <label>Mobile number</label>
         {{Form::tel('customer_phone', $customer_data['cus_add_phone'], array('class' => 'customer_phone', 'id' => 'customer_phone', 'placeholder' => 'Mobile number'))}}
         </p>


          <div class="step__footer mobfotr">
         <!-- <span class="btn btn-default" id="submit-add-card-button" style="display:none" disabled>Confirm</span> -->
         <input id="submit-add-card-button" type="submit" value="Pay now" disabled><br>
         
         </div> 

         <p class="m-t-10">All transactions are secure and encrypted.</p>


         <div class="step__footer">
         <span class="btn btn-default" id="submit-add-card-button-mob" >Pay now</span>
         <a id="one_step_back" class="step__footer__previous-link" href="https://{{$shop}}/cart">
         <svg focusable="false" aria-hidden="true" class="icon-svg icon-svg--color-accent icon-svg--size-10 previous-link__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
         <path d="M8 1L7 0 3 4 2 5l1 1 4 4 1-1-4-4"></path>
         </svg>
         <span class="step__footer__previous-link-content">Return to cart</span>
         </a>
         </div>


         <!----<form id="discount_form">
            <div class="msg_div" id="waiting_div_discount" style="display:none">
            <p class="m-t-10">Please wait while we fetch available discount rates...</p> <p class="m-t-10"><img src="public/img/91.gif" width="64" height="64" style="max-width: 20px; max-height: 20px" alt=""/></p>
            </div>
            <div id="err_msg" style="display:none"></div>
            <div id="show_msg" style="display:none"></div>
            <div class="input-group transparent1" >
              <div class="input-group-prepend">
              <span class="input-group-text transparent1"><i class="fa fa-money"></i>
                </span>
              </div>
              
              <input type="text" placeholder="Discount code" class="form-control2" name="c_code">
             <button class="btn btn-default btn-sm m-t-10" id="dis_code" type="submit">Apply Code</button>
            </form>--->
         <div class="" id="ship_err_msg" style="display:none;"></div>
         <div class="msg_div" id="waiting_div_ship" style="display:none">
         <p class="m-t-10">Please wait while we fetch available shipping rates...</p> <p class="m-t-10"><img src="public/img/91.gif" width="64" height="64" style="max-width: 20px; max-height: 20px" alt=""/></p>
         </div>
         <div class="card-body" id="shipping_carriers" onkeyup='saveValue(this);'> 
         </div>
         <!--<div class="card-body" id="updated_price" > 
            </div>--->
         </div>
         </div>
         </div>
         </div>
         </div>
         
         {{Form::hidden('raw_data', $raw, array('id' => 'raw_data'))}}
         {{Form::hidden('user', $user, array('id' => 'user'))}}
         {{ Form::close() }}
         <!--<div id="calculate_shipping">calculate_shipping</div>
            <div id="loading" style="display:none;"><img src="https://loading.io/spinners/spinner/lg.ajax-spinner-preloader.gif" /></div>--->
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
      });
    </script>
  @endif
@endif  

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
      // alert('AERTTT');
      console.log("tokenize the card");

      var requiredFields = {};

      // Get required, non-sensitive, values from host page
      requiredFields["full_name"] = document.getElementById("full_name").value;
      requiredFields["month"] = document.getElementById("month").value;
      requiredFields["year"] = document.getElementById("year").value;
      /*requiredFields['address1']= "";
      requiredFields['address2']= "";
      requiredFields['city']="";
      requiredFields['state']= "";
      requiredFields['zip']="";*/
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
           

  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }

    function show2() {
      document.getElementById('submit-add-card-button').style.display = 'block';
    }

    $(document).ready(function () {
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
      $.ajax({
        type: "GET",
        url: "{{secure_asset('swapp/checkout/fetch-afstates')}}?code=" + 'AF',
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
      $('#country').change(function () {
        var countryID = $(this).val();
        localStorage.setItem('countryid', countryID);
        localStorage.setItem('country', $("#country option:selected").html());
        if (countryID) {
          $.ajax({
            type: "GET",
            url: "{{secure_asset('swapp/checkout/fetch-states')}}?code=" + countryID,

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
            url: "{{secure_asset('swapp/checkout/fetch-states')}}?code=" + countryID,

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
    $(function () {
      window.onbeforeunload = function (e) {
        run_ajax();
        return true;
      }
      if ($(window).width() >= 767) {
        console.log('ekk');
        var confirmButton = $('#submit-add-card-button');
      } else {
        console.log('do');
        var confirmButton = $('#submit-add-card-button-mob');
      }

      confirmButton.click(function (e) {
        e.preventDefault();
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
        if ($('#checkbox_green').is(':checked')) {
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
        } else if ($('#checkbox_red').is(':checked')) {
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
        if ($("#saved_card").val()) { 
          console.log('else2');
          // console.log(customer_email.val());
          // console.log(customer_email.val().length);
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
          // if (billing_address_2_field.val().length == 0) {
          //   border_function(billing_address_2_field);
          //   i++;
          // } else {
          //   billing_address_2_field.removeAttr('style');
          // }
          // if (billing_phone_field.val().length == 0) {
          //   border_function(billing_phone_field);
          //   i++;
          // } else {
          //   billing_phone_field.removeAttr('style');
          // }
          //alert(i);
          if (i == 0) {
            // tokenizeCard();
            mypopup();
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
          // if (!isCardValid) {
          //   border_function(cardNumber);
          //   i++;
          // } else {
          //   cardNumber.removeAttr('style');
          // }
          /*if (!isCvvValid) {
            tokenizeCard();
            // border_function(CVV);
            // i++;
          } else {
            // CVV.removeAttr('style');
          }*/
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
          // if (billing_address_2_field.val().length == 0) {
          //   border_function(billing_address_2_field);
          //   i++;
          // } else {
          //   billing_address_2_field.removeAttr('style');
          // }
          // if (billing_phone_field.val().length == 0) {
          //   border_function(billing_phone_field);
          //   i++;
          // } else {
          //   billing_phone_field.removeAttr('style');
          // }
          if (i == 0) {
            if (confirm('Are You sure your details are correct?')) {
              tokenizeCard()
              // mypopup();
            }
            /* if(swal("Confirm!", "Your order is confirmed", "success")){
                  mypopup();
                }  */
          }

        }
      });
    });

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
        url: 'https://pay.kachyng.com/swapp/checkout-cardsubmit/cardsubmit',
        type: 'POST',
        data: {
          'payment_form': $('#payment-form').serialize(),
          'shipp_value': shippVal
        },
        beforeSend: function () {
          $('#loading').show();
          console.log('Order is Processing');
        },
        success: function (response) {
          console.log(response);
          var objJSON = JSON.parse(response);
          if (objJSON.success == 'true') {
            swal({
              type: 'info',
              html: 'Order Complete Successfully',
              showCloseButton: true
            });
            window.onbeforeunload = null;
            window.location.href = objJSON.redirect_url;
          } else {
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
      $('#checkbox_green').click(function () {
        var inputValue = $(this).attr("value");
        if ($(this).prop("checked") == true) {
          $("." + inputValue).show();
        } else {
          $("." + inputValue).hide();
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
            toggle_switch.html('Show Order Summery');
          } else {
            //change the button label to be 'Hide'
            toggle_switch.html('Hide Order Summery');
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
    setTimeout(function () {
      var customer_email = $('.customer_email').val();
      if (customer_email) {
        swal({
          type: 'info',
          html: 'You are not doing anything. we send your order as Abandoned Order',
          showCloseButton: true
        });
        run_ajax();
      }
    }, 600000);

    //}, 5000);

    // abandoned_checkouts
    function run_ajax() {
      var customer_email = $('.customer_email').val();
      if (customer_email) {
        console.log('customer_email: ' + customer_email);
        $.ajax({
          url: 'https://pay.kachyng.com/swapp/orders/abandoned_checkouts',
          type: 'POST',
          data: {
            'raw_data': $('#raw_data').val(),
            'customer_email': customer_email,
          },
          success: function (response) {
            console.log(response);
          }
        });
      }
    }


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