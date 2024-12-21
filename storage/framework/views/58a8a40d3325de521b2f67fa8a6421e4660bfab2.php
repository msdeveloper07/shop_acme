<?php
   $raw_DATA = json_decode($raw, true);
   $productSID = json_encode($raw_DATA['product_id']);
?>
<div class="shipping-section" style="display: none;">
   <div class="stepwizard">
      <div class="stepwizard-row setup-panel">
         <div class="stepwizard-step"> 
            <a href="https://<?php echo e($shop); ?>/cart" type="button" class="btn btn-default" >Cart <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
         </div>
         
         <div class="stepwizard-step"> 
            <a href="javascript:void(0)" id="back-info-page" type="button" class="btn btn-default">Information <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
         </div>
         <div class="stepwizard-step"> 
            <a href="javascript:void(0)" type="button" class="btn btn-default btn-success">Shipping <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
         </div>
         <div class="stepwizard-step"> 
            <a href="javascript:void(0)" type="button" class="btn btn-default" >Payment <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
         </div>
      </div>
   </div>

   <section class="round-border">
   <div class="top-header">
      <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__icon">
         <path d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z"></path>
      </svg>
      <p href="#collapse2" class="nav-toggle-2">Show Order Summery <i class="fa fa-chevron-down" aria-hidden="true"></i></p>
      <?php $__currentLoopData = $cart_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <strong href="" class="nav-price"><?php echo e($currency_symbol); ?>

      <?php echo e(number_format(@$cart_data['shipping_rates'] + @$cart_data['price'], 2)); ?>

      </strong>
   </div>
   <div id="collapse2" style="display:none">
      <div id="order_review2">
         <table class="product-table">
            <tbody>
               <tr class="product mobile-view">
                  <td class="product__image">
                     <div class="product-thumbnail">
                        <div class="product-thumbnail__wrapper">
                           <div class="prdct-img-mobile">
                              <div class="thubnail-img-width"><img class="product-thumbnail__image" src="<?php echo e($cart_data['image']); ?>"><span class="quantity-digits"><?php echo e(@$cart_data['quantity']); ?></span></div>
                              <span id="pname" attr_pid="<?php echo e(@$cart_data['product_id']); ?>"><?php echo e(@$cart_data['productTitle']); ?></span>
                              <span id="pdescription"><?php echo e(@$cart_data['productDES']); ?></span>
                           </div>
                           <?php if(array_key_exists('product_price',$cart_data)): ?>
                           <span class="product__price">
                           <strong><?php echo e($currency_symbol); ?> <?php echo e(@$cart_data['product_price']); ?></strong>
                           </span>
                           <?php endif; ?>
                           <!--<span class="product-thumbnail__quantity"><?php echo e($cart_data['quantity']); ?></span>-->
                        </div>
                     </div>
                  </td>
                  <td class="product__description">
                     <span></span>
                  </td>
               </tr>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
         </table>
         <!--  <div class="quantity-main">
            <div class="quantity-sec">
            <strong>Quantity : </strong><span><strong><?php echo e(@$cart_data['quantity']); ?></strong></span>
            </div>
            </div> -->
         <table class="shop_table">
            <tr class="tax_line">
               <th>Tax</th>
               <td>
                  <strong id="show_tax_line">
                  <span class="woocommerce-Price-currencySymbol" ><?php echo e(@$currency_symbol); ?></span> <?php echo e($tax); ?>

                  </strong>
               </td>
            </tr>
            <tbody>
               <tr class="cart-subtotal">
                  <th><strong>Subtotal</strong></th>
                  <td>
                     <strong>
                     <span class="woocommerce-Price-currencySymbol"><?php echo e($currency_symbol); ?></span><?php echo e($total_price); ?>

                     </strong>
                  </td>
               </tr>
               <tr>
                  <th><strong>Shipping</strong></th>
                  <?php if(@$cart_data['shipping_rates'] != 0): ?> 
                  <td><strong><span class="woocommerce-Price-currencySymbol"><?php echo e(@$currency_symbol); ?></span>
                     <span id="shipp_price" class="shipp_price_mob"> 
                     <?php echo e(@$cart_data['shipping_rates']); ?>

                     </span>
                     </strong>
                  </td>
                  <?php else: ?>
                  <td>
                     <span id="shipp_price" class="shipp_price_dek">
                     <strong>Free</strong>
                     </span>
                  </td>
                  <?php endif; ?>
               </tr>
               <tr class="discount_line" style="display: none;">
                  <th>Discount</th>
                  <td>
                     <strong id="discount_line_1">
                     <span class="woocommerce-Price-currencySymbol" ><?php echo e(@$currency_symbol); ?></span>
                     0.00
                     </strong>
                  </td>
               </tr>
               <tr class="shipping_method">
                  <th>Shipping method</th>
                  <td>
                     <strong id="shipping_method_line" class="shipping_method_line_mob">
                     <?php echo e(round(@$cart_data['h_shipping_method'], 2)); ?>

                     </strong>
                  </td>
               </tr>
            </tbody>
            <tfoot>
               <tr class="order-total">
                  <th>Total</th>
                  <td><strong id="total_mob">
                     <span class="woocommerce-Price-currencySymbol"><?php echo e(@$currency_symbol); ?></span> 
                     <?php echo e(number_format(@$cart_data['shipping_rates'] + @$total_price, 2)); ?>

                     </strong> 
                  </td>
               </tr>
            </tfoot>
         </table>
      </div>
   </section>

   <div class="step__sections">
      <div class="section">
         <div class="content-box">
            <div role="table" class="content-box__row content-box__row--tight-spacing-vertical">
               <div role="row" class="review-block">
                  <div class="review-block__inner">
                     <div role="rowheader" class="review-block__label">
                        Contact
                     </div>
                     <div role="cell" class="review-block__content">
                        <bdo dir="ltr"><?php echo e(@$customer_data['customer_email']); ?></bdo>
                     </div>
                     <div class="review-change_value">
                        <span aria-hidden="true"><a href="javascript:void(0)">Change</a></span>
                     </div>
                  </div>
               </div>
               <div role="row" class="review-block">
                  <div class="review-block__inner">
                     <div role="rowheader" class="review-block__label">
                        Ship to
                     </div>
                     <div role="cell" class="review-block__content">
                        <address class="address address--tight">
                        </address>
                     </div>
                     <div class="review-change_shipadd">
                        <span aria-hidden="true"><a href="javascript:void(0)">Change</a></span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>


      <!-- <div class="section section--shipping-method">
         <div class="section__header">
            <h2 class="section__title" id="main-header" tabindex="-1">
               Shipping Package
            </h2>
         </div>
         <div class="section__content">
            <fieldset class="content-box">
               <div class="content-box__row">
                  <?php $__currentLoopData = $kachyng_shippping; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $shiprate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="radio-wrapper" data-shipping-package="usps-FirstPackage-3.25">
                     <div class="radio__input">
                     <input type="radio" id="shipping_package" name="shipping_package" value="" shipping_package="<?php echo e($shiprate->package_name); ?>" carrier_id="<?php echo e($shiprate->carrier_id); ?>" <?php if(!$index): ?> <?php echo "checked"; ?> <?php endif; ?>>
                     </div>
                     <label class="radio__label" aria-hidden="true">
                        <span class="radio__label__primary" data-shipping-package-label-title="<?php echo e($shiprate->package_name); ?>">
                        <?php echo e($shiprate->package_name); ?>

                        <span class="small-text"></span>
                     </label>
                     <span class="radio__label__accessory">
                     <span class="content-box__emphasis">
                     
                     </span>
                     </span>      
                  </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               </div>
               <input type="button" id="calculate_shipping_rate" value="Calculate to Shipping">
            </fieldset>
         </div>
      </div> -->



      <div class="section section--shipping-method">
         <div class="section__header">
            <h2 class="section__title" id="main-header" tabindex="-1">
               Shipping Package
            </h2>
         </div>
         <div class="section__content">
            <fieldset class="content-box" data-shipping-methods=""> 
               <div class="content-box__row" id="append_shipping_method">
                  
               </div>
               <input type="button" id="continue-to-payment" value="Continue to Payment" style="">
               <div class="lst-btn-sec">
                  <div class="return-btn-sec-shipping"><a id="back-info"><i class="fa fa-chevron-left" aria-hidden="true"></i> Return to Information</a></div>
               </div>
            </fieldset>
         </div>
      </div>
   </div>
</div>
<div class="section-payment-info" style="display: none;">
   <div class="stepwizard-row setup-panel">
      <div class="stepwizard-step"> 
         <a href="https://<?php echo e($shop); ?>/cart" type="button" class="btn btn-default" >Cart <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
      </div>
      
      <div class="stepwizard-step"> 
         <a href="javascript:void(0)" type="button" id="backtoinfo" class="btn btn-default">Information <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
      </div>
      <div class="stepwizard-step"> 
         <a href="javascript:void(0)" type="button" id="backtoship" class="btn btn-default" >Shipping <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
      </div>
      <div class="stepwizard-step"> 
         <a href="javascript:void(0)" type="button" class="btn btn-default btn-success">Payment <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
      </div>
   </div>

   <section class="round-border">
   <div class="top-header">
      <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__icon">
         <path d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z"></path>
      </svg>
      <p href="#collapse3" class="nav-toggle-3">Show Order Summery <i class="fa fa-chevron-down" aria-hidden="true"></i></p>
      <?php $__currentLoopData = $cart_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
         // echo"<pre>"; print_r($cart_data);  die;
         ?>
      <strong href="" class="nav-price"><?php echo e($currency_symbol); ?>

      <?php echo e(number_format(@$cart_data['shipping_rates'] + @$cart_data['price'], 2)); ?>

      </strong>
   </div>
   <div id="collapse3" style="display:none">
      <div id="order_review3">
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
                              <div class="thubnail-img-width"><img class="product-thumbnail__image" src="<?php echo e($cart_data['image']); ?>"><span class="quantity-digits"><?php echo e(@$cart_data['quantity']); ?></span></div>
                              <span id="pname" attr_pid="<?php echo e(@$cart_data['product_id']); ?>"><?php echo e(@$cart_data['productTitle']); ?></span>
                              <span id="pdescription"><?php echo e(@$cart_data['productDES']); ?></span>
                           </div>
                           <?php if(array_key_exists('product_price',$cart_data)): ?>
                           <span class="product__price">
                           <strong><?php echo e($currency_symbol); ?> <?php echo e(@$cart_data['product_price']); ?></strong>
                           </span>
                           <?php endif; ?>
                           <!--<span class="product-thumbnail__quantity"><?php echo e($cart_data['quantity']); ?></span>-->
                        </div>
                     </div>
                  </td>
                  <td class="product__description">
                     <span></span>
                  </td>
               </tr>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
         </table>
         <!--  <div class="quantity-main">
            <div class="quantity-sec">
            <strong>Quantity : </strong><span><strong><?php echo e(@$cart_data['quantity']); ?></strong></span>
            </div>
            </div> -->
         <table class="shop_table">
            <tr class="tax_line">
               <th>Tax</th>
               <td>
                  <strong id="show_tax_line">
                  <span class="woocommerce-Price-currencySymbol" ><?php echo e(@$currency_symbol); ?></span> <?php echo e($tax); ?>

                  </strong>
               </td>
            </tr>
            <tbody>
               <tr class="cart-subtotal">
                  <th><strong>Subtotal</strong></th>
                  <td>
                     <strong>
                     <span class="woocommerce-Price-currencySymbol"><?php echo e($currency_symbol); ?></span><?php echo e($total_price); ?>

                     </strong>
                  </td>
               </tr>
               <tr>
                  <th><strong>Shipping</strong></th>
                  <?php if(@$cart_data['shipping_rates'] != 0): ?> 
                  <td><strong><span class="woocommerce-Price-currencySymbol"><?php echo e(@$currency_symbol); ?></span>
                     <span id="shipp_price"> 
                     <?php echo e(@$cart_data['shipping_rates']); ?>

                     </span>
                     </strong>
                  </td>
                  <?php else: ?>
                  <td>
                     <span id="shipp_price">
                     <strong>Free</strong>
                     </span>
                  </td>
                  <?php endif; ?>
               </tr>
               <tr class="discount_line" style="display: none;">
                  <th>Discount</th>
                  <td>
                     <strong id="discount_line_1">
                     <span class="woocommerce-Price-currencySymbol" ><?php echo e(@$currency_symbol); ?></span>
                     0.00
                     </strong>
                  </td>
               </tr>
               <tr class="shipping_method">
                  <th>Shipping method</th>
                  <td>
                     <strong id="shipping_method_line" class="shipping_method_line_mob">
                     <?php echo e(round(@$cart_data['h_shipping_method'], 2)); ?>

                     </strong>
                  </td>
               </tr>
            </tbody>
            <tfoot>
               <tr class="order-total">
                  <th>Total</th>
                  <td><strong>
                     <span class="woocommerce-Price-currencySymbol"><?php echo e(@$currency_symbol); ?></span> <?php echo e(number_format(@$cart_data['shipping_rates'] + @$total_price, 2)); ?></strong> 
                  </td>
               </tr>
            </tfoot>
         </table>
      </div>
   </section>

   <div class="content-box">
      <div role="table" class="content-box__row content-box__row--tight-spacing-vertical">
         <div role="row" class="review-block">
            <div class="review-block__inner">
               <div role="rowheader" class="review-block__label">
                  Contact
               </div>
               <div role="cell" class="review-block__content">
                  <bdo dir="ltr"><?php echo e(@$customer_data['customer_email']); ?></bdo>
               </div>
               <div class="review-change_value">
                  <span aria-hidden="true"><a href="javascript:void(0)">Change</a></span>
               </div>
            </div>
         </div>
         <div role="row" class="review-block">
            <div class="review-block__inner">
               <div role="rowheader" class="review-block__label">
                  Ship to
               </div>
               <div role="cell" class="review-block__content">
                  <address class="address address--tight">
                  </address>
               </div>
               <div class="review-change_value">
                  <span aria-hidden="true"><a href="javascript:void(0)">Change</a></span>
               </div>
            </div>
         </div>
         <div role="row" class="review-block">
            <div class="review-block__inner">
               <div role="rowheader" class="review-block__label">
                  Method
               </div>
               <div role="cell" class="review-block__content" id="ship_method" data-review-section="shipping-cost">
                  <strong class="emphasis">
                  <span class="skeleton-while-loading--inline" id="ship_price"></span>
                  </strong>
               </div>
               <div class="review-change_ship_add">
                  <span aria-hidden="true"><a href="javascript:void(0)">Change</a></span>
               </div>
            </div>
            <!--<div role="cell" class="review-block__link">
               <a href="/26218668/checkouts/e741ffad22a3f537a9ccda21c0e51f52?step=shipping_method">
               <span aria-hidden="true">Change</span>
               <span class="visually-hidden">Change shipping method</span>
               </a>    
               </div>-->
         </div>
      </div><br>
      <?php if(($user) != 'guest'): ?>
      <?php if($saved_cards): ?>
      <div id="add-class"><span>Use existing card</span></div>
      <div id="remove-class" style="display: none;"><span>Existing User Card</span></div>
      <div class="saved-credit-c">
         <p class="save-credit">Saved Credit card </p>

         <?php if(count($saved_cards) == '1'): ?>
         <div class="dropp">
            <div class="dropp-header">
               <span class="dropp-header__title js-value"><?php echo e($saved_cards[0]['cardType']); ?> <?php echo e($saved_cards[0]['cardDisplay']); ?> <?php echo e($saved_cards[0]['expiry']); ?></span> 
                
            </div>
            <div class="dropp-body">
               <label class="" for="saved_cards">
                  <span id="sc">
                  <?php echo e($saved_cards[0]['cardType']); ?> <?php echo e($saved_cards[0]['cardDisplay']); ?> <?php echo e($saved_cards[0]['expiry']); ?>

                  </span>
                  <input type="radio" id="saved_card" name="Saved Card" value="<?php echo e($saved_cards[0]['cardRef']); ?>">
                  <div class="check-my-btn" value="<?php echo e($saved_cards[0]['cardType']); ?> <?php echo e($saved_cards[0]['cardDisplay']); ?>  <?php echo e($saved_cards[0]['expiry']); ?>">
                     <div class="inside"></div>
                  </div>
               </label>
            </div>
         </div>
         <?php else: ?>
         <div class="dropp">
            <div class="dropp-header">
               <span class="dropp-header__title js-value"><?php echo e($saved_cards[0]['cardType']); ?> <?php echo e($saved_cards[0]['cardDisplay']); ?> <?php echo e($saved_cards[0]['expiry']); ?></span> 
               <a href="#" class="dropp-header__btn js-dropp-action">
               <i class="icon"></i>
               </a> 
            </div>
            <div class="dropp-body">
               <?php $__currentLoopData = $saved_cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saved_card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <label class="" for="saved_cards">
                  <span id="sc">
                  <?php echo e($saved_card['cardType']); ?> <?php echo e($saved_card['cardDisplay']); ?> <?php echo e($saved_card['expiry']); ?>

                  </span>
                  <input type="radio" id="saved_card" name="Saved Card" value="<?php echo e($saved_card['cardRef']); ?>">
                  <div class="check-my-btn" value="<?php echo e($saved_card['cardType']); ?> <?php echo e($saved_card['cardDisplay']); ?>  <?php echo e($saved_card['expiry']); ?>">
                     <div class="inside"></div>
                  </div>
               </label>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
         </div>
         <?php endif; ?>
         
      </div>
      <?php echo e(Form::text('cvv_saved', "", array('class' => 'form-control', 'placeholder' => 'CVV', 'id'=>'cvv_saved', 'style'=>'display:none' ))); ?>

      <?php endif; ?>
      <?php endif; ?>
      <div id="new_cc">
         <?php if($saved_cards): ?> 
         <!-- <p class="or-option"> or </p> -->
         <?php endif; ?>
         <!-- <label for="full_name">Payment</label> -->
         <div class="panel-heading">
         <h5 class="panel-title margin-top-bottom">Payment
         <span class="sub-heading-btm">All transactions are secure and encrypted.</span></h5>
         </div>
         <input type="text" id="full_name" name="full_name" class="tile-case" placeholder="Card holder name"><br>
         <div class="row">
            <div id="spreedly-number" style="width:150px; height:20px; border: 1px solid"></div>
            <input type="tel" id="month" name="month" maxlength="2" placeholder="MM" style="width: 55px;">
            <input type="tel" id="year" name="year" maxlength="4" placeholder="YYYY" style="width: 65px;">
            <div id="spreedly-cvv" style="width:58px; height:20px; border: 2px solid "></div>
         </div>
         <!-- <div class="row"> 
            <span class="savedcardt"><input type="checkbox" checked="checked">&nbsp; Save card</span>
         </div> -->
      </div>

      <div class="panel-heading">
         <h5 class="panel-title margin-top-bottom">Billing address<span class="sub-heading-btm">Select the address that matches your card or payment method.</span></h5>
      </div>
      <div role="table" class="contact-information">
         <div role="row" class="contact-information-main">
           <div class="contact-information-inner">
            <div class="radio__input">
               <input type="radio" id="shipping-val-3" name="shipping-val-3" value="same" checked="check">
             </div>
             <label class="radio__label" aria-hidden="true" for="">
                <span class="radio__label__primary">Same as shipping address</span>
                <span class="radio__label__accessory">
                </span>
             </label>
           </div>
         </div>
         <div role="row" class="contact-information-main">
           <div class="contact-information-inner">
            <div class="radio__input">
               <input type="radio" id="shipping-val-3" name="shipping-val-3" value="different" >
             </div>
             <label class="radio__label" aria-hidden="true" for="">
                <span class="radio__label__primary">Use a different billing address </span>
                <span class="radio__label__accessory">
                </span>
             </label>
           </div>
         </div>

         <div class="shipping-address shipping-address-desk">
         <!-- <h3 id="ship-to-different-address">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
            <span>Ship to a different address?</span><input type="checkbox" id="checkbox_green" value="green">
            </label>
         </h3> -->
         <div class="green box shipping_address gnmain" style="">
            <div class="woocommerce-shipping-fields__field-wrapper">
               <p class="group section__content group form-row" style="display: none;">
                  <?php echo e(Form::email('green_email', '', array('class' => 'green_email','id' => 'green_email', 'placeholder' => 'Email'))); ?>

               </p>
               
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <p class="group form-row form-row-wide" id="green_name">
                     <label class="">First Name</label>
                     <?php echo e(Form::text('green_name','' ,  array('class' => 'green_name', 'id' => 'green_first_name', 'placeholder' => 'First Name'))); ?>

                     </p>
                  </div>
                  
                  <div class="form-group col-md-6">
                     <p class="group section__content group form-row">
                     <label class="">Last name</label>
                     <?php echo e(Form::text('green_last','' ,  array('class' => 'green_last', 'id' => 'green_last', 'placeholder' => 'Last Name'))); ?>

                     </p>
                  </div>
               </div>
               
               <p class="group form-row form-row-wide address-field validate-required" id="billing_address_1_field">
                  <label class="">Address Line1</label>
                  <?php echo e(Form::text('green_add_address1', '', array('class' => 'green_add_address1', 'id' => 'green_add_address1', 'placeholder' => 'Address Line1'))); ?>

               </p>
               <p class="group form-row form-row-wide address-field" id="green_billing_address_2_field" >
                  <label class="">Address Line2</label>
                  <?php echo e(Form::text('green_add_address2', '', array('class' => 'green_add_address2','id' => 'green_add_address2', 'placeholder' => 'Address Line2'))); ?>

               </p>
               <p class="group form-row form-row-wide address-field" id="billing_city_field" data-priority="70">
                  <label class="">City</label>
                  <?php echo e(Form::text('green_city','' ,  array('class' => 'green_city', 'id' => 'green_city', 'placeholder' => 'City'))); ?>

               </p>
               
               <div class="main-zip">
               <div class="form-group col-md-6">
                  <div class="col-md-4 state-fld pr-0 ml-3">
                     <div class="form-group form-group-default form-group-default-select2">
                        <label class="">State</label>
                        <p class="group form-row form-row-wide" >
                           <input name="green_add_province" type="text" class="green_state_select cus_add_province form-control"  value="<?php echo e(@$cart_data['h_state']); ?>" region-data-default-value="<?php echo e(@$cart_data['h_state']); ?>" id="gds-cr-3">
                        </p>
                     </div>
                  </div>
               </div>
               <div class="form-group col-md-6">
                  <div class="col-md-4 zip-fld pr-0">
                     <div class="form-group form-group-default input-group fw-100">
                        <div class="form-input-group">
                           <label>ZIP code</label>
                           <p class="group form-row form-row-wide  address-field" id="billing_postcode_field">
                              <input name="green_add_zip" type="tel" class="green_add_zip" id="green_add_zip" value="<?php echo e(@$cart_data['h_zipcode']); ?>">
                           </p>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="col-md-4 country-btm  country-fld pr-0" >
                  <label class="">Country</label>
                  <p class="group form-row form-row-wide" >
                     <input name="green_add_country" type="text" class="green_country_to_state country_select    cus_add_country form-control gds-cr" id="gds-cr-3" value="<?php echo e(@$cart_data['h_country']); ?>" country-data-default-value="<?php echo e(@$cart_data['h_country']); ?>" disabled>
                  </p>
               </div>
               </div>


               
               <p class="group form-row form-row-wide 1" id="billing_phone_field" style="display: none;">
                  <label>Mobile number</label>
                  <?php echo e(Form::number('green_phone', '', array('class' => 'green_phone', 'id' => 'green_phone', 'placeholder' => 'Mobile number'))); ?>

               </p>
            </div>
         </div>
      </div>


      <div class="shipping-address shipping-address-mob">
         <!-- <h3 id="ship-to-different-address">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
            <span>Ship to a different address?</span><input type="checkbox" id="checkbox_red" value="red">
            </label>
         </h3> -->
         <div class="red box shipping_address" style="">
            <div class="woocommerce-shipping-fields__field-wrapper">
               <p class="group section__content group form-row" style="display: none;">
                  <?php echo e(Form::email('red_email', '', array('class' => 'red_email','id' => 'red_email', 'placeholder' => 'Email'))); ?>

               </p>
               <p class="group form-row form-row-wide" id="red_name">
                  <label>First Name</label>
                  <?php echo e(Form::text('red_name','' ,  array('class' => 'red_name', 'id' => 'red_first_name', 'placeholder' => 'First Name'))); ?>

               </p>
               <p class="group section__content group form-row">
                  <label>Last Name</label>
                  <?php echo e(Form::text('red_last','' ,  array('class' => 'red_last', 'id' => 'red_last', 'placeholder' => 'Last Name'))); ?>

               </p>
               <p class="group form-row form-row-wide address-field validate-required" id="billing_address_1_field">
                  <label>Address Line1</label>
                  <?php echo e(Form::text('red_add_address1', '', array('class' => 'red_add_address1', 'id' => 'red_add_address1', 'placeholder' => 'Address Line1'))); ?>

               </p>
               <p class="group form-row form-row-wide address-field" id="red_billing_address_2_field" >
                  <label>Address Line2</label>
                  <?php echo e(Form::text('red_add_address2', '', array('class' => 'red_add_address2','id' => 'red_add_address2', 'placeholder' => 'Address Line2'))); ?>

               </p>
               <p class="group form-row form-row-wide address-field" id="billing_city_field" data-priority="70">
                  <label>City</label>
                  <?php echo e(Form::text('red_city','' ,  array('class' => 'red_city', 'id' => 'red_city', 'placeholder' => 'City'))); ?>

               </p>
               <div class="row pt-0">
                  
                  <div class="col-md-4 state-fld pr-0 ml-3">
                     <div class="form-group form-group-default form-group-default-select2">
                        <label class="">State</label>
                        <p class="group form-row form-row-wide" >
                           <input name="red_add_province" type="text" class="red_state_select cus_add_province form-control"  value="<?php echo e(@$cart_data['h_state']); ?>" region-data-default-value="<?php echo e(@$cart_data['h_state']); ?>">
                        </p>
                     </div>
                  </div>

                  <div class="col-md-4 zip-fld pr-0">
                     <div class="form-group form-group-default input-group fw-100">
                        <div class="form-input-group">
                           <label>ZIP code</label>
                           <p class="group form-row form-row-wide  address-field" id="billing_postcode_field">
                              <input name="red_add_zip" type="tel" class="red_add_zip" id="red_add_zip" value="<?php echo e(@$cart_data['h_zipcode']); ?>">
                           </p>
                        </div>
                     </div>
                  </div>

                  <div class="col-md-4 country-btm  country-fld pr-0" >
                     <label class="">Country</label>
                     <p class="group form-row form-row-wide" >
                        <input name="red_add_country" type="text" class="red_country_to_state" id="country" value="<?php echo e(@$cart_data['h_country']); ?>" country-data-default-value="<?php echo e(@$cart_data['h_country']); ?>" disabled>
                     </p>
                  </div>
               </div>
               <p class="group form-row form-row-wide 3" id="billing_phone_field" style="display: none;">
                  <label>Mobile number</label>
                  <?php echo e(Form::number('red_phone', '', array('class' => 'red_phone', 'id' => 'red_phone', 'placeholder' => 'Mobile number'))); ?>

               </p>
            </div>
         </div>
      </div>

      </div>

      <?php if($user == 'guest'): ?>
      <span class="savedcardt"><input type="checkbox" checked="checked">&nbsp; Save my information for a faster checkout</span>
      <?php endif; ?>
      

      <div class="lst-btn-sec">
         <div class="pay-nw-btn-sec">
            <!-- <p class="m-t-10" >All transactions are secure and encrypted.</p> -->
            <input id="submit-add-card-button" type="submit" value="Pay now" style="">
         </div>
      </div>
      <div class="return-btn-sec"><a id="back-ship"><i class="fa fa-chevron-left" aria-hidden="true"></i> Return to shipping</a></div>
   </div>
</div>

<script type="text/javascript">

   // $("#calculate_shipping_rate").click(function(){
   //    var _carrier_id = $('input[name=shipping_package]:checked').attr('carrier_id');
   //    var _state = $("#state").val();
   //    var _country = $("#country").val();
   //    var _zip_code = $("#zip_code").val();
   //    var _city = $("#cus_add_city").val();
   //    var _countryID = 'US';
   //    var currency_symbol = "<?php //echo $currency_symbol; ?>";
   //    var formData = {
   //       carrier_id: _carrier_id,
   //       state: _state,
   //       country: _country,
   //       zip_code: _zip_code,
   //       city: _city,
   //       countryID: _countryID
   //    }
   //    var getUrl = window.location;
   //    var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
   //    $.ajax({
   //        headers: {
   //           'X-CSRF-Token': $('input[name="_token"]').val()
   //        },
   //        type: "POST",
   //        url: baseUrl+'/shipping_rate_estimate',
   //        data: formData,
   //        cache: false,
   //        beforeSend: function() {
   //           $('#loading-image').show();
   //        },
   //        complete: function() {
   //           $('#loading-image').hide();
   //        },
   //        success: function(result) {
   //          console.log('success');
   //          $("#append_shipping_method").empty();
   //          var object = result.data;
   //          $("#calculate_shipping_rate").hide();
   //          $.each(object, function(index, value) {
   //             $("#append_shipping_method").append('<div class="radio-wrapper" data-shipping-method="usps-FirstPackage-3.25"><div class="radio__input"><input type="radio" id="shipping-val" name="shipping-val" value="'+value.shipping_amount.amount+'" ship_method="'+value.carrier_nickname+'" ship_day="'+value.carrier_delivery_days+'"></div><label class="radio__label" aria-hidden="true" for="checkout_shipping_rate_id_usps-firstpackage-3_25"><span class="radio__label__primary" data-shipping-method-label-title="'+value.carrier_nickname+'">'+value.carrier_nickname+'<span class="small-text"> '+value.carrier_delivery_days+' Days</span></span><span class="radio__label__accessory"><span class="content-box__emphasis"> '+currency_symbol+' '+value.shipping_amount.amount+'</span></span></label></div>');
   //          });
   //        }
   //    });
   // });
   function getPackages()
   {
      var _product_IDS = "<?php echo($productSID); ?>";
      var _shop = "<?php echo $shop; ?>";
      var _carrier_id = "<?php echo $carrierId; ?>";
      var _state = $("#state").val();
      var _country = $("#country").val();
      var _zip_code = $("#zip_code").val();
      var _city = $("#cus_add_city").val();
      var _countryID = 'US';
      var currency_symbol = "<?php echo $currency_symbol; ?>";
      var formData = {
         carrier_id: _carrier_id,
         state: _state,
         country: _country,
         zip_code: _zip_code,
         city: _city,
         countryID: _countryID,
         productID: _product_IDS,
         s_op: _shop
      }
      var getUrl = window.location;
      var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
      $.ajax({
          headers: {
             'X-CSRF-Token': $('input[name="_token"]').val()
          },
          type: "POST",
          url: baseUrl+'/shipping_rate_estimate',
          data: formData,
          cache: false,
          beforeSend: function() {
             $('#loading-image').show();
          },
          complete: function() {
             $('#loading-image').hide();
          },
          success: function(result) {
            console.log('success');
            $(".section.section--shipping-method").show();
            $("#append_shipping_method").empty();
            var object = result.data;
            $("#calculate_shipping_rate").hide();
            $.each(object, function(index, value) {
               $("#append_shipping_method").append('<div class="radio-wrapper" data-shipping-method="usps-FirstPackage-3.25"><div class="radio__input"><input type="radio" id="shipping-val" name="shipping-val" value="'+value.shipping_amount.amount+'" ship_method="'+value.carrier_nickname+'" ship_day="'+value.carrier_delivery_days+'"></div><label class="radio__label" aria-hidden="true" for="checkout_shipping_rate_id_usps-firstpackage-3_25"><span class="radio__label__primary" data-shipping-method-label-title="'+value.carrier_nickname+'">'+value.carrier_nickname+'<span class="small-text"> '+value.carrier_delivery_days+' Days</span></span><span class="radio__label__accessory"><span class="content-box__emphasis"> '+currency_symbol+' '+value.shipping_amount.amount+'</span></span></label></div>');
            });
          }
      });
   }
   // $('input[name=shipping_package]').change(function(){
   //    //var _carrier_id = $('input[name=shipping_package]:checked').attr('carrier_id');
   //    var _carrier_id = "<?php //echo $carrierId; ?>";
   //    var _state = $("#state").val();
   //    var _country = $("#country").val();
   //    var _zip_code = $("#zip_code").val();
   //    var _city = $("#cus_add_city").val();
   //    var _countryID = 'US';
   //    var currency_symbol = "<?php //echo $currency_symbol; ?>";
   //    var formData = {
   //       carrier_id: _carrier_id,
   //       state: _state,
   //       country: _country,
   //       zip_code: _zip_code,
   //       city: _city,
   //       countryID: _countryID
   //    }
   //    var getUrl = window.location;
   //    var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
   //    $.ajax({
   //        headers: {
   //           'X-CSRF-Token': $('input[name="_token"]').val()
   //        },
   //        type: "POST",
   //        url: baseUrl+'/shipping_rate_estimate',
   //        data: formData,
   //        cache: false,
   //        beforeSend: function() {
   //           $('#loading-image').show();
   //        },
   //        complete: function() {
   //           $('#loading-image').hide();
   //        },
   //        success: function(result) {
   //          console.log('success');
   //          $(".section.section--shipping-method").show();
   //          $("#append_shipping_method").empty();
   //          var object = result.data;
   //          $("#calculate_shipping_rate").hide();
   //          $.each(object, function(index, value) {
   //             $("#append_shipping_method").append('<div class="radio-wrapper" data-shipping-method="usps-FirstPackage-3.25"><div class="radio__input"><input type="radio" id="shipping-val" name="shipping-val" value="'+value.shipping_amount.amount+'" ship_method="'+value.carrier_nickname+'" ship_day="'+value.carrier_delivery_days+'"></div><label class="radio__label" aria-hidden="true" for="checkout_shipping_rate_id_usps-firstpackage-3_25"><span class="radio__label__primary" data-shipping-method-label-title="'+value.carrier_nickname+'">'+value.carrier_nickname+'<span class="small-text"> '+value.carrier_delivery_days+' Days</span></span><span class="radio__label__accessory"><span class="content-box__emphasis"> '+currency_symbol+' '+value.shipping_amount.amount+'</span></span></label></div>');
   //          });
   //        }
   //    });
   // });
</script>