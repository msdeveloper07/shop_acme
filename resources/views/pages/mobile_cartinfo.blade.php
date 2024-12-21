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
                              <span id="pname">{{@$cart_data['productTitle']}}</span>
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
                     {{@$cart_data['shipping_rates']}}
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
                     <span class="woocommerce-Price-currencySymbol" >{{@$currency_symbol}}</span> 0.00
                     </strong>
                  </td>
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
               <tr class="shipping_method">
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